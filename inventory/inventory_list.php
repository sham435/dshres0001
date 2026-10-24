<?php
// inventory_list.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;

// Handle search and filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$section_filter = isset($_GET['section']) ? (int)$_GET['section'] : 0;
$condition_filter = isset($_GET['condition']) ? mysqli_real_escape_string($conn, $_GET['condition']) : '';

// Build query with filters
$where = "WHERE i.school_id = $school_id";

if ($search) {
    $where .= " AND (i.item_name LIKE '%$search%' OR i.inventory_number LIKE '%$search%' OR i.serial_number LIKE '%$search%')";
}

if ($section_filter > 0) {
    $where .= " AND i.section_id = $section_filter";
}

if ($condition_filter) {
    $where .= " AND i.condition_status = '$condition_filter'";
}

// Get items with joins
$items_query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
                FROM inventory_items i 
                LEFT JOIN sections s ON i.section_id = s.id 
                LEFT JOIN officers o ON i.incharge_officer_id = o.id 
                $where 
                ORDER BY i.created_at DESC";

$items = mysqli_query($conn, $items_query);
$total_items = mysqli_num_rows($items);

// Get sections for filter
$sections = mysqli_query($conn, "SELECT id, section_name FROM sections WHERE school_id = $school_id ORDER BY section_name");
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-list"></i> Inventory Items List</h2>
                <p class="text-muted">Total Items: <?php echo $total_items; ?></p>
            </div>
            <div>
                <a href="insert_data.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Item
                </a>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="content-card mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, inventory number..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="section">
                        <option value="">All Sections</option>
                        <?php while ($sec = mysqli_fetch_assoc($sections)): ?>
                        <option value="<?php echo $sec['id']; ?>" <?php echo $section_filter == $sec['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sec['section_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="condition">
                        <option value="">All Conditions</option>
                        <option value="working" <?php echo $condition_filter == 'working' ? 'selected' : ''; ?>>Working</option>
                        <option value="good" <?php echo $condition_filter == 'good' ? 'selected' : ''; ?>>Good</option>
                        <option value="not_working" <?php echo $condition_filter == 'not_working' ? 'selected' : ''; ?>>Not Working</option>
                        <option value="not_usable" <?php echo $condition_filter == 'not_usable' ? 'selected' : ''; ?>>Not Usable</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Items Table -->
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Inventory #</th>
                            <th>Serial #</th>
                            <th>Section</th>
                            <th>Book #</th>
                            <th>In-charge</th>
                            <th>Condition</th>
                            <th>Status</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($items && mysqli_num_rows($items) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image_path'] && file_exists($item['image_path'])): ?>
                                        <img src="<?php echo $item['image_path']; ?>" alt="Item" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div style="width: 50px; height: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['inventory_number']); ?></td>
                                <td><?php echo htmlspecialchars($item['serial_number'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['section_name'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                                <td><?php echo htmlspecialchars($item['incharge_name'] ?: 'Not assigned'); ?></td>
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
                                <td>
                                    <button class="btn btn-sm btn-primary" title="Edit" 
                                            onclick="location.href='edit_item.php?id=<?php echo $item['id']; ?>'">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" title="View" 
                                            onclick="location.href='view_item.php?id=<?php echo $item['id']; ?>'">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Delete" 
                                            onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo addslashes($item['item_name']); ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                    No inventory items found.<br>
                                    <a href="insert_data.php" class="btn btn-sm btn-primary mt-2">Add Your First Item</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteItemForm" method="POST" action="delete_item.php" style="display: none;">
    <input type="hidden" name="item_id" id="delete_item_id">
</form>

<script>
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
        document.getElementById('delete_item_id').value = id;
        document.getElementById('deleteItemForm').submit();
    }
}
</script>

<?php require_once "footer.php"; ?>