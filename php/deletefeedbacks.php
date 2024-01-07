<?php
include("connection.php");

// Initialize response array
$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteid'])) {
    $id = $_POST['deleteid'];

    // Use prepared statement to prevent SQL injection
    $sql = "DELETE FROM feedbacks_tbl WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Data has been deleted successfully.';
        } else {
            $response['message'] = 'Error deleting data: ' . mysqli_error($con);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Error preparing statement: ' . mysqli_error($con);
    }
} else {
    $response['message'] = 'Invalid request.';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
