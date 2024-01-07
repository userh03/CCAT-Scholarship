<?php
// Include your database connection
include("connection.php");

// Get form data
$app_scholar_type = mysqli_real_escape_string($con, $_POST['app_scholar_type']);
$app_year = mysqli_real_escape_string($con, $_POST['app_year']);
$app_student_number = mysqli_real_escape_string($con, $_POST['app_student_number']);

// Get Data From DB
$query = "SELECT * FROM students_tbl WHERE student_id_number = '$app_student_number'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

$lname = $row['lname'];
$fname = $row['fname'];
$section = $row['section'];
$department = $row['department'];
$mobile = $row['mobile'];
$email = $row['email'];

// Check if there is an existing application with the same scholar type for the current year
$queryExisting = "SELECT * FROM approved_applicants_tbl 
                  WHERE app_student_number = '$app_student_number' 
                  AND app_year = '$app_year' 
                  AND app_scholar_type = '$app_scholar_type'";
$resultExisting = mysqli_query($con, $queryExisting);

if (mysqli_num_rows($resultExisting) > 0) {
    // Display message and redirect
    header("location: ../admin-tables-ts.php?error=exists");
    exit(); // Stop executing further
}

// Insert data into the database
$sql = "INSERT INTO approved_applicants_tbl (app_student_number, app_fname, app_lname, app_mobile, app_section, app_department, app_email, app_scholar_type, app_year, app_status, app_adviser)
        VALUES ('$app_student_number', '$fname', '$lname', '$mobile', '$section', '$department', '$email', '$app_scholar_type', '$app_year', 'Approved', 'NULL')";

if ($con->query($sql) === TRUE) {
    // Create folder
    $folderPath = "../recruitment_scholar/" . $app_student_number . '-' . $app_scholar_type . '-' . $app_year;
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    // Move uploaded files to the folder
    foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
        $fileName = $_FILES['files']['name'][$index];
        $filePath = $folderPath . "/" . $fileName;

        // Move the file to the destination folder
        if (move_uploaded_file($tmpName, $filePath)) {
            echo "File '$fileName' uploaded successfully.<br>";
        } else {
            echo "Error uploading file '$fileName'.<br>";
        }
    }

    // Redirect to a success page or wherever you want
    header("location: ../admin-tables-ts.php?success=true");
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

?>
