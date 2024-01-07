<?php
require_once 'connection.php'; // Include the connection file

if (isset($_POST['past_year']) && isset($_POST['s_course'])) {
    $selectedPastYear = $_POST['past_year'];
    $selectedCourse = $_POST['s_course'];

    // You can send a response back if required.
    $response = "Selected past year: " . $selectedPastYear . ", Selected course: " . $selectedCourse;

    // Update the database using prepared statements
    $sql = "UPDATE year_tbl SET past_year = ?";
    $sql2 = "UPDATE section_courses SET s_course = ?";

    // Use a transaction to ensure both updates succeed or fail together
    $con->begin_transaction();

    try {
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $selectedPastYear);
        $stmt->execute();

        $stmt2 = $con->prepare($sql2);
        $stmt2->bind_param("s", $selectedCourse);
        $stmt2->execute();

        $con->commit(); // Both queries succeeded, commit the transaction
        echo "success";
    } catch (Exception $e) {
        $con->rollback(); // An error occurred, rollback the transaction
        echo "error" . $e->getMessage();
    }
} else {
    echo "Incomplete data received";
}
?>
