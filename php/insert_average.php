<?php
include("connection.php");
session_start();

if (isset($_POST['average'])) {
  $average = $_POST['average'];
  
  if ($average != '0.00') {
    $s_id = $_SESSION['s_id'];
    $query = "SELECT * FROM students_tbl WHERE s_id = '$s_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $fname = $row['fname'];
    $lname = $row['lname'];
    $section = $row['section'];
    $department = $row['department'];

    // check if the data already exists
    $checkQuery = "SELECT * FROM top_ten_students WHERE t_fname = '$fname' AND t_lname = '$lname' AND t_section = '$section'";
    $checkResult = mysqli_query($con, $checkQuery);
    $rowCount = mysqli_num_rows($checkResult);

    if ($rowCount == 0) {
      // insert the new record
      $sql = "INSERT INTO top_ten_students (t_fname, t_lname, t_department, t_section, average) VALUES ('$fname', '$lname', '$department','$section', '$average')";
      $result = mysqli_query($con, $sql);
      if ($result) {
        // Data inserted successfully
        echo "Data inserted successfully.";
      } else {
        // Error inserting data
        echo "Error inserting data: " . mysqli_error($con);
      }
    } else {
      // replace the existing record
      $sql = "UPDATE top_ten_students SET average = '$average' WHERE t_fname = '$fname' AND t_lname = '$lname' AND t_section = '$section'";
      $result = mysqli_query($con, $sql);
      if ($result) {
        // Data replaced successfully
        echo "Data replaced successfully.";
      } else {
        // Error replacing data
        echo "Error replacing data: " . mysqli_error($con);
      }
    }
  } else {
    // Average is 0.00, do not insert or update
    echo "Average value of 0.00 is not allowed.";
  }
}
?>
