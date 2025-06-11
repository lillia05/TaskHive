<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$task_id = intval($_GET['id']);

//Memverifikasi tugas pengguna
$check_query = "SELECT id FROM tasks WHERE id = $task_id AND user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    $delete_query = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";
    mysqli_query($conn, $delete_query);
}

header("Location: index.php");
exit;
?>
