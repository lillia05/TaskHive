<?php
session_start();
include 'config/db.php';

$page_title = 'Profile';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

//mengambil data pengguna
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light min-vh-100">
            <div class="list-group list-group-flush mt-3">
                <a href="dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="tasks/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-check-square"></i> Tasks
                </a>
                <a href="categories/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-folder"></i> Categories
                </a>
                <a href="priorities/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-flag"></i> Priorities
                </a>
                <a href="status/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-circle"></i> Status
                </a>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="admin/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-people"></i> Admin Panel
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- bagian isi -->
        <div class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <h1 class="mb-4">My Profile</h1>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Profile Information</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['profile_success'])) {
                                    echo '<div class="alert alert-success">' . $_SESSION['profile_success'] . '</div>';
                                    unset($_SESSION['profile_success']);
                                }
                                if (isset($_SESSION['profile_error'])) {
                                    echo '<div class="alert alert-danger">' . $_SESSION['profile_error'] . '</div>';
                                    unset($_SESSION['profile_error']);
                                }
                                ?>
                                
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="full_name" class="form-label">Full Name</label>
                                                <input type="text" name="full_name" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" name="username" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="profile_picture" class="form-label">Profile Picture</label>
                                        <input type="file" name="profile_picture" class="form-control" accept="image/*">
                                        <small class="text-muted">Leave empty to keep current picture</small>
                                    </div>
                                    
                                    <hr>
                                    
                                    <h6>Change Password (Optional)</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <input type="password" name="new_password" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                                <input type="password" name="confirm_password" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" name="update_profile" class="btn btn-primary">
                                        <i class="bi bi-check"></i> Update Profile
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Current Profile Picture</h5>
                            </div>
                            <div class="card-body text-center">
                                <?php if ($user['profile_picture']): ?>
                                    <img src="uploads/<?php echo $user['profile_picture']; ?>" 
                                         alt="Profile Picture" class="img-fluid rounded-circle mb-3" 
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" 
                                         style="width: 150px; height: 150px;">
                                        <i class="bi bi-person fs-1 text-white"></i>
                                    </div>
                                <?php endif; ?>
                                <h6><?php echo htmlspecialchars($user['full_name']); ?></h6>
                                <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></p>
                                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Account Statistics</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stats = [
                                    'total_tasks' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE user_id = $user_id"))['count'],
                                    'total_categories' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM categories WHERE user_id = $user_id"))['count'],
                                    'total_priorities' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM priorities WHERE user_id = $user_id"))['count'],
                                    'total_status' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM status WHERE user_id = $user_id"))['count']
                                ];
                                ?>
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <h4 class="text-primary"><?php echo $stats['total_tasks']; ?></h4>
                                        <small>Tasks</small>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h4 class="text-success"><?php echo $stats['total_categories']; ?></h4>
                                        <small>Categories</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-warning"><?php echo $stats['total_priorities']; ?></h4>
                                        <small>Priorities</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-info"><?php echo $stats['total_status']; ?></h4>
                                        <small>Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    //cek apakah username atau email sudah tersedia
    $check_query = "SELECT * FROM users WHERE (username='$username' OR email='$email') AND id != $user_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['profile_error'] = "Username atau email sudah digunakan oleh user lain.";
    } else {
        //menangani ganti password
        $password_update = "";
        if (!empty($new_password)) {
            if ($new_password !== $confirm_password) {
                $_SESSION['profile_error'] = "Password konfirmasi tidak cocok.";
                header("Location: profile.php");
                exit;
            }
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $password_update = ", password = '$password_hash'";
        }
        
        //menangani file upload
        $picture_update = "";
        if (!empty($_FILES['profile_picture']['name'])) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            //menghapus foto lama
            if (!empty($user['profile_picture']) && file_exists($upload_dir . $user['profile_picture'])) {
                unlink($upload_dir . $user['profile_picture']);
            }
            
            // Gunakan nama file asli
            $new_filename = $_FILES['profile_picture']['name'];
            
            // Cek apakah file sudah ada, jika ya tambahkan nomor
            $original_name = pathinfo($new_filename, PATHINFO_FILENAME);
            $file_extension = pathinfo($new_filename, PATHINFO_EXTENSION);
            $counter = 1;
            
            while (file_exists($upload_dir . $new_filename)) {
                $new_filename = $original_name . '_' . $counter . '.' . $file_extension;
                $counter++;
            }
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $new_filename)) {
                $picture_update = ", profile_picture = '$new_filename'";
            }
        }
        
        $update_query = "UPDATE users SET 
                        full_name = '$full_name', 
                        username = '$username', 
                        email = '$email'
                        $password_update
                        $picture_update
                        WHERE id = $user_id";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['full_name'] = $full_name;
            $_SESSION['username'] = $username;
            $_SESSION['profile_success'] = "Profile berhasil diupdate!";
        } else {
            $_SESSION['profile_error'] = "Terjadi kesalahan saat mengupdate profile.";
        }
    }
    
    header("Location: profile.php");
    exit;
}

include 'includes/footer.php';
?>