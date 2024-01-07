<?php
include("connection.php");
session_start();
// Define the target directory where the uploaded image will be saved
$target_dir = "../a_profiles/";

// Check if the file upload field is set
if (isset($_FILES["fileToUpload"])) {
    // Generate a unique name for the uploaded image by concatenating the admin ID with the original file name and profile label
    $sa_id = $_SESSION['sa_id'];
    $query = "SELECT * FROM superadmin_tbl WHERE sa_id = '$sa_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $superadmin_id_number = $row['superadmin_id_number'];
    $sa_lname = $row['sa_lname'];
    $file_name = $sa_id . '_' . $superadmin_id_number . '_' . $sa_lname . '_profile';
    $file_extension = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . $file_name . '.' . $file_extension;

    // Check if the file is a valid image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) {
        die("Error: File is not an image");
    } elseif (!in_array($file_extension, array('jpeg', 'jpg', 'png'))) {
        die("Error: Only JPEG, JPG, and PNG file types are allowed");
    }

    // Check if the file already exists in the target directory
    if (file_exists($target_file)) {
        unlink($target_file);
    }

    // Upload the file to the target directory
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Update profile picture
        $profile_picture = $target_file;

        // Remove the "../" from the beginning of the file path
        $profile_picture = substr($target_file, 3);
        
        $sql = "UPDATE superadmin_tbl SET profile_picture='$profile_picture' WHERE sa_id='$sa_id'";
        $result = $con->query($sql);

        // Close database connection
        $con->close();

        // Redirect to the user page
        ob_start();
        header("Location: ../admin-page-user.php");
        ob_end_flush();
        exit();
    } else {
        die("Error: Failed to upload file");
    }
} else {
    die("Error: File upload field not set");
}
?>
