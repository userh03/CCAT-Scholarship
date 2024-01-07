<?php
include("connection.php");

if (count($_POST) > 0) {
    mysqli_query($con, "UPDATE superadmin_tbl SET mobile = '" . $_POST['mobile'] . "',
                                                  sa_building = '" . $_POST['sa_building'] . "',
                                                  sa_fname = '" . $_POST['sa_fname'] . "',
                                                  sa_lname = '" . $_POST['sa_lname'] . "' WHERE sa_id = '" . $_GET['updateid'] . "'");

    echo "<script>window.location = '../admin-page-user.php?update=true' </script>";
}

$result = mysqli_query($con, "SELECT * FROM superadmin_tbl WHERE sa_id = '" . $_GET['updateid'] . "'");
$row = mysqli_fetch_array($result);
?>
