<?php
session_start();
include '../config/db.php';

$page_title = 'Create Priority';
$base_path = '../';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
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
                <a href="index.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-flag"></i> Priorities
                </a>
                <a href="../status/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-circle"></i> Status
                </a>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="../admin/index.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-people"></i> Admin Panel
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <h1 class="mb-4">Create New Priority</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Priority Name *</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Priority Level *</label>
                                        <select name="level" class="form-select" required>
                                            <option value="">Select Level</option>
                                            <option value="1">1 - Lowest</option>
                                            <option value="2">2 - Low</option>
                                            <option value="3">3 - Medium</option>
                                            <option value="4">4 - High</option>
                                            <option value="5">5 - Critical</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color</label>
                                        <input type="color" name="color" class="form-control form-control-color" value="#3498db">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="create" class="btn btn-success">
                                    <i class="bi bi-check"></i> Create Priority
                                </button>
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
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
if (isset($_POST['create'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $level = intval($_POST['level']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    
    $query = "INSERT INTO priorities (user_id, name, level, color) 
              VALUES ($user_id, '$name', $level, '$color')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Priority created successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Error creating priority.');</script>";
    }
}

include '../includes/footer.php';
?>
