<?php
require_once 'connection.php'; // Include the connection file

if (isset($_POST['feedbackSub'])) {
    // Retrieve data from the AJAX request and sanitize
    $message = $con->real_escape_string($_POST['message']);
    $stars = $con->real_escape_string($_POST['stars']);
    $fname = $con->real_escape_string($_POST['fname']);
    $lname = $con->real_escape_string($_POST['lname']);
    $section = $con->real_escape_string($_POST['section']);
    $image = $con->real_escape_string($_POST['image']);
    $f_student_number = $con->real_escape_string($_POST['f_student_number']);

    //YEAR
    $query3 = "SELECT * FROM year_tbl";
    $result3 = mysqli_query($con, $query3);
    $row3 = mysqli_fetch_assoc($result3);
  
    $year_submitted = $row3['year']; // Fix: Use $year_submitted instead of $year
    // YEAR

    // Check if the f_student_number already exists in the database
    $checkStmt = $con->prepare("SELECT COUNT(*) FROM feedbacks_tbl WHERE f_student_number = ? AND year_submitted = ?");
    $checkStmt->bind_param("ii", $f_student_number, $year_submitted); // Fix: Add $year_submitted to the parameters
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count < 2) {
        // Create a prepared statement
        $stmt = $con->prepare("INSERT INTO feedbacks_tbl (number_stars, message, fname, lname, image, section, f_student_number, year_submitted) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the statement
        $stmt->bind_param("ssssssis", $stars, $message, $fname, $lname, $image, $section, $f_student_number, $year_submitted);

        // Execute the statement
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close the connection
            $con->close();

            // Respond with a success message along with $fname
            echo json_encode(['status' => 'success', 'message' => 'Feedback has been submitted!', 'fname' => $fname]);
        } else {
            // Respond with an error message
            echo json_encode(['status' => 'error', 'message' => 'Error executing the statement']);
        }
    } else {
        // Respond with a message that the f_student_number was counted 2 times
        echo json_encode(['status' => 'error2', 'message' => 'f_student_number was counted 2 times.']);
    }
} else {
    // Respond with an error message if it's not a POST request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
