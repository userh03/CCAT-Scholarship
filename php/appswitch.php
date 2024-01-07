<?php
include("connection.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = json_decode(file_get_contents('php://input'), true);


    $toggleValue = $postData['toggleValue'];

    // Update your table with the $toggleValue
    $sql = "UPDATE application_on_off SET enable_disable = $toggleValue";
    
    if ($con->query($sql) === TRUE) {
        echo json_encode(['status' => $toggleValue]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $con->error]);
    }

    $con->close();
}
?>
