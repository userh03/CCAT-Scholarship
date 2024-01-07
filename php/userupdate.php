<?php 
    session_start();
    include("connection.php");

    if (isset($_SESSION['s_id']) && count($_POST) > 0) {
        mysqli_query($con, "UPDATE students_tbl SET mobile = '" . $_POST['mobile']. "' WHERE s_id = '" . $_SESSION['s_id'] . "'");

        echo "<script>
              window.location = '../user-profile.php?update=true' </script>";
    }

    $result = mysqli_query($con, "SELECT * FROM students_tbl WHERE s_id = '" . $_SESSION['s_id'] . "'");
    $row = mysqli_fetch_array($result);
?>
