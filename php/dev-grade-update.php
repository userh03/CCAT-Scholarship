<?php
include("connection.php");

if (isset($_POST['grade']) && isset($_POST['student_id_number']) && isset($_POST['ggid'])) {
    $newGrade = $_POST['grade'];
    $student_id_number = $_POST['student_id_number'];
    $ggid = $_POST['ggid'];

    // Use a prepared statement to prevent SQL injection
    $query = "UPDATE docs_tbl SET grade = ? WHERE id = ? AND student_id_number = ?";
    $stmt = $con->prepare($query);

    if (!$stmt) {
        // Handle preparation error
        echo json_encode(['success' => false, 'message' => 'Error preparing the SQL statement']);
        exit();
    }

    // Bind parameters
    $stmt->bind_param("ssi", $newGrade, $ggid, $student_id_number);

    // Execute the statement
    $result = $stmt->execute();

    if ($result) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            // Update successful
            echo json_encode(['success' => true]);
        } else {
            // No rows were affected, possibly the same grade value or invalid ggid
            echo json_encode(['success' => false, 'message' => 'No changes detected.']);
        }
    } else {
        // Failed to execute the statement
        echo json_encode(['success' => false, 'message' => 'Failed to update the grade. ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();

} else {
    // Handle the case where 'grade' or 'student_id_number' or 'ggid' is not set in the POST data
    echo json_encode(['success' => false, 'message' => 'Grade, Student ID number, or ggid is missing']);
}
?>
