<?php
session_start();
include '../config/db.php';

// cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$user_id = intval($_GET['id']);

// mencegah penghapusan akun sendiri
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['admin_error'] = "Anda tidak dapat menghapus akun sendiri.";
    header("Location: index.php");
    exit;
}

// mengambil data user berdasarkan ID
$user_query = "SELECT profile_picture FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if ($user) {
    // menghapus file profil jika ada
    if (!empty($user['profile_picture']) && file_exists('../uploads/' . $user['profile_picture'])) {
        unlink('../uploads/' . $user['profile_picture']);
    }
    
    // menghapus user dari database
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
