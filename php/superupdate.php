<?php 
    include("connection.php");

    if(count($_POST) > 0){
        $result = mysqli_query($con, "SELECT password FROM admin_tbl WHERE a_id = '" . $_GET['updateid'] . "'");
        $row = mysqli_fetch_array($result);
        
        if ($_POST['password'] === "") {
            mysqli_query($con, "UPDATE admin_tbl SET a_fname = '" . $_POST['a_fname']. "', 
                                                     a_lname = '" . $_POST['a_lname']. "', 
                                                     username = '" . $_POST['username']. "', 
                                                     department = '" . $_POST['department']. "', 
                                                     mobile = '" . $_POST['mobile']. "'
                                WHERE a_id = '" . $_GET['updateid'] . "'");
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE admin_tbl SET a_fname = '" . $_POST['a_fname']. "', 
                                                     a_lname = '" . $_POST['a_lname']. "', 
                                                     username = '" . $_POST['username']. "', 
                                                     password = '" . $password . "', 
                                                     department = '" . $_POST['department']. "', 
                                                     mobile = '" . $_POST['mobile']. "'
                                                    WHERE a_id = '" . $_GET['updateid'] . "'");
        }

        echo "<script>
              window.location = '../admin-add-edit-coordinator.php?update=true' </script>";
    }

    $result = mysqli_query($con, "SELECT * FROM admin_tbl WHERE a_id = '" . $_GET['updateid'] . "'");
    $row = mysqli_fetch_array($result);
?>
