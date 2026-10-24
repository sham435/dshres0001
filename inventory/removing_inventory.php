<?php
// removing_inventory.php
// Start with NO whitespace or HTML before this PHP tag

// Check for download FIRST - before any output or includes
if (isset($_POST['download_report'])) {
    // For download, only include necessary files
    require_once "include.php";
    session_start();
    
    $school_id = $_SESSION['school_id'] ?? 1;
    $report_type = $_POST['report_type'];
    
    // Clear any output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    if ($report_type == 'pending') {
        $query = "SELECT r.*, s.section_name, o.officer_name as incharge_name 
                  FROM removal_recommendations r 
                  LEFT JOIN sections s ON r.section_id = s.id 
                  LEFT JOIN officers o ON r.incharge_officer_id = o.id 
                  WHERE r.school_id = $school_id AND r.status = 'pending'
                  ORDER BY r.recommendation_date DESC";
        $filename = "pending_removals_" . date('Y-m-d') . ".csv";
        $title = "Pending Removal Recommendations";
    } elseif ($report_type == 'archive') {
        $query = "SELECT * FROM removed_items_archive 
                  WHERE school_id = $school_id 
                  ORDER BY removal_date DESC";
        $filename = "removed_items_archive_" . date('Y-m-d') . ".csv";
        $title = "Removed Items Archive";
    } else {
        exit();
    }
    
    $result = mysqli_query($conn, $query);
    
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
    
    // Add title
    fputcsv($output, [$title]);
    fputcsv($output, ['Generated on: ' . date('Y-m-d H:i:s')]);
    fputcsv($output, []);
    
    if ($report_type == 'pending') {
        // Headers for pending
        fputcsv($output, ['ID', 'Item Name', 'Inventory Number', 'Section', 'Reason', 'Recommended By', 'Recommendation Date', 'Notes']);
        
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['original_item_id'],
                $row['item_name'],
                $row['inventory_number'],
                $row['section_name'] ?? 'N/A',
                $row['recommendation_reason'],
                $row['recommended_by'],
                date('Y-m-d', strtotime($row['recommendation_date'])),
                $row['notes'] ?? ''
            ]);
        }
    } else {
        // Headers for archive
        fputcsv($output, ['ID', 'Item Name', 'Inventory Number', 'Section', 'Removal Reason', 'Removed By', 'Removal Date', 'Disposal Method', 'Notes']);
        
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, [
                $row['original_item_id'],
                $row['item_name'],
                $row['inventory_number'],
                $row['section_name'] ?? 'N/A',
                $row['removal_reason'],
                $row['removed_by'],
                date('Y-m-d', strtotime($row['removal_date'])),
                $row['disposal_method'] ?? 'N/A',
                $row['notes'] ?? ''
            ]);
        }
    }
    
    fclose($output);
    exit();
}

// For normal page view, include full header
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'items';

// Handle form submissions (except download which is handled above)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Recommend item for removal
    if (isset($_POST['recommend_removal'])) {
        $item_id = (int)$_POST['item_id'];
        $reason = mysqli_real_escape_string($conn, trim($_POST['reason']));
        $notes = mysqli_real_escape_string($conn, trim($_POST['notes']));
        
        // Get item details
        $item_query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
                       FROM inventory_items i 
                       LEFT JOIN sections s ON i.section_id = s.id 
                       LEFT JOIN officers o ON i.incharge_officer_id = o.id 
                       WHERE i.id = $item_id AND i.school_id = $school_id";
        $item_result = mysqli_query($conn, $item_query);
        
        if ($item_result && mysqli_num_rows($item_result) > 0) {
            $item = mysqli_fetch_assoc($item_result);
            
            // Check if already recommended
            $check = mysqli_query($conn, "SELECT id FROM removal_recommendations WHERE original_item_id = $item_id AND status = 'pending'");
            if (mysqli_num_rows($check) > 0) {
                $error = "This item is already pending removal recommendation.";
            } else {
                // Insert into recommendations table
                $sql = "INSERT INTO removal_recommendations (
                            original_item_id, school_id, item_name, brand_name, 
                            inventory_number, serial_number, section_id, book_number,
                            incharge_officer_id, condition_status, recommendation_reason, 
                            recommended_by, notes, status
                        ) VALUES (
                            $item_id, $school_id, 
                            '" . mysqli_real_escape_string($conn, $item['item_name']) . "',
                            " . ($item['brand_name'] ? "'" . mysqli_real_escape_string($conn, $item['brand_name']) . "'" : "NULL") . ",
                            '" . mysqli_real_escape_string($conn, $item['inventory_number']) . "',
                            " . ($item['serial_number'] ? "'" . mysqli_real_escape_string($conn, $item['serial_number']) . "'" : "NULL") . ",
                            " . ($item['section_id'] ?: "NULL") . ",
                            '" . mysqli_real_escape_string($conn, $item['book_number']) . "',
                            " . ($item['incharge_officer_id'] ?: "NULL") . ",
                            '" . mysqli_real_escape_string($conn, $item['condition_status']) . "',
                            '$reason', 
                            '" . mysqli_real_escape_string($conn, $_SESSION['user_email']) . "',
                            " . ($notes ? "'$notes'" : "NULL") . ",
                            'pending'
                        )";
                
                if (mysqli_query($conn, $sql)) {
                    // Delete from main inventory
                    $delete_sql = "DELETE FROM inventory_items WHERE id = $item_id";
                    if (mysqli_query($conn, $delete_sql)) {
                        $message = "Item moved to pending removal recommendations.";
                    } else {
                        $error = "Error deleting from inventory: " . mysqli_error($conn);
                    }
                } else {
                    $error = "Error inserting into recommendations: " . mysqli_error($conn);
                }
            }
        } else {
            $error = "Item not found.";
        }
    }
    
    // Approve/Reject recommendation
    elseif (isset($_POST['update_recommendation'])) {
        $rec_id = (int)$_POST['recommendation_id'];
        $action = $_POST['update_recommendation'];
        $admin_notes = mysqli_real_escape_string($conn, trim($_POST['admin_notes']));
        
        // Get recommendation details
        $rec_query = "SELECT * FROM removal_recommendations WHERE id = $rec_id";
        $rec_result = mysqli_query($conn, $rec_query);
        
        if ($rec_result && mysqli_num_rows($rec_result) > 0) {
            $rec = mysqli_fetch_assoc($rec_result);
            
            if ($action == 'approve') {
                // Get section name and incharge name for archive
                $section_name = 'Unknown';
                if ($rec['section_id']) {
                    $sec_result = mysqli_query($conn, "SELECT section_name FROM sections WHERE id = " . $rec['section_id']);
                    if ($sec_result && mysqli_num_rows($sec_result) > 0) {
                        $section_name = mysqli_fetch_assoc($sec_result)['section_name'];
                    }
                }
                
                $incharge_name = 'Unknown';
                if ($rec['incharge_officer_id']) {
                    $ofc_result = mysqli_query($conn, "SELECT officer_name FROM officers WHERE id = " . $rec['incharge_officer_id']);
                    if ($ofc_result && mysqli_num_rows($ofc_result) > 0) {
                        $incharge_name = mysqli_fetch_assoc($ofc_result)['officer_name'];
                    }
                }
                
                // Get disposal method from form
                $disposal_method = isset($_POST['disposal_method']) ? mysqli_real_escape_string($conn, $_POST['disposal_method']) : '';
                
                // Move to archive
                $archive_sql = "INSERT INTO removed_items_archive (
                                    original_item_id, school_id, item_name, brand_name,
                                    inventory_number, serial_number, section_id, section_name,
                                    book_number, incharge_officer_id, incharge_officer_name,
                                    condition_status, removal_reason, removed_by, approval_status,
                                    approved_by, disposal_method, notes
                                ) VALUES (
                                    " . $rec['original_item_id'] . ", 
                                    " . $rec['school_id'] . ",
                                    '" . mysqli_real_escape_string($conn, $rec['item_name']) . "',
                                    " . ($rec['brand_name'] ? "'" . mysqli_real_escape_string($conn, $rec['brand_name']) . "'" : "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $rec['inventory_number']) . "',
                                    " . ($rec['serial_number'] ? "'" . mysqli_real_escape_string($conn, $rec['serial_number']) . "'" : "NULL") . ",
                                    " . ($rec['section_id'] ?: "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $section_name) . "',
                                    '" . mysqli_real_escape_string($conn, $rec['book_number']) . "',
                                    " . ($rec['incharge_officer_id'] ?: "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $incharge_name) . "',
                                    '" . mysqli_real_escape_string($conn, $rec['condition_status']) . "',
                                    '" . mysqli_real_escape_string($conn, $rec['recommendation_reason']) . "',
                                    '" . mysqli_real_escape_string($conn, $_SESSION['user_email']) . "',
                                    'approved',
                                    '" . mysqli_real_escape_string($conn, $_SESSION['user_email']) . "',
                                    " . ($disposal_method ? "'$disposal_method'" : "NULL") . ",
                                    " . ($admin_notes ? "'$admin_notes'" : "NULL") . "
                                )";
                
                if (mysqli_query($conn, $archive_sql)) {
                    // Delete from recommendations
                    $delete_rec_sql = "DELETE FROM removal_recommendations WHERE id = $rec_id";
                    if (mysqli_query($conn, $delete_rec_sql)) {
                        $message = "Item approved and moved to archive.";
                    } else {
                        $error = "Error deleting recommendation: " . mysqli_error($conn);
                    }
                } else {
                    $error = "Error archiving item: " . mysqli_error($conn);
                }
                
            } elseif ($action == 'reject') {
                // Move back to main inventory
                $restore_sql = "INSERT INTO inventory_items (
                                    id, school_id, item_name, brand_name, inventory_number,
                                    serial_number, section_id, book_number, incharge_officer_id,
                                    condition_status
                                ) VALUES (
                                    " . $rec['original_item_id'] . ", 
                                    " . $rec['school_id'] . ",
                                    '" . mysqli_real_escape_string($conn, $rec['item_name']) . "',
                                    " . ($rec['brand_name'] ? "'" . mysqli_real_escape_string($conn, $rec['brand_name']) . "'" : "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $rec['inventory_number']) . "',
                                    " . ($rec['serial_number'] ? "'" . mysqli_real_escape_string($conn, $rec['serial_number']) . "'" : "NULL") . ",
                                    " . ($rec['section_id'] ?: "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $rec['book_number']) . "',
                                    " . ($rec['incharge_officer_id'] ?: "NULL") . ",
                                    '" . mysqli_real_escape_string($conn, $rec['condition_status']) . "'
                                )";
                
                if (mysqli_query($conn, $restore_sql)) {
                    // Delete from recommendations
                    $delete_rec_sql = "DELETE FROM removal_recommendations WHERE id = $rec_id";
                    if (mysqli_query($conn, $delete_rec_sql)) {
                        $message = "Item rejected and moved back to inventory.";
                    } else {
                        $error = "Error deleting recommendation: " . mysqli_error($conn);
                    }
                } else {
                    $error = "Error restoring item: " . mysqli_error($conn);
                }
            }
        } else {
            $error = "Recommendation not found.";
        }
    }
    
    // Permanently delete from archive (admin only)
    elseif (isset($_POST['permanent_delete'])) {
        $archive_id = (int)$_POST['archive_id'];
        $confirm = $_POST['confirm_delete'] ?? '';
        
        if ($confirm == 'PERMANENTLY DELETE') {
            // Delete from archive
            $delete_sql = "DELETE FROM removed_items_archive WHERE id = $archive_id";
            if (mysqli_query($conn, $delete_sql)) {
                $message = "Item permanently deleted from archive.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        } else {
            $error = "Please type 'PERMANENTLY DELETE' to confirm.";
        }
    }
}

// Get current inventory items (excluding those in pending)
$items_query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
                FROM inventory_items i 
                LEFT JOIN sections s ON i.section_id = s.id 
                LEFT JOIN officers o ON i.incharge_officer_id = o.id 
                WHERE i.school_id = $school_id 
                AND NOT EXISTS (
                    SELECT 1 FROM removal_recommendations r 
                    WHERE r.original_item_id = i.id 
                    AND r.status = 'pending'
                )
                ORDER BY i.item_name";
$items = mysqli_query($conn, $items_query);

// Get pending recommendations
$pending_query = "SELECT r.*, s.section_name, o.officer_name as incharge_name 
                  FROM removal_recommendations r 
                  LEFT JOIN sections s ON r.section_id = s.id 
                  LEFT JOIN officers o ON r.incharge_officer_id = o.id 
                  WHERE r.school_id = $school_id AND r.status = 'pending'
                  ORDER BY r.recommendation_date DESC";
$pending = mysqli_query($conn, $pending_query);

// Get archived removed items
$archive_query = "SELECT a.* 
                  FROM removed_items_archive a 
                  WHERE a.school_id = $school_id 
                  ORDER BY a.removal_date DESC";
$archive = mysqli_query($conn, $archive_query);

// Count for badges
$pending_count = $pending ? mysqli_num_rows($pending) : 0;
$archive_count = $archive ? mysqli_num_rows($archive) : 0;
$items_count = $items ? mysqli_num_rows($items) : 0;
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-trash"></i> Inventory Removal Management</h2>
                <p class="text-muted">Manage item removals, recommendations, and archives</p>
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

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Current Inventory</h5>
                        <h2><?php echo $items_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Pending Removal</h5>
                        <h2><?php echo $pending_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Archived Removed</h5>
                        <h2><?php echo $archive_count; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="removalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $active_tab == 'items' ? 'active' : ''; ?>" 
                        onclick="location.href='?tab=items'">
                    <i class="bi bi-box"></i> Current Inventory
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $active_tab == 'pending' ? 'active' : ''; ?>" 
                        onclick="location.href='?tab=pending'">
                    <i class="bi bi-clock-history"></i> Pending Removals
                    <?php if ($pending_count > 0): ?>
                    <span class="badge bg-danger"><?php echo $pending_count; ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $active_tab == 'archive' ? 'active' : ''; ?>" 
                        onclick="location.href='?tab=archive'">
                    <i class="bi bi-archive"></i> Removed Items Archive
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Current Inventory Tab -->
            <?php if ($active_tab == 'items'): ?>
            <div class="content-card">
                <h5 class="mb-3"><i class="bi bi-box"></i> Current Inventory Items</h5>
                <p class="text-muted">Select items to recommend for removal</p>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Inventory #</th>
                                <th>Section</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($items && mysqli_num_rows($items) > 0): ?>
                                <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                <tr>
                                    <td>#<?php echo $item['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                    <td><?php echo htmlspecialchars($item['section_name'] ?: '-'); ?></td>
                                    <td>
                                        <?php
                                        $condition_class = [
                                            'working' => 'success',
                                            'good' => 'info',
                                            'not_working' => 'warning',
                                            'not_usable' => 'danger'
                                        ];
                                        ?>
                                        <span class="badge bg-<?php echo $condition_class[$item['condition_status']] ?? 'secondary'; ?>">
                                            <?php echo ucfirst($item['condition_status']); ?>
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
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                                data-bs-target="#recommendModal<?php echo $item['id']; ?>">
                                            <i class="bi bi-trash"></i> Recommend Removal
                                        </button>
                                        <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Recommend Modal -->
                                <div class="modal fade" id="recommendModal<?php echo $item['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-trash"></i> Recommend Item for Removal
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Item</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($item['item_name'] . ' (' . $item['inventory_number'] . ')'); ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Removal <span class="text-danger">*</span></label>
                                                        <select class="form-select" name="reason" required>
                                                            <option value="">Select Reason</option>
                                                            <option value="Damaged">Damaged / Not Usable</option>
                                                            <option value="Obsolete">Obsolete / Outdated</option>
                                                            <option value="Lost">Lost / Missing</option>
                                                            <option value="Sold">Sold / Disposed</option>
                                                            <option value="Transferred Out">Transferred to Another School</option>
                                                            <option value="Donated">Donated</option>
                                                            <option value="Stolen">Stolen (Reported)</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Additional Notes</label>
                                                        <textarea class="form-control" name="notes" rows="3" placeholder="Any additional details..."></textarea>
                                                    </div>
                                                    
                                                    <div class="alert alert-info">
                                                        <i class="bi bi-info-circle"></i>
                                                        This item will be moved to pending removals list.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="recommend_removal" class="btn btn-warning">
                                                        <i class="bi bi-check-circle"></i> Move to Pending
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted">No inventory items found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pending Removals Tab -->
            <?php if ($active_tab == 'pending'): ?>
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-clock-history"></i> Pending Removal Recommendations</h5>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="report_type" value="pending">
                        <button type="submit" name="download_report" class="btn btn-success">
                            <i class="bi bi-download"></i> Download Pending List
                        </button>
                    </form>
                </div>
                <p class="text-muted">Review and approve/reject removal requests</p>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Inventory #</th>
                                <th>Section</th>
                                <th>Reason</th>
                                <th>Recommended By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($pending && mysqli_num_rows($pending) > 0): ?>
                                <?php while ($rec = mysqli_fetch_assoc($pending)): ?>
                                <tr>
                                    <td>#<?php echo $rec['original_item_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($rec['item_name']); ?></strong></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($rec['inventory_number']); ?></span></td>
                                    <td><?php echo htmlspecialchars($rec['section_name'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($rec['recommendation_reason']); ?></td>
                                    <td><?php echo htmlspecialchars($rec['recommended_by']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($rec['recommendation_date'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" 
                                                data-bs-target="#reviewModal<?php echo $rec['id']; ?>">
                                            <i class="bi bi-check-circle"></i> Review
                                        </button>
                                    </td>
                                </tr>

                                <!-- Review Modal -->
                                <div class="modal fade" id="reviewModal<?php echo $rec['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Review Removal Recommendation</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="recommendation_id" value="<?php echo $rec['id']; ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Item</label>
                                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($rec['item_name'] . ' (' . $rec['inventory_number'] . ')'); ?>" readonly>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason</label>
                                                        <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($rec['recommendation_reason']); ?></textarea>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Notes</label>
                                                        <textarea class="form-control" rows="2" readonly><?php echo htmlspecialchars($rec['notes'] ?: 'No notes'); ?></textarea>
                                                    </div>
                                                    
                                                    <div class="mb-3" id="disposal_field_<?php echo $rec['id']; ?>" style="display: none;">
                                                        <label class="form-label">Disposal Method (if approved)</label>
                                                        <select class="form-select" name="disposal_method">
                                                            <option value="">Select Method</option>
                                                            <option value="Destroyed">Destroyed</option>
                                                            <option value="Donated">Donated</option>
                                                            <option value="Sold">Sold</option>
                                                            <option value="Recycled">Recycled</option>
                                                            <option value="Transferred">Transferred</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Review Notes</label>
                                                        <textarea class="form-control" name="admin_notes" rows="2" placeholder="Add notes about your decision..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_recommendation" value="reject" class="btn btn-danger" onclick="return confirm('Reject will move this item back to inventory. Continue?')">
                                                        <i class="bi bi-x-circle"></i> Reject & Restore
                                                    </button>
                                                    <button type="submit" name="update_recommendation" value="approve" class="btn btn-success" onclick="return confirm('Approve will permanently remove this item from inventory and move to archive. Continue?')">
                                                        <i class="bi bi-check-circle"></i> Approve & Archive
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center text-muted">No pending recommendations.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Removed Items Archive Tab -->
            <?php if ($active_tab == 'archive'): ?>
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5><i class="bi bi-archive"></i> Removed Items Archive</h5>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="report_type" value="archive">
                        <button type="submit" name="download_report" class="btn btn-success">
                            <i class="bi bi-download"></i> Download Archive List
                        </button>
                    </form>
                </div>
                <p class="text-muted">History of permanently removed items</p>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Item Name</th>
                                <th>Inventory #</th>
                                <th>Section</th>
                                <th>Removal Reason</th>
                                <th>Removed By</th>
                                <th>Removal Date</th>
                                <th>Disposal Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($archive && mysqli_num_rows($archive) > 0): ?>
                                <?php while ($arch = mysqli_fetch_assoc($archive)): ?>
                                <tr>
                                    <td>#<?php echo $arch['original_item_id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($arch['item_name']); ?></strong></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($arch['inventory_number']); ?></span></td>
                                    <td><?php echo htmlspecialchars($arch['section_name'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($arch['removal_reason']); ?></td>
                                    <td><?php echo htmlspecialchars($arch['removed_by']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($arch['removal_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($arch['disposal_method'] ?: '-'); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                data-bs-target="#viewArchiveModal<?php echo $arch['id']; ?>">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        <?php if ($_SESSION['user_type'] == 'admin'): ?>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                                data-bs-target="#deleteArchiveModal<?php echo $arch['id']; ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <!-- View Archive Modal -->
                                <div class="modal fade" id="viewArchiveModal<?php echo $arch['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title">Archived Item Details</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-sm">
                                                    <tr><th>Item Name:</th><td><?php echo htmlspecialchars($arch['item_name']); ?></td></tr>
                                                    <tr><th>Brand:</th><td><?php echo htmlspecialchars($arch['brand_name'] ?: '-'); ?></td></tr>
                                                    <tr><th>Inventory #:</th><td><?php echo htmlspecialchars($arch['inventory_number']); ?></td></tr>
                                                    <tr><th>Serial #:</th><td><?php echo htmlspecialchars($arch['serial_number'] ?: '-'); ?></td></tr>
                                                    <tr><th>Section:</th><td><?php echo htmlspecialchars($arch['section_name'] ?: '-'); ?></td></tr>
                                                    <tr><th>Book #:</th><td><?php echo htmlspecialchars($arch['book_number']); ?></td></tr>
                                                    <tr><th>Removal Reason:</th><td><?php echo htmlspecialchars($arch['removal_reason']); ?></td></tr>
                                                    <tr><th>Removed By:</th><td><?php echo htmlspecialchars($arch['removed_by']); ?></td></tr>
                                                    <tr><th>Removal Date:</th><td><?php echo date('F j, Y h:i A', strtotime($arch['removal_date'])); ?></td></tr>
                                                    <tr><th>Disposal Method:</th><td><?php echo htmlspecialchars($arch['disposal_method'] ?: '-'); ?></td></tr>
                                                    <tr><th>Notes:</th><td><?php echo nl2br(htmlspecialchars($arch['notes'] ?: 'No notes')); ?></td></tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permanent Delete Modal (Admin only) -->
                                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                                <div class="modal fade" id="deleteArchiveModal<?php echo $arch['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Permanently Delete Archived Item</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="archive_id" value="<?php echo $arch['id']; ?>">
                                                    
                                                    <div class="alert alert-danger">
                                                        <i class="bi bi-exclamation-triangle"></i>
                                                        <strong>Warning:</strong> This action cannot be undone!
                                                    </div>
                                                    
                                                    <p>You are about to permanently delete: <strong><?php echo htmlspecialchars($arch['item_name']); ?></strong></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Type <strong>PERMANENTLY DELETE</strong> to confirm:</label>
                                                        <input type="text" class="form-control" name="confirm_delete" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="permanent_delete" class="btn btn-danger">
                                                        <i class="bi bi-trash"></i> Permanently Delete
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="9" class="text-center text-muted">No archived items found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Workflow Summary Card -->
        <div class="content-card mt-4">
            <h6><i class="bi bi-diagram-3"></i> Removal Workflow</h6>
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="p-3 bg-light rounded">
                        <i class="bi bi-box text-primary" style="font-size: 2rem;"></i>
                        <h6>Step 1: Current Inventory</h6>
                        <small>Select items to recommend for removal</small>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-3 bg-light rounded">
                        <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                        <h6>Step 2: Pending Review</h6>
                        <small>Approve or reject removal requests</small>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-3 bg-light rounded">
                        <i class="bi bi-archive text-secondary" style="font-size: 2rem;"></i>
                        <h6>Step 3: Archive</h6>
                        <small>Permanently removed items history</small>
                    </div>
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

.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    font-weight: 600;
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
}

.badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.6rem;
}

.modal-dialog {
    max-width: 500px;
}

.modal-header.bg-warning, .modal-header.bg-primary, .modal-header.bg-info, .modal-header.bg-danger {
    color: white;
}

.btn-sm {
    margin-right: 3px;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 8px 10px;
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
}
</style>

<script>
// Show/hide disposal field when approve button is hovered
document.querySelectorAll('button[value="approve"]').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        // Find the closest modal and then find the disposal field
        const modal = this.closest('.modal');
        if (modal) {
            const modalId = modal.id;
            const recId = modalId.replace('reviewModal', '');
            const disposalField = document.getElementById('disposal_field_' + recId);
            
            // Show the disposal field
            if (disposalField) {
                disposalField.style.display = 'block';
            }
        }
    });
});

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Confirm before permanent deletion
document.querySelectorAll('[name="permanent_delete"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const confirmText = prompt('Type "PERMANENTLY DELETE" to confirm:');
        if (confirmText !== 'PERMANENTLY DELETE') {
            e.preventDefault();
            alert('Confirmation text did not match.');
        }
    });
});
</script>

<?php require_once "footer.php"; ?>