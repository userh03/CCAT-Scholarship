<?php
    session_start();

    // Assuming sa_id, a_id, and student_id are the session variables you are checking
    if (isset($_SESSION['sa_id']) && !empty($_SESSION['sa_id'])) {
        header("Location: admin-dashboard.php");
        exit();
    } elseif (isset($_SESSION['a_id']) && !empty($_SESSION['a_id'])) {
        header("Location: coordinator-dashboard.php");
        exit();
    } elseif (isset($_SESSION['s_id']) && !empty($_SESSION['s_id'])) {
        header("Location: user.php");
        exit();
    } elseif (isset($_SESSION['dev_id']) && !empty($_SESSION['dev_id'])) {
        header("Location: developer-dashboard.php");
        exit();
    }
?>