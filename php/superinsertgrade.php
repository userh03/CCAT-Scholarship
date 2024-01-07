<?php
include("connection.php");
include("function.php");

if(isset($_POST['isubmit']))
{
    $student_id_number = mysqli_real_escape_string($con, $_POST['student_id_number']);
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $semester = mysqli_real_escape_string($con, $_POST['semester']);
    $grade = mysqli_real_escape_string($con, $_POST['grade']);
    $s_year = mysqli_real_escape_string($con, $_POST['s_year']);
    $subject_code = mysqli_real_escape_string($con, $_POST['subject_code']);
    $subject_name = mysqli_real_escape_string($con, $_POST['subject_name']);
    $units = mysqli_real_escape_string($con, $_POST['units']);

    // insert the new record
    $insert_sql = "INSERT INTO docs_tbl (student_id_number, first_name, last_name, grade, s_year, subject_code, semester, units, subject_name) 
                          VALUES ('$student_id_number', '$first_name', '$last_name', '$grade', '$s_year', '$subject_code', '$semester', '$units', '$subject_name')";
    $insert_result = mysqli_query($con, $insert_sql);

    if($insert_result)
    {   
        // Record inserted successfully, redirect to developer-add-edit-grades.php
        header("location: ../developer-add-edit-grades.php?success=true");
        exit;
    }
    else
    {
        echo "ERROR: " . mysqli_error($con);
    }
}
?>
