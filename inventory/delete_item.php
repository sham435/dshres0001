<?php
// delete_item.php
require_once "include.php";
session_start();

$school_id = $_SESSION['school_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    
    // Get image path to delete file
    $img_query = "SELECT image_path FROM inventory_items WHERE id = $item_id AND school_id = $school_id";
    $img_result = mysqli_query($conn, $img_query);
    
    if ($img_result && mysqli_num_rows($img_result) > 0) {
        $item = mysqli_fetch_assoc($img_result);
        
        // Delete image file if exists
        if ($item['image_path'] && file_exists($item['image_path'])) {
            unlink($item['image_path']);
        }
        
        // Delete from database
        $delete_sql = "DELETE FROM inventory_items WHERE id = $item_id AND school_id = $school_id";
        if (mysqli_query($conn, $delete_sql)) {
            header("location: inventory_list.php?msg=deleted");
            exit();
        }
    }
}

header("location: inventory_list.php");
exit();
?>