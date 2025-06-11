<?php
session_start();
include '../config/db.php';

$page_title = 'Edit Priority';
$base_path = '../';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$priority_id = intval($_GET['id']);

// Get priority data
$priority_query = "SELECT * FROM priorities WHERE id = $priority_id AND user_id = $user_id";
$priority_result = mysqli_query($conn, $priority_query);
$priority = mysqli_fetch_assoc($priority_result);

if (!$priority) {
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
                <h1 class="mb-4">Edit Priority</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Priority Name *</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($priority['name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Priority Level *</label>
                                        <select name="level" class="form-select" required>
                                            <option value="1" <?php echo ($priority['level'] == 1) ? 'selected' : ''; ?>>1 - Lowest</option>
                                            <option value="2" <?php echo ($priority['level'] == 2) ? 'selected' : ''; ?>>2 - Low</option>
                                            <option value="3" <?php echo ($priority['level'] == 3) ? 'selected' : ''; ?>>3 - Medium</option>
                                            <option value="4" <?php echo ($priority['level'] == 4) ? 'selected' : ''; ?>>4 - High</option>
                                            <option value="5" <?php echo ($priority['level'] == 5) ? 'selected' : ''; ?>>5 - Critical</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color</label>
                                        <input type="color" name="color" class="form-control form-control-color" value="<?php echo $priority['color']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-warning">
                                    <i class="bi bi-check"></i> Update Priority
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
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $level = intval($_POST['level']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    
    $query = "UPDATE priorities SET 
              name = '$name', 
              level = $level, 
              color = '$color'
              WHERE id = $priority_id AND user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Priority updated successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Error updating priority.');</script>";
    }
}

include '../includes/footer.php';
?>
