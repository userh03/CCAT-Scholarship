<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if (isset($_GET['restoreid'])) {
    $sa_id = $_GET['restoreid'];

    $query = "SELECT * FROM developer_logs_tbl";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $admin_fname = $row['admin_fname'];
    $admin_lname = $row['admin_lname'];
    $sa_fname = $row['sa_fname'];
    $sa_lname = $row['sa_lname'];
    $time_log = $row['time_log'];
    $activity = $row['activity'];
    $superadmin_id_number = $row['superadmin_id_number'];
    $sa_building = $row['sa_building'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE superadmin_tbl SET isValid = 1 WHERE sa_id = '$sa_id'";
    $stmt = mysqli_prepare($con, $sql);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Perform the SQL query to log the activity
        $logQuery = "INSERT INTO developer_logs_tbl (admin_fname, admin_lname, time_log, activity, superadmin_id_number, sa_fname, sa_lname, sa_building) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Restore', ?, ?, ?, ?)";
        $logStmt = mysqli_prepare($con, $logQuery);

        // Bind parameters for the log query
        mysqli_stmt_bind_param($logStmt, "ssssss", $admin_fname, $admin_lname, $superadmin_id_number, $sa_fname, $sa_lname, $sa_building);

        // Execute the log query
        $logResult = mysqli_stmt_execute($logStmt);

        if ($logResult) {
            header('Location: ../developer-rubbish-admin.php');
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
