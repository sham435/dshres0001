<?php
// check_inventory.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';
$active_section = isset($_GET['section']) ? $_GET['section'] : 'check';
$image_search_results = [];

// Function to parse inventory number and extract range
function parseInventoryNumber($inv_number) {
    $inv_number = trim($inv_number);
    
    // Check if it contains a range pattern like 17/34/64/1 to 17/34/64/20
    if (preg_match('/(.+)\/(\d+)\s+to\s+\1\/(\d+)/i', $inv_number, $matches)) {
        $base = $matches[1];
        $start = (int)$matches[2];
        $end = (int)$matches[3];
        return [
            'type' => 'range',
            'base' => $base,
            'start' => $start,
            'end' => $end,
            'count' => $end - $start + 1
        ];
    }
    
    // Check if it ends with a number like 17/34/64/1 or 17/34/64
    if (preg_match('/(.+)\/(\d+)$/', $inv_number, $matches)) {
        return [
            'type' => 'single',
            'base' => $matches[1],
            'number' => (int)$matches[2],
            'count' => 1
        ];
    }
    
    // Regular inventory number
    return [
        'type' => 'regular',
        'count' => 1
    ];
}

// Handle image search
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image_search'])) {
    if (isset($_FILES['search_image']) && $_FILES['search_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['search_image']['tmp_name']);
        finfo_close($file_info);
        
        if (in_array($mime_type, $allowed_types) && $_FILES['search_image']['size'] <= $max_size) {
            // Get all items with images
            $items_query = "SELECT i.*, s.section_name 
                           FROM inventory_items i 
                           LEFT JOIN sections s ON i.section_id = s.id 
                           WHERE i.school_id = $school_id 
                           AND i.image_path IS NOT NULL 
                           AND i.image_path != ''
                           ORDER BY i.item_name";
            $items_result = mysqli_query($conn, $items_query);
            
            if ($items_result && mysqli_num_rows($items_result) > 0) {
                // For demo purposes, we'll just show items that have images
                // In a real application, you would use image recognition or metadata matching
                while ($item = mysqli_fetch_assoc($items_result)) {
                    if (file_exists($item['image_path'])) {
                        $image_search_results[] = $item;
                    }
                }
                
                if (count($image_search_results) > 0) {
                    $message = "Found " . count($image_search_results) . " items with images.";
                } else {
                    $error = "No items with valid images found.";
                }
            } else {
                $error = "No items with images found in the system.";
            }
        } else {
            $error = "Invalid image file. Max size 5MB. Allowed: JPG, PNG, GIF";
        }
    } else {
        $error = "Please select an image to search.";
    }
}

// Handle image upload for item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_item_image'])) {
    $item_id = (int)$_POST['item_id'];
    $upload_dir = 'uploads/items/';
    
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['item_image']['tmp_name']);
        finfo_close($file_info);
        
        if (in_array($mime_type, $allowed_types) && $_FILES['item_image']['size'] <= $max_size) {
            // Generate unique filename
            $extension = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
            $filename = 'item_' . $item_id . '_' . time() . '.' . $extension;
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['item_image']['tmp_name'], $target_path)) {
                // Update item with new image path
                $update_sql = "UPDATE inventory_items SET image_path = '$target_path' WHERE id = $item_id AND school_id = $school_id";
                if (mysqli_query($conn, $update_sql)) {
                    $message = "Image uploaded successfully for item #$item_id.";
                } else {
                    $error = "Error updating item: " . mysqli_error($conn);
                }
            } else {
                $error = "Failed to upload image";
            }
        } else {
            $error = "Invalid image file. Max size 5MB. Allowed: JPG, PNG, GIF";
        }
    } else {
        $error = "Please select an image to upload.";
    }
}

// Handle camera capture
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['camera_capture'])) {
    $image_data = $_POST['image_data'];
    $item_id = (int)($_POST['item_id'] ?? 0);
    
    // Remove data:image/png;base64, part
    $image_data = str_replace('data:image/png;base64,', '', $image_data);
    $image_data = str_replace(' ', '+', $image_data);
    $image_data = base64_decode($image_data);
    
    if ($image_data) {
        $upload_dir = 'uploads/items/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $filename = 'camera_' . time() . '_' . uniqid() . '.png';
        $target_path = $upload_dir . $filename;
        
        if (file_put_contents($target_path, $image_data)) {
            if ($item_id > 0) {
                // Update specific item
                $update_sql = "UPDATE inventory_items SET image_path = '$target_path' WHERE id = $item_id AND school_id = $school_id";
                mysqli_query($conn, $update_sql);
                $message = "Camera image saved and attached to item.";
            } else {
                $message = "Camera image saved. You can now search with this image.";
                // Store in session for later use
                $_SESSION['last_camera_image'] = $target_path;
                
                // For demo, add to search results
                $items_query = "SELECT i.*, s.section_name 
                               FROM inventory_items i 
                               LEFT JOIN sections s ON i.section_id = s.id 
                               WHERE i.school_id = $school_id 
                               AND i.image_path IS NOT NULL 
                               AND i.image_path != ''
                               ORDER BY i.item_name";
                $items_result = mysqli_query($conn, $items_query);
                
                if ($items_result && mysqli_num_rows($items_result) > 0) {
                    while ($item = mysqli_fetch_assoc($items_result)) {
                        if (file_exists($item['image_path'])) {
                            $image_search_results[] = $item;
                        }
                    }
                }
            }
        } else {
            $error = "Failed to save camera image.";
        }
    } else {
        $error = "Invalid image data.";
    }
}

// Handle form submissions for check sessions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_session'])) {
    $session_name = mysqli_real_escape_string($conn, trim($_POST['session_name']));
    $check_date = mysqli_real_escape_string($conn, $_POST['check_date']);
    $conducted_by = mysqli_real_escape_string($conn, trim($_POST['conducted_by']));
    $notes = mysqli_real_escape_string($conn, trim($_POST['notes']));
    $filter_type = $_POST['filter_type'] ?? 'all';
    $filter_value = mysqli_real_escape_string($conn, trim($_POST['filter_value'] ?? ''));
    
    if (empty($session_name)) {
        $error = "Session name is required";
    } elseif (empty($check_date)) {
        $error = "Check date is required";
    } else {
        // Insert session
        $sql = "INSERT INTO inventory_check_sessions (school_id, session_name, check_date, conducted_by, notes, status) 
                VALUES ($school_id, '$session_name', '$check_date', " . ($conducted_by ? "'$conducted_by'" : "NULL") . ", " . ($notes ? "'$notes'" : "NULL") . ", 'active')";
        
        if (mysqli_query($conn, $sql)) {
            $session_id = mysqli_insert_id($conn);
            
            // Build query based on filter
            $items_where = "WHERE i.school_id = $school_id";
            
            if ($filter_type == 'section' && !empty($filter_value)) {
                $items_where .= " AND i.section_id = " . (int)$filter_value;
            } elseif ($filter_type == 'book_number' && !empty($filter_value)) {
                $items_where .= " AND i.book_number LIKE '%" . mysqli_real_escape_string($conn, $filter_value) . "%'";
            }
            
            // Get filtered inventory items
            $items_query = "SELECT i.id, i.item_name, i.inventory_number 
                           FROM inventory_items i 
                           $items_where 
                           ORDER BY i.item_name";
            $items_result = mysqli_query($conn, $items_query);
            
            if ($items_result && mysqli_num_rows($items_result) > 0) {
                $insert_count = 0;
                while ($item = mysqli_fetch_assoc($items_result)) {
                    // Parse inventory number to determine book balance
                    $parsed = parseInventoryNumber($item['inventory_number']);
                    $book_balance = $parsed['count'];
                    
                    $insert_sql = "INSERT INTO inventory_check_items (session_id, item_id, item_name, inventory_number, book_balance) 
                                   VALUES ($session_id, {$item['id']}, '" . mysqli_real_escape_string($conn, $item['item_name']) . "', '" . mysqli_real_escape_string($conn, $item['inventory_number']) . "', $book_balance)";
                    if (mysqli_query($conn, $insert_sql)) {
                        $insert_count++;
                    }
                }
                $message = "Check session created successfully with $insert_count items.";
            } else {
                $message = "Check session created but no inventory items found matching the filter.";
            }
        } else {
            $error = "Error creating session: " . mysqli_error($conn);
        }
    }
}

// Update check items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_checks'])) {
    $session_id = (int)$_POST['session_id'];
    $items = $_POST['items'] ?? [];
    
    $updated = 0;
    foreach ($items as $check_item_id => $values) {
        $check_item_id = (int)$check_item_id;
        $real_balance = (int)($values['real_balance'] ?? 0);
        $remarks = mysqli_real_escape_string($conn, trim($values['remarks'] ?? ''));
        
        // Get book balance
        $book_query = "SELECT book_balance FROM inventory_check_items WHERE id = $check_item_id AND session_id = $session_id";
        $book_result = mysqli_query($conn, $book_query);
        $book_row = mysqli_fetch_assoc($book_result);
        $book_balance = $book_row['book_balance'] ?? 0;
        
        // Calculate surplus and deficiency
        $surplus = max(0, $real_balance - $book_balance);
        $deficiency = max(0, $book_balance - $real_balance);
        
        $update_sql = "UPDATE inventory_check_items SET 
                      real_balance = $real_balance,
                      surplus = $surplus,
                      deficiency = $deficiency,
                      remarks = " . ($remarks ? "'$remarks'" : "NULL") . ",
                      checked_status = 'checked',
                      checked_at = NOW()
                      WHERE id = $check_item_id AND session_id = $session_id";
        
        if (mysqli_query($conn, $update_sql)) {
            $updated++;
        }
    }
    
    if ($updated > 0) {
        $message = "Updated $updated items successfully.";
    }
}

// Complete check session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete_session'])) {
    $session_id = (int)$_POST['session_id'];
    
    $sql = "UPDATE inventory_check_sessions SET status = 'completed' WHERE id = $session_id AND school_id = $school_id";
    if (mysqli_query($conn, $sql)) {
        $message = "Check session completed successfully.";
    } else {
        $error = "Error completing session: " . mysqli_error($conn);
    }
}

// Get all sections for filter dropdown
$sections_query = "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name";
$sections = mysqli_query($conn, $sections_query);

// Get active sessions
$active_sessions_query = "SELECT * FROM inventory_check_sessions 
                         WHERE school_id = $school_id AND status = 'active' 
                         ORDER BY created_at DESC";
$active_sessions = mysqli_query($conn, $active_sessions_query);

// Get completed sessions for history
$completed_sessions_query = "SELECT * FROM inventory_check_sessions 
                            WHERE school_id = $school_id AND status = 'completed' 
                            ORDER BY check_date DESC LIMIT 10";
$completed_sessions = mysqli_query($conn, $completed_sessions_query);

// Get current session items if session_id is provided
$current_session_id = isset($_GET['session_id']) ? (int)$_GET['session_id'] : 0;
$current_session = null;
$check_items = null;

if ($current_session_id > 0) {
    $session_query = "SELECT * FROM inventory_check_sessions WHERE id = $current_session_id AND school_id = $school_id";
    $session_result = mysqli_query($conn, $session_query);
    if ($session_result && mysqli_num_rows($session_result) > 0) {
        $current_session = mysqli_fetch_assoc($session_result);
        
        $items_query = "SELECT * FROM inventory_check_items 
                       WHERE session_id = $current_session_id 
                       ORDER BY item_name";
        $check_items = mysqli_query($conn, $items_query);
    }
}

// Get all items for quick reference
$all_items_query = "SELECT i.*, s.section_name 
                   FROM inventory_items i 
                   LEFT JOIN sections s ON i.section_id = s.id 
                   WHERE i.school_id = $school_id 
                   ORDER BY i.item_name LIMIT 20";
$all_items = mysqli_query($conn, $all_items_query);
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-check-circle"></i> Inventory Check</h2>
                <p class="text-muted">Physical inventory verification and image-based search</p>
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

        <!-- Navigation Tabs for Sections -->
        <ul class="nav nav-tabs mb-4" id="checkTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $active_section == 'check' ? 'active' : ''; ?>" 
                        onclick="location.href='?section=check'">
                    <i class="bi bi-clipboard-check"></i> Physical Check
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $active_section == 'image_search' ? 'active' : ''; ?>" 
                        onclick="location.href='?section=image_search'">
                    <i class="bi bi-image"></i> Image Search
                </button>
            </li>
        </ul>

        <!-- Section 1: Physical Check -->
        <?php if ($active_section == 'check'): ?>
        
        <!-- Create New Check Session with Filter -->
        <div class="content-card">
            <h5 class="mb-3"><i class="bi bi-plus-circle"></i> Create New Inventory Check</h5>
            
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Session Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="session_name" placeholder="e.g., Monthly Check - March 2026" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Check Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="check_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Conducted By</label>
                    <input type="text" class="form-control" name="conducted_by" placeholder="Your name">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="create_session" class="btn btn-primary w-100">
                        <i class="bi bi-play"></i> Start Check
                    </button>
                </div>
                
                <!-- Filter Options -->
                <div class="col-md-6">
                    <label class="form-label">Filter Items By</label>
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-select" name="filter_type" id="filterType" onchange="toggleFilterInput()">
                                <option value="all">All Items</option>
                                <option value="section">Section/Room</option>
                                <option value="book_number">Book Number</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <div id="filterInputSection" style="display: none;">
                                <select class="form-select" name="filter_value" id="sectionFilter">
                                    <option value="">Select Section</option>
                                    <?php while ($sec = mysqli_fetch_assoc($sections)): ?>
                                    <option value="<?php echo $sec['id']; ?>"><?php echo htmlspecialchars($sec['section_name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div id="filterInputBook" style="display: none;">
                                <input type="text" class="form-control" name="filter_value" id="bookFilter" placeholder="Enter book number">
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Select filter type to load specific items for checking</small>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Additional notes about this check..."></textarea>
                </div>
            </form>
        </div>

        <!-- Active Check Sessions -->
        <div class="content-card">
            <h5 class="mb-3"><i class="bi bi-clock-history"></i> Active Check Sessions</h5>
            
            <?php if ($active_sessions && mysqli_num_rows($active_sessions) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Session Name</th>
                                <th>Check Date</th>
                                <th>Conducted By</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($session = mysqli_fetch_assoc($active_sessions)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($session['session_name']); ?></strong></td>
                                <td><?php echo date('Y-m-d', strtotime($session['check_date'])); ?></td>
                                <td><?php echo htmlspecialchars($session['conducted_by'] ?: 'N/A'); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($session['created_at'])); ?></td>
                                <td>
                                    <a href="?section=check&session_id=<?php echo $session['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Continue Check
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">No active check sessions. Create one above.</p>
            <?php endif; ?>
        </div>

        <!-- Current Check Session (if selected) -->
        <?php if ($current_session): ?>
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-clipboard-check"></i> Current Check: <?php echo htmlspecialchars($current_session['session_name']); ?></h5>
                <div>
                    <span class="badge bg-primary me-2">Check Date: <?php echo date('Y-m-d', strtotime($current_session['check_date'])); ?></span>
                    <span class="badge bg-info">Conducted By: <?php echo htmlspecialchars($current_session['conducted_by'] ?: 'Not specified'); ?></span>
                </div>
            </div>
            
            <?php if ($current_session['notes']): ?>
                <div class="alert alert-light">
                    <small><strong>Notes:</strong> <?php echo htmlspecialchars($current_session['notes']); ?></small>
                </div>
            <?php endif; ?>

            <form method="POST" id="checkForm">
                <input type="hidden" name="session_id" value="<?php echo $current_session_id; ?>">
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="18%">Item Name</th>
                                <th width="15%">Inventory Number</th>
                                <th width="8%">Book Balance</th>
                                <th width="8%">Real Balance</th>
                                <th width="8%">Surplus</th>
                                <th width="8%">Deficiency</th>
                                <th width="15%">Remarks</th>
                                <th width="5%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($check_items && mysqli_num_rows($check_items) > 0): ?>
                                <?php $index = 1; ?>
                                <?php while ($item = mysqli_fetch_assoc($check_items)): 
                                    $parsed = parseInventoryNumber($item['inventory_number']);
                                ?>
                                <tr class="<?php echo $item['checked_status'] == 'checked' ? 'table-success' : ''; ?>">
                                    <td><?php echo $index++; ?></td>
                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td>
                                        <span class="badge bg-secondary" title="<?php echo $parsed['type']; ?>">
                                            <?php echo htmlspecialchars($item['inventory_number']); ?>
                                        </span>
                                        <?php if ($parsed['type'] == 'range'): ?>
                                            <small class="text-muted d-block">(<?php echo $parsed['count']; ?> items in range)</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><strong><?php echo $item['book_balance']; ?></strong></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm real-balance" 
                                               name="items[<?php echo $item['id']; ?>][real_balance]" 
                                               value="<?php echo $item['real_balance']; ?>" 
                                               min="0" 
                                               onchange="calculateDifferences(this, <?php echo $item['id']; ?>, <?php echo $item['book_balance']; ?>)">
                                    </td>
                                    <td>
                                        <span class="badge bg-success surplus-<?php echo $item['id']; ?>" id="surplus_<?php echo $item['id']; ?>">
                                            <?php echo $item['surplus']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger deficiency-<?php echo $item['id']; ?>" id="deficiency_<?php echo $item['id']; ?>">
                                            <?php echo $item['deficiency']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                               name="items[<?php echo $item['id']; ?>][remarks]" 
                                               value="<?php echo htmlspecialchars($item['remarks'] ?? ''); ?>" 
                                               placeholder="Remarks">
                                    </td>
                                    <td>
                                        <?php if ($item['checked_status'] == 'checked'): ?>
                                            <span class="badge bg-success">✓</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">⏺</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No items found in this check session.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="submit" name="update_checks" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                        <button type="button" class="btn btn-info" onclick="calculateAll()">
                            <i class="bi bi-calculator"></i> Recalculate All
                        </button>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button type="submit" name="complete_session" class="btn btn-success" onclick="return confirm('Complete this check session? This will mark it as finished.')">
                            <i class="bi bi-check-all"></i> Complete Session
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Card -->
        <div class="content-card mt-3">
            <h6><i class="bi bi-pie-chart"></i> Check Summary</h6>
            <div class="row">
                <?php
                $total_items = mysqli_num_rows($check_items);
                $checked_count = 0;
                $total_surplus = 0;
                $total_deficiency = 0;
                
                if ($check_items) {
                    mysqli_data_seek($check_items, 0);
                    while ($item = mysqli_fetch_assoc($check_items)) {
                        if ($item['checked_status'] == 'checked') $checked_count++;
                        $total_surplus += $item['surplus'];
                        $total_deficiency += $item['deficiency'];
                    }
                }
                ?>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Items</h6>
                            <h3><?php echo $total_items; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Checked Items</h6>
                            <h3><?php echo $checked_count; ?></h3>
                            <small><?php echo $total_items > 0 ? round(($checked_count/$total_items)*100) : 0; ?>% complete</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Surplus</h6>
                            <h3 class="text-success"><?php echo $total_surplus; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-muted">Total Deficiency</h6>
                            <h3 class="text-danger"><?php echo $total_deficiency; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Completed Sessions -->
        <div class="content-card mt-4">
            <h5 class="mb-3"><i class="bi bi-clock"></i> Recent Completed Checks</h5>
            
            <?php if ($completed_sessions && mysqli_num_rows($completed_sessions) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Session Name</th>
                                <th>Check Date</th>
                                <th>Conducted By</th>
                                <th>Completed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($session = mysqli_fetch_assoc($completed_sessions)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($session['session_name']); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($session['check_date'])); ?></td>
                                <td><?php echo htmlspecialchars($session['conducted_by'] ?: 'N/A'); ?></td>
                                <td><?php echo date('Y-m-d', strtotime($session['created_at'])); ?></td>
                                <td>
                                    <a href="check_report.php?session_id=<?php echo $session['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> View Report
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center py-3">No completed check sessions yet.</p>
            <?php endif; ?>
        </div>

        <!-- Inventory Number Format Guide -->
        <div class="content-card mt-3">
            <h6><i class="bi bi-info-circle"></i> Inventory Number Format Guide</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="border p-2 rounded">
                        <strong>Single Item:</strong><br>
                        <code>17/34/65</code> or <code>17/34/64/1</code>
                        <small class="text-muted d-block">→ Book Balance: 1</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border p-2 rounded">
                        <strong>Range Format:</strong><br>
                        <code>17/34/64/1 to 17/34/64/20</code>
                        <small class="text-muted d-block">→ Book Balance: 20</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border p-2 rounded">
                        <strong>Regular Format:</strong><br>
                        <code>INV-001</code>
                        <small class="text-muted d-block">→ Book Balance: 1</small>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>

        <!-- Section 2: Image Search -->
        <?php if ($active_section == 'image_search'): ?>

        <!-- Image Search Interface -->
        <div class="content-card">
            <h5 class="mb-3"><i class="bi bi-image"></i> Search Items by Image</h5>
            <p class="text-muted">Upload an image to find matching inventory items. Useful when you can't identify an item by name or number.</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-upload"></i> Upload Image</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="search_image" class="form-label">Select Image File</label>
                                    <input type="file" class="form-control" name="search_image" id="search_image" accept="image/*" required>
                                    <small class="text-muted">Max size: 5MB. Allowed: JPG, PNG, GIF</small>
                                </div>
                                <button type="submit" name="image_search" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search Matching Items
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="bi bi-camera"></i> Take Photo</h6>
                        </div>
                        <div class="card-body">
                            <div id="cameraSection">
                                <button type="button" class="btn btn-success mb-3" id="startCamera" onclick="startCamera()">
                                    <i class="bi bi-camera"></i> Start Camera
                                </button>
                                <div id="camera" style="display: none;">
                                    <video id="video" width="100%" height="auto" autoplay></video>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary" id="captureBtn" onclick="captureImage()">
                                            <i class="bi bi-camera"></i> Capture
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="stopCamera" onclick="stopCamera()">
                                            <i class="bi bi-stop"></i> Stop
                                        </button>
                                    </div>
                                </div>
                                <div id="capturedImage" style="display: none;" class="mt-3">
                                    <img id="capturedImg" src="" class="img-fluid rounded" style="max-height: 200px;">
                                    <form method="POST" id="cameraForm" class="mt-2">
                                        <input type="hidden" name="image_data" id="imageData">
                                        <input type="hidden" name="item_id" id="cameraItemId" value="0">
                                        <button type="submit" name="camera_capture" class="btn btn-success">
                                            <i class="bi bi-search"></i> Search with This Image
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <?php if (!empty($image_search_results)): ?>
        <div class="content-card mt-4">
            <h5 class="mb-3"><i class="bi bi-images"></i> Items with Images (<?php echo count($image_search_results); ?>)</h5>
            
            <div class="row">
                <?php foreach ($image_search_results as $item): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-img-top text-center p-3" style="height: 200px; overflow: hidden;">
                            <img src="<?php echo $item['image_path']; ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" 
                                 style="max-width: 100%; max-height: 180px; object-fit: contain;">
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><?php echo htmlspecialchars($item['item_name']); ?></h6>
                            <p class="card-text small">
                                <strong>Inventory #:</strong> <?php echo htmlspecialchars($item['inventory_number']); ?><br>
                                <strong>Brand:</strong> <?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?><br>
                                <strong>Section:</strong> <?php echo htmlspecialchars($item['section_name'] ?: '-'); ?><br>
                                <strong>Book #:</strong> <?php echo htmlspecialchars($item['book_number']); ?>
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Image Upload for Items -->
        <div class="content-card mt-4">
            <h5 class="mb-3"><i class="bi bi-camera"></i> Quick Image Upload for Items</h5>
            <p class="text-muted">Upload or capture images for specific items to help with future identification.</p>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Inventory #</th>
                            <th>Current Image</th>
                            <th>Upload New Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($all_items && mysqli_num_rows($all_items) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($all_items)): ?>
                            <tr>
                                <td>#<?php echo $item['id']; ?></td>
                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td>
                                    <?php if ($item['image_path'] && file_exists($item['image_path'])): ?>
                                        <img src="<?php echo $item['image_path']; ?>" alt="Item" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <span class="text-muted">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" enctype="multipart/form-data" class="d-flex">
                                        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                        <input type="file" name="item_image" class="form-control form-control-sm me-2" style="width: 150px;" accept="image/*" required>
                                        <button type="submit" name="upload_item_image" class="btn btn-sm btn-primary">
                                            <i class="bi bi-upload"></i> Upload
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php endif; ?>
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

.table th {
    vertical-align: middle;
}

.real-balance {
    width: 100px;
}

.badge {
    font-size: 0.9rem;
    padding: 0.4rem 0.6rem;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.table-success {
    background-color: #d4edda !important;
}

.border {
    border: 1px solid #dee2e6 !important;
}

#video {
    border-radius: 8px;
    background: #f0f0f0;
    max-width: 100%;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .real-balance {
        width: 70px;
    }
}
</style>

<script>
// Function to toggle filter input based on selection (only if elements exist)
function toggleFilterInput() {
    const filterType = document.getElementById('filterType');
    if (!filterType) return; // Exit if element doesn't exist (on image search tab)
    
    const filterValue = filterType.value;
    const sectionInput = document.getElementById('filterInputSection');
    const bookInput = document.getElementById('filterInputBook');
    
    if (sectionInput) sectionInput.style.display = 'none';
    if (bookInput) bookInput.style.display = 'none';
    
    if (filterValue === 'section' && sectionInput) {
        sectionInput.style.display = 'block';
    } else if (filterValue === 'book_number' && bookInput) {
        bookInput.style.display = 'block';
    }
}

// Function to calculate surplus and deficiency
function calculateDifferences(input, itemId, bookBalance) {
    const realBalance = parseInt(input.value) || 0;
    
    const surplus = Math.max(0, realBalance - bookBalance);
    const deficiency = Math.max(0, bookBalance - realBalance);
    
    const surplusEl = document.getElementById('surplus_' + itemId);
    const deficiencyEl = document.getElementById('deficiency_' + itemId);
    
    if (surplusEl) surplusEl.innerText = surplus;
    if (deficiencyEl) deficiencyEl.innerText = deficiency;
}

// Function to calculate all differences
function calculateAll() {
    const inputs = document.querySelectorAll('.real-balance');
    inputs.forEach(input => {
        const row = input.closest('tr');
        if (!row) return;
        
        const bookCell = row.cells[3];
        if (!bookCell) return;
        
        const bookBalance = parseInt(bookCell.innerText) || 0;
        const match = input.name.match(/\[(\d+)\]/);
        if (!match) return;
        
        const itemId = match[1];
        calculateDifferences(input, itemId, bookBalance);
    });
}

// Camera functions
let stream = null;

function startCamera() {
    const cameraDiv = document.getElementById('camera');
    const startBtn = document.getElementById('startCamera');
    
    if (!cameraDiv || !startBtn) return;
    
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(videoStream) {
                stream = videoStream;
                const video = document.getElementById('video');
                if (video) {
                    video.srcObject = stream;
                    video.play();
                    
                    cameraDiv.style.display = 'block';
                    startBtn.style.display = 'none';
                }
            })
            .catch(function(error) {
                alert('Could not access camera: ' + error.message);
            });
    } else {
        alert('Camera not supported on this device/browser.');
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
    
    const cameraDiv = document.getElementById('camera');
    const startBtn = document.getElementById('startCamera');
    const capturedDiv = document.getElementById('capturedImage');
    
    if (cameraDiv) cameraDiv.style.display = 'none';
    if (startBtn) startBtn.style.display = 'inline-block';
    if (capturedDiv) capturedDiv.style.display = 'none';
}

function captureImage() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    
    if (!video || !canvas) return;
    
    const context = canvas.getContext('2d');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    const imageData = canvas.toDataURL('image/png');
    
    const imageDataInput = document.getElementById('imageData');
    const capturedImg = document.getElementById('capturedImg');
    const capturedDiv = document.getElementById('capturedImage');
    
    if (imageDataInput) imageDataInput.value = imageData;
    if (capturedImg) capturedImg.src = imageData;
    if (capturedDiv) capturedDiv.style.display = 'block';
    
    // Stop camera after capture
    stopCamera();
}

// Auto-calculate on page load (only if on check tab)
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'check';
    
    if (section === 'check') {
        calculateAll();
        toggleFilterInput();
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