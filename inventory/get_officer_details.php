<?php
// get_officer_details.php
require_once "include.php";
session_start();

$school_id = $_SESSION['school_id'] ?? 1;
$officer_id = isset($_GET['officer_id']) ? (int)$_GET['officer_id'] : 0;

if ($officer_id) {
    $result = mysqli_query($conn, "SELECT id, officer_name, officer_id_number, phone FROM officers WHERE id = $officer_id AND school_id = $school_id");
    if ($row = mysqli_fetch_assoc($result)) {
        header('Content-Type: application/json');
        echo json_encode($row);
        exit();
    }
}

echo json_encode(['error' => 'Officer not found']);
?>