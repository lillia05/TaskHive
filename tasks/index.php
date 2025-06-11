<?php
session_start();
$base_path = '../';
include '../config/db.php';

$page_title = 'Tasks';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Handle filters
$where_conditions = ["t.user_id = $user_id"];
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_priority = isset($_GET['priority']) ? $_GET['priority'] : '';
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$filter_due_date = isset($_GET['due_date']) ? $_GET['due_date'] : '';

if ($filter_category) {
    $where_conditions[] = "t.category_id = " . intval($filter_category);
}
if ($filter_priority) {
    $where_conditions[] = "t.priority_id = " . intval($filter_priority);
}
if ($filter_status) {
    $where_conditions[] = "t.status_id = " . intval($filter_status);
}
if ($filter_due_date) {
    if ($filter_due_date == 'overdue') {
        $where_conditions[] = "t.due_date < CURDATE() AND s.is_completed = false";
    } elseif ($filter_due_date == 'today') {
        $where_conditions[] = "t.due_date = CURDATE()";
    } elseif ($filter_due_date == 'week') {
        $where_conditions[] = "t.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
    }
}

$where_clause = implode(' AND ', $where_conditions);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light min-vh-100">
            <div class="list-group list-group-flush mt-3">
                <a href="../dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="index.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-check-square"></i> Tasks
                </a>
                <a href="../categories/index.php" class="list-group-item list-group-item-action">
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
                    <h1>Tasks Management</h1>
                    <a href="create.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> New Task
                    </a>
                </div>
                
                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php
                                    $categories = mysqli_query($conn, "SELECT * FROM categories WHERE user_id = $user_id ORDER BY name");
                                    while ($cat = mysqli_fetch_assoc($categories)) {
                                        $selected = ($filter_category == $cat['id']) ? 'selected' : '';
                                        echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="">All Priorities</option>
                                    <?php
                                    $priorities = mysqli_query($conn, "SELECT * FROM priorities WHERE user_id = $user_id ORDER BY level DESC");
                                    while ($pri = mysqli_fetch_assoc($priorities)) {
                                        $selected = ($filter_priority == $pri['id']) ? 'selected' : '';
                                        echo "<option value='{$pri['id']}' $selected>{$pri['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <?php
                                    $statuses = mysqli_query($conn, "SELECT * FROM status WHERE user_id = $user_id ORDER BY order_position");
                                    while ($stat = mysqli_fetch_assoc($statuses)) {
                                        $selected = ($filter_status == $stat['id']) ? 'selected' : '';
                                        echo "<option value='{$stat['id']}' $selected>{$stat['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Due Date</label>
                                <select name="due_date" class="form-select">
                                    <option value="">All Dates</option>
                                    <option value="overdue" <?php echo ($filter_due_date == 'overdue') ? 'selected' : ''; ?>>Overdue</option>
                                    <option value="today" <?php echo ($filter_due_date == 'today') ? 'selected' : ''; ?>>Today</option>
                                    <option value="week" <?php echo ($filter_due_date == 'week') ? 'selected' : ''; ?>>This Week</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Tasks Table -->
                <div class="card">
                    <div class="card-body">
                        <?php
                        $tasks_query = "
                            SELECT t.*, c.name as category_name, c.color as category_color,
                                   p.name as priority_name, p.level as priority_level, p.color as priority_color,
                                   s.name as status_name, s.color as status_color, s.is_completed
                            FROM tasks t 
                            JOIN categories c ON t.category_id = c.id 
                            JOIN priorities p ON t.priority_id = p.id 
                            JOIN status s ON t.status_id = s.id 
                            WHERE $where_clause
                            ORDER BY p.level DESC, t.due_date ASC, t.created_at DESC
                        ";
                        $tasks_result = mysqli_query($conn, $tasks_query);
                        
                        if (mysqli_num_rows($tasks_result) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Task</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($task = mysqli_fetch_assoc($tasks_result)): ?>
                                    <tr class="<?php echo $task['is_completed'] ? 'table-success' : ''; ?>">
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($task['title']); ?></strong>
                                                <?php if ($task['description']): ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($task['description'], 0, 100)); ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $task['category_color']; ?>">
                                                <?php echo htmlspecialchars($task['category_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $task['priority_color']; ?>">
                                                <?php echo htmlspecialchars($task['priority_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: <?php echo $task['status_color']; ?>">
                                                <?php echo htmlspecialchars($task['status_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($task['due_date']) {
                                                $due_date = date('d/m/Y', strtotime($task['due_date']));
                                                $is_overdue = !$task['is_completed'] && strtotime($task['due_date']) < strtotime('today');
                                                echo $is_overdue ? "<span class='text-danger'>$due_date</span>" : $due_date;
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $task['id']; ?>" class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Delete this task?')">
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
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="text-muted">No tasks found</h4>
                            <p class="text-muted">Create your first task to get started!</p>
                            <a href="create.php" class="btn btn-primary">
                                <i class="bi bi-plus"></i> Create Task
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