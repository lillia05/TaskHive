<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$priority_id = intval($_GET['id']);

// Verify priority belongs to user
$check_query = "SELECT id FROM priorities WHERE id = $priority_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Get default priority (first priority of user)
    $default_priority = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM priorities WHERE user_id = $user_id AND id != $priority_id ORDER BY id LIMIT 1"));
    
    if ($default_priority) {
        // Move tasks to default priority
        mysqli_query($conn, "UPDATE tasks SET priority_id = {$default_priority['id']} WHERE priority_id = $priority_id");
    }
    
    // Delete priority
    mysqli_query($conn, "DELETE FROM priorities WHERE id = $priority_id AND user_id = $user_id");
}

header("Location: index.php");
exit;
?>
