<?php 
include("connection.php");

if(isset($_GET['deleteid'])){
    $a_id = $_GET['deleteid'];

    $sql = "UPDATE admin_tbl SET username = '', password = '', isValid = 2 WHERE a_id = $a_id";
    $result = mysqli_query($con, $sql);

    if($result){
        echo "<script>
                    window.location.href = '../admin-add-edit-admin.php'  
              </script>";
    }
    else{
        die(mysql_error($con));
    }
}   
?>
    