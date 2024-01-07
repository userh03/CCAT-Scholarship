<?php
include("connection.php");
include("function.php");

$default_image = "images/user.png";

if(isset($_POST['asubmit']))
{
    $teacher_id_number = $_POST['teacher_id_number'];
    $a_fname = $_POST['a_fname'];
    $a_lname = $_POST['a_lname'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];

    // check if the profile picture is set, if not, use default image
    if(isset($_POST['profile_picture'])) {
        $profile_picture = $_POST['profile_picture'];
    } else {
        $profile_picture = $default_image;
    }

     // check if the teacher ID number already exists in the database
     $sql_id = "SELECT * FROM admin_tbl WHERE teacher_id_number='$teacher_id_number'";
     $result_id = mysqli_query($con, $sql_id);
     $count_id = mysqli_num_rows($result_id);

     // check if the email already exists in the database
     $sql_email = "SELECT * FROM admin_tbl WHERE email='$email'";
     $result_email = mysqli_query($con, $sql_email);
     $count_email = mysqli_num_rows($result_email);

     if($count_id > 0)
     {
         // teacher ID number already exists, display error message and stop execution
         echo '<script>
                 window.location.href = "../developer-add-edit-coordinator.php?row_filled=true";
             </script>';
         exit;
     }

     if($count_email > 0)
     {
         // email already exists, display error message and stop execution
         echo '<script>
                  window.location.href = "../developer-add-edit-coordinator.php?row_email=true";
             </script>';
         exit;
     }

    else
    {

        // username and teacher ID number do not exist, insert the new record
        $sql = "INSERT INTO admin_tbl (teacher_id_number, a_fname, a_lname, email, department, mobile, profile_picture, isValid) VALUES ('$teacher_id_number', '$a_fname', '$a_lname', '$email', '$department', '$mobile', '$default_image', '1')";
        $result = mysqli_query($con, $sql);

        if($result)
        {   
            // record inserted successfully, redirect to superadmin_add-edit-admin.php
            header("location: ../developer-add-edit-coordinator.php?success=true");
            exit;
        }
        else
        {
            echo(mysqli_error("ERROR" + $con));
        }
    }
}
?>
