<?php
// check_report.php
// Start with NO whitespace or HTML before this PHP tag

// Check for exports FIRST - before any output or includes
if (isset($_POST['export_pdf']) || isset($_POST['export_excel'])) {
    
    // For export, only include necessary files
    require_once "include.php";
    session_start();
    
    $school_id = $_SESSION['school_id'] ?? 1;
    $session_id = isset($_POST['session_id']) ? (int)$_POST['session_id'] : 0;
    
    if (!$session_id) {
        die("Invalid session ID");
    }
    
    // Get session details
    $session_query = "SELECT * FROM inventory_check_sessions 
                      WHERE id = $session_id AND school_id = $school_id";
    $session_result = mysqli_query($conn, $session_query);
    
    if (!$session_result || mysqli_num_rows($session_result) == 0) {
        die("Session not found");
    }
    
    $session = mysqli_fetch_assoc($session_result);
    
    // Get all check items for this session
    $items_query = "SELECT ci.* 
                    FROM inventory_check_items ci 
                    WHERE ci.session_id = $session_id 
                    ORDER BY ci.item_name";
    $items_result = mysqli_query($conn, $items_query);
    
    $items_data = [];
    $checked_items = 0;
    $total_book = 0;
    $total_real = 0;
    $total_surplus = 0;
    $total_deficiency = 0;
    $items_with_discrepancy = 0;
    
    if ($items_result && mysqli_num_rows($items_result) > 0) {
        while ($item = mysqli_fetch_assoc($items_result)) {
            $items_data[] = $item;
            
            if ($item['checked_status'] == 'checked') {
                $checked_items++;
            }
            
            $total_book += $item['book_balance'];
            $total_real += $item['real_balance'];
            $total_surplus += $item['surplus'];
            $total_deficiency += $item['deficiency'];
            
            if ($item['surplus'] > 0 || $item['deficiency'] > 0) {
                $items_with_discrepancy++;
            }
        }
    }
    
    // Handle PDF export
    if (isset($_POST['export_pdf'])) {
        exportPDF($conn, $session, $items_data, $school_id, [
            'checked_items' => $checked_items,
            'total_book' => $total_book,
            'total_real' => $total_real,
            'total_surplus' => $total_surplus,
            'total_deficiency' => $total_deficiency,
            'items_with_discrepancy' => $items_with_discrepancy
        ]);
        exit();
    }
    
    // Handle Excel export
    if (isset($_POST['export_excel'])) {
        exportExcel($session, $items_data, [
            'checked_items' => $checked_items,
            'total_book' => $total_book,
            'total_real' => $total_real,
            'total_surplus' => $total_surplus,
            'total_deficiency' => $total_deficiency,
            'items_with_discrepancy' => $items_with_discrepancy
        ]);
        exit();
    }
}

// For normal page view, include full header
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';

// Get session ID from URL
$session_id = isset($_GET['session_id']) ? (int)$_GET['session_id'] : 0;

if (!$session_id) {
    header("location: check_inventory.php");
    exit();
}

// Get session details
$session_query = "SELECT * FROM inventory_check_sessions 
                  WHERE id = $session_id AND school_id = $school_id";
$session_result = mysqli_query($conn, $session_query);

if (!$session_result || mysqli_num_rows($session_result) == 0) {
    header("location: check_inventory.php");
    exit();
}

$session = mysqli_fetch_assoc($session_result);

// Get all check items for this session
$items_query = "SELECT ci.* 
                FROM inventory_check_items ci 
                WHERE ci.session_id = $session_id 
                ORDER BY ci.item_name";
$items_result = mysqli_query($conn, $items_query);

if (!$items_result) {
    die("Error fetching items: " . mysqli_error($conn));
}

// Calculate summary statistics
$total_items = mysqli_num_rows($items_result);
$checked_items = 0;
$total_book = 0;
$total_real = 0;
$total_surplus = 0;
$total_deficiency = 0;
$items_with_discrepancy = 0;

$items_data = [];
if ($items_result && mysqli_num_rows($items_result) > 0) {
    while ($item = mysqli_fetch_assoc($items_result)) {
        $items_data[] = $item;
        
        if ($item['checked_status'] == 'checked') {
            $checked_items++;
        }
        
        $total_book += $item['book_balance'];
        $total_real += $item['real_balance'];
        $total_surplus += $item['surplus'];
        $total_deficiency += $item['deficiency'];
        
        if ($item['surplus'] > 0 || $item['deficiency'] > 0) {
            $items_with_discrepancy++;
        }
    }
}

// Function to export as PDF
function exportPDF($conn, $session, $items_data, $school_id, $stats) {
    // Get school info
    $school_query = "SELECT * FROM school_info WHERE id = $school_id";
    $school_result = mysqli_query($conn, $school_query);
    $school = mysqli_fetch_assoc($school_result);
    
    // Include FPDF
    require_once('fpdf/fpdf.php');
    
    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Inventory Check Report', 0, 1, 'C');
            $this->Ln(5);
        }
        
        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $pdf = new PDF();
    $pdf->AddPage();
    
    // School Info
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 8, $school['school_name'], 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, $school['address'], 0, 1, 'L');
    $pdf->Cell(0, 6, 'Phone: ' . $school['phone'] . ' | Email: ' . $school['email'], 0, 1, 'L');
    $pdf->Ln(5);
    
    // Session Info
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'Session: ' . $session['session_name'], 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Check Date: ' . date('F j, Y', strtotime($session['check_date'])), 0, 1, 'L');
    $pdf->Cell(0, 6, 'Conducted By: ' . ($session['conducted_by'] ?: 'Not specified'), 0, 1, 'L');
    if ($session['notes']) {
        $pdf->Cell(0, 6, 'Notes: ' . $session['notes'], 0, 1, 'L');
    }
    $pdf->Cell(0, 6, 'Report Generated: ' . date('F j, Y h:i A'), 0, 1, 'L');
    $pdf->Ln(5);
    
    // Summary Statistics
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'Summary', 0, 1, 'L');
    $pdf->SetFont('Arial', '', 10);
    
    $pdf->Cell(50, 6, 'Total Items:', 0, 0, 'L');
    $pdf->Cell(50, 6, count($items_data), 0, 1, 'L');
    
    $pdf->Cell(50, 6, 'Checked Items:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['checked_items'] . ' (' . round(($stats['checked_items']/count($items_data))*100) . '%)', 0, 1, 'L');
    
    $pdf->Cell(50, 6, 'Items with Discrepancy:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['items_with_discrepancy'], 0, 1, 'L');
    
    $pdf->Cell(50, 6, 'Total Book Balance:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['total_book'], 0, 1, 'L');
    
    $pdf->Cell(50, 6, 'Total Real Balance:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['total_real'], 0, 1, 'L');
    
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(50, 6, 'Total Surplus:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['total_surplus'], 0, 1, 'L');
    
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(50, 6, 'Total Deficiency:', 0, 0, 'L');
    $pdf->Cell(50, 6, $stats['total_deficiency'], 0, 1, 'L');
    
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(10);
    
    // Items Table
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(10, 7, '#', 1, 0, 'C');
    $pdf->Cell(60, 7, 'Item Name', 1, 0, 'L');
    $pdf->Cell(30, 7, 'Inventory #', 1, 0, 'L');
    $pdf->Cell(20, 7, 'Book', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Real', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Surplus', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Deficit', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 9);
    $count = 1;
    foreach ($items_data as $item) {
        $pdf->Cell(10, 6, $count++, 1, 0, 'C');
        $pdf->Cell(60, 6, substr($item['item_name'], 0, 25), 1, 0, 'L');
        $pdf->Cell(30, 6, $item['inventory_number'], 1, 0, 'L');
        $pdf->Cell(20, 6, $item['book_balance'], 1, 0, 'C');
        $pdf->Cell(20, 6, $item['real_balance'], 1, 0, 'C');
        
        if ($item['surplus'] > 0) {
            $pdf->SetTextColor(0, 128, 0);
        } else {
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->Cell(20, 6, $item['surplus'], 1, 0, 'C');
        
        if ($item['deficiency'] > 0) {
            $pdf->SetTextColor(255, 0, 0);
        } else {
            $pdf->SetTextColor(0, 0, 0);
        }
        $pdf->Cell(20, 6, $item['deficiency'], 1, 1, 'C');
        
        $pdf->SetTextColor(0, 0, 0);
    }
    
    // Output PDF
    $filename = 'check_report_' . $session['id'] . '_' . date('Y-m-d') . '.pdf';
    $pdf->Output('D', $filename);
    exit();
}

// Function to export as Excel
function exportExcel($session, $items_data, $stats) {
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    $filename = 'check_report_' . $session['id'] . '_' . date('Y-m-d') . '.csv';
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
    
    // Title
    fputcsv($output, ['INVENTORY CHECK REPORT']);
    fputcsv($output, ['Session: ' . $session['session_name']]);
    fputcsv($output, ['Date: ' . date('F j, Y', strtotime($session['check_date']))]);
    fputcsv($output, ['Conducted By: ' . ($session['conducted_by'] ?: 'Not specified')]);
    if ($session['notes']) {
        fputcsv($output, ['Notes: ' . $session['notes']]);
    }
    fputcsv($output, ['Generated: ' . date('Y-m-d H:i:s')]);
    fputcsv($output, []);
    
    // Summary
    fputcsv($output, ['SUMMARY']);
    fputcsv($output, ['Total Items', count($items_data)]);
    fputcsv($output, ['Checked Items', $stats['checked_items']]);
    fputcsv($output, ['Items with Discrepancy', $stats['items_with_discrepancy']]);
    fputcsv($output, ['Total Book Balance', $stats['total_book']]);
    fputcsv($output, ['Total Real Balance', $stats['total_real']]);
    fputcsv($output, ['Total Surplus', $stats['total_surplus']]);
    fputcsv($output, ['Total Deficiency', $stats['total_deficiency']]);
    fputcsv($output, []);
    
    // Headers
    fputcsv($output, ['#', 'Item Name', 'Inventory Number', 'Book Balance', 'Real Balance', 'Surplus', 'Deficiency', 'Remarks', 'Status']);
    
    // Data rows
    $count = 1;
    foreach ($items_data as $item) {
        fputcsv($output, [
            $count++,
            $item['item_name'],
            $item['inventory_number'],
            $item['book_balance'],
            $item['real_balance'],
            $item['surplus'],
            $item['deficiency'],
            $item['remarks'] ?? '',
            $item['checked_status']
        ]);
    }
    
    fclose($output);
    exit();
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-file-text"></i> Inventory Check Report</h2>
                <p class="text-muted">Detailed report for: <?php echo htmlspecialchars($session['session_name']); ?></p>
            </div>
            <div>
                <a href="check_inventory.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Checks
                </a>
            </div>
        </div>

        <!-- Session Info Card -->
        <div class="content-card">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3"><?php echo htmlspecialchars($session['session_name']); ?></h5>
                    <table class="table table-sm">
                        <tr>
                            <td style="width: 120px;"><strong>Check Date:</strong></td>
                            <td><?php echo date('F j, Y', strtotime($session['check_date'])); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Conducted By:</strong></td>
                            <td><?php echo htmlspecialchars($session['conducted_by'] ?: 'Not specified'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <?php if ($session['status'] == 'completed'): ?>
                                    <span class="badge bg-success">Completed</span>
                                <?php elseif ($session['status'] == 'active'): ?>
                                    <span class="badge bg-warning">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo $session['status']; ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($session['notes']): ?>
                        <tr>
                            <td><strong>Notes:</strong></td>
                            <td><?php echo nl2br(htmlspecialchars($session['notes'])); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td><?php echo date('F j, Y h:i A', strtotime($session['created_at'])); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group-vertical w-100">
                        <form method="POST" class="mb-2">
                            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                            <button type="submit" name="export_pdf" class="btn btn-danger w-100">
                                <i class="bi bi-file-pdf"></i> Export as PDF
                            </button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                            <button type="submit" name="export_excel" class="btn btn-success w-100">
                                <i class="bi bi-file-excel"></i> Export as Excel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Items</h5>
                        <h2><?php echo $total_items; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Checked Items</h5>
                        <h2><?php echo $checked_items; ?></h2>
                        <small><?php echo $total_items > 0 ? round(($checked_items/$total_items)*100) : 0; ?>% complete</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Discrepancies</h5>
                        <h2><?php echo $items_with_discrepancy; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Net Difference</h5>
                        <h2 class="<?php echo ($total_real - $total_book) >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php 
                            $net_diff = $total_real - $total_book;
                            echo ($net_diff >= 0 ? '+' : '') . $net_diff;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Summary -->
        <div class="content-card">
            <h5 class="mb-3">Balance Summary</h5>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 200px;">Total Book Balance:</th>
                            <td><strong><?php echo $total_book; ?></strong></td>
                        </tr>
                        <tr>
                            <th>Total Real Balance:</th>
                            <td><strong><?php echo $total_real; ?></strong></td>
                        </tr>
                        <tr class="table-success">
                            <th>Total Surplus:</th>
                            <td><strong class="text-success">+<?php echo $total_surplus; ?></strong></td>
                        </tr>
                        <tr class="table-danger">
                            <th>Total Deficiency:</th>
                            <td><strong class="text-danger">-<?php echo $total_deficiency; ?></strong></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Accuracy Rate</h6>
                            <?php
                            $accuracy = 100;
                            if ($total_book > 0) {
                                $accuracy = 100 - (($total_surplus + $total_deficiency) / $total_book * 100);
                                $accuracy = max(0, min(100, round($accuracy, 2)));
                            }
                            ?>
                            <h2><?php echo $accuracy; ?>%</h2>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: <?php echo $accuracy; ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="content-card">
            <h5 class="mb-3">Item-wise Details</h5>
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Inventory Number</th>
                            <th>Book Balance</th>
                            <th>Real Balance</th>
                            <th>Surplus</th>
                            <th>Deficiency</th>
                            <th>Remarks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($items_data) > 0): ?>
                            <?php $count = 1; ?>
                            <?php foreach ($items_data as $item): ?>
                            <tr class="<?php 
                                if ($item['surplus'] > 0) echo 'table-success';
                                elseif ($item['deficiency'] > 0) echo 'table-danger';
                            ?>">
                                <td><?php echo $count++; ?></td>
                                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td class="text-center"><?php echo $item['book_balance']; ?></td>
                                <td class="text-center"><?php echo $item['real_balance']; ?></td>
                                <td class="text-center <?php echo $item['surplus'] > 0 ? 'text-success fw-bold' : ''; ?>">
                                    <?php echo $item['surplus'] > 0 ? '+' . $item['surplus'] : '-'; ?>
                                </td>
                                <td class="text-center <?php echo $item['deficiency'] > 0 ? 'text-danger fw-bold' : ''; ?>">
                                    <?php echo $item['deficiency'] > 0 ? '-' . $item['deficiency'] : '-'; ?>
                                </td>
                                <td><?php echo htmlspecialchars($item['remarks'] ?? ''); ?></td>
                                <td>
                                    <?php if ($item['checked_status'] == 'checked'): ?>
                                        <span class="badge bg-success">Checked</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No items found in this check session.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="3">Totals</th>
                            <th class="text-center"><?php echo $total_book; ?></th>
                            <th class="text-center"><?php echo $total_real; ?></th>
                            <th class="text-center text-success"><?php echo $total_surplus; ?></th>
                            <th class="text-center text-danger"><?php echo $total_deficiency; ?></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Discrepancy Analysis -->
        <?php if ($items_with_discrepancy > 0): ?>
        <div class="content-card">
            <h5 class="mb-3">Discrepancy Analysis</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6>Items with Surplus</h6>
                    <table class="table table-sm table-success">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Inventory #</th>
                                <th>Book</th>
                                <th>Real</th>
                                <th>Surplus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $surplus_items = array_filter($items_data, function($item) {
                                return $item['surplus'] > 0;
                            });
                            if (count($surplus_items) > 0):
                                foreach ($surplus_items as $item): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['inventory_number']); ?></td>
                                <td><?php echo $item['book_balance']; ?></td>
                                <td><?php echo $item['real_balance']; ?></td>
                                <td class="text-success fw-bold">+<?php echo $item['surplus']; ?></td>
                            </tr>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No surplus items</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Items with Deficiency</h6>
                    <table class="table table-sm table-danger">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Inventory #</th>
                                <th>Book</th>
                                <th>Real</th>
                                <th>Deficiency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $deficit_items = array_filter($items_data, function($item) {
                                return $item['deficiency'] > 0;
                            });
                            if (count($deficit_items) > 0):
                                foreach ($deficit_items as $item): 
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['inventory_number']); ?></td>
                                <td><?php echo $item['book_balance']; ?></td>
                                <td><?php echo $item['real_balance']; ?></td>
                                <td class="text-danger fw-bold">-<?php echo $item['deficiency']; ?></td>
                            </tr>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No deficit items</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="content-card">
            <div class="row">
                <div class="col-12">
                    <a href="check_inventory.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Inventory Checks
                    </a>
                    <?php if ($session['status'] == 'active'): ?>
                    <a href="check_inventory.php?session_id=<?php echo $session_id; ?>" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Continue This Check
                    </a>
                    <?php endif; ?>
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

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table th {
    vertical-align: middle;
}

.table-success {
    background-color: #d4edda !important;
}

.table-danger {
    background-color: #f8d7da !important;
}

.badge {
    font-size: 0.9rem;
    padding: 0.4rem 0.6rem;
}

.progress {
    border-radius: 10px;
}

@media print {
    .btn, .btn-group, .content-card:last-child {
        display: none;
    }
}
</style>

<script>
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