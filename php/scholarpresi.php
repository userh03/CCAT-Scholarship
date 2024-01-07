<?php 
include("connection.php");
session_start();
$s_id = $_SESSION['s_id'];
$query2 = "SELECT * FROM students_tbl WHERE s_id = '$s_id'";
$result = mysqli_query($con, $query2);
$row = mysqli_fetch_assoc($result);
$student_id_number = $row['student_id_number'];

$studentID = $student_id_number;

// Prepare and execute the SQL query to update the table
$query = "UPDATE applicant_tbl 
          SET app_scholar_type = 'Academic Scholarship Presidential' 
          WHERE app_student_number = :studentID 
          AND app_scholar_type = 'Academic Scholarship'";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':studentID', $studentID);
$stmt->execute();

// Check if the update was successful
if ($stmt->rowCount() > 0) {

} else {

}

// Close the database connection
$pdo = null;
?>