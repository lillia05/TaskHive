<?php
session_start();
include '../config/db.php';

$page_title = 'Edit Category';
$base_path = '../';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$category_id = intval($_GET['id']);

// mengambil data kategori berdasarkan ID dan user_id
$category_query = "SELECT * FROM categories WHERE id = $category_id AND user_id = $user_id";
$category_result = mysqli_query($conn, $category_query);
$category = mysqli_fetch_assoc($category_result);

if (!$category) {
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
                <a href="index.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-folder"></i> Categories
                </a>
                <a href="../priorities/index.php" class="list-group-item list-group-item-action">
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
                <h1 class="mb-4">Edit Category</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Category Name *</label>
                                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color</label>
                                        <input type="color" name="color" class="form-control form-control-color" value="<?php echo $category['color']; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-warning">
                                    <i class="bi bi-check"></i> Update Category
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
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    
    $query = "UPDATE categories SET 
              name = '$name', 
              description = '$description', 
              color = '$color' 
              WHERE id = $category_id AND user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Category updated successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Error updating category.');</script>";
    }
}

include '../includes/footer.php';
?>
