<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if (isset($_POST['restoreall'])) {

    // Use prepared statement to prevent SQL injection in UPDATE query
    $sqlUpdate = "UPDATE superadmin_tbl SET isValid = 1 WHERE isValid = 0";
    $stmtUpdate = mysqli_prepare($con, $sqlUpdate);

    // Execute the update query
    $resultUpdate = mysqli_stmt_execute($stmtUpdate);

    if ($resultUpdate) {
        // Fetch data from developer_logs_tbl after updating records
        $query = "SELECT * FROM developer_logs_tbl WHERE isValid = 0";
        $resultSelect = mysqli_query($con, $query);

        if ($resultSelect) {
            // Loop through all rows
            while ($row = mysqli_fetch_assoc($resultSelect)) {
                $admin_fname = $row['admin_fname'];
                $admin_lname = $row['admin_lname'];
                $time_log = $row['time_log'];

                // Perform the SQL query to log the activity
                $logQuery = "INSERT INTO developer_logs_tbl (admin_fname, admin_lname, time_log, activity, superadmin_id_number, sa_fname, sa_lname, sa_building) VALUES (?, ?, ?, 'Account Restore ALL', NULL, 'Restored', 'All coordinators', 'Departments')";
                $logStmt = mysqli_prepare($con, $logQuery);

                // Bind parameters for the log query
                mysqli_stmt_bind_param($logStmt, "sss", $admin_fname, $admin_lname, $time_log);

                // Execute the log query
                $logResult = mysqli_stmt_execute($logStmt);

                if (!$logResult) {
                    $response['message'] = 'Error logging activity: ' . mysqli_error($con);
                    break; // Exit the loop if an error occurs
                }
            }

            if ($logResult) {
                $response['success'] = true;
                header('Location: ../developer-rubbish-admin.php');
                exit;
            }
        } else {
            $response['message'] = 'Error fetching data from developer_logs_tbl: ' . mysqli_error($con);
        }
    } else {
        $response['message'] = 'Error restoring the data: ' . mysqli_error($con);
    }

    // Close the statements
    mysqli_stmt_close($stmtUpdate);
    mysqli_stmt_close($logStmt);
} else {
    $response['message'] = 'Invalid request.';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
