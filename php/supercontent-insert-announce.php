<?php
// Include the database connection file
require_once("connection.php");
$target_dir = "../updates_announcements/";

// Check if the form is submitted
if (isset($_POST['asubmit'])) {
    // Get the form data and sanitize it
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $content = mysqli_real_escape_string($con, $_POST['content']);

    // Prepare the SQL query
    $sql = "INSERT INTO announceupdate_tbl (title, content) VALUES ('$title', '$content')";

    // Execute the SQL query
    if (mysqli_query($con, $sql)) {
        // Get the ID of the inserted row
        $insertedId = mysqli_insert_id($con);

        // File upload handling (assuming 'images' is the name of the file input)
        if (isset($_FILES["images"])) {
            // Generate a unique name for the uploaded image
            $file_extension = strtolower(pathinfo($_FILES["images"]["name"], PATHINFO_EXTENSION));
            $file_name = "img" . $insertedId . "_announce." . $file_extension;
            $target_file = $target_dir . $file_name;

            // Check if the file is a valid image
            $check = getimagesize($_FILES["images"]["tmp_name"]);
            if ($check === false) {
                die("Error: File is not an image");
            } elseif (!in_array($file_extension, array('jpeg', 'jpg', 'png'))) {
                die("Error: Only JPEG, JPG, and PNG file types are allowed");
            }

            // Upload the file to the target directory
            if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_file)) {
                // Update the 'images' column with the file path in the database
                $file_path = $target_dir . $file_name;
                
                // Remove the "../" from the beginning of the file path
                $file_path = substr($target_dir, 3) . $file_name;
                $updateSql = "UPDATE announceupdate_tbl SET images = '$file_path' WHERE id = $insertedId";
                if (mysqli_query($con, $updateSql)) {
                    // Redirect to success page
                    header('Location: ../admin-user-content-announce.php?success=true');
                    exit;
                } else {
                    // Redirect to error page
                    header('Location: ../admin-user-content-announce.php?success=false');
                    exit;
                }
            } else {
                // Redirect to error page
                header('Location: ../admin-user-content-announce.php?success=false');
                exit;
            }
        } else {
            // Redirect to success page (without file upload)
            header('Location: ../admin-user-content-announce.php?success=true');
            exit;
        }
    } else {
        // Redirect to error page
        header('Location: ../admin-user-content-announce.php?success=false');
        exit;
    }
}

// Close the database connection
mysqli_close($con);
?>
