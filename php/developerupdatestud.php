<?php 
    include("connection.php");

    if (count($_POST) > 0) {
        $result = mysqli_query($con, "SELECT password FROM students_tbl WHERE s_id = '" . $_GET['updateid'] . "'");
        $row = mysqli_fetch_array($result);
        
        if ($_POST['password'] === "") {
            mysqli_query($con, "UPDATE students_tbl SET fname = '" . $_POST['fname']. "', 
                                                        lname = '" . $_POST['lname']. "', 
                                                        username = '" . $_POST['username']. "', 
                                                        section = '" . $_POST['section']. "', 
                                                        department = '" . $_POST['department']. "', 
                                                        mobile = '" . $_POST['mobile']. "' WHERE s_id = '" . $_GET['updateid'] . "'");
        } else {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            mysqli_query($con, "UPDATE students_tbl SET fname = '" . $_POST['fname']. "', 
                                                        lname = '" . $_POST['lname']. "', 
                                                        username = '" . $_POST['username']. "', 
                                                        password = '" . $password . "', 
                                                        section = '" . $_POST['section']. "', 
                                                        department = '" . $_POST['department']. "', 
                                                        mobile = '" . $_POST['mobile']. "' WHERE s_id = '" . $_GET['updateid'] . "'");
        }

        echo "<script>
              window.location = '../developer-add-edit-students.php?update=true' </script>";
    }

    $result = mysqli_query($con, "SELECT * FROM students_tbl WHERE s_id = '" . $_GET['updateid'] . "'");
    $row = mysqli_fetch_array($result);
?>
