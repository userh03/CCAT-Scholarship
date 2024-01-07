<?php 
include("connection.php");

if(isset($_GET['deleteid'])){
    $sa_id = $_GET['deleteid'];

    $sql = "UPDATE superadmin_tbl SET isValid = 2 WHERE sa_id = $sa_id";
    $result = mysqli_query($con, $sql);

    if($result){
        echo "<script>
                    window.location.href = '../developer-rubbish-admin.php'  
              </script>";
    }
    else{
        die(mysql_error($con));
    }
}   
?>
    