<?php
// validation.php
function inputEmptyRegister($sname, $cnumber, $email, $pass, $mobile, $repass) {
    return empty($sname) || empty($cnumber) || empty($email) || empty($mobile) || empty($pass) || empty($repass);
}

function inputEmptyLogin($email, $pass) {
    return empty($email) || empty($pass);
}

function emailInvalid($email) {
    return !preg_match("/^[a-zA-Z\d\._-]+@[a-zA-Z\d_-]+\.[a-zA-Z\d\.]{2,}$/", $email);
}

function mobileInvalid($mobile) {
    return !preg_match("/^[0][\d]{9}$/", $mobile);
}

function passwordInvalid($pass) {
    return !preg_match("/^.{5,}$/", $pass);
}

function passNotMatch($pass, $repass) {
    return $pass !== $repass;
}

function cnumberAvailable($conn, $cnumber) {
    $sql = "SELECT * FROM users WHERE Census_number = ?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?err=failedstmt");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "i", $cnumber);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);
    $exists = mysqli_fetch_assoc($data) ? true : false;
    
    mysqli_stmt_close($stmt);
    return $exists;
}

function validatePhone($phone) {
    return preg_match("/^[0-9]{10,15}$/", $phone);
}

function validateSchoolSetup($data) {
    $errors = [];
    
    if (empty($data['school_name'])) $errors[] = "School name is required";
    if (empty($data['address'])) $errors[] = "Address is required";
    if (empty($data['phone'])) $errors[] = "Phone number is required";
    if (empty($data['email'])) $errors[] = "Email is required";
    if (empty($data['principal_name'])) $errors[] = "Principal name is required";
    if (empty($data['principal_phone'])) $errors[] = "Principal phone is required";
    if (empty($data['officer_name'])) $errors[] = "Inventory officer name is required";
    if (empty($data['officer_phone'])) $errors[] = "Inventory officer phone is required";
    if (empty($data['username'])) $errors[] = "Username is required";
    if (empty($data['password'])) $errors[] = "Password is required";
    
    // Email validation
    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Password validation
    if (!empty($data['password']) && strlen($data['password']) < 5) {
        $errors[] = "Password must be at least 5 characters";
    }
    
    return $errors;
}
?>