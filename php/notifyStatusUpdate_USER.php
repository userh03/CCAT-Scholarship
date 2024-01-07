<?php
include("connection.php");

// Execute the SQL query to update only view_status to 0
$query = "UPDATE notify_users_tbl SET view_status = 0";

if (mysqli_query($con, $query)) {
    // The update was successful
    echo 'Success';
} else {
    // The update failed
    echo 'Error: ' . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>
