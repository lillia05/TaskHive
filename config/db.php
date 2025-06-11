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
    // Kategori Default
    $categories = [
        ['College', 'College tasks and activities', '#3498db'],
        ['Organization', 'Organization activities and tasks', '#e74c3c'],
        ['Personal', 'Personal tasks and activities', '#2ecc71'],
        ['Daily', 'Daily activities and tasks', '#f39c12'],
        ['Work', 'Work-related tasks', '#9b59b6']
    ];
    
    foreach ($categories as $cat) {
        mysqli_query($conn, "INSERT INTO categories (user_id, name, description, color) VALUES ($user_id, '{$cat[0]}', '{$cat[1]}', '{$cat[2]}')");
    }
    
    // Prioritas Default
    $priorities = [
        ['Normal', 1, '#95a5a6'],
        ['Medium', 2, '#3498db'],
        ['Important', 3, '#f39c12'],
        ['Very Important', 4, '#e67e22'],
        ['Urgent', 5, '#e74c3c']
    ];
    
    foreach ($priorities as $pri) {
        mysqli_query($conn, "INSERT INTO priorities (user_id, name, level, color) VALUES ($user_id, '{$pri[0]}', {$pri[1]}, '{$pri[2]}')");
    }
    
    // Status Default
    $statuses = [
        ['To Do', false, '#95a5a6' ],
        ['In Progress', false, '#3498db'],
        ['Done', true, '#2ecc71']
    ];
    
    foreach ($statuses as $stat) {
        $completed = $stat[1] ? 'true' : 'false';
        mysqli_query($conn, "INSERT INTO status (user_id, name, is_completed, color) VALUES ($user_id, '{$stat[0]}', $completed, '{$stat[2]}')");
    }
}
?>
