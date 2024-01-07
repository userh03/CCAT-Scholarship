<?php
    session_start();
    include("connection.php");

    if (isset($_SESSION['a_id']) && count($_POST) > 0) {
        // Check if the password is set and not empty
        if (isset($_POST['password']) && $_POST['password'] !== "") {
            // Hash the password
            $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // Update the database with the hashed password
            mysqli_query($con, "UPDATE admin_tbl SET password = '" . $password_hash . "' WHERE a_id = '" . $_SESSION['a_id'] . "'");
            
            echo 'success';
            exit(); // Exit to prevent further script execution
        }
    }

    // If no update occurred or if there was an issue
    echo 'error';
?>
