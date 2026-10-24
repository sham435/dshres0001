<?php
// report.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';

// Get school info for report header
$school_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM school_info WHERE id = $school_id"));

// Get filter values
$filter_section = isset($_GET['section']) ? (int)$_GET['section'] : 0;
$filter_condition = isset($_GET['condition']) ? mysqli_real_escape_string($conn, $_GET['condition']) : '';
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$filter_date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$filter_date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';
$filter_search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Handle report generation
if (isset($_POST['generate_report'])) {
    $report_type = $_POST['report_type'];
    $report_data = getReportData($conn, $school_id, $_POST);
    
    if ($report_type == 'pdf') {
        generatePDFReport($conn, $school_id, $school_info, $_POST, $report_data);
    } elseif ($report_type == 'excel') {
        generateExcelReport($conn, $school_id, $school_info, $_POST, $report_data);
    }
    exit();
}

// Function to get report data based on filters
function getReportData($conn, $school_id, $filters) {
    $where = "WHERE i.school_id = $school_id";
    
    if (!empty($filters['section'])) {
        $where .= " AND i.section_id = " . (int)$filters['section'];
    }
    
    if (!empty($filters['condition'])) {
        $where .= " AND i.condition_status = '" . mysqli_real_escape_string($conn, $filters['condition']) . "'";
    }
    
    if (!empty($filters['status'])) {
        if ($filters['status'] == 'available') {
            $where .= " AND i.is_transferred = 0 AND (i.borrowed_officer IS NULL OR i.borrowed_officer = '')";
        } elseif ($filters['status'] == 'borrowed') {
            $where .= " AND i.borrowed_officer IS NOT NULL AND i.borrowed_officer != ''";
        } elseif ($filters['status'] == 'transferred') {
            $where .= " AND i.is_transferred = 1";
        }
    }
    
    if (!empty($filters['date_from'])) {
        $where .= " AND DATE(i.received_date) >= '" . mysqli_real_escape_string($conn, $filters['date_from']) . "'";
    }
    
    if (!empty($filters['date_to'])) {
        $where .= " AND DATE(i.received_date) <= '" . mysqli_real_escape_string($conn, $filters['date_to']) . "'";
    }
    
    if (!empty($filters['search'])) {
        $search = mysqli_real_escape_string($conn, $filters['search']);
        $where .= " AND (i.item_name LIKE '%$search%' 
                        OR i.inventory_number LIKE '%$search%' 
                        OR i.serial_number LIKE '%$search%' 
                        OR i.brand_name LIKE '%$search%')";
    }
    
    $query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
              FROM inventory_items i 
              LEFT JOIN sections s ON i.section_id = s.id 
              LEFT JOIN officers o ON i.incharge_officer_id = o.id 
              $where 
              ORDER BY i.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $data = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    
    return $data;
}

// Function to generate PDF report
function generatePDFReport($conn, $school_id, $school_info, $filters, $data) {
    // Include TCPDF library
    require_once('vendor/tecnickcom/tcpdf/tcpdf.php');
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Inventory System');
    $pdf->SetAuthor($school_info['school_name']);
    $pdf->SetTitle('Inventory Report - ' . $school_info['school_name']);
    $pdf->SetSubject('Inventory Report');
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 10);
    
    // School Header
    $html = '<h1 style="text-align:center; color:#4e73df;">' . $school_info['school_name'] . '</h1>';
    $html .= '<h3 style="text-align:center; color:#666;">Inventory Report</h3>';
    $html .= '<p style="text-align:center;">Generated on: ' . date('F j, Y h:i A') . '</p>';
    
    // School Info
    $html .= '<table style="width:100%; margin-bottom:15px; border:1px solid #ddd;">';
    $html .= '<tr><td style="width:50%; padding:5px;"><strong>Address:</strong> ' . $school_info['address'] . '</td>';
    $html .= '<td style="width:50%; padding:5px;"><strong>Phone:</strong> ' . $school_info['phone'] . '</td></tr>';
    $html .= '<tr><td style="width:50%; padding:5px;"><strong>Email:</strong> ' . $school_info['email'] . '</td>';
    $html .= '<td style="width:50%; padding:5px;"><strong>Principal:</strong> ' . $school_info['principal_name'] . '</td></tr>';
    $html .= '</table>';
    
    // Filter Info
    if (!empty($filters['section']) || !empty($filters['condition']) || !empty($filters['status']) || !empty($filters['date_from']) || !empty($filters['date_to'])) {
        $html .= '<h4>Applied Filters:</h4>';
        $html .= '<table style="width:100%; margin-bottom:15px; border:1px solid #ddd;">';
        
        if (!empty($filters['section'])) {
            $sec_result = mysqli_query($conn, "SELECT section_name FROM sections WHERE id = " . (int)$filters['section']);
            $sec_name = ($sec_result && mysqli_num_rows($sec_result) > 0) ? mysqli_fetch_assoc($sec_result)['section_name'] : 'Unknown';
            $html .= '<tr><td style="width:30%; padding:5px;"><strong>Section:</strong></td><td>' . $sec_name . '</td></tr>';
        }
        
        if (!empty($filters['condition'])) {
            $condition_labels = ['working' => 'Working', 'good' => 'Good', 'not_working' => 'Not Working', 'not_usable' => 'Not Usable'];
            $html .= '<tr><td style="width:30%; padding:5px;"><strong>Condition:</strong></td><td>' . ($condition_labels[$filters['condition']] ?? $filters['condition']) . '</td></tr>';
        }
        
        if (!empty($filters['status'])) {
            $status_labels = ['available' => 'Available', 'borrowed' => 'Borrowed', 'transferred' => 'Transferred'];
            $html .= '<tr><td style="width:30%; padding:5px;"><strong>Status:</strong></td><td>' . ($status_labels[$filters['status']] ?? $filters['status']) . '</td></tr>';
        }
        
        if (!empty($filters['date_from'])) {
            $html .= '<tr><td style="width:30%; padding:5px;"><strong>From Date:</strong></td><td>' . $filters['date_from'] . '</td></tr>';
        }
        
        if (!empty($filters['date_to'])) {
            $html .= '<tr><td style="width:30%; padding:5px;"><strong>To Date:</strong></td><td>' . $filters['date_to'] . '</td></tr>';
        }
        
        $html .= '</table>';
    }
    
    // Summary
    $html .= '<h4>Summary</h4>';
    $html .= '<table style="width:100%; margin-bottom:15px; border:1px solid #ddd;">';
    $html .= '<tr><td style="width:50%; padding:5px;"><strong>Total Items:</strong> ' . count($data) . '</td>';
    
    $total_value = 0; // You can add value calculation if needed
    $html .= '<td style="width:50%; padding:5px;"><strong>Report Date:</strong> ' . date('Y-m-d') . '</td></tr>';
    $html .= '</table>';
    
    // Items Table
    if (count($data) > 0) {
        $html .= '<h4>Inventory Items</h4>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%;">';
        $html .= '<thead style="background-color:#4e73df; color:white;">';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Item Name</th>';
        $html .= '<th>Brand</th>';
        $html .= '<th>Inventory #</th>';
        $html .= '<th>Serial #</th>';
        $html .= '<th>Section</th>';
        $html .= '<th>Book #</th>';
        $html .= '<th>Condition</th>';
        $html .= '<th>Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($data as $index => $item) {
            $condition_labels = ['working' => 'Working', 'good' => 'Good', 'not_working' => 'Not Working', 'not_usable' => 'Not Usable'];
            $status = $item['is_transferred'] ? 'Transferred' : ($item['borrowed_officer'] ? 'Borrowed' : 'Available');
            
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['item_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['brand_name'] ?: '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($item['inventory_number']) . '</td>';
            $html .= '<td>' . htmlspecialchars($item['serial_number'] ?: '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($item['section_name'] ?: '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($item['book_number']) . '</td>';
            $html .= '<td>' . ($condition_labels[$item['condition_status']] ?? $item['condition_status']) . '</td>';
            $html .= '<td>' . $status . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
    } else {
        $html .= '<p style="text-align:center; color:#999;">No items found matching the selected filters.</p>';
    }
    
    // Footer
    $html .= '<p style="text-align:center; margin-top:30px; color:#666; font-size:8pt;">';
    $html .= 'Generated by Inventory Management System | ';
    $html .= 'Inventory Officer: ' . $school_info['inventory_officer_name'] . ' | ';
    $html .= 'Contact: ' . $school_info['inventory_officer_phone'];
    $html .= '</p>';
    
    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Close and output PDF document
    $filename = 'Inventory_Report_' . date('Y-m-d_His') . '.pdf';
    $pdf->Output($filename, 'D');
    exit();
}

// Function to generate Excel report
function generateExcelReport($conn, $school_id, $school_info, $filters, $data) {
    // Clear output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers for Excel download
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Inventory_Report_' . date('Y-m-d_His') . '.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // School Header
    fputcsv($output, [strtoupper($school_info['school_name']) . ' - INVENTORY REPORT']);
    fputcsv($output, ['Generated on: ' . date('F j, Y h:i A')]);
    fputcsv($output, []);
    
    // School Info
    fputcsv($output, ['SCHOOL INFORMATION']);
    fputcsv($output, ['Address:', $school_info['address']]);
    fputcsv($output, ['Phone:', $school_info['phone']]);
    fputcsv($output, ['Email:', $school_info['email']]);
    fputcsv($output, ['Principal:', $school_info['principal_name']]);
    fputcsv($output, []);
    
    // Filter Info
    if (!empty($filters['section']) || !empty($filters['condition']) || !empty($filters['status']) || !empty($filters['date_from']) || !empty($filters['date_to'])) {
        fputcsv($output, ['APPLIED FILTERS']);
        
        if (!empty($filters['section'])) {
            $sec_result = mysqli_query($conn, "SELECT section_name FROM sections WHERE id = " . (int)$filters['section']);
            $sec_name = ($sec_result && mysqli_num_rows($sec_result) > 0) ? mysqli_fetch_assoc($sec_result)['section_name'] : 'Unknown';
            fputcsv($output, ['Section:', $sec_name]);
        }
        
        if (!empty($filters['condition'])) {
            $condition_labels = ['working' => 'Working', 'good' => 'Good', 'not_working' => 'Not Working', 'not_usable' => 'Not Usable'];
            fputcsv($output, ['Condition:', $condition_labels[$filters['condition']] ?? $filters['condition']]);
        }
        
        if (!empty($filters['status'])) {
            $status_labels = ['available' => 'Available', 'borrowed' => 'Borrowed', 'transferred' => 'Transferred'];
            fputcsv($output, ['Status:', $status_labels[$filters['status']] ?? $filters['status']]);
        }
        
        if (!empty($filters['date_from'])) {
            fputcsv($output, ['From Date:', $filters['date_from']]);
        }
        
        if (!empty($filters['date_to'])) {
            fputcsv($output, ['To Date:', $filters['date_to']]);
        }
        fputcsv($output, []);
    }
    
    // Summary
    fputcsv($output, ['SUMMARY']);
    fputcsv($output, ['Total Items:', count($data)]);
    fputcsv($output, ['Report Date:', date('Y-m-d')]);
    fputcsv($output, []);
    
    // Column Headers
    $headers = [
        'Sl No',
        'Item Name',
        'Brand',
        'Inventory Number',
        'Serial Number',
        'Section',
        'Book Number',
        'Incharge Officer',
        'Received Date',
        'Received From',
        'Condition',
        'Status',
        'Borrowed Officer',
        'Transfer Status',
        'Transfer Date'
    ];
    
    fputcsv($output, $headers);
    
    // Data Rows
    if (count($data) > 0) {
        foreach ($data as $index => $item) {
            $condition_labels = ['working' => 'Working', 'good' => 'Good', 'not_working' => 'Not Working', 'not_usable' => 'Not Usable'];
            $status = $item['is_transferred'] ? 'Transferred' : ($item['borrowed_officer'] ? 'Borrowed' : 'Available');
            
            $row = [
                $index + 1,
                $item['item_name'],
                $item['brand_name'] ?: '',
                $item['inventory_number'],
                $item['serial_number'] ?: '',
                $item['section_name'] ?: '',
                $item['book_number'],
                $item['incharge_name'] ?: '',
                $item['received_date'] ?: '',
                $item['received_from'] ?: '',
                $condition_labels[$item['condition_status']] ?? $item['condition_status'],
                $status,
                $item['borrowed_officer'] ?: '',
                $item['is_transferred'] ? 'Yes' : 'No',
                $item['transferred_date'] ?: ''
            ];
            
            fputcsv($output, $row);
        }
    } else {
        fputcsv($output, ['No items found matching the selected filters.']);
    }
    
    // Footer
    fputcsv($output, []);
    fputcsv($output, ['']);
    fputcsv($output, ['Generated by Inventory Management System']);
    fputcsv($output, ['Inventory Officer: ' . $school_info['inventory_officer_name']]);
    fputcsv($output, ['Contact: ' . $school_info['inventory_officer_phone']]);
    
    fclose($output);
    exit();
}

// Get sections for filter
$sections = mysqli_query($conn, "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name");

// Get initial data for display
$initial_data = getReportData($conn, $school_id, [
    'section' => $filter_section,
    'condition' => $filter_condition,
    'status' => $filter_status,
    'date_from' => $filter_date_from,
    'date_to' => $filter_date_to,
    'search' => $filter_search
]);
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-file-earmark-text"></i> Inventory Reports</h2>
                <p class="text-muted">Generate and download inventory reports with filters</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="content-card">
            <h5 class="mb-3"><i class="bi bi-funnel"></i> Filter Options</h5>
            
            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">Section</label>
                    <select class="form-select" name="section">
                        <option value="">All Sections</option>
                        <?php while ($sec = mysqli_fetch_assoc($sections)): ?>
                        <option value="<?php echo $sec['id']; ?>" <?php echo $filter_section == $sec['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sec['section_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Condition</label>
                    <select class="form-select" name="condition">
                        <option value="">All Conditions</option>
                        <option value="working" <?php echo $filter_condition == 'working' ? 'selected' : ''; ?>>Working</option>
                        <option value="good" <?php echo $filter_condition == 'good' ? 'selected' : ''; ?>>Good</option>
                        <option value="not_working" <?php echo $filter_condition == 'not_working' ? 'selected' : ''; ?>>Not Working</option>
                        <option value="not_usable" <?php echo $filter_condition == 'not_usable' ? 'selected' : ''; ?>>Not Usable</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="available" <?php echo $filter_status == 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="borrowed" <?php echo $filter_status == 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                        <option value="transferred" <?php echo $filter_status == 'transferred' ? 'selected' : ''; ?>>Transferred</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" placeholder="Item name, inventory #..." value="<?php echo htmlspecialchars($filter_search); ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" class="form-control" name="date_from" value="<?php echo $filter_date_from; ?>">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" class="form-control" name="date_to" value="<?php echo $filter_date_to; ?>">
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Apply Filters
                    </button>
                    <a href="report.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Report Actions Card -->
        <div class="content-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0"><i class="bi bi-download"></i> Download Report</h5>
                    <p class="text-muted small mb-0">Download the current filtered data as PDF or Excel</p>
                </div>
                <div class="col-md-6 text-end">
                    <form method="POST" style="display: inline-block;" id="pdfForm">
                        <input type="hidden" name="section" value="<?php echo $filter_section; ?>">
                        <input type="hidden" name="condition" value="<?php echo $filter_condition; ?>">
                        <input type="hidden" name="status" value="<?php echo $filter_status; ?>">
                        <input type="hidden" name="date_from" value="<?php echo $filter_date_from; ?>">
                        <input type="hidden" name="date_to" value="<?php echo $filter_date_to; ?>">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($filter_search); ?>">
                        <input type="hidden" name="report_type" value="pdf">
                        <button type="submit" name="generate_report" class="btn btn-danger btn-lg">
                            <i class="bi bi-file-pdf"></i> Download PDF
                        </button>
                    </form>
                    
                    <form method="POST" style="display: inline-block; margin-left: 10px;" id="excelForm">
                        <input type="hidden" name="section" value="<?php echo $filter_section; ?>">
                        <input type="hidden" name="condition" value="<?php echo $filter_condition; ?>">
                        <input type="hidden" name="status" value="<?php echo $filter_status; ?>">
                        <input type="hidden" name="date_from" value="<?php echo $filter_date_from; ?>">
                        <input type="hidden" name="date_to" value="<?php echo $filter_date_to; ?>">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($filter_search); ?>">
                        <input type="hidden" name="report_type" value="excel">
                        <button type="submit" name="generate_report" class="btn btn-success btn-lg">
                            <i class="bi bi-file-excel"></i> Download Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Items</h5>
                        <h2><?php echo count($initial_data); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Available</h5>
                        <h2>
                            <?php 
                            $available = array_filter($initial_data, function($item) {
                                return !$item['is_transferred'] && empty($item['borrowed_officer']);
                            });
                            echo count($available);
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed</h5>
                        <h2>
                            <?php 
                            $borrowed = array_filter($initial_data, function($item) {
                                return !empty($item['borrowed_officer']);
                            });
                            echo count($borrowed);
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Transferred</h5>
                        <h2>
                            <?php 
                            $transferred = array_filter($initial_data, function($item) {
                                return $item['is_transferred'] == 1;
                            });
                            echo count($transferred);
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="content-card mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-table"></i> Report Results</h5>
                <span class="badge bg-primary"><?php echo count($initial_data); ?> items found</span>
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
                            <th>Condition</th>
                            <th>Status</th>
                            <th>Received Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($initial_data) > 0): ?>
                            <?php foreach ($initial_data as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td><?php echo htmlspecialchars($item['section_name'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                                <td>
                                    <?php
                                    $condition_class = [
                                        'working' => 'success',
                                        'good' => 'info',
                                        'not_working' => 'warning',
                                        'not_usable' => 'danger'
                                    ];
                                    $condition_text = [
                                        'working' => 'Working',
                                        'good' => 'Good',
                                        'not_working' => 'Not Working',
                                        'not_usable' => 'Not Usable'
                                    ];
                                    $status = $item['condition_status'] ?: 'working';
                                    ?>
                                    <span class="badge bg-<?php echo $condition_class[$status] ?? 'secondary'; ?>">
                                        <?php echo $condition_text[$status] ?? $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($item['is_transferred']): ?>
                                        <span class="badge bg-warning">Transferred</span>
                                    <?php elseif ($item['borrowed_officer']): ?>
                                        <span class="badge bg-info">Borrowed</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $item['received_date'] ? date('Y-m-d', strtotime($item['received_date'])) : '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                    No items found matching the selected filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- School Info Footer -->
        <div class="content-card">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="bi bi-building"></i> School Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <td style="width: 120px;"><strong>School Name:</strong></td>
                            <td><?php echo $school_info['school_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td><?php echo $school_info['address']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Contact:</strong></td>
                            <td><?php echo $school_info['phone']; ?> | <?php echo $school_info['email']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="bi bi-person-badge"></i> Report Generated By</h6>
                    <table class="table table-sm">
                        <tr>
                            <td style="width: 120px;"><strong>Principal:</strong></td>
                            <td><?php echo $school_info['principal_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Inventory Officer:</strong></td>
                            <td><?php echo $school_info['inventory_officer_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Officer Phone:</strong></td>
                            <td><?php echo $school_info['inventory_officer_phone']; ?></td>
                        </tr>
                    </table>
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
    max-height: 500px;
    overflow-y: auto;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}

.btn-lg {
    padding: 10px 20px;
}

@media (max-width: 768px) {
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>

<script>
// Form validation for report generation
document.getElementById('pdfForm')?.addEventListener('submit', function(e) {
    if (<?php echo count($initial_data); ?> === 0) {
        e.preventDefault();
        alert('No data to generate PDF report. Please adjust your filters.');
    }
});

document.getElementById('excelForm')?.addEventListener('submit', function(e) {
    if (<?php echo count($initial_data); ?> === 0) {
        e.preventDefault();
        alert('No data to generate Excel report. Please adjust your filters.');
    }
});

// Auto-submit filter form when filters change (optional)
// You can enable this if you want real-time filtering
/*
document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
*/
</script>

<?php require_once "footer.php"; ?>