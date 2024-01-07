<?php 
include("connection.php");

if(isset($_GET['deleteid'])){
    $id = $_GET['deleteid'];

    $sql = "DELETE FROM newsupdate_tbl WHERE id = $id";
    $result = mysqli_query($con, $sql);

    if($result){
        echo "<script>
                    window.location.href = '../admin-user-content.php'  
              </script>";
    }
    else{
        die(mysql_error($con));
    }
}   
?>
    