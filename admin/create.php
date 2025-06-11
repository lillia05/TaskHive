<?php
session_start();
include '../config/db.php';

// cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$page_title = 'Tambah User';
$base_path = '../';
include '../includes/header.php';
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
                <h1 class="mb-4">Tambah Data User</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Nama Lengkap *</label>
                                        <input type="text" name="full_name" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username *</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password *</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role *</label>
                                        <select name="role" class="form-select" required>
                                            <option value="user">User Biasa</option>
                                            <option value="admin">Administrator</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">Foto Profile</label>
                                        <input type="file" name="profile_picture" class="form-control" accept="image/*">
                                        <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="simpan" class="btn btn-success">
                                    <i class="bi bi-check"></i> Simpan
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Cek apakah username atau email sudah ada
    $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['admin_error'] = "Username atau email sudah digunakan.";
        header("Location: index.php");
        exit;
    }
    
    $profile_picture = null;
    
    // Handle file upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = '../uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $profile_picture = time() . '_' . uniqid() . '.' . $file_extension;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture)) {
            // file upload berhasil
        } else {
            $profile_picture = null;
        }
    }
    
    $insert_query = "INSERT INTO users (full_name, username, email, password, role, profile_picture) 
                     VALUES ('$full_name', '$username', '$email', '$password_hash', '$role', '$profile_picture')";
    
    if (mysqli_query($conn, $insert_query)) {
        $user_id = mysqli_insert_id($conn);
        
        // membuat data default untuk user baru
        createDefaultData($user_id, $conn);
        
        $_SESSION['admin_success'] = "User berhasil ditambahkan!";
        echo "<script>
            alert('Data Berhasil Ditambah');
            window.location.href = 'index.php';
        </script>";
    } else {
        $_SESSION['admin_error'] = "Terjadi kesalahan saat menambah user.";
        header("Location: index.php");
        exit;
    }
}

include '../includes/footer.php';
?>
