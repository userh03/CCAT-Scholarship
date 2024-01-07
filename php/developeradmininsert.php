<?php
include("connection.php");
include("function.php");

$default_image = "images/user.png";

if(isset($_POST['asubmit']))
{
    $superadmin_id_number = $_POST['superadmin_id_number'];
    $sa_fname = $_POST['sa_fname'];
    $sa_lname = $_POST['sa_lname'];
    $email = $_POST['email'];
    $sa_building = $_POST['sa_building'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $mobile = $_POST['mobile'];

    // check if passwords match
    if ($password !== $confirmPassword) {
        // Passwords do not match, redirect with an error message
        echo '<script>
              window.location.href = "../developer-add-edit-admin.php?matched=false";
              </script>';
        exit;
    }

    // check if the profile picture is set, if not, use default image
    if(isset($_POST['profile_picture'])) {
        $profile_picture = $_POST['profile_picture'];
    } else {
        $profile_picture = $default_image;
    }

    // check if the teacher ID number already exists in the database
    $sql = "SELECT * FROM superadmin_tbl WHERE superadmin_id_number='$superadmin_id_number'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if($count > 0)
    {
        // teacher ID number already exists, display error message and stop execution
        echo '<script>
              window.location.href = "../developer-add-edit-admin.php?row_filled=true";
              </script>';
        exit;
    }

    // check if the username already exists in the database
    $sql = "SELECT username FROM superadmin_tbl WHERE username='$username'
             UNION
            SELECT username FROM admin_tbl WHERE username='$username'
             UNION
            SELECT username FROM students_tbl WHERE username='$username'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);
        
    if($count > 0)
    {
        // username already exists, display error message and stop execution
        echo '<script>
              window.location.href = "../developer-add-edit-admin.php?username_exists=true";
              </script>';
        exit;
    }

    else
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // username and teacher ID number do not exist, insert the new record
        $sql = "INSERT INTO superadmin_tbl (superadmin_id_number, sa_fname, sa_lname, email, sa_building, username, password, mobile, profile_picture, isValid) VALUES ('$superadmin_id_number', '$sa_fname', '$sa_lname', '$email', '$sa_building', '$username', '$hashed_password', '$mobile', '$default_image', '1')";
        $result = mysqli_query($con, $sql);

        if($result)
        {   
            // record inserted successfully, redirect to superadmin_add-edit-admin.php
            header("location: ../developer-add-edit-admin.php?success=true");
            exit;
        }
        else
        {
            echo(mysqli_error("ERROR" + $con));
        }
    }
}
?>
