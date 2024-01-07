<?php
include("connection.php");

// Array of departments to be updated
$departmentsToUpdate = [
    "Department of Computer Studies",
    "Department of Engineering",
    "Department of Industrial Technology",
    "Department of Management Studies",
    "Department of Teacher Education"
];

// Update departments in the students_tbl
foreach ($departmentsToUpdate as $department) {
    $newDepartment = str_replace("Department of", "Department օf", $department);
    $sql = "UPDATE students_tbl SET department = '$newDepartment' WHERE department = '$department'";
    
    // Execute the SQL query
    if ($con->query($sql) === FALSE) {
        echo json_encode(['status' => 'error', 'message' => $con->error]);
        // Optionally, you might want to exit the script here if an error occurs
        // exit();
    }
}

echo json_encode(['status' => 'success']);
?>