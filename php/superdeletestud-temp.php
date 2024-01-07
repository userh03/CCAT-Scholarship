<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteid'])) {
    $s_id = $_POST['deleteid'];

    // Sanitize and escape input data
    $sa_fname = mysqli_real_escape_string($con, $_POST['sa_fname']);
    $sa_lname = mysqli_real_escape_string($con, $_POST['sa_lname']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $student_id_number = mysqli_real_escape_string($con, $_POST['student_id_number']);
    $teacher_id_number = mysqli_real_escape_string($con, $_POST['teacher_id_number']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $s_department = mysqli_real_escape_string($con, $_POST['s_department']);

    // Use prepared statement to prevent SQL injection
    $sql = "UPDATE students_tbl SET isValid = 0 WHERE s_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "i", $s_id);

    // Execute the update query
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Perform the SQL query to log the activity
        $logQuery = "INSERT INTO admin_logs_tbl (a_fname, a_lname, time_log, activity, student_id_number, student_fname, student_lname, teacher_id, department, student_department) VALUES (?, ?, CURRENT_TIMESTAMP, 'Account Soft Delete', ?, ?, ?, ?, ?, ?)";
        $logStmt = mysqli_prepare($con, $logQuery);

        // Bind parameters for the log query
        mysqli_stmt_bind_param($logStmt, "ssssssss", $sa_fname, $sa_lname, $student_id_number, $fname, $lname, $teacher_id_number, $department, $s_department); // Updated line

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
