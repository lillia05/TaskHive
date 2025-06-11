<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$category_id = intval($_GET['id']);

// Verify category belongs to user
$check_query = "SELECT id FROM categories WHERE id = $category_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Get default category (first category of user)
    $default_category = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM categories WHERE user_id = $user_id AND id != $category_id ORDER BY id LIMIT 1"));
    
    if ($default_category) {
        // Move tasks to default category
        mysqli_query($conn, "UPDATE tasks SET category_id = {$default_category['id']} WHERE category_id = $category_id");
    }
    
    // Delete category
    mysqli_query($conn, "DELETE FROM categories WHERE id = $category_id AND user_id = $user_id");
}

header("Location: index.php");
exit;
?>
