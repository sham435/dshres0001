<?php
// bulk_upload.php
// Check for template download FIRST - before any output or includes
if (isset($_GET['download_template'])) {
    // For template download, only include necessary files
    require_once "include.php";
    session_start();
    
    // Check if user is logged in
    if (!isset($_SESSION["user_email"])) {
        header("location: login.php");
        exit();
    }
    
    downloadTemplate();
    exit();
}

// For normal page view, include full header
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';
$preview_data = [];

// Function to download CSV template (NO INSTRUCTIONS, just headers and sample data)
function downloadTemplate() {
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="inventory_template.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Simple headers without format instructions
    $headers = [
        'Item Name',
        'Brand Name',
        'Inventory Number',
        'Serial Number',
        'Section Name',
        'Book Number',
        'Incharge Officer',
        'Received Date',
        'Received From',
        'Is Transferred? (Yes/No)',
        'Transfer Date',
        'Borrowed Officer',
        'Condition Status'
    ];
    
    // Write headers
    fputcsv($output, $headers);
    
    // Sample data rows (just data, no notes)
    $sample_data = [
        ['Laptop', 'Dell', 'INV-001', 'SN123456', 'Science Lab', 'B001', 'Mr. Silva', '2024-01-15', 'Supplier ABC', 'No', '', '', 'working'],
        ['Projector', 'Epson', 'INV-002', 'PR789012', 'AV Room', 'B002', 'Mr. Perera', '2024-01-20', 'Donor XYZ', 'Yes', '2024-02-01', '', 'good'],
        ['Whiteboard', '', 'INV-003', '', 'Classroom 1A', 'B003', '', '2024-01-25', 'School Store', 'No', '', 'Mrs. Kamala', 'working']
    ];
    
    // Write sample data
    foreach ($sample_data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit();
}

// Handle file upload and preview
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['preview_upload'])) {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        // Check file size (max 5MB)
        if ($_FILES['excel_file']['size'] > 5 * 1024 * 1024) {
            $error = "File size too large. Maximum size is 5MB.";
        } else {
            // Process the file
            $file_tmp = $_FILES['excel_file']['tmp_name'];
            $file_name = $_FILES['excel_file']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, ['csv'])) {
                $preview_data = processCSVFile($file_tmp, $conn, $school_id);
                
                if (isset($preview_data['error'])) {
                    $error = $preview_data['error'];
                    $preview_data = [];
                }
            } else {
                $error = "Invalid file type. Please upload CSV files only.";
            }
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

// Handle final bulk insert
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_upload'])) {
    $items = json_decode($_POST['items_data'], true);
    $success_count = 0;
    $fail_count = 0;
    $errors = [];
    
    foreach ($items as $index => $item) {
        // Validate ONLY required fields: Item Name, Inventory Number, Book Number
        $required_errors = [];
        if (empty($item['item_name'])) $required_errors[] = "Item Name is required";
        if (empty($item['inventory_number'])) $required_errors[] = "Inventory Number is required";
        if (empty($item['book_number'])) $required_errors[] = "Book Number is required";
        
        if (!empty($required_errors)) {
            $fail_count++;
            $errors[] = "Row " . ($index + 2) . ": " . implode(", ", $required_errors);
            continue;
        }
        
        // Check if inventory number already exists (optional validation)
        $check_sql = "SELECT id FROM inventory_items WHERE inventory_number = '" . mysqli_real_escape_string($conn, $item['inventory_number']) . "' AND school_id = $school_id";
        $check_result = mysqli_query($conn, $check_sql);
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $fail_count++;
            $errors[] = "Row " . ($index + 2) . ": Inventory number '{$item['inventory_number']}' already exists";
            continue;
        }
        
        // Get section ID from section name (if provided, otherwise NULL)
        $section_id = 'NULL';
        if (!empty($item['section'])) {
            $section_id_result = getSectionIdByName($conn, $school_id, $item['section']);
            if ($section_id_result) {
                $section_id = $section_id_result;
            }
        }
        
        // Get incharge officer ID if provided
        $incharge_id = 'NULL';
        if (!empty($item['incharge_officer'])) {
            $incharge_id_result = getOfficerIdByName($conn, $school_id, $item['incharge_officer']);
            if ($incharge_id_result) {
                $incharge_id = $incharge_id_result;
            }
        }
        
        // Prepare values for insertion (all fields optional except the three required ones)
        $item_name = mysqli_real_escape_string($conn, $item['item_name']);
        $brand_name = !empty($item['brand_name']) ? "'" . mysqli_real_escape_string($conn, $item['brand_name']) . "'" : 'NULL';
        $inventory_number = mysqli_real_escape_string($conn, $item['inventory_number']);
        $serial_number = !empty($item['serial_number']) ? "'" . mysqli_real_escape_string($conn, $item['serial_number']) . "'" : 'NULL';
        $book_number = mysqli_real_escape_string($conn, $item['book_number']);
        $received_date = !empty($item['received_date']) ? "'" . mysqli_real_escape_string($conn, $item['received_date']) . "'" : 'NULL';
        $received_from = !empty($item['received_from']) ? "'" . mysqli_real_escape_string($conn, $item['received_from']) . "'" : 'NULL';
        $is_transferred = !empty($item['is_transferred']) && strtolower($item['is_transferred']) == 'yes' ? 1 : 0;
        $transferred_date = !empty($item['transferred_date']) ? "'" . mysqli_real_escape_string($conn, $item['transferred_date']) . "'" : 'NULL';
        $borrowed_officer = !empty($item['borrowed_officer']) ? "'" . mysqli_real_escape_string($conn, $item['borrowed_officer']) . "'" : 'NULL';
        $condition_status = !empty($item['condition_status']) ? "'" . mysqli_real_escape_string($conn, $item['condition_status']) . "'" : "'working'";
        
        // Build the SQL query - FIXED: removed the extra comma and fixed syntax
        $sql = "INSERT INTO inventory_items (
                    school_id, 
                    item_name, 
                    brand_name, 
                    inventory_number, 
                    serial_number,
                    section_id, 
                    book_number, 
                    incharge_officer_id, 
                    received_date,
                    received_from, 
                    is_transferred, 
                    transferred_date, 
                    borrowed_officer,
                    condition_status
                ) VALUES (
                    $school_id, 
                    '$item_name', 
                    $brand_name, 
                    '$inventory_number', 
                    $serial_number,
                    $section_id, 
                    '$book_number', 
                    $incharge_id, 
                    $received_date,
                    $received_from, 
                    $is_transferred, 
                    $transferred_date, 
                    $borrowed_officer,
                    $condition_status
                )";
        
        // Debug: Log the SQL query (remove in production)
        // error_log($sql);
        
        if (mysqli_query($conn, $sql)) {
            $success_count++;
        } else {
            $fail_count++;
            $errors[] = "Row " . ($index + 2) . ": Database error - " . mysqli_error($conn);
        }
    }
    
    if ($success_count > 0) {
        $message = "Successfully imported $success_count items.";
        if ($fail_count > 0) {
            $message .= " Failed to import $fail_count items.";
        }
    } else {
        $error = "Failed to import any items. Please check your data.";
    }
    
    if (!empty($errors)) {
        $error .= "<br><small>" . implode("<br>", array_slice($errors, 0, 5)) . "</small>";
        if (count($errors) > 5) {
            $error .= "<br>... and " . (count($errors) - 5) . " more errors.";
        }
    }
}

// Function to process CSV file - NO VALIDATION except for required fields
function processCSVFile($file_path, $conn, $school_id) {
    $data = [];
    
    try {
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $row_count = 0;
            
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip completely empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                if ($row_count == 0) {
                    // Skip header row
                    $row_count++;
                    continue;
                }
                
                // Ensure we have at least 13 columns
                while (count($row) < 13) {
                    $row[] = '';
                }
                
                // Map data to columns
                $item = [
                    'item_name' => trim($row[0] ?? ''),
                    'brand_name' => trim($row[1] ?? ''),
                    'inventory_number' => trim($row[2] ?? ''),
                    'serial_number' => trim($row[3] ?? ''),
                    'section' => trim($row[4] ?? ''),
                    'book_number' => trim($row[5] ?? ''),
                    'incharge_officer' => trim($row[6] ?? ''),
                    'received_date' => trim($row[7] ?? ''),
                    'received_from' => trim($row[8] ?? ''),
                    'is_transferred' => trim($row[9] ?? ''),
                    'transferred_date' => trim($row[10] ?? ''),
                    'borrowed_officer' => trim($row[11] ?? ''),
                    'condition_status' => trim($row[12] ?? '')
                ];
                
                // Check ONLY required fields: Item Name, Inventory Number, Book Number
                $errors = [];
                if (empty($item['item_name'])) $errors[] = "Item Name is required";
                if (empty($item['inventory_number'])) $errors[] = "Inventory Number is required";
                if (empty($item['book_number'])) $errors[] = "Book Number is required";
                
                // Add validation status
                $item['validation'] = [
                    'is_valid' => empty($errors),
                    'errors' => $errors
                ];
                
                $data[] = $item;
                $row_count++;
            }
            fclose($handle);
        }
    } catch (Exception $e) {
        return ['error' => "Error processing file: " . $e->getMessage()];
    }
    
    return $data;
}

// Helper function to get section ID by name
function getSectionIdByName($conn, $school_id, $section_name) {
    $section_name = mysqli_real_escape_string($conn, trim($section_name));
    $query = "SELECT id FROM sections WHERE school_id = $school_id AND section_name = '$section_name'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
    
    return null;
}

// Helper function to get officer ID by name
function getOfficerIdByName($conn, $school_id, $officer_name) {
    $officer_name = mysqli_real_escape_string($conn, trim($officer_name));
    $query = "SELECT id FROM officers WHERE school_id = $school_id AND officer_name = '$officer_name'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
    
    return null;
}

// Get sections and officers for reference
$sections = mysqli_query($conn, "SELECT section_name FROM sections WHERE school_id = $school_id ORDER BY section_name");
$officers = mysqli_query($conn, "SELECT officer_name FROM officers WHERE school_id = $school_id ORDER BY officer_name");
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-cloud-upload"></i> Bulk Upload Inventory Items</h2>
                <p class="text-muted">Upload multiple items at once using CSV format</p>
            </div>
            <div>
                <a href="?download_template=1" class="btn btn-success">
                    <i class="bi bi-download"></i> Download Template
                </a>
                <a href="insert_data.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Single Entry
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

        <!-- Upload Form -->
        <div class="content-card">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3"><i class="bi bi-upload"></i> Upload CSV File</h5>
                    
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control" name="excel_file" id="excel_file" accept=".csv" required>
                            <small class="text-muted">Maximum file size: 5MB | Only CSV files accepted</small>
                        </div>
                        
                        <button type="submit" name="preview_upload" class="btn btn-primary">
                            <i class="bi bi-eye"></i> Preview Data
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-eraser"></i> Clear
                        </button>
                    </form>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6><i class="bi bi-info-circle"></i> Required Fields:</h6>
                            <ul class="small">
                                <li class="text-danger">Item Name</li>
                                <li class="text-danger">Inventory Number</li>
                                <li class="text-danger">Book Number</li>
                            </ul>
                            <p class="small text-muted mb-0">All other fields are optional</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reference Tables (Optional - can be removed if not needed) -->
        <div class="row">
            <div class="col-md-6">
                <div class="content-card">
                    <h6><i class="bi bi-grid"></i> Available Sections (Optional)</h6>
                    <div class="table-responsive" style="max-height: 200px;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Section Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($sections && mysqli_num_rows($sections) > 0):
                                    while ($sec = mysqli_fetch_assoc($sections)): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($sec['section_name']); ?></td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td class="text-muted">No sections found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-card">
                    <h6><i class="bi bi-people"></i> Available Officers (Optional)</h6>
                    <div class="table-responsive" style="max-height: 200px;">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Officer Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if ($officers && mysqli_num_rows($officers) > 0):
                                    while ($ofc = mysqli_fetch_assoc($officers)): 
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ofc['officer_name']); ?></td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td class="text-muted">No officers found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <?php if (!empty($preview_data)): 
            $valid_items = array_filter($preview_data, function($item) {
                return $item['validation']['is_valid'];
            });
        ?>
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-table"></i> Data Preview</h5>
                <div>
                    <span class="badge bg-primary">Total: <?php echo count($preview_data); ?></span>
                    <span class="badge bg-success">Valid: <?php echo count($valid_items); ?></span>
                    <span class="badge bg-danger">Invalid: <?php echo count($preview_data) - count($valid_items); ?></span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Inventory #</th>
                            <th>Section</th>
                            <th>Book #</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preview_data as $index => $item): ?>
                        <tr class="<?php echo $item['validation']['is_valid'] ? '' : 'table-danger'; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php echo htmlspecialchars($item['item_name']); ?>
                                <?php if (!$item['validation']['is_valid']): ?>
                                    <br><small class="text-danger"><?php echo implode(', ', $item['validation']['errors']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                            <td><?php echo htmlspecialchars($item['section'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                            <td>
                                <?php if (!empty($item['is_transferred']) && strtolower($item['is_transferred']) == 'yes'): ?>
                                    <span class="badge bg-warning">Transferred</span>
                                <?php elseif (!empty($item['borrowed_officer'])): ?>
                                    <span class="badge bg-info">Borrowed</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Confirm Upload Form -->
            <?php if (count($valid_items) > 0): ?>
            <form method="POST" class="mt-3">
                <input type="hidden" name="items_data" value="<?php echo htmlspecialchars(json_encode(array_values($valid_items))); ?>">
                <button type="submit" name="confirm_upload" class="btn btn-success btn-lg" onclick="return confirm('Are you sure you want to upload <?php echo count($valid_items); ?> items?')">
                    <i class="bi bi-check-circle"></i> Confirm Upload (<?php echo count($valid_items); ?> valid items)
                </button>
                <a href="bulk_upload.php" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </form>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Simple Instructions Card -->
        <div class="content-card mt-4">
            <h6><i class="bi bi-question-circle"></i> Quick Guide</h6>
            <div class="row">
                <div class="col-md-12">
                    <ol class="small">
                        <li>Download the template CSV file</li>
                        <li>Fill in your data (only Item Name, Inventory Number, and Book Number are required)</li>
                        <li>Save as CSV and upload</li>
                        <li>Preview and confirm upload</li>
                    </ol>
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

.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

.table-danger td {
    background-color: #fff3f3;
}

.table-danger td small {
    color: #dc3545;
}

.btn-lg {
    padding: 12px 25px;
    margin-right: 10px;
}

@media (max-width: 768px) {
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
}
</style>

<script>
// File validation
document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
    const fileInput = document.getElementById('excel_file');
    if (fileInput.files.length === 0) {
        e.preventDefault();
        alert('Please select a file to upload');
        return;
    }
    
    const fileName = fileInput.files[0].name;
    const fileExt = fileName.split('.').pop().toLowerCase();
    
    if (fileExt !== 'csv') {
        e.preventDefault();
        alert('Please upload only CSV files');
        return;
    }
    
    const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
    if (fileSize > 5) {
        e.preventDefault();
        alert('File size must be less than 5MB');
        return;
    }
});

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