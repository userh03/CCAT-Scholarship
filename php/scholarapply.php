<?php
include("connection.php");

session_start();

// Get Data From DB
$s_id = $_SESSION['s_id'];
$query = "SELECT * FROM students_tbl WHERE s_id = '$s_id'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

$student_id_number = $row['student_id_number'];
$lname = $row['lname'];
$fname = $row['fname'];
$section = $row['section'];
$department = $row['department'];
$mobile = $row['mobile'];
$email = $row['email'];

$query2 = "SELECT * FROM year_tbl";
$result2 = mysqli_query($con, $query2);
$row2 = mysqli_fetch_assoc($result2);

$year = $row2['year'];
// Get form data
$app_scholar_type = $_POST['app_scholar_type'];
$app_adviser = $_POST['app_adviser'];

// Get app_year
$query3 = "SELECT app_year FROM applicant_tbl WHERE app_student_number = '$student_id_number'
           UNION
           SELECT app_year FROM approved_applicants_tbl WHERE app_student_number = '$student_id_number'
           UNION
           SELECT app_year FROM denied_applicants_tbl WHERE app_student_number = '$student_id_number'";
$result3 = mysqli_query($con, $query3);
$row3 = mysqli_fetch_assoc($result3);

$app_year = $row3['app_year'];

// Check if there is an existing application with the same scholar type
if ($year == $app_year && scholarshipExistsInDB($app_scholar_type)) {
    // Display message and redirect
    header("location: ../user.php?error=exists");
    exit(); // Stop executing further
}

// Insert data into table
$sql = "INSERT INTO applicant_tbl (app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_scholar_type, app_status, app_year)
        VALUES ('$student_id_number', '$fname', '$lname', '$mobile', '$email', '$section', '$department', '$app_adviser', '$app_scholar_type', 'Pending', '$year')";

// Set the time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$sent_time = date("H:i:s");
$notifySql = "INSERT INTO notify_tbl (app_fname, app_lname, sent_time, view_status) VALUES (?, ?, ?, 1)";

$stmt = $con->prepare($notifySql);
$stmt->bind_param("sss", $fname, $lname, $sent_time);

if ($stmt->execute()) {
    // Insertion was successful
} else {
    echo "Error inserting data into notify_tbl: " . $stmt->error;
}

if ($con->query($sql) === TRUE) {
    // Create folder
    $folderPath = "../uploaded_docs/" . $student_id_number . '-' . $year;
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

    header("location: ../user.php?success=true");
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}

$con->close();

function scholarshipExistsInDB($scholarshipType) {
    global $con, $student_id_number; // Assuming you have these variables available
    
    $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_scholar_type LIKE '%$scholarshipType%'
            UNION
            SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_scholar_type LIKE '%$scholarshipType%'
            UNION
            SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_scholar_type LIKE '%$scholarshipType%'";
    $result = mysqli_query($con, $query);

    return mysqli_num_rows($result) > 0;
}
?>
