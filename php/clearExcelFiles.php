<?php

$folderPath = '../documents/';

// Get all .xlsx files in the folder
$files = glob($folderPath . '/*.xlsx');
$response = [];

// Iterate through the files and delete each one
foreach ($files as $file) {
    if (is_file($file)) {
        if (unlink($file)) {
            $response[] = ['status' => 'success', 'message' => 'Deleted file: ' . $file];
        } else {
            $response[] = ['status' => 'error', 'message' => 'Error deleting file: ' . $file];
        }
    }
}

// Encode the response as JSON and send it back
header('Content-Type: application/json');
echo json_encode($response);

?>
