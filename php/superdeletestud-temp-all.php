<?php 
include("connection.php");

if(isset($_POST['deleteall'])){
    $sql = "UPDATE students_tbl SET isValid = 2 WHERE isValid = 0";
    $result = mysqli_query($con, $sql);

    if($result){
        echo "<script>
                    window.location.href = '../admin-rubbish.php'  
              </script>";
    }
    else{
        die(mysql_error($con));
    }
}   
?>
    