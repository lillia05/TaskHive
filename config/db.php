<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "taskhive"; 

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Function to create default data for new user
function createDefaultData($user_id, $conn) {
    // Default Categories
    $categories = [
        ['Personal', 'Personal tasks and activities', '#e74c3c', 'person'],
        ['Work', 'Work-related tasks', '#2ecc71', 'briefcase'],
        ['Shopping', 'Shopping lists and purchases', '#f39c12', 'cart'],
        ['Health', 'Health and fitness activities', '#9b59b6', 'heart']
    ];
    
    foreach ($categories as $cat) {
        mysqli_query($conn, "INSERT INTO categories (user_id, name, description, color, icon) VALUES ($user_id, '{$cat[0]}', '{$cat[1]}', '{$cat[2]}', '{$cat[3]}')");
    }
    
    // Default Priorities
    $priorities = [
        ['Low', 1, '#95a5a6', 'flag'],
        ['Normal', 2, '#3498db', 'flag'],
        ['Medium', 3, '#f39c12', 'flag'],
        ['High', 4, '#e67e22', 'flag'],
        ['Critical', 5, '#e74c3c', 'exclamation']
    ];
    
    foreach ($priorities as $pri) {
        mysqli_query($conn, "INSERT INTO priorities (user_id, name, level, color, icon) VALUES ($user_id, '{$pri[0]}', {$pri[1]}, '{$pri[2]}', '{$pri[3]}')");
    }
    
    // Default Status
    $statuses = [
        ['To Do', false, '#95a5a6', 1, 'circle'],
        ['In Progress', false, '#3498db', 2, 'play'],
        ['Done', true, '#2ecc71', 3, 'check']
    ];
    
    foreach ($statuses as $stat) {
        $completed = $stat[1] ? 'true' : 'false';
        mysqli_query($conn, "INSERT INTO status (user_id, name, is_completed, color, order_position, icon) VALUES ($user_id, '{$stat[0]}', $completed, '{$stat[2]}', {$stat[3]}, '{$stat[4]}')");
    }
}
?>
