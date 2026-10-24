<?php
// insert_data.php
require_once "header.php";

$school_id = $_SESSION['school_id'] ?? 1;
$message = '';
$error = '';

// Create upload directory if it doesn't exist
$upload_dir = 'uploads/items/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_item'])) {
    
    // Get form data
    $item_name = mysqli_real_escape_string($conn, trim($_POST['item_name']));
    $brand_name = mysqli_real_escape_string($conn, trim($_POST['brand_name']));
    $inventory_number = mysqli_real_escape_string($conn, trim($_POST['inventory_number']));
    $serial_number = mysqli_real_escape_string($conn, trim($_POST['serial_number']));
    $section_id = !empty($_POST['section_id']) ? (int)$_POST['section_id'] : 0;
    $book_number = mysqli_real_escape_string($conn, trim($_POST['book_number']));
    $incharge_officer_id = !empty($_POST['incharge_officer_id']) ? (int)$_POST['incharge_officer_id'] : 'NULL';
    $received_date = !empty($_POST['received_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['received_date']) . "'" : 'NULL';
    $received_from = !empty($_POST['received_from']) ? "'" . mysqli_real_escape_string($conn, trim($_POST['received_from'])) . "'" : 'NULL';
    $is_transferred = isset($_POST['is_transferred']) ? 1 : 0;
    $transferred_date = !empty($_POST['transferred_date']) ? "'" . mysqli_real_escape_string($conn, $_POST['transferred_date']) . "'" : 'NULL';
    $borrowed_officer = !empty($_POST['borrowed_officer']) ? "'" . mysqli_real_escape_string($conn, trim($_POST['borrowed_officer'])) . "'" : 'NULL';
    $condition_status = mysqli_real_escape_string($conn, $_POST['condition_status']);
    
    // Validation
    $errors = [];
    
    if (empty($item_name)) {
        $errors[] = "Item name is required";
    }
    
    if (empty($inventory_number)) {
        $errors[] = "Inventory number is required";
    } else {
        // Check if inventory number already exists
        $check_sql = "SELECT id FROM inventory_items WHERE inventory_number = '$inventory_number' AND school_id = $school_id";
        $check_result = mysqli_query($conn, $check_sql);
        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $errors[] = "Inventory number already exists. Please use a unique number.";
        }
    }
    
    if (empty($section_id)) {
        $errors[] = "Section is required";
    }
    
    if (empty($book_number)) {
        $errors[] = "Book number is required";
    }
    
    // Handle image upload
    $image_path = 'NULL';
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['item_image']['tmp_name']);
        finfo_close($file_info);
        
        if (in_array($mime_type, $allowed_types) && $_FILES['item_image']['size'] <= $max_size) {
            // Generate unique filename
            $extension = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
            $filename = 'item_' . time() . '_' . uniqid() . '.' . $extension;
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['item_image']['tmp_name'], $target_path)) {
                $image_path = "'" . $target_path . "'";
            } else {
                $errors[] = "Failed to upload image";
            }
        } else {
            $errors[] = "Invalid image file. Max size 5MB. Allowed: JPG, PNG, GIF";
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        // First, add brand_name column if it doesn't exist
        $check_column = mysqli_query($conn, "SHOW COLUMNS FROM inventory_items LIKE 'brand_name'");
        if (mysqli_num_rows($check_column) == 0) {
            mysqli_query($conn, "ALTER TABLE inventory_items ADD COLUMN brand_name VARCHAR(255) AFTER item_name");
        }
        
        $sql = "INSERT INTO inventory_items (
                    school_id, item_name, brand_name, inventory_number, serial_number, 
                    section_id, book_number, incharge_officer_id, received_date, 
                    received_from, is_transferred, transferred_date, borrowed_officer, 
                    condition_status, image_path
                ) VALUES (
                    $school_id, '$item_name', " . ($brand_name ? "'$brand_name'" : "NULL") . ", '$inventory_number', " . ($serial_number ? "'$serial_number'" : "NULL") . ",
                    $section_id, '$book_number', $incharge_officer_id, $received_date,
                    $received_from, $is_transferred, $transferred_date,
                    $borrowed_officer, '$condition_status', $image_path
                )";
        
        if (mysqli_query($conn, $sql)) {
            $item_id = mysqli_insert_id($conn);
            $message = "Item added successfully! Inventory Number: $inventory_number";
            
            // Clear form data for new entry
            $_POST = array();
        } else {
            $error = "Error adding item: " . mysqli_error($conn);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

// Get sections for dropdown
$sections_query = "SELECT s.*, 
                   (SELECT o.id FROM officers o WHERE o.section_id = s.id AND o.is_incharge = 1 LIMIT 1) as incharge_id,
                   (SELECT o.officer_name FROM officers o WHERE o.section_id = s.id AND o.is_incharge = 1 LIMIT 1) as incharge_name
                   FROM sections s 
                   WHERE s.school_id = $school_id 
                   ORDER BY s.section_name";
$sections = mysqli_query($conn, $sections_query);

if (!$sections) {
    die("Error fetching sections: " . mysqli_error($conn));
}

// Get all officers for borrowing
$officers_query = "SELECT o.*, s.section_name 
                   FROM officers o 
                   LEFT JOIN sections s ON o.section_id = s.id 
                   WHERE o.school_id = $school_id 
                   ORDER BY o.officer_name";
$officers = mysqli_query($conn, $officers_query);

if (!$officers) {
    die("Error fetching officers: " . mysqli_error($conn));
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="bi bi-plus-circle"></i> Insert New Inventory Item</h2>
                <p class="text-muted">Add new items to the school inventory system</p>
            </div>
            <div>
                <a href="inventory_list.php" class="btn btn-info">
                    <i class="bi bi-list"></i> View All Items
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

        <!-- Item Entry Form -->
        <div class="content-card">
            <form method="POST" enctype="multipart/form-data" id="itemForm" class="needs-validation" novalidate>
                <!-- Required Fields Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-star-fill"></i> Required Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Item Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="item_name" 
                               value="<?php echo htmlspecialchars($_POST['item_name'] ?? ''); ?>" required>
                        <small class="text-muted">Enter the full name of the item</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="brand_name" 
                               value="<?php echo htmlspecialchars($_POST['brand_name'] ?? ''); ?>">
                        <small class="text-muted">Brand or manufacturer (optional)</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Inventory Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="inventory_number" 
                               value="<?php echo htmlspecialchars($_POST['inventory_number'] ?? ''); ?>" required>
                        <small class="text-muted">Unique identifier - can contain letters, numbers, symbols, etc.</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Section <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" name="section_id" id="section_id" required onchange="updateInchargeOfficer()">
                            <option value="">-- Select Section --</option>
                            <?php 
                            mysqli_data_seek($sections, 0);
                            while ($section = mysqli_fetch_assoc($sections)): 
                            ?>
                            <option value="<?php echo $section['id']; ?>" 
                                    data-incharge-id="<?php echo $section['incharge_id'] ?? ''; ?>"
                                    data-incharge-name="<?php echo htmlspecialchars($section['incharge_name'] ?? ''); ?>"
                                    <?php echo (isset($_POST['section_id']) && $_POST['section_id'] == $section['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['section_name']); ?> 
                                (<?php echo $section['section_type']; ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-muted">Select the section where this item belongs</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Book Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="book_number" 
                               value="<?php echo htmlspecialchars($_POST['book_number'] ?? ''); ?>" required>
                        <small class="text-muted">Register book page number</small>
                    </div>
                </div>

                <!-- Optional Fields Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-success mb-3">
                            <i class="bi bi-info-circle"></i> Additional Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" class="form-control" name="serial_number" 
                               value="<?php echo htmlspecialchars($_POST['serial_number'] ?? ''); ?>">
                        <small class="text-muted">Manufacturer's serial number (if available)</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">In-charge Officer</label>
                        <select class="form-select" name="incharge_officer_id" id="incharge_officer_id">
                            <option value="">-- Auto-filled from section --</option>
                            <?php 
                            mysqli_data_seek($officers, 0);
                            while ($ofc = mysqli_fetch_assoc($officers)): 
                            ?>
                            <option value="<?php echo $ofc['id']; ?>" 
                                    data-section="<?php echo $ofc['section_id']; ?>"
                                    <?php echo (isset($_POST['incharge_officer_id']) && $_POST['incharge_officer_id'] == $ofc['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ofc['officer_name']); ?> 
                                (<?php echo htmlspecialchars($ofc['section_name'] ?? 'No Section'); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-muted">Automatically filled when section is selected</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Received Date</label>
                        <input type="date" class="form-control" name="received_date" 
                               value="<?php echo htmlspecialchars($_POST['received_date'] ?? date('Y-m-d')); ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Received From</label>
                        <input type="text" class="form-control" name="received_from" 
                               value="<?php echo htmlspecialchars($_POST['received_from'] ?? ''); ?>"
                               placeholder="Supplier/Donor/Source">
                    </div>
                </div>

                <!-- Transfer Information Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-warning mb-3">
                            <i class="bi bi-arrow-left-right"></i> Transfer Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="is_transferred" id="is_transferred" value="1" 
                                   onchange="toggleTransferFields()"
                                   <?php echo (isset($_POST['is_transferred']) && $_POST['is_transferred'] == '1') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_transferred">
                                <strong>Item is transferred?</strong>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3" id="transferred_date_field" style="<?php echo (!isset($_POST['is_transferred']) || $_POST['is_transferred'] != '1') ? 'display: none;' : ''; ?>">
                        <label class="form-label">Transfer Date</label>
                        <input type="date" class="form-control" name="transferred_date" 
                               value="<?php echo htmlspecialchars($_POST['transferred_date'] ?? date('Y-m-d')); ?>">
                    </div>
                </div>

                <!-- Borrowed Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-info mb-3">
                            <i class="bi bi-person-badge"></i> Borrowing Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Borrowed Officer</label>
                        <select class="form-select" name="borrowed_officer">
                            <option value="">-- Not Borrowed --</option>
                            <?php 
                            mysqli_data_seek($officers, 0);
                            while ($ofc = mysqli_fetch_assoc($officers)): 
                            ?>
                            <option value="<?php echo htmlspecialchars($ofc['officer_name']); ?>" 
                                    <?php echo (isset($_POST['borrowed_officer']) && $_POST['borrowed_officer'] == $ofc['officer_name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ofc['officer_name']); ?> 
                                (<?php echo htmlspecialchars($ofc['section_name'] ?? 'No Section'); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-muted">If item is currently borrowed</small>
                    </div>
                </div>

                <!-- Condition and Image -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-secondary mb-3">
                            <i class="bi bi-clipboard-check"></i> Condition & Media
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Condition Status</label>
                        <select class="form-select" name="condition_status">
                            <option value="working" <?php echo (isset($_POST['condition_status']) && $_POST['condition_status'] == 'working') ? 'selected' : ''; ?>>Working - Good Condition</option>
                            <option value="good" <?php echo (isset($_POST['condition_status']) && $_POST['condition_status'] == 'good') ? 'selected' : ''; ?>>Good - Minor Issues</option>
                            <option value="not_working" <?php echo (isset($_POST['condition_status']) && $_POST['condition_status'] == 'not_working') ? 'selected' : ''; ?>>Not Working - Needs Repair</option>
                            <option value="not_usable" <?php echo (isset($_POST['condition_status']) && $_POST['condition_status'] == 'not_usable') ? 'selected' : ''; ?>>Not Usable - Damaged</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item Image</label>
                        <input type="file" class="form-control" name="item_image" id="item_image" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted">Max size: 5MB. Allowed: JPG, PNG, GIF</small>
                        <div class="mt-2" id="image_preview"></div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <hr>
                        <button type="submit" name="save_item" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Save Inventory Item
                        </button>
                        <button type="reset" class="btn btn-secondary btn-lg" onclick="clearForm()">
                            <i class="bi bi-eraser"></i> Clear Form
                        </button>
                        <a href="inventory_list.php" class="btn btn-info btn-lg">
                            <i class="bi bi-list"></i> View All Items
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Tips Card -->
        <div class="content-card mt-4">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">
                        <i class="bi bi-lightbulb"></i> Field Guidelines
                    </h6>
                    <ul class="small">
                        <li><strong class="text-danger">*</strong> Marked fields are required</li>
                        <li>Inventory number must be unique but can contain any characters</li>
                        <li>In-charge officer auto-fills when you select a section</li>
                        <li>Book number refers to the inventory register page</li>
                        <li>Brand name is optional - can be left blank</li>
                        <li>Upload clear images for better identification</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">
                        <i class="bi bi-info-circle"></i> Recent Items
                    </h6>
                    <?php
                    $recent = mysqli_query($conn, "SELECT i.*, s.section_name 
                        FROM inventory_items i 
                        LEFT JOIN sections s ON i.section_id = s.id 
                        WHERE i.school_id = $school_id 
                        ORDER BY i.created_at DESC LIMIT 5");
                    
                    if ($recent && mysqli_num_rows($recent) > 0):
                    ?>
                    <ul class="list-group list-group-flush">
                        <?php while ($item = mysqli_fetch_assoc($recent)): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo htmlspecialchars($item['item_name']); ?></strong>
                                <?php if (!empty($item['brand_name'])): ?>
                                    <br><small class="text-muted">Brand: <?php echo htmlspecialchars($item['brand_name']); ?></small>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?php echo htmlspecialchars($item['inventory_number']); ?></small>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted small">No items added yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Form styling */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.3rem;
}

.form-control:focus, .form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.required-field::after {
    content: " *";
    color: red;
}

/* Preview image */
#image_preview img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 5px;
    border: 1px solid #ddd;
    padding: 3px;
}

/* Section headers */
h5 i {
    margin-right: 8px;
}

/* Card styling */
.content-card {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

/* Button styling */
.btn-lg {
    padding: 12px 25px;
    margin-right: 10px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Alert styling */
.alert {
    border-radius: 10px;
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #28a745;
}

.alert-danger {
    border-left-color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-lg {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>

<script>
// Function to update incharge officer based on section selection
function updateInchargeOfficer() {
    const sectionSelect = document.getElementById('section_id');
    const inchargeSelect = document.getElementById('incharge_officer_id');
    const selectedOption = sectionSelect.options[sectionSelect.selectedIndex];
    
    if (selectedOption && selectedOption.value) {
        const inchargeId = selectedOption.getAttribute('data-incharge-id');
        const inchargeName = selectedOption.getAttribute('data-incharge-name');
        
        if (inchargeId) {
            // Set the incharge officer select to this officer
            for (let i = 0; i < inchargeSelect.options.length; i++) {
                if (inchargeSelect.options[i].value == inchargeId) {
                    inchargeSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Show a small notification
            showNotification(`In-charge officer set to: ${inchargeName}`);
        } else {
            inchargeSelect.selectedIndex = 0;
            showNotification('No in-charge officer assigned to this section', 'warning');
        }
    } else {
        inchargeSelect.selectedIndex = 0;
    }
}

// Function to toggle transfer date field
function toggleTransferFields() {
    const isTransferred = document.getElementById('is_transferred').checked;
    const transferField = document.getElementById('transferred_date_field');
    
    if (isTransferred) {
        transferField.style.display = 'block';
    } else {
        transferField.style.display = 'none';
    }
}

// Function to preview image before upload
function previewImage(input) {
    const preview = document.getElementById('image_preview');
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '150px';
            img.style.maxHeight = '150px';
            preview.appendChild(img);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Function to show notifications
function showNotification(message, type = 'info') {
    // You can implement a toast notification here
    console.log(message);
}

// Function to clear form
function clearForm() {
    if (confirm('Are you sure you want to clear all form fields?')) {
        document.getElementById('itemForm').reset();
        document.getElementById('image_preview').innerHTML = '';
        toggleTransferFields();
    }
}

// Form validation - REMOVED inventory number validation
document.getElementById('itemForm').addEventListener('submit', function(e) {
    const itemName = document.querySelector('[name="item_name"]').value.trim();
    const invNumber = document.querySelector('[name="inventory_number"]').value.trim();
    const section = document.querySelector('[name="section_id"]').value;
    const bookNumber = document.querySelector('[name="book_number"]').value.trim();
    
    if (!itemName || !invNumber || !section || !bookNumber) {
        e.preventDefault();
        alert('Please fill in all required fields (marked with *)');
        return;
    }
    
    // Removed inventory number validation - now accepts any characters
});

// Auto-populate fields on page load if section is selected
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section_id');
    if (sectionSelect.value) {
        updateInchargeOfficer();
    }
    toggleTransferFields();
});

// Check for URL parameters for messages
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    alert('Item added successfully!');
}
</script>

<?php require_once "footer.php"; ?>