<?php
session_start();
include 'config/db.php';

$page_title = 'Dashboard';
include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Get statistics
$total_tasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks WHERE user_id = $user_id"))['count'];
$completed_tasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks t JOIN status s ON t.status_id = s.id WHERE t.user_id = $user_id AND s.is_completed = true"))['count'];
$pending_tasks = $total_tasks - $completed_tasks;
$overdue_tasks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM tasks t JOIN status s ON t.status_id = s.id WHERE t.user_id = $user_id AND s.is_completed = false AND t.due_date < CURDATE()"))['count'];
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light min-vh-100">
            <div class="list-group list-group-flush mt-3">
                <a href="dashboard.php" class="list-group-item list-group-item-action active">
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
                <a href="admin/" class="list-group-item list-group-item-action">
                    <i class="bi bi-people"></i> Admin Panel
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Content Area -->
        <div class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <h1 class="mb-4">Dashboard</h1>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $total_tasks; ?></h4>
                                        <p class="mb-0">Total Tasks</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-square fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $completed_tasks; ?></h4>
                                        <p class="mb-0">Completed</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-check-circle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $pending_tasks; ?></h4>
                                        <p class="mb-0">Pending</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-clock fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4><?php echo $overdue_tasks; ?></h4>
                                        <p class="mb-0">Overdue</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-exclamation-triangle fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Tasks -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Tasks</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $recent_tasks = mysqli_query($conn, "
                                    SELECT t.*, c.name as category_name, p.name as priority_name, s.name as status_name, s.color as status_color
                                    FROM tasks t 
                                    JOIN categories c ON t.category_id = c.id 
                                    JOIN priorities p ON t.priority_id = p.id 
                                    JOIN status s ON t.status_id = s.id 
                                    WHERE t.user_id = $user_id 
                                    ORDER BY t.created_at DESC 
                                    LIMIT 5
                                ");
                                
                                if (mysqli_num_rows($recent_tasks) > 0):
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Task</th>
                                                <th>Category</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($task = mysqli_fetch_assoc($recent_tasks)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td><?php echo htmlspecialchars($task['category_name']); ?></td>
                                                <td><?php echo htmlspecialchars($task['priority_name']); ?></td>
                                                <td>
                                                    <span class="badge" style="background-color: <?php echo $task['status_color']; ?>">
                                                        <?php echo htmlspecialchars($task['status_name']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $task['due_date'] ? date('d/m/Y', strtotime($task['due_date'])) : '-'; ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <p class="text-muted">Belum ada task. <a href="tasks/create.php">Buat task pertama Anda</a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="tasks/create.php" class="btn btn-primary">
                                        <i class="bi bi-plus"></i> New Task
                                    </a>
                                    <a href="categories/create.php" class="btn btn-outline-primary">
                                        <i class="bi bi-folder-plus"></i> New Category
                                    </a>
                                    <a href="priorities/create.php" class="btn btn-outline-primary">
                                        <i class="bi bi-flag"></i> New Priority
                                    </a>
                                    <a href="status/create.php" class="btn btn-outline-primary">
                                        <i class="bi bi-circle"></i> New Status
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
