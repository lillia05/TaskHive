<?php
session_start();
include '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$user_id = intval($_GET['id']);

// Prevent admin from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['admin_error'] = "Anda tidak dapat menghapus akun sendiri.";
    header("Location: index.php");
    exit;
}

// Get user data to delete profile picture
$user_query = "SELECT profile_picture FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if ($user) {
    // Delete profile picture if exists
    if (!empty($user['profile_picture']) && file_exists('../uploads/' . $user['profile_picture'])) {
        unlink('../uploads/' . $user['profile_picture']);
    }
    
    // Delete user (all related data will be deleted due to CASCADE)
    $delete_query = "DELETE FROM users WHERE id = $user_id";
    
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['admin_success'] = "User berhasil dihapus!";
    } else {
        $_SESSION['admin_error'] = "Terjadi kesalahan saat menghapus user.";
    }
} else {
    $_SESSION['admin_error'] = "User tidak ditemukan.";
}

header("Location: index.php");
exit;
?>
