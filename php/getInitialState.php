<?php
include("connection.php");
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Query to retrieve the toggleState from your database
$sql = "SELECT enable_disable FROM application_on_off";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Assuming there is only one record, fetch the toggleState value
    $row = $result->fetch_assoc();
    $toggleState = $row["toggleState"];

    // Send the toggleState as a JSON response
    echo json_encode(['toggleState' => $toggleState]);
} else {
    // Handle the case where no records are found
    echo json_encode(['error' => 'No records found']);
}

// Close the database connection
$con->close();
?>
