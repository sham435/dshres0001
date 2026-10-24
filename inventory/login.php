<?php
// login.php
require_once "./include.php";
session_start();

// Get school info for display
$school_sql = "SELECT * FROM school_info LIMIT 1";
$school_result = mysqli_query($conn, $school_sql);
$school = mysqli_fetch_assoc($school_result);

// If no school is set up, redirect to setup
if(!$school){
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $school['school_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 90%;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .school-logo {
            max-width: 100px;
            max-height: 100px;
            margin: 0 auto 15px;
            display: block;
            border-radius: 50%;
        }
        .school-name {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?php if($school['logo_path'] && file_exists($school['logo_path'])): ?>
            <img src="<?php echo $school['logo_path']; ?>" alt="School Logo" class="school-logo">
        <?php else: ?>
            <div class="school-logo bg-light d-flex align-items-center justify-content-center">
                <span class="display-4">🏫</span>
            </div>
        <?php endif; ?>
        
        <div class="school-name">
            <?php echo htmlspecialchars($school['school_name']); ?>
        </div>

        <?php if(isset($_GET['err'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    $error = $_GET['err'];
                    if($error == 'login_empty_inputs') echo "Please enter both email and password";
                    elseif($error == 'invalid_email') echo "Please enter a valid email address";
                    elseif($error == 'invalid_password') echo "Password must be at least 5 characters";
                    elseif($error == 'loginfailedpssword') echo "Incorrect password";
                    elseif($error == 'loginfailedemail') echo "Email not found";
                    elseif($error == 'failedstmt') echo "System error. Please try again";
                    else echo "Login failed";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="auth/login.php" method="POST" id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo isset($_COOKIE['emailcookie']) ? $_COOKIE['emailcookie'] : ''; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="pass" 
                       value="<?php echo isset($_COOKIE['passwordcookie']) ? $_COOKIE['passwordcookie'] : ''; ?>" required>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="re-check"
                       <?php echo isset($_COOKIE['emailcookie']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            
            <button type="submit" name="login-btn" class="btn btn-primary w-100">Login</button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                Inventory Management System v1.0
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    </script>
</body>
</html>