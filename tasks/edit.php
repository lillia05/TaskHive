<?php
session_start();
include '../config/db.php';

$base_path = '../';
$page_title = 'Edit Task';
include '../includes/header.php';

$user_id = $_SESSION['user_id'];
$task_id = intval($_GET['id']);

//mengambil data tugas
$task_query = "SELECT * FROM tasks WHERE id = $task_id AND user_id = $user_id";
$task_result = mysqli_query($conn, $task_query);
$task = mysqli_fetch_assoc($task_result);

if (!$task) {
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
        
        <!-- Bagian isi -->
        <div class="col-md-9 col-lg-10">
            <div class="container-fluid py-4">
                <h1 class="mb-4">Edit Task</h1>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Task Title *</label>
                                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select name="category_id" class="form-select" required>
                                            <?php
                                            $categories = mysqli_query($conn, "SELECT * FROM categories WHERE user_id = $user_id ORDER BY name");
                                            while ($cat = mysqli_fetch_assoc($categories)) {
                                                $selected = ($cat['id'] == $task['category_id']) ? 'selected' : '';
                                                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="priority_id" class="form-label">Priority *</label>
                                        <select name="priority_id" class="form-select" required>
                                            <?php
                                            $priorities = mysqli_query($conn, "SELECT * FROM priorities WHERE user_id = $user_id ORDER BY level DESC");
                                            while ($pri = mysqli_fetch_assoc($priorities)) {
                                                $selected = ($pri['id'] == $task['priority_id']) ? 'selected' : '';
                                                echo "<option value='{$pri['id']}' $selected>{$pri['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="status_id" class="form-label">Status *</label>
                                        <select name="status_id" class="form-select" required>
                                            <?php
                                            $statuses = mysqli_query($conn, "SELECT * FROM status WHERE user_id = $user_id ORDER BY order_position");
                                            while ($stat = mysqli_fetch_assoc($statuses)) {
                                                $selected = ($stat['id'] == $task['status_id']) ? 'selected' : '';
                                                echo "<option value='{$stat['id']}' $selected>{$stat['name']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" name="due_date" class="form-control" value="<?php echo $task['due_date']; ?>">
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" name="update" class="btn btn-warning">
                                    <i class="bi bi-check"></i> Update Task
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
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = intval($_POST['category_id']);
    $priority_id = intval($_POST['priority_id']);
    $status_id = intval($_POST['status_id']);
    $due_date = $_POST['due_date'] ? "'{$_POST['due_date']}'" : 'NULL';
    
    //Periksa apakah status selesai untuk mengatur completed_at
    $status_check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT is_completed FROM status WHERE id = $status_id"));
    $completed_at = $status_check['is_completed'] ? 'NOW()' : 'NULL';
    
    $query = "UPDATE tasks SET 
              title = '$title', 
              description = '$description', 
              category_id = $category_id, 
              priority_id = $priority_id, 
              status_id = $status_id, 
              due_date = $due_date, 
              completed_at = $completed_at
              WHERE id = $task_id AND user_id = $user_id";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Task updated successfully!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>alert('Error updating task.');</script>";
    }
}

include '../includes/footer.php';
?>
