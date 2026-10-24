<?php
// auth/logout.php
session_start();
session_unset();
session_destroy();

// Clear cookies
if(isset($_COOKIE["emailcookie"])){
    setcookie("emailcookie", "", time() - (3600 * 24 * 7), "/");
}
if(isset($_COOKIE["passwordcookie"])){
    setcookie("passwordcookie", "", time() - (3600 * 24 * 7), "/");
}

header("location: ../login.php");
exit();
?>