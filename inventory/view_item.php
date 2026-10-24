<?php
// view_item.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;

// Get item ID from URL
$item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$item_id) {
    header("location: inventory_list.php");
    exit();
}

// Fetch item details with joins
$query = "SELECT i.*, s.section_name, o.officer_name as incharge_name 
          FROM inventory_items i 
          LEFT JOIN sections s ON i.section_id = s.id 
          LEFT JOIN officers o ON i.incharge_officer_id = o.id 
          WHERE i.id = $item_id AND i.school_id = $school_id";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("location: inventory_list.php");
    exit();
}

$item = mysqli_fetch_assoc($result);

// Format condition for display
$condition_labels = [
    'working' => ['Working - Good Condition', 'success'],
    'good' => ['Good - Minor Issues', 'info'],
    'not_working' => ['Not Working - Needs Repair', 'warning'],
    'not_usable' => ['Not Usable - Damaged', 'danger']
];

$condition = $condition_labels[$item['condition_status']] ?? ['Unknown', 'secondary'];
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-eye"></i> View Item Details</h2>
                <p class="text-muted">Complete information about the inventory item</p>
            </div>
            <div>
                <a href="inventory_list.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <a href="edit_item.php?id=<?php echo $item_id; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Edit Item
                </a>
            </div>
        </div>

        <!-- Item Details Card -->
        <div class="content-card">
            <div class="row">
                <!-- Image Section -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-image"></i> Item Image</h6>
                        </div>
                        <div class="card-body text-center">
                            <?php if ($item['image_path'] && file_exists($item['image_path'])): ?>
                                <img src="<?php echo $item['image_path']; ?>" alt="Item Image" class="img-fluid rounded" style="max-height: 300px; border: 1px solid #ddd; padding: 5px;">
                                <p class="text-muted mt-2">
                                    <small>Click image to enlarge</small>
                                </p>
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <div>
                                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                        <p class="text-muted mt-2">No image uploaded</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="bi bi-info-circle"></i> Item Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px;">Item Name:</th>
                                    <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Brand Name:</th>
                                    <td><?php echo htmlspecialchars($item['brand_name'] ?: '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Inventory Number:</th>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['inventory_number']); ?></span></td>
                                </tr>
                                <tr>
                                    <th>Serial Number:</th>
                                    <td><?php echo htmlspecialchars($item['serial_number'] ?: '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Section:</th>
                                    <td><span class="badge bg-info"><?php echo htmlspecialchars($item['section_name'] ?: 'N/A'); ?></span></td>
                                </tr>
                                <tr>
                                    <th>Book Number:</th>
                                    <td><?php echo htmlspecialchars($item['book_number']); ?></td>
                                </tr>
                                <tr>
                                    <th>In-charge Officer:</th>
                                    <td>
                                        <?php if ($item['incharge_name']): ?>
                                            <span class="badge bg-success"><?php echo htmlspecialchars($item['incharge_name']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not Assigned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Received Date:</th>
                                    <td><?php echo $item['received_date'] ? date('F j, Y', strtotime($item['received_date'])) : '-'; ?></td>
                                </tr>
                                <tr>
                                    <th>Received From:</th>
                                    <td><?php echo htmlspecialchars($item['received_from'] ?: '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Condition:</th>
                                    <td>
                                        <span class="badge bg-<?php echo $condition[1]; ?>">
                                            <?php echo $condition[0]; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php if ($item['is_transferred']): ?>
                                            <span class="badge bg-warning">Transferred</span>
                                        <?php elseif ($item['borrowed_officer']): ?>
                                            <span class="badge bg-info">Borrowed by <?php echo htmlspecialchars($item['borrowed_officer']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Transfer Information Card -->
                    <?php if ($item['is_transferred']): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-warning text-white">
                            <h6 class="mb-0"><i class="bi bi-arrow-left-right"></i> Transfer Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <tr>
                                    <th style="width: 200px;">Transfer Date:</th>
                                    <td><?php echo $item['transferred_date'] ? date('F j, Y', strtotime($item['transferred_date'])) : '-'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- System Information Card -->
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bi bi-clock-history"></i> System Information</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <tr>
                                    <th style="width: 200px;">Item ID:</th>
                                    <td>#<?php echo $item['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Created Date:</th>
                                    <td><?php echo date('F j, Y h:i A', strtotime($item['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td><?php echo date('F j, Y h:i A', strtotime($item['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="content-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="inventory_list.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <a href="edit_item.php?id=<?php echo $item_id; ?>" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Edit Item
                            </a>
                        </div>
                        <div>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $item_id; ?>, '<?php echo addslashes($item['item_name']); ?>')">
                                <i class="bi bi-trash"></i> Delete Item
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteItemForm" method="POST" action="delete_item.php" style="display: none;">
    <input type="hidden" name="item_id" id="delete_item_id">
</form>

<style>
/* Card styling */
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
    padding: 12px 15px;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.table td {
    color: #212529;
}

/* Image styling */
.img-fluid {
    max-width: 100%;
    height: auto;
    transition: transform 0.3s;
}

.img-fluid:hover {
    transform: scale(1.02);
    cursor: pointer;
}

/* Badge styling */
.badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.8rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table th {
        width: 120px !important;
    }
    
    .btn {
        margin-bottom: 5px;
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

// Image zoom on click
document.addEventListener('DOMContentLoaded', function() {
    const itemImage = document.querySelector('.img-fluid');
    if (itemImage) {
        itemImage.addEventListener('click', function() {
            // Create modal for image zoom
            const modalHtml = `
                <div class="modal fade" id="imageZoomModal" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Item Image</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${this.src}" class="img-fluid" alt="Item Image">
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            const modalContainer = document.createElement('div');
            modalContainer.innerHTML = modalHtml;
            document.body.appendChild(modalContainer);
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
            modal.show();
            
            // Remove modal after hidden
            document.getElementById('imageZoomModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        });
    }
});
</script>

<?php require_once "footer.php"; ?>