<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>TaskHive - Login</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head> 
<body class="bg-light"> 
    <div class="container mt-5"> 
        <div class="row justify-content-center"> 
            <div class="col-md-6 col-lg-4"> 
                <div class="card shadow-sm rounded-4"> 
                    <div class="card-body p-4"> 
                        <div class="text-center mb-4">
                            <h2 class="text-primary"><i class="bi bi-hexagon"></i> TaskHive</h2>
                            <p class="text-muted">Sarang Produktivitas Anda</p>
                        </div>

                        <?php
                        session_start();
                        if (isset($_SESSION['user_id'])) {
                            header("Location: dashboard.php");
                            exit;
                        }

                        if (isset($_SESSION['login_error'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['login_error'] . '</div>';
                            unset($_SESSION['login_error']);
                        }

                        if (isset($_SESSION['register_success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['register_success'] . '</div>';
                            unset($_SESSION['register_success']);
                        }
                        ?>

                        <form method="POST" action="auth/login.php"> 
                            <div class="mb-3"> 
                                <label for="username" class="form-label">Username</label> 
                                <input type="text" name="username" class="form-control" id="username" required> 
                            </div> 
                            <div class="mb-3"> 
                                <label for="password" class="form-label">Password</label> 
                                <input type="password" name="password" class="form-control" id="password" required> 
                            </div> 
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button> 
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Belum punya akun? <a href="auth/register.php" class="text-decoration-none">Daftar di sini</a></p>
                        </div>
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</body> 
</html>
