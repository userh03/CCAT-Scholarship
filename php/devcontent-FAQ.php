<?php
include("connection.php");

if (count($_POST) > 0) {
    mysqli_query($con, "UPDATE faq_tbl SET question = '" . $_POST['question'] . "', answer = '" . $_POST['answer'] . "' WHERE id = '" . $_GET['updateid'] . "'");

    echo "<script>
          window.location = '../developer-user-FAQ.php?update=true' </script>";
}

$result = mysqli_query($con, "SELECT * FROM faq_tbl WHERE id = '" . $_GET['updateid'] . "'");
$row = mysqli_fetch_array($result);
?>
