<?php
// edit_data.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';

// Handle search and filters
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$section_filter = isset($_GET['section']) ? (int)$_GET['section'] : 0;
$condition_filter = isset($_GET['condition']) ? mysqli_real_escape_string($conn, $_GET['condition']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build query with filters
$where = "WHERE i.school_id = $school_id";

if ($search) {
    $where .= " AND (i.item_name LIKE '%$search%' 
                     OR i.inventory_number LIKE '%$search%' 
                     OR i.serial_number LIKE '%$search%' 
                     OR i.brand_name LIKE '%$search%'
                     OR i.book_number LIKE '%$search%')";
}

if ($section_filter > 0) {
    $where .= " AND i.section_id = $section_filter";
}

if ($condition_filter) {
    $where .= " AND i.condition_status = '$condition_filter'";
}

if ($status_filter) {
    if ($status_filter == 'transferred') {
        $where .= " AND i.is_transferred = 1";
    } elseif ($status_filter == 'borrowed') {
        $where .= " AND i.borrowed_officer IS NOT NULL AND i.borrowed_officer != ''";
    } elseif ($status_filter == 'available') {
        $where .= " AND i.is_transferred = 0 AND (i.borrowed_officer IS NULL OR i.borrowed_officer = '')";
    }
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

// Get counts for statistics
$total_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM inventory_items WHERE school_id = $school_id"))['count'];
$total_available = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM inventory_items WHERE school_id = $school_id AND is_transferred = 0 AND (borrowed_officer IS NULL OR borrowed_officer = '')"))['count'];
$total_borrowed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM inventory_items WHERE school_id = $school_id AND borrowed_officer IS NOT NULL AND borrowed_officer != ''"))['count'];
$total_transferred = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM inventory_items WHERE school_id = $school_id AND is_transferred = 1"))['count'];
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-pencil-square"></i> Edit Inventory Data</h2>
                <p class="text-muted">Search, filter and edit inventory items</p>
            </div>
            <div>
                <a href="insert_data.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Item
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Items</h5>
                        <h2><?php echo $total_all; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Available</h5>
                        <h2><?php echo $total_available; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed</h5>
                        <h2><?php echo $total_borrowed; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Transferred</h5>
                        <h2><?php echo $total_transferred; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> Item updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> Item deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Bar -->
        <div class="content-card mb-4">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, brand, inventory number, book number..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="section">
                        <option value="">All Sections</option>
                        <?php 
                        mysqli_data_seek($sections, 0);
                        while ($sec = mysqli_fetch_assoc($sections)): 
                        ?>
                        <option value="<?php echo $sec['id']; ?>" <?php echo $section_filter == $sec['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sec['section_name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="condition">
                        <option value="">All Conditions</option>
                        <option value="working" <?php echo $condition_filter == 'working' ? 'selected' : ''; ?>>Working</option>
                        <option value="good" <?php echo $condition_filter == 'good' ? 'selected' : ''; ?>>Good</option>
                        <option value="not_working" <?php echo $condition_filter == 'not_working' ? 'selected' : ''; ?>>Not Working</option>
                        <option value="not_usable" <?php echo $condition_filter == 'not_usable' ? 'selected' : ''; ?>>Not Usable</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="available" <?php echo $status_filter == 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="borrowed" <?php echo $status_filter == 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                        <option value="transferred" <?php echo $status_filter == 'transferred' ? 'selected' : ''; ?>>Transferred</option>
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-list"></i> Inventory Items (<?php echo $total_items; ?> found)</h5>
                <div>
                    <a href="edit_data.php" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Item Name</th>
                            <th>Brand</th>
                            <th>Inventory #</th>
                            <th>Section</th>
                            <th>Book #</th>
                            <th>Condition</th>
                            <th>Status</th>
                            <th width="250">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($items && mysqli_num_rows($items) > 0): ?>
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                            <tr>
                                <td>#<?php echo $item['id']; ?></td>
                                <td>
                                    <?php if ($item['image_path'] && file_exists($item['image_path'])): ?>
                                        <img src="<?php echo $item['image_path']; ?>" alt="Item" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div style="width: 40px; height: 40px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                    <?php if ($item['serial_number']): ?>
                                        <br><small class="text-muted">SN: <?php echo htmlspecialchars($item['serial_number']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                <td>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($item['section_name'] ?: 'N/A'); ?></span>
                                </td>
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="view_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete" 
                                                onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo addslashes($item['item_name']); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem;"></i><br>
                                    No inventory items found matching your criteria.<br>
                                    <a href="insert_data.php" class="btn btn-sm btn-primary mt-2">Add New Item</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Edit Modal -->
        <div class="modal fade" id="quickEditModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-lightning"></i> Quick Edit Item
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="quickEditForm" method="POST" action="quick_edit_item.php">
                        <div class="modal-body">
                            <input type="hidden" name="item_id" id="quick_edit_id">
                            
                            <div class="mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="item_name" id="quick_item_name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-select" name="condition_status" id="quick_condition">
                                    <option value="working">Working - Good Condition</option>
                                    <option value="good">Good - Minor Issues</option>
                                    <option value="not_working">Not Working - Needs Repair</option>
                                    <option value="not_usable">Not Usable - Damaged</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" id="quick_status">
                                    <option value="available">Available</option>
                                    <option value="borrowed">Borrowed</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="borrowed_field" style="display: none;">
                                <label class="form-label">Borrowed Officer Name</label>
                                <input type="text" class="form-control" name="borrowed_officer" id="quick_borrowed">
                            </div>
                            
                            <div class="mb-3" id="transferred_field" style="display: none;">
                                <label class="form-label">Transfer Date</label>
                                <input type="date" class="form-control" name="transferred_date" id="quick_transferred">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i> Quick Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Form -->
        <form id="deleteItemForm" method="POST" action="delete_item.php" style="display: none;">
            <input type="hidden" name="item_id" id="delete_item_id">
        </form>
    </div>
</div>

<style>
/* Table styling */
.table td {
    vertical-align: middle;
}

.btn-group {
    gap: 3px;
}

.btn-group .btn {
    border-radius: 4px !important;
}

/* Hover effects */
.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Card styling */
.content-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

/* Statistics cards */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card .card-body {
    padding: 20px;
}

.card h2 {
    margin: 0;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-wrap: wrap;
    }
    
    .btn-group .btn {
        flex: 1 0 auto;
        margin-bottom: 3px;
    }
}
</style>

<script>
// Delete confirmation
function confirmDelete(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
        document.getElementById('delete_item_id').value = id;
        document.getElementById('deleteItemForm').submit();
    }
}

// Quick edit function
function quickEdit(id) {
    // Fetch item details
    fetch(`get_item_details.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error loading item details');
                return;
            }
            
            // Populate modal fields
            document.getElementById('quick_edit_id').value = id;
            document.getElementById('quick_item_name').value = data.item_name;
            document.getElementById('quick_condition').value = data.condition_status;
            
            // Set status
            if (data.is_transferred == 1) {
                document.getElementById('quick_status').value = 'transferred';
                document.getElementById('transferred_field').style.display = 'block';
                document.getElementById('borrowed_field').style.display = 'none';
                document.getElementById('quick_transferred').value = data.transferred_date || '';
            } else if (data.borrowed_officer) {
                document.getElementById('quick_status').value = 'borrowed';
                document.getElementById('borrowed_field').style.display = 'block';
                document.getElementById('transferred_field').style.display = 'none';
                document.getElementById('quick_borrowed').value = data.borrowed_officer;
            } else {
                document.getElementById('quick_status').value = 'available';
                document.getElementById('borrowed_field').style.display = 'none';
                document.getElementById('transferred_field').style.display = 'none';
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('quickEditModal'));
            modal.show();
        })
        .catch(error => {
            alert('Error loading item details');
        });
}

// Show/hide fields based on status selection
document.getElementById('quick_status')?.addEventListener('change', function() {
    const status = this.value;
    const borrowedField = document.getElementById('borrowed_field');
    const transferredField = document.getElementById('transferred_field');
    
    borrowedField.style.display = 'none';
    transferredField.style.display = 'none';
    
    if (status === 'borrowed') {
        borrowedField.style.display = 'block';
    } else if (status === 'transferred') {
        transferredField.style.display = 'block';
    }
});

// Handle quick edit form submission
document.getElementById('quickEditForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('quick_edit_item.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Item updated successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error updating item');
    });
});
</script>

<?php require_once "footer.php"; ?>