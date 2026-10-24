<?php
// index.php
require_once "./include.php";
session_start();

// If school is already set up, redirect to login
$sql = "SELECT * FROM school_info LIMIT 1";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Setup - Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .preview-logo {
            max-width: 150px;
            max-height: 150px;
            margin: 10px 0;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="setup-container">
            <h2 class="text-center mb-4">School Setup Wizard</h2>
            <p class="text-center text-muted">Please enter your school information to get started</p>
            
            <?php if(isset($_GET['err'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                        $error = $_GET['err'];
                        if($error == 'empty_inputs') echo "Please fill in all fields";
                        elseif($error == 'invalid_email') echo "Please enter a valid email address";
                        elseif($error == 'invalid_phone') echo "Please enter a valid phone number";
                        elseif($error == 'username_taken') echo "Username already taken";
                        elseif($error == 'upload_failed') echo "Failed to upload logo";
                        elseif($error == 'db_error') echo "Database error occurred";
                        else echo "An error occurred";
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    School information saved successfully! 
                    <a href="login.php" class="alert-link">Proceed to Login</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="save_school.php" method="POST" enctype="multipart/form-data" id="schoolSetupForm">
                <!-- School Basic Information -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">School Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="school_name" class="form-label">School Name *</label>
                            <input type="text" class="form-control" id="school_name" name="school_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Principal Information -->
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Principal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="principal_name" class="form-label">Principal Name *</label>
                                <input type="text" class="form-control" id="principal_name" name="principal_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="principal_phone" class="form-label">Principal Phone *</label>
                                <input type="tel" class="form-control" id="principal_phone" name="principal_phone" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Officer Information -->
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Inventory Officer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="officer_name" class="form-label">Officer Name *</label>
                                <input type="text" class="form-control" id="officer_name" name="officer_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="officer_phone" class="form-label">Officer Phone *</label>
                                <input type="tel" class="form-control" id="officer_phone" name="officer_phone" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Credentials -->
                <div class="card mb-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Login Credentials</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="text-muted">Minimum 5 characters</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- School Logo -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">School Logo</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="logo" class="form-label">Upload Logo (Optional)</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" onchange="previewLogo(this)">
                            <small class="text-muted">Allowed formats: JPG, PNG, GIF (Max size: 2MB)</small>
                            <div class="mt-2">
                                <img id="logoPreview" class="preview-logo img-thumbnail" alt="Logo Preview">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="setup_school" class="btn btn-primary btn-lg">Save School Information</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form validation
        document.getElementById('schoolSetupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            if (password.length < 5) {
                e.preventDefault();
                alert('Password must be at least 5 characters long');
            }
        });
    </script>
</body>
</html>