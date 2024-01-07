<?php 
    include("connection.php");

    if(count($_POST) > 0){
        $result = mysqli_query($con, "SELECT password FROM superadmin_tbl WHERE sa_id = '" . $_GET['updateid'] . "'");
        $row = mysqli_fetch_array($result);
        
        if ($_POST['password'] === "") {
            mysqli_query($con, "UPDATE superadmin_tbl SET sa_fname = '" . $_POST['sa_fname']. "', 
                                                     sa_lname = '" . $_POST['sa_lname']. "', 
                                                     username = '" . $_POST['username']. "', 
                                                     sa_building = '" . $_POST['sa_building']. "', 
                                                     mobile = '" . $_POST['mobile']. "'
                                WHERE sa_id = '" . $_GET['updateid'] . "'");
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE superadmin_tbl SET sa_fname = '" . $_POST['sa_fname']. "', 
                                                     sa_lname = '" . $_POST['sa_lname']. "', 
                                                     username = '" . $_POST['username']. "', 
                                                     password = '" . $password . "', 
                                                     sa_building = '" . $_POST['sa_building']. "' WHERE sa_id = '" . $_GET['updateid'] . "'");
        }

        echo "<script>
              window.location = '../developer-add-edit-admin.php?update=true' </script>";
    }

    $result = mysqli_query($con, "SELECT * FROM superadmin_tbl WHERE sa_id = '" . $_GET['updateid'] . "'");
    $row = mysqli_fetch_array($result);
?>
