<?php
session_start();
include '../config/db.php';

// cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$page_title = 'Edit User';
$base_path = '../';
include '../includes/header.php';

$user_id = intval($_GET['id']);

// mengambil data user berdasarkan ID
$user_query = "SELECT id, role, created_at FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    header("Location: index.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light min-vh-100">
            <div class="list-group list-group-flush mt-3">
                <a href="../dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="../tasks/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-check-square"></i> Tasks
                </a>
                <a href="../categories/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-folder"></i> Categories
                </a>
                <a href="../priorities/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-flag"></i> Priorities
                </a>
                <a href="../status/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-circle"></i> Status
                </a>
                <a href="index.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-people"></i> Admin Panel
                </a>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <h1 class="mb-4">Edit User Role</h1>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role *</label>
                                        <select name="role" class="form-select" required>
                                            <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User Biasa</option>
                                            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                                        </select>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" name="update" class="btn btn-warning">
                                            <i class="bi bi-check"></i> Update Role
                                        </button>
                                        <a href="index.php" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">User Info</h5>
                            </div>
                            <div class="card-body text-center">
                                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <hr>
                                <small class="text-muted">
                                    Bergabung: <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['update'])) {
    $role = $_POST['role'];

    $update_query = "UPDATE users SET role = '$role' WHERE id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['admin_success'] = "Role user berhasil diperbarui!";
    } else {
        $_SESSION['admin_error'] = "Gagal memperbarui role user.";
    }

    header("Location: index.php");
    exit;
}

include '../includes/footer.php';
?>
