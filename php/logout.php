<?php
session_start();
include("connection.php");

date_default_timezone_set('Asia/Manila');

// Check if the user is logged in and determine their user type (superadmin, admin, student)
if ($_SESSION['sa_id']) {
    // User is superadmin
    $loggedInUserType = 'superadmin';
    $userId = $_SESSION['sa_id'];
} elseif ($_SESSION['a_id']) {
    // User is admin
    $loggedInUserType = 'admin';
    $userId = $_SESSION['a_id'];
} elseif ($_SESSION['s_id']) {
    // User is student
    $loggedInUserType = 'student';
    $userId = $_SESSION['s_id'];
}

// Get the current date and time in the required format
$currentDateTime = date('Y-m-d H:i:s');

// Prepare and execute the SQL query to update the logout_time in the logs_tbl
$query = "UPDATE logs_tbl SET logout_time = ? WHERE user_id = ? AND user_type = ? AND logout_time IS NULL";
$statement = $con->prepare($query);
$statement->bind_param("sis", $currentDateTime, $userId, $loggedInUserType);
$statement->execute();

// Close the statement and database connection
$statement->close();
$con->close();

// Store a flag in the session to indicate that a logout notification should be shown
$_SESSION['showLogoutNotification'] = true;

// Destroy the session and redirect to the login page
session_unset();
session_destroy();
header("location: ../index.php?logout=true");
exit();
?>
