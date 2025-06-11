<?php
session_start();
include '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TaskHive - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="text-primary"><i class="bi bi-hexagon"></i> TaskHive</h2>
                            <p class="text-muted">Daftar Akun Baru</p>
                        </div>

                        <?php
                        if (isset($_SESSION['register_error'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['register_error'] . '</div>';
                            unset($_SESSION['register_error']);
                        }
                        ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Foto Profile (Opsional)</label>
                                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" name="register" class="btn btn-success w-100 mb-3">
                                <i class="bi bi-person-plus"></i> Daftar
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun? <a href="../index.php" class="text-decoration-none">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['register'])) {
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Check if username or email already exists
        $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['register_error'] = "Username atau email sudah digunakan.";
        } else {
            $profile_picture = null;
            
            // Handle file upload dengan nama asli
            if (!empty($_FILES['profile_picture']['name'])) {
                $upload_dir = '../uploads/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                // Gunakan nama file asli
                $profile_picture = $_FILES['profile_picture']['name'];
                
                // Cek apakah file sudah ada, jika ya tambahkan nomor
                $original_name = pathinfo($profile_picture, PATHINFO_FILENAME);
                $file_extension = pathinfo($profile_picture, PATHINFO_EXTENSION);
                $counter = 1;
                
                while (file_exists($upload_dir . $profile_picture)) {
                    $profile_picture = $original_name . '_' . $counter . '.' . $file_extension;
                    $counter++;
                }
                
                if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture)) {
                    $profile_picture = null;
                }
            }
            
            $insert_query = "INSERT INTO users (full_name, username, email, password, profile_picture) VALUES ('$full_name', '$username', '$email', '$password', '$profile_picture')";
            
            if (mysqli_query($conn, $insert_query)) {
                $user_id = mysqli_insert_id($conn);
                createDefaultData($user_id, $conn);
                
                $_SESSION['register_success'] = "Registrasi berhasil! Silakan login.";
                header("Location: ../index.php");
                exit;
            } else {
                $_SESSION['register_error'] = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
    ?>
</body>
</html>