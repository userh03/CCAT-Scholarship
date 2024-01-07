<?php
session_start();
include("connection.php");
include("function.php");

date_default_timezone_set('Asia/Manila');

$response = array(); // Initialize an array to hold the response data

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check developer table
    $query = "SELECT * FROM devs_tbl WHERE isValid = 1 AND BINARY username = '$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        if (password_verify($password, $user_data['password'])) {
            $_SESSION['dev_id'] = $user_data['dev_id'];

            // Log user login
            $currentDateTime = date('Y-m-d H:i:s');
            $loggedInUserType = 'developer';
            $devId = $user_data['dev_id'];

            $query = "INSERT INTO logs_tbl (user_id, user_type, login_time) VALUES (?, ?, ?)";
            $statement = $con->prepare($query);
            $statement->bind_param("iss", $devId, $loggedInUserType, $currentDateTime);
            $statement->execute();

            $response['success'] = true;
            $response['message'] = "Login successful!";
            $response['redirect'] = "developer-dashboard.php?login=true";
        }
    }

    if (!empty($username) && !empty($password) && !is_numeric($username)) {
        // Check superadmin table
        $query = "SELECT * FROM superadmin_tbl WHERE isValid = 1 AND BINARY username = '$username'";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if (password_verify($password, $user_data['password'])) {
                $_SESSION['sa_id'] = $user_data['sa_id'];

                // Log user login
                $currentDateTime = date('Y-m-d H:i:s');
                $loggedInUserType = 'admin';
                $saId = $user_data['sa_id'];

                $query = "INSERT INTO logs_tbl (user_id, user_type, login_time) VALUES (?, ?, ?)";
                $statement = $con->prepare($query);
                $statement->bind_param("iss", $saId, $loggedInUserType, $currentDateTime);
                $statement->execute();

                $response['success'] = true;
                $response['message'] = "Login successful!";
                $response['redirect'] = "admin-dashboard.php?login=true";
            }
        } else {
            // Check admin table
            $query = "SELECT * FROM admin_tbl WHERE isValid = 1 AND BINARY username = '$username'";
            $result = mysqli_query($con, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);

                if (password_verify($password, $user_data['password'])) {
                    $_SESSION['a_id'] = $user_data['a_id'];

                    // Log user login
                    $currentDateTime = date('Y-m-d H:i:s');
                    $loggedInUserType = 'coordinator';
                    $aId = $user_data['a_id'];

                    $query = "INSERT INTO logs_tbl (user_id, user_type, login_time) VALUES (?, ?, ?)";
                    $statement = $con->prepare($query);
                    $statement->bind_param("iss", $aId, $loggedInUserType, $currentDateTime);
                    $statement->execute();

                    $response['success'] = true;
                    $response['message'] = "Login successful!";
                    $response['redirect'] = "coordinator-dashboard.php?login=true";
                }
            } else {
                // Check student table
                $query = "SELECT * FROM students_tbl WHERE isValid = 1 AND BINARY username = '$username'";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $user_data = mysqli_fetch_assoc($result);

                    if (password_verify($password, $user_data['password'])) {
                        $_SESSION['s_id'] = $user_data['s_id'];

                        // Log user login
                        $currentDateTime = date('Y-m-d H:i:s');
                        $loggedInUserType = 'student';
                        $sId = $user_data['s_id'];

                        $query = "INSERT INTO logs_tbl (user_id, user_type, login_time) VALUES (?, ?, ?)";
                        $statement = $con->prepare($query);
                        $statement->bind_param("iss", $sId, $loggedInUserType, $currentDateTime);
                        $statement->execute();

                        $response['success'] = true;
                        $response['message'] = "Login successful!";
                        $response['redirect'] = "user.php?login=true";
                    }
                }
            }

            $query2 = "SELECT username, password, isValid, 'coordinator' as userType FROM admin_tbl
            UNION
            SELECT username, password, isValid, 'student' as userType FROM students_tbl
            UNION
            SELECT username, password, isValid, 'admin' as userType FROM superadmin_tbl
            UNION
            SELECT username, password, isValid, 'developer' as userType FROM devs_tbl";

            $result2 = mysqli_query($con, $query2);

            // Check if any rows are returned
            if (mysqli_num_rows($result2) > 0) {
                // Fetching the results
                while ($row = mysqli_fetch_assoc($result2)) {
                    // Check the value of isValid
                    if ($row['isValid'] == 0 && password_verify($password, $row['password']) && $row['username'] == $username) {
                        $response['trash'] = "User is in the trash!";
                    } else {
                        $response['wrongpass'] = "Wrong Username/Password";
                    }
                }
            }
        }
    } 
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
