<?php
include("connection.php");

if (isset($_POST['id'])) {
  // Validate and sanitize the input
  $notificationId = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
  
  // Prepare the SQL statement using a prepared statement
  $query = "UPDATE notify_tbl SET view_status = 0 WHERE id = ?";
  
  // Initialize a prepared statement
  if ($stmt = mysqli_prepare($con, $query)) {
    // Bind the notificationId parameter
    mysqli_stmt_bind_param($stmt, "i", $notificationId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
      // Send a success response back to the client (JavaScript)
      echo "Success"; // You can customize this response as needed
    } else {
      // Send an error response back to the client (JavaScript)
      echo "Error: " . mysqli_error($con); // You can customize this response as needed
    }
    
    // Close the statement
    mysqli_stmt_close($stmt);
  } else {
    // Handle errors, if any
    echo "Error: Unable to prepare statement";
  }
} else {
  // Handle invalid or missing data
  echo "Invalid data";
}
?>
