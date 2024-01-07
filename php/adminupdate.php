<?php
include("connection.php");

if (count($_POST) > 0) {
    mysqli_query($con, "UPDATE admin_tbl SET mobile = '" . $_POST['mobile'] . "' WHERE a_id = '" . $_GET['updateid'] . "'");

    echo "<script>
    window.location = '../coordinator-page-user.php?update=true'</script>";}

$result = mysqli_query($con, "SELECT * FROM admin_tbl WHERE a_id = '" . $_GET['updateid'] . "'");
$row = mysqli_fetch_array($result);
?>
