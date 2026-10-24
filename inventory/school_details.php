<?php
// school_details.php
require_once "header.php";

// Get current school data
$school_id = $_SESSION['school_id'] ?? 1; // Default to first school
$sql = "SELECT * FROM school_info WHERE id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $school_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $school_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_school'])) {
    $school_name = trim($_POST['school_name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $principal_name = trim($_POST['principal_name']);
    $principal_phone = trim($_POST['principal_phone']);
    $officer_name = trim($_POST['officer_name']);
    $officer_phone = trim($_POST['officer_phone']);
    $username = trim($_POST['username']);
    
    $errors = [];
    
    // Validation
    if (empty($school_name)) $errors[] = "School name is required";
    if (empty($address)) $errors[] = "Address is required";
    if (empty($phone)) $errors[] = "Phone number is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    
    // Handle logo upload
    $logo_path = $school_data['logo_path']; // Keep existing logo by default
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES['logo']['tmp_name']);
        finfo_close($file_info);
        
        if (in_array($mime_type, $allowed_types) && $_FILES['logo']['size'] <= $max_size) {
            $upload_dir = 'uploads/logos/';
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Delete old logo if exists
            if ($school_data['logo_path'] && file_exists($school_data['logo_path'])) {
                unlink($school_data['logo_path']);
            }
            
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'logo_' . time() . '_' . uniqid() . '.' . $extension;
            $target_path = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                $logo_path = $target_path;
            } else {
                $errors[] = "Failed to upload logo";
            }
        } else {
            $errors[] = "Invalid file type or size too large";
        }
    }
    
    // Handle password update
    $password_sql = "";
    $password_params = [];
    
    if (!empty($_POST['new_password'])) {
        if (strlen($_POST['new_password']) < 5) {
            $errors[] = "Password must be at least 5 characters";
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors[] = "Passwords do not match";
        } else {
            $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $password_sql = ", password = ?";
            $password_params[] = $hashed_password;
        }
    }
    
    if (empty($errors)) {
        // Build update query
        $sql = "UPDATE school_info SET 
                school_name = ?, 
                address = ?, 
                phone = ?, 
                email = ?, 
                principal_name = ?, 
                principal_phone = ?, 
                inventory_officer_name = ?, 
                inventory_officer_phone = ?, 
                username = ?,
                logo_path = ? 
                $password_sql 
                WHERE id = ?";
        
        $update_stmt = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($update_stmt, $sql)) {
            // Bind parameters
            if (!empty($password_params)) {
                mysqli_stmt_bind_param(
                    $update_stmt, 
                    "sssssssssssi", 
                    $school_name, 
                    $address, 
                    $phone, 
                    $email, 
                    $principal_name, 
                    $principal_phone, 
                    $officer_name, 
                    $officer_phone, 
                    $username, 
                    $logo_path,
                    $password_params[0],
                    $school_id
                );
            } else {
                mysqli_stmt_bind_param(
                    $update_stmt, 
                    "ssssssssssi", 
                    $school_name, 
                    $address, 
                    $phone, 
                    $email, 
                    $principal_name, 
                    $principal_phone, 
                    $officer_name, 
                    $officer_phone, 
                    $username, 
                    $logo_path,
                    $school_id
                );
            }
            
            if (mysqli_stmt_execute($update_stmt)) {
                $success_message = "School details updated successfully!";
                
                // Refresh school data
                $school_data['school_name'] = $school_name;
                $school_data['address'] = $address;
                $school_data['phone'] = $phone;
                $school_data['email'] = $email;
                $school_data['principal_name'] = $principal_name;
                $school_data['principal_phone'] = $principal_phone;
                $school_data['inventory_officer_name'] = $officer_name;
                $school_data['inventory_officer_phone'] = $officer_phone;
                $school_data['username'] = $username;
                $school_data['logo_path'] = $logo_path;
            } else {
                $errors[] = "Database error: " . mysqli_error($conn);
            }
            
            mysqli_stmt_close($update_stmt);
        } else {
            $errors[] = "Failed to prepare update statement";
        }
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h2><i class="bi bi-building"></i> Change School Details</h2>
            <p class="text-muted">Update your school information and settings</p>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- School Details Form -->
        <div class="content-card">
            <form action="" method="POST" enctype="multipart/form-data" id="schoolDetailsForm">
                <!-- School Basic Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">
                            <i class="bi bi-info-circle"></i> Basic Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="school_name" class="form-label">School Name *</label>
                        <input type="text" class="form-control" id="school_name" name="school_name" 
                               value="<?php echo htmlspecialchars($school_data['school_name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($school_data['username'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="address" class="form-label">Address *</label>
                        <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($school_data['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($school_data['phone'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($school_data['email'] ?? ''); ?>" required>
                    </div>
                </div>

                <!-- Principal Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-success mb-3">
                            <i class="bi bi-person-badge"></i> Principal Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="principal_name" class="form-label">Principal Name *</label>
                        <input type="text" class="form-control" id="principal_name" name="principal_name" 
                               value="<?php echo htmlspecialchars($school_data['principal_name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="principal_phone" class="form-label">Principal Phone *</label>
                        <input type="tel" class="form-control" id="principal_phone" name="principal_phone" 
                               value="<?php echo htmlspecialchars($school_data['principal_phone'] ?? ''); ?>" required>
                    </div>
                </div>

                <!-- Inventory Officer Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-info mb-3">
                            <i class="bi bi-person-workspace"></i> Inventory Officer Information
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="officer_name" class="form-label">Officer Name *</label>
                        <input type="text" class="form-control" id="officer_name" name="officer_name" 
                               value="<?php echo htmlspecialchars($school_data['inventory_officer_name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="officer_phone" class="form-label">Officer Phone *</label>
                        <input type="tel" class="form-control" id="officer_phone" name="officer_phone" 
                               value="<?php echo htmlspecialchars($school_data['inventory_officer_phone'] ?? ''); ?>" required>
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-warning mb-3">
                            <i class="bi bi-key"></i> Change Password (Optional)
                        </h5>
                        <p class="text-muted small">Leave blank to keep current password</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                        <small class="text-muted">Minimum 5 characters</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <!-- School Logo -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-secondary mb-3">
                            <i class="bi bi-image"></i> School Logo
                        </h5>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="logo" class="form-label">Upload New Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                        <small class="text-muted">Allowed formats: JPG, PNG, GIF (Max size: 2MB)</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Current Logo</label>
                        <div>
                            <?php if (!empty($school_data['logo_path']) && file_exists($school_data['logo_path'])): ?>
                                <img src="<?php echo $school_data['logo_path']; ?>" alt="School Logo" class="preview-logo" id="currentLogo">
                            <?php else: ?>
                                <div class="border rounded p-3 text-center text-muted">
                                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    <p class="mb-0">No logo uploaded</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <button type="submit" name="update_school" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update School Details
                        </button>
                        <a href="main_page.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Additional Information Card -->
        <div class="content-card mt-4">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary">
                        <i class="bi bi-clock-history"></i> System Information
                    </h6>
                    <table class="table table-sm">
                        <tr>
                            <td>School ID:</td>
                            <td><strong>#<?php echo $school_data['id'] ?? 'N/A'; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Created Date:</td>
                            <td><strong><?php echo isset($school_data['created_at']) ? date('F j, Y', strtotime($school_data['created_at'])) : 'N/A'; ?></strong></td>
                        </tr>
                        <tr>
                            <td>Last Updated:</td>
                            <td><strong><?php echo isset($school_data['updated_at']) ? date('F j, Y', strtotime($school_data['updated_at'])) : 'Not available'; ?></strong></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-success">
                        <i class="bi bi-shield-check"></i> Security Tips
                    </h6>
                    <ul class="small">
                        <li>Regularly update your password for security</li>
                        <li>Keep school contact information current</li>
                        <li>Use official school email addresses</li>
                        <li>Upload a clear school logo for branding</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JavaScript -->
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create or update preview
            let preview = document.getElementById('logoPreview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'logoPreview';
                preview.className = 'preview-logo mt-2';
                input.parentNode.appendChild(preview);
            }
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Password match validation
document.getElementById('schoolDetailsForm').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    if (newPass || confirmPass) {
        if (newPass.length < 5) {
            e.preventDefault();
            alert('Password must be at least 5 characters long');
            return;
        }
        
        if (newPass !== confirmPass) {
            e.preventDefault();
            alert('Passwords do not match');
            return;
        }
    }
});

// Phone number validation
function validatePhone(input) {
    input.value = input.value.replace(/[^0-9]/g, '');
}
</script>

<?php require_once "footer.php"; ?>