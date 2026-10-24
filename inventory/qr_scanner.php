<?php
// qr_scanner.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';
$scanned_item = null;

// Handle QR code data from scanner
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qr_data'])) {
    $qr_data = $_POST['qr_data'];
    $item_data = json_decode($qr_data, true);
    
    if ($item_data && isset($item_data['id']) && isset($item_data['school_id'])) {
        if ($item_data['school_id'] == $school_id) {
            $item_id = (int)$item_data['id'];
            
            $query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
                      FROM inventory_items i 
                      LEFT JOIN sections s ON i.section_id = s.id 
                      LEFT JOIN officers o ON i.incharge_officer_id = o.id 
                      WHERE i.id = $item_id AND i.school_id = $school_id";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $scanned_item = mysqli_fetch_assoc($result);
                $message = "Item found!";
            } else {
                $error = "Item not found in database.";
            }
        } else {
            $error = "This QR code belongs to a different school.";
        }
    } else {
        $error = "Invalid QR code data.";
    }
}

// Handle inventory check update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_check'])) {
    $item_id = (int)$_POST['item_id'];
    $real_balance = (int)$_POST['real_balance'];
    $remarks = mysqli_real_escape_string($conn, trim($_POST['remarks']));
    
    // Get current book balance
    $item_query = "SELECT * FROM inventory_items WHERE id = $item_id AND school_id = $school_id";
    $item_result = mysqli_query($conn, $item_query);
    
    if ($item_result && mysqli_num_rows($item_result) > 0) {
        $item = mysqli_fetch_assoc($item_result);
        
        // Create or update check session
        $check_sql = "INSERT INTO inventory_check_sessions (school_id, session_name, check_date, conducted_by, status) 
                      VALUES ($school_id, 'QR Scan Check', CURDATE(), '" . mysqli_real_escape_string($conn, $_SESSION['user_email']) . "', 'active')";
        mysqli_query($conn, $check_sql);
        $session_id = mysqli_insert_id($conn);
        
        // Parse inventory number for book balance
        $parsed = parseInventoryNumber($item['inventory_number']);
        $book_balance = $parsed['count'];
        
        // Calculate surplus/deficiency
        $surplus = max(0, $real_balance - $book_balance);
        $deficiency = max(0, $book_balance - $real_balance);
        
        // Insert check item
        $check_item_sql = "INSERT INTO inventory_check_items 
                           (session_id, item_id, item_name, inventory_number, book_balance, real_balance, surplus, deficiency, remarks, checked_status, checked_at) 
                           VALUES 
                           ($session_id, $item_id, '" . mysqli_real_escape_string($conn, $item['item_name']) . "', 
                            '" . mysqli_real_escape_string($conn, $item['inventory_number']) . "', 
                            $book_balance, $real_balance, $surplus, $deficiency, 
                            " . ($remarks ? "'$remarks'" : "NULL") . ", 'checked', NOW())";
        
        if (mysqli_query($conn, $check_item_sql)) {
            $message = "Inventory check recorded successfully!";
            $scanned_item = null; // Clear scanned item
        } else {
            $error = "Error recording check: " . mysqli_error($conn);
        }
    }
}

// Function to parse inventory number (copy from check_inventory.php)
function parseInventoryNumber($inv_number) {
    $inv_number = trim($inv_number);
    
    if (preg_match('/(.+)\/(\d+)\s+to\s+\1\/(\d+)/i', $inv_number, $matches)) {
        $base = $matches[1];
        $start = (int)$matches[2];
        $end = (int)$matches[3];
        return ['type' => 'range', 'count' => $end - $start + 1];
    }
    
    if (preg_match('/(.+)\/(\d+)$/', $inv_number, $matches)) {
        return ['type' => 'single', 'count' => 1];
    }
    
    return ['type' => 'regular', 'count' => 1];
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-camera"></i> QR Scanner</h2>
                <p class="text-muted">Scan QR codes to check inventory items</p>
            </div>
            <div>
                <a href="qr_generator.php" class="btn btn-primary">
                    <i class="bi bi-qr-code"></i> Generate QR Codes
                </a>
            </div>
        </div>

        <!-- Messages -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Scanner Section -->
        <div class="content-card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-camera"></i> Scan QR Code</h6>
                        </div>
                        <div class="card-body">
                            <div id="qr-reader" style="width: 100%;"></div>
                            <div id="qr-reader-results" class="mt-3"></div>
                            
                            <form method="POST" id="qrForm" style="display: none;">
                                <input type="hidden" name="qr_data" id="qrData">
                            </form>
                            
                            <div class="mt-3 text-center">
                                <p class="text-muted">Position the QR code in front of your camera</p>
                                <button class="btn btn-danger" onclick="stopScanner()">
                                    <i class="bi bi-stop"></i> Stop Scanner
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> How to Scan</h6>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li>Allow camera access when prompted</li>
                                <li>Point camera at the item's QR code</li>
                                <li>Scanner will automatically detect the code</li>
                                <li>Item details will appear below</li>
                                <li>Enter physical count and verify</li>
                            </ol>
                            <hr>
                            <h6>Manual Entry</h6>
                            <p class="small">If scanner doesn't work, you can manually enter the item ID:</p>
                            <form method="POST" class="row g-2">
                                <div class="col-8">
                                    <input type="text" class="form-control" name="qr_data" placeholder='{"id":123,"name":"Item"}'>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-secondary w-100">Manual Check</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scanned Item Details -->
        <?php if ($scanned_item): ?>
        <div class="content-card mt-4">
            <h5 class="mb-3"><i class="bi bi-box"></i> Scanned Item Details</h5>
            
            <div class="row">
                <div class="col-md-4 text-center">
                    <?php if ($scanned_item['image_path'] && file_exists($scanned_item['image_path'])): ?>
                        <img src="<?php echo $scanned_item['image_path']; ?>" alt="Item" class="img-fluid rounded" style="max-height: 200px;">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 150px;">Item Name:</th>
                            <td><strong><?php echo htmlspecialchars($scanned_item['item_name']); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Inventory Number:</th>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($scanned_item['inventory_number']); ?></span></td>
                        </tr>
                        <tr>
                            <th>Brand:</th>
                            <td><?php echo htmlspecialchars($scanned_item['brand_name'] ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Section:</th>
                            <td><?php echo htmlspecialchars($scanned_item['section_name'] ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Book Number:</th>
                            <td><?php echo htmlspecialchars($scanned_item['book_number']); ?></td>
                        </tr>
                        <tr>
                            <th>In-charge:</th>
                            <td><?php echo htmlspecialchars($scanned_item['incharge_name'] ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Condition:</th>
                            <td>
                                <?php
                                $condition_class = [
                                    'working' => 'success',
                                    'good' => 'info',
                                    'not_working' => 'warning',
                                    'not_usable' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $condition_class[$scanned_item['condition_status']] ?? 'secondary'; ?>">
                                    <?php echo ucfirst($scanned_item['condition_status']); ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Inventory Check Form -->
            <div class="mt-4">
                <h6><i class="bi bi-clipboard-check"></i> Record Physical Count</h6>
                <form method="POST" class="row g-3">
                    <input type="hidden" name="item_id" value="<?php echo $scanned_item['id']; ?>">
                    
                    <div class="col-md-3">
                        <label class="form-label">Book Balance</label>
                        <?php 
                        $parsed = parseInventoryNumber($scanned_item['inventory_number']);
                        $book_balance = $parsed['count'];
                        ?>
                        <input type="text" class="form-control" value="<?php echo $book_balance; ?>" readonly>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Real Balance <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="real_balance" min="0" required>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks" placeholder="Any issues?">
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" name="update_check" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Record Check
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent QR Scans (Optional) -->
        <div class="content-card mt-4">
            <h5 class="mb-3"><i class="bi bi-clock-history"></i> Recent QR Scans</h5>
            
            <?php
            $recent_scans = mysqli_query($conn, "SELECT ci.*, cs.session_name 
                                                 FROM inventory_check_items ci 
                                                 JOIN inventory_check_sessions cs ON ci.session_id = cs.id 
                                                 WHERE cs.school_id = $school_id AND cs.session_name = 'QR Scan Check'
                                                 ORDER BY ci.checked_at DESC LIMIT 10");
            
            if ($recent_scans && mysqli_num_rows($recent_scans) > 0):
            ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Item Name</th>
                            <th>Inventory #</th>
                            <th>Book Balance</th>
                            <th>Real Balance</th>
                            <th>Surplus</th>
                            <th>Deficiency</th>
                            <th>Scanned At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($scan = mysqli_fetch_assoc($recent_scans)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($scan['item_name']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($scan['inventory_number']); ?></span></td>
                            <td><?php echo $scan['book_balance']; ?></td>
                            <td><?php echo $scan['real_balance']; ?></td>
                            <td class="text-success"><?php echo $scan['surplus']; ?></td>
                            <td class="text-danger"><?php echo $scan['deficiency']; ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($scan['checked_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted text-center py-3">No recent QR scans.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<style>
.content-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

#qr-reader {
    border-radius: 8px;
    overflow: hidden;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.badge {
    font-size: 0.9rem;
    padding: 0.4rem 0.6rem;
}
</style>

<script>
let html5QrcodeScanner = null;

// Initialize scanner on page load
document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});

function startScanner() {
    const qrReaderElement = document.getElementById('qr-reader');
    if (!qrReaderElement) return;
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { fps: 10, qrbox: { width: 250, height: 250 } },
        /* verbose= */ false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanError);
}

function onScanSuccess(decodedText, decodedResult) {
    // Handle the scanned code
    console.log('Scanned:', decodedText);
    
    // Show success message
    const resultsDiv = document.getElementById('qr-reader-results');
    if (resultsDiv) {
        resultsDiv.innerHTML = '<div class="alert alert-success">QR Code detected! Processing...</div>';
    }
    
    // Set the data and submit form
    document.getElementById('qrData').value = decodedText;
    document.getElementById('qrForm').submit();
}

function onScanError(errorMessage) {
    // Handle scan error (optional)
    console.log('Scan error:', errorMessage);
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
        
        const resultsDiv = document.getElementById('qr-reader-results');
        if (resultsDiv) {
            resultsDiv.innerHTML = '<div class="alert alert-info">Scanner stopped. Refresh page to restart.</div>';
        }
    }
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<?php require_once "footer.php"; ?>