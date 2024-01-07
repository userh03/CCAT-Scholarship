<?php
include("connection.php");
include("function.php");

$default_image = "images/user.png";

if(isset($_POST['isubmit']))
{
    $student_id_number = $_POST['student_id_number'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $section = $_POST['section'];
    $department = $_POST['department'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    // check if the profile picture is set, if not, use default image
    if(isset($_POST['profile_picture'])) {
        $profile_picture = $_POST['profile_picture'];
    } else {
        $profile_picture = $default_image;
    }

     // check if the teacher ID number already exists in the database
     $sql_id = "SELECT * FROM students_tbl WHERE student_id_number='$student_id_number'";
     $result_id = mysqli_query($con, $sql_id);
     $count_id = mysqli_num_rows($result_id);

     // check if the email already exists in the database
     $sql_email = "SELECT * FROM students_tbl WHERE email='$email'";
     $result_email = mysqli_query($con, $sql_email);
     $count_email = mysqli_num_rows($result_email);

     if($count_id > 0)
     {
         // teacher ID number already exists, display error message and stop execution
         echo '<script>
                 window.location.href = "../admin-add-edit-students.php?row_filled=true";
             </script>';
         exit;
     }

     if($count_email > 0)
     {
         // email already exists, display error message and stop execution
         echo '<script>
                  window.location.href = "../admin-add-edit-students.php?row_email=true";
             </script>';
         exit;
     }

    else
    {

        // insert the new record with the hashed password
        $sql = "INSERT INTO students_tbl (student_id_number, fname, lname, email, section, department, mobile, profile_picture, isValid) VALUES ('$student_id_number', '$fname', '$lname', '$email', '$section', '$department', '$mobile', '$default_image', '1')";
        $result = mysqli_query($con, $sql);

        if($result)
        {   
            // record inserted successfully, redirect to superadmin_add-edit-students.php
            header("location: ../admin-add-edit-students.php?success=true");
            exit;
        }
        else
        {
            echo(mysqli_error("ERROR" + $con));
        }
    }
}
?>
