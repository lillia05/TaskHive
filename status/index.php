<?php
session_start();
include '../config/db.php';

$page_title = 'Status';
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Status Management</h1>
                    <a href="create.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> New Status
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <?php
                        $status_query = "SELECT * FROM status WHERE user_id = $user_id ORDER BY order_position";
                        $status_result = mysqli_query($conn, $status_query);
                        
                        if (mysqli_num_rows($status_result) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Order</th>
                                        <th>Color</th>
                                        <th>Tasks Count</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($status = mysqli_fetch_assoc($status_result)): ?>
                                    <?php
                                    $task_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE status_id = {$status['id']}"))['count'];
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($status['name']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $status['is_completed'] ? 'success' : 'warning'; ?>">
                                                <?php echo $status['is_completed'] ? 'Completed' : 'In Progress'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo $status['order_position']; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $status['color']; ?>">
                                                <?php echo $status['color']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $task_count; ?> tasks</span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($status['created_at'])); ?></td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $status['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $status['id']; ?>" class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Delete this status? All tasks will be moved to default status.')">
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
                            <i class="bi bi-circle display-1 text-muted"></i>
                            <h4 class="text-muted">No status found</h4>
                            <p class="text-muted">Create your first status to track your tasks!</p>
                            <a href="create.php" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Status
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
