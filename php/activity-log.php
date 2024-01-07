<?php
// Include the database connection
include 'connection.php';

// Function to sanitize input data
function sanitize($data) {
    // Implement your data sanitization logic here
    // For simplicity, you can use mysqli_real_escape_string or other suitable methods
    return $data;
}

// Function to record log
function recordLog($teacher_id, $a_fname, $a_lname, $department, $time_log, $activity, $student_number, $student_fname, $student_lname, $con) {
    $teacher_id = sanitize($teacher_id);
    $a_fname = sanitize($a_fname);
    $a_lname = sanitize($a_lname);
    $department = sanitize($department);
    $time_log = sanitize($time_log);
    $activity = sanitize($activity);
    $student_number = sanitize($student_number);
    $student_fname = sanitize($student_fname);
    $student_lname = sanitize($student_lname);

    // SQL query to insert log into the admin_logs_tbl
    $sql = "INSERT INTO admin_logs_tbl (teacher_id, a_fname, a_lname, department, time_log, activity, student_number, student_fname, student_lname)
            VALUES ('$teacher_id', '$a_fname', '$a_lname', '$department', '$time_log', '$activity', '$student_number', '$student_fname', '$student_lname')";

    if ($con->query($sql) === TRUE) {
        $responsee = array("status" => "success", "message" => "Log recorded successfully");
    } else {
        $responsee = array("status" => "error", "message" => "Error: " . $sql . "<br>" . $con->error);
    }

    // Return JSON-encoded response
    echo json_encode($responsee);
}

// Check if data is received through POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example usage
    $teacher_id = $_POST['teacher_id'] ?? "";
    $a_fname = $_POST['a_fname'] ?? "";
    $a_lname = $_POST['a_lname'] ?? "";
    $department = $_POST['department'] ?? "";
    $time_log = $_POST['time_log'] ?? "";
    $activity = $_POST['activity'] ?? "";
    $student_number = $_POST['student_number'] ?? "";
    $student_fname = $_POST['student_fname'] ?? "";
    $student_lname = $_POST['student_lname'] ?? "";

    // Call the function to record the log
    recordLog($teacher_id, $a_fname, $a_lname, $department, $time_log, $activity, $student_number, $student_fname, $student_lname, $con);

} else {
    // Return an error if data is not received through POST
    $responsee = array("status" => "error", "message" => "Invalid request method");
    echo json_encode($responsee);
}

// You can close the database connection if needed
// $con->close();
?>
