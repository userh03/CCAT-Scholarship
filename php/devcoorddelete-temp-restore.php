<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if (isset($_GET['restoreid'])) {
    $a_id = $_GET['restoreid'];

    $query = "SELECT * FROM superadmin_logs_tbl";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $admin_fname = $row['admin_fname'];
    $admin_lname = $row['admin_lname'];
    $a_fname = $row['a_fname'];
    $a_lname = $row['a_lname'];
    $time_log = $row['time_log'];
    $activity = $row['activity'];
    $teacher_id_number = $row['teacher_id_number'];
    $department = $row['department'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE admin_tbl SET isValid = 1 WHERE a_id = '$a_id'";
    $stmt = mysqli_prepare($con, $sql);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Perform the SQL query to log the activity
        $logQuery = "INSERT INTO superadmin_logs_tbl (admin_fname, admin_lname, time_log, activity, teacher_id_number, a_fname, a_lname, department) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Restore', ?, ?, ?, ?)";
        $logStmt = mysqli_prepare($con, $logQuery);

        // Bind parameters for the log query
        mysqli_stmt_bind_param($logStmt, "ssssss", $admin_fname, $admin_lname, $teacher_id_number, $a_fname, $a_lname, $department);

        // Execute the log query
        $logResult = mysqli_stmt_execute($logStmt);

        if ($logResult) {
            header('Location: ../developer-rubbish-coordinator.php');
            exit; 
        } else {
            $response['message'] = 'Error logging activity: ' . mysqli_error($con);
        }        
    } else {
        $response['message'] = 'Error restoring the data: ' . mysqli_error($con);
    }

    // Close the statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($logStmt);
} else {
    $response['message'] = 'Invalid request.';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
