<?php
session_start();
include '../config/db.php';

$page_title = 'Categories';
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Categories Management</h1>
                    <a href="create.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> New Category
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <?php
                        $categories_query = "SELECT * FROM categories WHERE user_id = $user_id ORDER BY name";
                        $categories_result = mysqli_query($conn, $categories_query);
                        
                        if (mysqli_num_rows($categories_result) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Color</th>
                                        <th>Tasks Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                    <?php
                                    $task_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE category_id = {$category['id']}"))['count'];
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $category['color']; ?>">
                                                <?php echo $category['color']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $task_count; ?> tasks</span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($category['created_at'])); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $category['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Delete this category? All tasks will be moved to default category.')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-folder display-1 text-muted"></i>
                            <h4 class="text-muted">No categories found</h4>
                            <p class="text-muted">Create your first category to organize your tasks!</p>
                            <a href="create.php" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Category
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
