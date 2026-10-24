<?php
// save_school.php
require_once "./include.php";
require_once "./validation.php";

if(isset($_POST["setup_school"])){
    $school_name = $_POST["school_name"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $principal_name = $_POST["principal_name"];
    $principal_phone = $_POST["principal_phone"];
    $officer_name = $_POST["officer_name"];
    $officer_phone = $_POST["officer_phone"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Basic validation
    if(empty($school_name) || empty($address) || empty($phone) || empty($email) || 
       empty($principal_name) || empty($principal_phone) || empty($officer_name) || 
       empty($officer_phone) || empty($username) || empty($password)){
        header("location: index.php?err=empty_inputs");
        exit();
    }

    // Email validation
    if(emailInvalid($email)){
        header("location: index.php?err=invalid_email");
        exit();
    }

    // Password validation
    if(passwordInvalid($password)){
        header("location: index.php?err=invalid_password");
        exit();
    }

    // Check if username already exists
    $check_sql = "SELECT * FROM school_info WHERE username = ?";
    $check_stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($check_stmt, $check_sql)){
        mysqli_stmt_bind_param($check_stmt, "s", $username);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) > 0){
            header("location: index.php?err=username_taken");
            exit();
        }
    }

    // Handle logo upload
    $logo_path = null;
    if(isset($_FILES['logo']) && $_FILES['logo']['error'] == 0){
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if(in_array($_FILES['logo']['type'], $allowed_types) && $_FILES['logo']['size'] <= $max_size){
            $upload_dir = 'uploads/logos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'logo_' . time() . '.' . $extension;
            $target_path = $upload_dir . $filename;
            
            if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)){
                $logo_path = $target_path;
            }
        } else {
            header("location: index.php?err=upload_failed");
            exit();
        }
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO school_info (school_name, address, phone, email, principal_name, 
            principal_phone, inventory_officer_name, inventory_officer_phone, username, password, logo_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, "sssssssssss", $school_name, $address, $phone, $email, 
                              $principal_name, $principal_phone, $officer_name, $officer_phone, 
                              $username, $hashed_password, $logo_path);
        
        if(mysqli_stmt_execute($stmt)){
            header("location: index.php?success=1");
        } else {
            header("location: index.php?err=db_error");
        }
        
        mysqli_stmt_close($stmt);
    } else {
        header("location: index.php?err=db_error");
    }
} else {
    header("location: index.php");
    exit();
}
?>