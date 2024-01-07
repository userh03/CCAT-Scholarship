<?php
function check_login($con)
{
    if(isset($_SESSION['a_id']))
    {
        $a_id = $_SESSION['a_id'];
        $query = "select * from admin_tbl where a_id = '$a_id' limit 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else if(isset($_SESSION['s_id']))
    {
        $s_id = $_SESSION['s_id'];
        $query = "select * from students_tbl where s_id = '$s_id' limit 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else if(isset($_SESSION['sa_id']))
    {
        $sa_id = $_SESSION['sa_id'];
        $query = "select * from superadmin_tbl where sa_id = '$sa_id' limit 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    //redirect to login
    header("location: ../index.php");
    die;
}