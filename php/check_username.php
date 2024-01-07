<?php
include("connection.php");

// Create a new PDO instance
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    // Query the database to check if the username exists in any of the tables
    $stmt = $pdo->prepare("SELECT * FROM admin_tbl WHERE username = ?
                           UNION
                           SELECT * FROM students_tbl WHERE username = ?
                           UNION
                           SELECT * FROM superadmin_tbl WHERE username = ?");
    $stmt->execute([$username, $username, $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo '<p class="availability unavailable">Username not available</p>';
    } else {
        echo '<p class="availability available">Username available</p>';
    }
}
?>
