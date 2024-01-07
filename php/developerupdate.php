<?php
include("connection.php");

if (count($_POST) > 0) {
    mysqli_query($con, "UPDATE devs_tbl SET d_fname = '" . $_POST['d_fname'] . "',
                                           d_lname = '" . $_POST['d_lname'] . "' WHERE dev_id = '" . $_GET['updateid'] . "'");

    echo "<script>window.location = '../developer-page-user.php?update=true' </script>";
}

$result = mysqli_query($con, "SELECT * FROM devs_tbl WHERE dev_id = '" . $_GET['updateid'] . "'");
$row = mysqli_fetch_array($result);
?>
