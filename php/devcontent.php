<?php
include("connection.php");

// Function to delete the old image
function deleteOldImage($id, $con){
    $sql = "SELECT images FROM newsupdate_tbl WHERE id='$id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $oldImagePath = $row['images'];

    if(file_exists($oldImagePath)){
        unlink($oldImagePath);
    }
}

// Function to remove "../" from the image path
function sanitizeImagePath($path) {
    return str_replace("../", "", $path);
}

// Function to sanitize user input to prevent SQL injection
function sanitizeInput($input) {
    global $con;
    $sanitizedInput = mysqli_real_escape_string($con, $input);
    return $sanitizedInput;
}

// Check if the form was submitted
if(isset($_POST['submit'])){
    $id = $_GET['updateid'];
    
    $title = sanitizeInput($_POST['title']); // Sanitize the title input
    $content = sanitizeInput($_POST['content']);

    if(isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK){
        $image_tmp = $_FILES['image_upload']['tmp_name'];
        $image_name = $_FILES['image_upload']['name'];

        $target_dir = "../updates_announcements/";
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $target_file = $target_dir . "img" . $id . "_news." . $image_extension;

        deleteOldImage($id, $con);

        if(move_uploaded_file($image_tmp, $target_file)){
            $target_file_sanitized = sanitizeImagePath($target_file);
            $sql = "UPDATE newsupdate_tbl SET title='$title', content='$content', images='$target_file_sanitized' WHERE id='$id'";
            if (mysqli_query($con, $sql)) {
                header("Location: ../developer-user-content.php?update=true");
                exit();
            } else {
                echo "Error updating content, title, and image: " . mysqli_error($con);
            }
        } else {
            echo "Error uploading the image.";
        }
    } else {
        $sql = "UPDATE newsupdate_tbl SET title='$title', content='$content' WHERE id='$id'";
        if (mysqli_query($con, $sql)) {
            header("Location: ../developer-user-content.php?update=true");
            exit();
        } else {
            echo "Error updating content and title: " . mysqli_error($con);
        }
    }
}
?>
