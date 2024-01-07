<?php
// get_status.php

include("connection.php");

// Assuming you receive the application student number through POST
$appStudentNumber = mysqli_real_escape_string($con, $_POST['student_id_number']);

$query = "SELECT * FROM applicant_tbl WHERE app_student_number = '$appStudentNumber' AND app_year = (SELECT year FROM year_tbl LIMIT 1)
        UNION
        SELECT * FROM approved_applicants_tbl WHERE app_student_number = '$appStudentNumber' AND app_year = (SELECT year FROM year_tbl LIMIT 1)
        UNION
        SELECT * FROM denied_applicants_tbl WHERE app_student_number = '$appStudentNumber' AND app_year = (SELECT year FROM year_tbl LIMIT 1)";

$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $latestStatus = $row['app_status'];

    // Return the latest status as "success"
    echo $latestStatus;
} else {
    // Return a message indicating no status is found
    echo "No status found";
}

// Close the database connection
mysqli_close($con);
?>
