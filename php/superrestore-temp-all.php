<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if (isset($_POST['restoreall'])) {

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE admin_tbl SET isValid = 1 WHERE isValid = 0";
    $stmt = mysqli_prepare($con, $sql);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Fetch data from superadmin_logs_tbl after updating records
        $query = "SELECT * FROM superadmin_logs_tbl";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Loop through all rows
            while ($row = mysqli_fetch_assoc($result)) {
                $admin_fname = $row['admin_fname'];
                $admin_lname = $row['admin_lname'];

                // Perform the SQL query to log the activity
                $logQuery = "INSERT INTO superadmin_logs_tbl (admin_fname, admin_lname, time_log, activity, teacher_id_number, a_fname, a_lname, department) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Restore ALL', NULL, 'Restored', 'All coordinators', 'Departments')";
                $logStmt = mysqli_prepare($con, $logQuery);

                // Bind parameters for the log query
                mysqli_stmt_bind_param($logStmt, "ss", $admin_fname, $admin_lname);

                // Execute the log query
                $logResult = mysqli_stmt_execute($logStmt);

                if (!$logResult) {
                    $response['message'] = 'Error logging activity: ' . mysqli_error($con);
                    break; // Exit the loop if an error occurs
                }
            }

            header('Location: ../admin-rubbish-coordinator.php');
            exit;
        } else {
            $response['message'] = 'Error fetching data from superadmin_logs_tbl: ' . mysqli_error($con);
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
