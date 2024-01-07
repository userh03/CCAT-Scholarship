<?php
session_start();
include("php/connection.php");

if(isset($_GET['s_id'])) {
    $s_id = $_GET['s_id'];
    $query = "SELECT isValid FROM students_tbl WHERE s_id = '$s_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $isValid = $row['isValid'];
    echo json_encode(['isValid' => $isValid]);
} else {
    echo json_encode(['isValid' => null]);
}
?>
