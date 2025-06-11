<?php
session_start();
include '../config/db.php';

$page_title = 'Create Status';
$base_path = '../';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Get next order position
$next_order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(MAX(order_position), 0) + 1 as next_order FROM status WHERE user_id = $user_id"))['next_order'];
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
                <a href="index.php" class="list-group-item list-group-item-action active">
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
                <h1 class="mb-4">Create New Status</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Status Name *</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="is_completed" class="form-label">Status Type *</label>
                                        <select name="is_completed" class="form-select" required>
                                            <option value="0">In Progress</option>
                                            <option value="1">Completed</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="order_position" class="form-label">Order Position</label>
                                        <input type="number" name="order_position" class="form-control" value="<?php echo $next_order; ?>" min="1">
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
                                    <i class="bi bi-check"></i> Create Status
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
    $is_completed = intval($_POST['is_completed']);
    $order_position = intval($_POST['order_position']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    
    $query = "INSERT INTO status (user_id, name, is_completed, order_position, color) 
              VALUES ($user_id, '$name', $is_completed, $order_position, '$color')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Status created successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Error creating status.');</script>";
    }
}

include '../includes/footer.php';
?>
