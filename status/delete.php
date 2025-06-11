<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$status_id = intval($_GET['id']);

// Verify status belongs to user
$check_query = "SELECT id FROM status WHERE id = $status_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Get default status (first status of user)
    $default_status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM status WHERE user_id = $user_id AND id != $status_id ORDER BY id LIMIT 1"));
    
    if ($default_status) {
        // Move tasks to default status
        mysqli_query($conn, "UPDATE tasks SET status_id = {$default_status['id']} WHERE status_id = $status_id");
    }
    
    // Delete status
    mysqli_query($conn, "DELETE FROM status WHERE id = $status_id AND user_id = $user_id");
}

header("Location: index.php");
exit;
?>
