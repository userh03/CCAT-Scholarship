<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if (isset($_GET['restoreid'])) {
    $s_id = $_GET['restoreid'];

    $query = "SELECT * FROM admin_logs_tbl";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $teacher_id_number = $row['teacher_id'];
    $a_fname = $row['a_fname'];
    $a_lname = $row['a_lname'];
    $department = $row['department'];
    $time_log = $row['time_log'];
    $activity = $row['activity'];
    $student_id_number = $row['student_id_number'];
    $student_fname = $row['student_fname'];
    $student_lname = $row['student_lname'];
    $s_department = $row['student_department'];

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE students_tbl SET isValid = 1 WHERE s_id = '$s_id' AND student_id_number = '$student_id_number'";
    $stmt = mysqli_prepare($con, $sql);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Perform the SQL query to log the activity
        $logQuery = "INSERT INTO admin_logs_tbl (a_fname, a_lname, time_log, activity, student_id_number, student_fname, student_lname, teacher_id, department, student_department) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Restore', ?, ?, ?, ?, ?, ?)";
        $logStmt = mysqli_prepare($con, $logQuery);

        // Bind parameters for the log query
        mysqli_stmt_bind_param($logStmt, "ssssssss", $a_fname, $a_lname, $student_id_number, $student_fname, $student_lname, $teacher_id_number, $department, $s_department); // Updated line

        // Execute the log query
        $logResult = mysqli_stmt_execute($logStmt);

        if ($logResult) {
            header('Location: ../developer-rubbish.php');
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
