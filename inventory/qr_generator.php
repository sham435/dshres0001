<?php
// qr_generator.php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

// Include FPDF
require_once('fpdf/fpdf.php');

function createQrImage(string $data, string $outputPath): void {
    $qrCode = QrCode::create($data)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium)
        ->setSize(180)
        ->setMargin(5);

    $writer = new PngWriter();
    $result = $writer->write($qrCode);
    file_put_contents($outputPath, $result->getString());
}

// Custom PDF class for batch labels (3 columns × 4 rows = 12 per page)
class Batch_PDF_Label extends FPDF {
    private $width = 70; // mm (A4/3 columns ≈ 70mm with margins)
    private $height = 55; // mm (A4/4 rows ≈ 55mm with increased height)
    private $marginLeft = 5;
    private $marginTop = 5;
    private $col = 0;
    private $row = 0;
    
    function __construct() {
        parent::__construct('P', 'mm', 'A4');
        $this->SetAutoPageBreak(false);
        $this->AddPage();
    }
    
    function AddLabel($schoolName, $itemName, $invNumber, $section, $qrPath) {
        
        $x = $this->marginLeft + ($this->col * $this->width);
        $y = $this->marginTop + ($this->row * $this->height);
        
        // Draw border with increased thickness
        $this->SetLineWidth(0.4);
        $this->Rect($x, $y, $this->width, $this->height);
        
        // School name
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY($x + 3, $y + 4);
        $this->Cell($this->width - 6, 5, $this->truncate($schoolName, 25), 0, 1, 'C');
        
        // QR Code - larger for better scanning
        if (file_exists($qrPath)) {
            $this->Image($qrPath, $x + ($this->width - 45) / 2, $y + 12, 45, 45);
        }
        
        // Item name
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY($x + 3, $y + 62);
        $this->Cell($this->width - 6, 4, $this->truncate($itemName, 22), 0, 1, 'C');
        
        // Inventory number
        $this->SetFont('Arial', '', 7);
        $this->SetXY($x + 3, $y + 67);
        $this->Cell($this->width - 6, 4, '#' . $invNumber, 0, 1, 'C');
        
        // Section
        if ($section && $section != 'N/A' && $section != '') {
            $this->SetFont('Arial', '', 6);
            $this->SetXY($x + 3, $y + 72);
            $this->Cell($this->width - 6, 3, $this->truncate($section, 20), 0, 1, 'C');
        }
        
        // Update position
        $this->col++;
        if ($this->col >= 3) { // 3 columns
            $this->col = 0;
            $this->row++;
            if ($this->row >= 4) { // 4 rows
                $this->AddPage();
                $this->row = 0;
            }
        }
    }
    
    private function truncate($text, $length) {
        if (strlen($text) > $length) {
            return substr($text, 0, $length - 3) . '...';
        }
        return $text;
    }
}

// Custom PDF class for single label (one per page, larger)
class Single_PDF_Label extends FPDF {
    private $width = 150; // mm
    private $height = 120; // mm (increased height)
    
    function __construct() {
        parent::__construct('P', 'mm', 'A4');
        $this->SetAutoPageBreak(false);
        $this->AddPage();
    }
    
    function AddLabel($schoolName, $itemName, $invNumber, $section, $qrPath) {
        
        // Center the label on the page
        $x = ($this->GetPageWidth() - $this->width) / 2;
        $y = ($this->GetPageHeight() - $this->height) / 2;
        
        // Draw border with increased thickness
        $this->SetLineWidth(0.6);
        $this->Rect($x, $y, $this->width, $this->height);
        
        // School name
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY($x + 8, $y + 12);
        $this->Cell($this->width - 16, 8, $this->truncate($schoolName, 30), 0, 1, 'C');
        
        // QR Code - larger for single label
        if (file_exists($qrPath)) {
            $this->Image($qrPath, $x + ($this->width - 70) / 2, $y + 30, 70, 70);
        }
        
        // Item name
        $this->SetFont('Arial', 'B', 13);
        $this->SetXY($x + 8, $y + 110);
        $this->Cell($this->width - 16, 7, $this->truncate($itemName, 25), 0, 1, 'C');
        
        // Inventory number
        $this->SetFont('Arial', '', 10);
        $this->SetXY($x + 8, $y + 118);
        $this->Cell($this->width - 16, 6, '#' . $invNumber, 0, 1, 'C');
        
        // Section
        if ($section && $section != 'N/A' && $section != '') {
            $this->SetFont('Arial', '', 9);
            $this->SetXY($x + 8, $y + 125);
            $this->Cell($this->width - 16, 5, $this->truncate($section, 25), 0, 1, 'C');
        }
    }
    
    private function truncate($text, $length) {
        if (strlen($text) > $length) {
            return substr($text, 0, $length - 3) . '...';
        }
        return $text;
    }
}

if (isset($_GET['download_label']) || isset($_POST['generate_labels'])) {
    
    require_once "include.php";
    require_once "vendor/autoload.php";
    
    session_start();
    
    if (empty($_SESSION['school_id'])) {
        http_response_code(403);
        die("Session expired or unauthorized access.");
    }
    
    $school_id = (int)$_SESSION['school_id'];
    
    $tempDir = 'temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }
    
    $school_query = "SELECT * FROM school_info WHERE id = $school_id";
    $school_result = mysqli_query($conn, $school_query);
    $school = mysqli_fetch_assoc($school_result) ?: ['school_name' => 'Unknown School'];
    
    // Handle single label download (one per page, larger)
    if (isset($_GET['download_label']) && isset($_GET['item_id'])) {
        $item_id = (int)$_GET['item_id'];
        
        $item_query = "SELECT i.*, s.section_name 
                       FROM inventory_items i 
                       LEFT JOIN sections s ON i.section_id = s.id 
                       WHERE i.id = $item_id AND i.school_id = $school_id";
        $item_result = mysqli_query($conn, $item_query);
        
        if ($item_result && mysqli_num_rows($item_result) > 0) {
            $item = mysqli_fetch_assoc($item_result);
            
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
            $base_url = $protocol . '://' . $host . $scriptDir;
            $qr_data = $base_url . '/view_item.php?id=' . urlencode($item['id']);
            
            // Generate QR code image
            $qrPath = $tempDir . 'qr_' . $item['id'] . '_' . uniqid() . '.png';
            createQrImage($qr_data, $qrPath);
            
            // Create single label PDF (one per page, centered)
            $pdf = new Single_PDF_Label();
            $pdf->AddLabel(
                $school['school_name'],
                $item['item_name'],
                $item['inventory_number'],
                $item['section_name'] ?? '',
                $qrPath
            );
            
            // Clean up temp QR file
            if (file_exists($qrPath)) unlink($qrPath);
            
            // Output PDF
            while (ob_get_level()) ob_end_clean();
            
            $safe_inv = preg_replace('/[^a-zA-Z0-9_-]/', '_', $item['inventory_number']);
            $fileName = 'label_' . $safe_inv . '.pdf';
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Content-Length: ' . strlen($pdf->Output('S')));
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $pdf->Output('D', $fileName);
            exit;
        } else {
            die("Item not found or access denied.");
        }
    }
    
    // Handle batch label generation (3 columns × 4 rows = 12 per page)
    if (isset($_POST['generate_labels']) && !empty($_POST['selected_items'])) {
        $selected_items = array_map('intval', $_POST['selected_items']);
        
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        $base_url = $protocol . '://' . $host . $scriptDir;
        
        // Create a single PDF with all labels (3×4 = 12 per page)
        $pdf = new Batch_PDF_Label();
        
        foreach ($selected_items as $item_id) {
            $item_query = "SELECT i.*, s.section_name 
                           FROM inventory_items i 
                           LEFT JOIN sections s ON i.section_id = s.id 
                           WHERE i.id = $item_id AND i.school_id = $school_id";
            $item_result = mysqli_query($conn, $item_query);
            
            if ($item_result && mysqli_num_rows($item_result) > 0) {
                $item = mysqli_fetch_assoc($item_result);
                
                $qr_data = $base_url . '/view_item.php?id=' . urlencode($item['id']);
                
                // Generate QR code image
                $qrPath = $tempDir . 'qr_' . $item['id'] . '_' . uniqid() . '.png';
                createQrImage($qr_data, $qrPath);
                
                // Add label to PDF
                $pdf->AddLabel(
                    $school['school_name'],
                    $item['item_name'],
                    $item['inventory_number'],
                    $item['section_name'] ?? '',
                    $qrPath
                );
                
                // Clean up temp QR file
                if (file_exists($qrPath)) unlink($qrPath);
            }
        }
        
        while (ob_get_level()) ob_end_clean();
        
        // Output PDF directly
        $fileName = 'inventory_labels_' . date('Y-m-d_His') . '.pdf';
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($pdf->Output('S')));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $pdf->Output('D', $fileName);
        exit;
    }
    
    die("Invalid request.");
}

require_once "header.php";

$school_id = $_SESSION['school_id'] ?? null;
$message = '';
$error = '';

if (!$school_id) {
    $error = "Please log in to access this page.";
} else {
    $items_query = "SELECT i.*, s.section_name 
                    FROM inventory_items i 
                    LEFT JOIN sections s ON i.section_id = s.id 
                    WHERE i.school_id = $school_id 
                    ORDER BY i.item_name";
    $items = mysqli_query($conn, $items_query);

    $sections_query = "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name";
    $sections = mysqli_query($conn, $sections_query);
}
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-qr-code"></i> Inventory QR Code Labels</h2>
                <p class="text-muted">
                    <strong>Single item:</strong> One large label per page<br>
                    <strong>Multiple items:</strong> 3 columns × 4 rows = 12 labels per page
                </p>
            </div>
        </div>

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

        <div class="content-card">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Items</label>
                    <input type="text" class="form-control" name="search" placeholder="Item name or inventory number" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Section</label>
                    <select class="form-select" name="section">
                        <option value="">All Sections</option>
                        <?php 
                        mysqli_data_seek($sections, 0);
                        while ($sec = mysqli_fetch_assoc($sections)): 
                        ?>
                        <option value="<?php echo $sec['id']; ?>" <?php echo (isset($_GET['section']) && $_GET['section'] == $sec['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sec['section_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"> </label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <label class="form-label"> </label>
                    <a href="qr_generator.php" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>

        <form method="POST" action="qr_generator.php" id="labelForm" onsubmit="return validateSelection()">
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-grid"></i> Select Items for Label Generation</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-info" onclick="selectAll()">
                            <i class="bi bi-check-all"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">
                            <i class="bi bi-x"></i> Deselect All
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%"><input type="checkbox" id="selectAllCheckbox" onchange="toggleAll(this)"></th>
                                <th width="5%">ID</th>
                                <th width="25%">Item Name</th>
                                <th width="15%">Inventory #</th>
                                <th width="15%">Section</th>
                                <th width="10%">Book #</th>
                                <th width="25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $display_items = $items;
                            if (isset($_GET['search']) && !empty($_GET['search'])) {
                                $search = mysqli_real_escape_string($conn, $_GET['search']);
                                $display_items = mysqli_query($conn, "SELECT i.*, s.section_name 
                                                                     FROM inventory_items i 
                                                                     LEFT JOIN sections s ON i.section_id = s.id 
                                                                     WHERE i.school_id = $school_id 
                                                                     AND (i.item_name LIKE '%$search%' OR i.inventory_number LIKE '%$search%')
                                                                     ORDER BY i.item_name");
                            }
                            if (isset($_GET['section']) && !empty($_GET['section'])) {
                                $section = (int)$_GET['section'];
                                $display_items = mysqli_query($conn, "SELECT i.*, s.section_name 
                                                                     FROM inventory_items i 
                                                                     LEFT JOIN sections s ON i.section_id = s.id 
                                                                     WHERE i.school_id = $school_id AND i.section_id = $section
                                                                     ORDER BY i.item_name");
                            }
                            
                            if ($display_items && mysqli_num_rows($display_items) > 0): 
                                while ($item = mysqli_fetch_assoc($display_items)): 
                            ?>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" class="item-checkbox">
                                </td>
                                <td>#<?php echo $item['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td><?php echo htmlspecialchars($item['section_name'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                                <td>
                                    <a href="qr_generator.php?download_label=1&item_id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-file-pdf"></i> Single Label
                                    </a>
                                    <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                    No items found.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-8">
                        <span id="selectedCount" class="badge bg-primary">0 items selected</span>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <button type="submit" name="generate_labels" class="btn btn-success btn-lg">
                            <i class="bi bi-file-pdf"></i> Generate Multi-Label PDF (3×4 = 12 per page)
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="content-card mt-4">
            <div class="row">
                <div class="col-md-3">
                    <h6><i class="bi bi-eye"></i> Single Label</h6>
                    <div class="border p-2 text-center bg-light rounded">
                        <div style="width: 120px; height: 100px; margin: 0 auto; background: white; border: 2px solid #4e73df; padding: 5px;">
                            <div style="font-size: 8px; font-weight: bold;">SCHOOL</div>
                            <div style="width: 40px; height: 40px; margin: 5px auto; background: #000;"></div>
                            <div style="font-size: 7px; font-weight: bold;">Item Name</div>
                            <div style="font-size: 6px;">#INV-001</div>
                        </div>
                        <p class="mt-1 mb-0"><small>150×120mm, centered</small></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6><i class="bi bi-eye"></i> Multi Label</h6>
                    <div class="border p-2 text-center bg-light rounded">
                        <div style="width: 120px; height: 100px; margin: 0 auto; background: white; border: 1px solid #4e73df; padding: 2px;">
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px;">
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                                <div style="background: #f0f0f0; height: 30px;"></div>
                            </div>
                        </div>
                        <p class="mt-1 mb-0"><small>3 columns × 4 rows = 12 per page</small></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-info-circle"></i> Label Specifications</h6>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <th>Type</th>
                            <th>Dimensions</th>
                            <th>Layout</th>
                            <th>QR Size</th>
                        </tr>
                        <tr>
                            <td>Single</td>
                            <td>150mm × 120mm</td>
                            <td>Centered on page</td>
                            <td>70mm</td>
                        </tr>
                        <tr>
                            <td>Multi</td>
                            <td>70mm × 55mm</td>
                            <td>3 columns × 4 rows</td>
                            <td>45mm</td>
                        </tr>
                    </table>
                    <p class="small text-muted mt-2">
                        ✓ Borders increased for better spacing<br>
                        ✓ Perfect for printing on sticker paper<br>
                        ✓ Scan QR code to view item details
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.table th { vertical-align: middle; }
.badge { font-size: 0.9rem; padding: 0.4rem 0.6rem; }
.btn-sm { margin-right: 3px; }
#selectedCount { font-size: 1rem; padding: 0.5rem 1rem; }
</style>

<script>
function toggleAll(source) {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = source.checked);
    updateSelectedCount();
}

function selectAll() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = true);
    document.getElementById('selectAllCheckbox').checked = true;
    updateSelectedCount();
}

function deselectAll() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAllCheckbox').checked = false;
    updateSelectedCount();
}

function updateSelectedCount() {
    const count = document.querySelectorAll('.item-checkbox:checked').length;
    const span = document.getElementById('selectedCount');
    span.textContent = count + ' item' + (count !== 1 ? 's' : '') + ' selected';
    span.className = count > 0 ? 'badge bg-success' : 'badge bg-primary';
}

function validateSelection() {
    const count = document.querySelectorAll('.item-checkbox:checked').length;
    if (count === 0) {
        alert('Please select at least one item.');
        return false;
    }
    return confirm('Generate PDF with ' + count + ' label' + (count !== 1 ? 's' : '') + '?');
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateSelectedCount);
    });
    updateSelectedCount();
});

setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>

<?php require_once "footer.php"; ?>