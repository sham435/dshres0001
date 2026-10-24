<?php
// quick_edit_item.php
require_once "include.php";
session_start();

$school_id = $_SESSION['school_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = (int)$_POST['item_id'];
    $item_name = mysqli_real_escape_string($conn, trim($_POST['item_name']));
    $condition_status = mysqli_real_escape_string($conn, $_POST['condition_status']);
    $status = $_POST['status'];
    
    // Set values based on status
    $is_transferred = ($status == 'transferred') ? 1 : 0;
    $borrowed_officer = ($status == 'borrowed' && !empty($_POST['borrowed_officer'])) ? "'" . mysqli_real_escape_string($conn, $_POST['borrowed_officer']) . "'" : 'NULL';
    $transferred_date = ($status == 'transferred' && !empty($_POST['transferred_date'])) ? "'" . mysqli_real_escape_string($conn, $_POST['transferred_date']) . "'" : 'NULL';
    
    // If not transferred, set transferred_date to NULL
    if ($status != 'transferred') {
        $transferred_date = 'NULL';
    }
    
    // Update query
    $sql = "UPDATE inventory_items SET 
            item_name = '$item_name',
            condition_status = '$condition_status',
            is_transferred = $is_transferred,
            borrowed_officer = $borrowed_officer,
            transferred_date = $transferred_date
            WHERE id = $item_id AND school_id = $school_id";
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Item updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($conn)]);
    }
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>