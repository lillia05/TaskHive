<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "taskhive"; 

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// fungsi untuk membuat data default
function createDefaultData($user_id, $conn) {
    // Default Categories
    $categories = [
        ['Personal', 'Personal tasks and activities', '#e74c3c'],
        ['Work', 'Work-related tasks', '#2ecc71'],
        ['Shopping', 'Shopping lists and purchases', '#f39c12'],
        ['Health', 'Health and fitness activities', '#9b59b6']
    ];
    
    foreach ($categories as $cat) {
        mysqli_query($conn, "INSERT INTO categories (user_id, name, description, color) VALUES ($user_id, '{$cat[0]}', '{$cat[1]}', '{$cat[2]}', '{$cat[3]}')");
    }
    
    // Default Priorities
    $priorities = [
        ['Low', 1, '#95a5a6'],
        ['Normal', 2, '#3498db'],
        ['Medium', 3, '#f39c12'],
        ['High', 4, '#e67e22'],
        ['Critical', 5, '#e74c3c']
    ];
    
    foreach ($priorities as $pri) {
        mysqli_query($conn, "INSERT INTO priorities (user_id, name, level, color) VALUES ($user_id, '{$pri[0]}', {$pri[1]}, '{$pri[2]}', '{$pri[3]}')");
    }
    
    // Default Status
    $statuses = [
        ['To Do', false, '#95a5a6' ],
        ['In Progress', false, '#3498db'],
        ['Done', true, '#2ecc71']
    ];
    
    foreach ($statuses as $stat) {
        $completed = $stat[1] ? 'true' : 'false';
        mysqli_query($conn, "INSERT INTO status (user_id, name, is_completed, color) VALUES ($user_id, '{$stat[0]}', $completed, '{$stat[2]}', {$stat[3]}, '{$stat[4]}')");
    }
}
?>
