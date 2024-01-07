<?php
require_once 'connection.php'; // Include the connection file

if (isset($_POST['stars'])) {
    $selectedStars = $_POST['stars'];

    // Here you can process the selected semester and year as needed.
    // For example, you can perform calculations or additional checks.

    // After processing, you can send a response back if required.
    $response = "Selected stars: " . $selectedStars;

    // Update the database using the $con connection
    $sql = "UPDATE choose_stars_tbl SET number_stars = '$selectedStars'";

    // Use a transaction to ensure both updates succeed or fail together
    $con->begin_transaction();

    try {
        if ($con->query($sql) === TRUE) {
            $con->commit(); // Both queries succeeded, commit the transaction
            echo "success";
        } else {
            throw new Exception("Error updating database");
        }
    } catch (Exception $e) {
        $con->rollback(); // An error occurred, rollback the transaction
        echo "error" . $e->getMessage();
    }
} else {
    echo "Incomplete data received";
}
?>
