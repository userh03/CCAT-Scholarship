<?php
    include("connection.php");
    session_start();
    // Define the target directory where the uploaded image will be saved
    $target_dir = "../a_profiles/";

    // Check if the file upload field is set
    if (isset($_FILES["fileToUpload"])) {
        // Generate a unique name for the uploaded image by concatenating the admin ID with the original file name and profile label
        $a_id = $_SESSION['a_id'];
        $query = "SELECT teacher_id_number, a_lname FROM admin_tbl WHERE a_id = '$a_id'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $teacher_id_number = $row['teacher_id_number'];
        $a_lname = $row['a_lname'];
        $file_name = $a_id . '_' . $teacher_id_number . '_' . $a_lname . '_profile';
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
            
            $sql = "UPDATE admin_tbl SET profile_picture='$profile_picture' WHERE a_id='$a_id'";
            $result = $con->query($sql);

            // Close database connection
            $con->close();
            ob_start(); // turn on output buffering
            header("Location: ../coordinator-page-user.php");
            ob_end_flush(); // send the output buffer
            exit();
        } else {
            die("Error: Failed to upload file");
        }
    } else {
        die("Error: File upload field not set");
    }
?>



