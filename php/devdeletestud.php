<?php
include("connection.php");

if (isset($_GET['deleteid'])) {
    $s_id = $_GET['deleteid'];

    $sql = "UPDATE students_tbl SET isValid = 2 WHERE s_id = $s_id";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>
                    window.location.href = '../developer-rubbish.php'  
              </script>";
    } else {
        die(mysqli_error($con));
    }
}
?>
