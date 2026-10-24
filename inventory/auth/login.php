<?php
// auth/login.php
require_once "../include.php";
require_once "../validation.php";

if(isset($_POST["login-btn"])){
    $email    = $_POST["email"];
    $pass     = $_POST["pass"];
    $remember = isset($_POST["re-check"]) ? $_POST["re-check"] : null;

    if(inputEmptyLogin($email,$pass)){
        header("location: ../login.php?err=login_empty_inputs");
        exit();
    }
    else if(emailInvalid($email)){
        header("location: ../login.php?err=invalid_email");
        exit();
    }
    else if(passwordInvalid($pass)){
        header("location: ../login.php?err=invalid_password");
        exit();
    }
    else{
        loginUser($conn,$email,$pass,$remember);
    }
}
else{
    header("location: ../login.php");
    exit();
}

function loginUser($conn,$email,$pass,$remember){
    // First check in school_info table (for admin)
    $sql = "SELECT * FROM school_info WHERE username = ? OR email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../login.php?err=failedstmt");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $email, $email);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($data)){
        $password = $row["password"];
        
        if(password_verify($pass, $password)){
            session_start();
            $_SESSION["user_email"] = $row["email"] ?? $row["username"];
            $_SESSION["user_type"] = "admin";
            $_SESSION["school_id"] = $row["id"];

            if(isset($remember)){
                setcookie("emailcookie", $email, time() + (3600 * 24 * 7), "/");
                setcookie("passwordcookie", $pass, time() + (3600 * 24 * 7), "/");
            }
            else{
                if(isset($_COOKIE["emailcookie"])){
                    setcookie("emailcookie", "", time() - (3600 * 24 * 7), "/");
                }
                if(isset($_COOKIE["passwordcookie"])){
                    setcookie("passwordcookie", "", time() - (3600 * 24 * 7), "/");
                }
            }

            header("location: ../main_page.php");
            exit();
        }
    }
    
    // If not found in school_info, check users table
    $sql = "SELECT * FROM users WHERE email = ?;";
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $data = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($data)){
        $password = $row["passaword"]; // Note: typo in your database column
        
        if($pass == $password || password_verify($pass, $password)){
            session_start();
            $_SESSION["user_email"] = $row["email"];
            $_SESSION["user_type"] = "user";
            $_SESSION["user_id"] = $row["id"];

            if(isset($remember)){
                setcookie("emailcookie", $email, time() + (3600 * 24 * 7), "/");
                setcookie("passwordcookie", $pass, time() + (3600 * 24 * 7), "/");
            }
            else{
                if(isset($_COOKIE["emailcookie"])){
                    setcookie("emailcookie", "", time() - (3600 * 24 * 7), "/");
                }
                if(isset($_COOKIE["passwordcookie"])){
                    setcookie("passwordcookie", "", time() - (3600 * 24 * 7), "/");
                }
            }

            header("location: ../main_page.php");
            exit();
        }
        else{
            header("location: ../login.php?err=loginfailedpssword");
            exit();
        }
    }
    else{
        header("location: ../login.php?err=loginfailedemail");
        exit();
    }
    
    mysqli_stmt_close($stmt);
}
?>