<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteid'])) {
    $sa_id = $_POST['deleteid'];

    // Sanitize and escape input data
    $sa_fname = mysqli_real_escape_string($con, $_POST['sa_fname']);
    $sa_lname = mysqli_real_escape_string($con, $_POST['sa_lname']);
    $admin_fname = mysqli_real_escape_string($con, $_POST['admin_fname']);
    $admin_lname = mysqli_real_escape_string($con, $_POST['admin_lname']);
    $superadmin_id_number = mysqli_real_escape_string($con, $_POST['superadmin_id_number']);
    $sa_building = mysqli_real_escape_string($con, $_POST['sa_building']);

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE superadmin_tbl SET isValid = 0 WHERE sa_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $sa_id);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Perform the SQL query to log the activity
        $logQuery = "INSERT INTO developer_logs_tbl (admin_fname, admin_lname, time_log, activity, superadmin_id_number, sa_fname, sa_lname, sa_building) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Soft Delete', ?, ?, ?, ?)";
        $logStmt = mysqli_prepare($con, $logQuery);

        // Bind parameters for the log query
        mysqli_stmt_bind_param($logStmt, "ssssss", $admin_fname, $admin_lname, $superadmin_id_number, $sa_fname, $sa_lname, $sa_building); // Updated line

        // Execute the log query
        $logResult = mysqli_stmt_execute($logStmt);

        if ($logResult) {
            $response['success'] = true;
            $response['message'] = 'Data has been deleted successfully.';
        } else {
            $response['message'] = 'Error logging activity: ' . mysqli_error($con);
        }
    } else {
        $response['message'] = 'Error moving data to Account Bin: ' . mysqli_error($con);
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
