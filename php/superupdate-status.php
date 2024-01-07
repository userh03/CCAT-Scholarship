<?php
include("connection.php");

// Check if the app_id and status parameters are set
if(isset($_POST['app_id']) && isset($_POST['status'])) {
    // Get the app_id and status values
    $app_id = $_POST['app_id'];
    $app_status = $_POST['status'];
    $teacher_id_number = $_POST['teacher_id_number'];
    $a_fname = $_POST['a_fname'];
    $a_lname = $_POST['a_lname'];
    $department = $_POST['department'];

    // Update the app_status in the database
    $sql = "UPDATE applicant_tbl SET app_status = '$app_status' WHERE app_id = '$app_id'";
    $result = mysqli_query($con, $sql);

    // Move rows with app_status "Approved" to approved_applicants_tbl
    if ($app_status == 'Approved') {
        // Retrieve the row from applicant_tbl with the specified app_id and app_status
        $select_sql = "SELECT * FROM applicant_tbl WHERE app_id = '$app_id' AND app_status = '$app_status'";
        $select_result = mysqli_query($con, $select_sql);

        if ($select_result && mysqli_num_rows($select_result) > 0) {
            // Fetch the row data
            $row = mysqli_fetch_assoc($select_result);

            // Set the time zone to Asia/Manila
            date_default_timezone_set('Asia/Manila');

            $sent_time = date("H:i:s");
            $notifySql = "INSERT INTO notify_users_tbl (student_id_number, app_status, message, icon, sent_time, view_status) VALUES ('".$row['app_student_number']."', ?, 'your application is now', 'fa-check', ?, 1)";

            $stmt = $con->prepare($notifySql);

            if ($stmt) {
                // Assuming 's' is the correct data type for 'app_status' and 'sent_time'.
                // If 'sent_time' is of type TIME or DATETIME, you may need to adjust the data type specifier.
                $stmt->bind_param("ss", $app_status, $sent_time);
                
                // Set the values for the placeholders
                $app_status = "Approved"; // Replace with the actual value

                if ($stmt->execute()) {
                    // Insertion into notify_users_tbl was successful
                    $response = array('status' => 'Success', 'message' => 'Row updated.');
                
                    // Use prepared statements to prevent SQL injection
                    $insert_sql = "INSERT INTO approved_applicants_tbl (app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_scholar_type, app_status, app_year)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                    // Prepare the statement
                    $insert_stmt = $con->prepare($insert_sql);
                
                    // Bind parameters
                    $insert_stmt->bind_param("ssssssssssss", $row['app_id'], $row['app_student_number'], $row['app_fname'], $row['app_lname'], $row['app_mobile'], $row['app_email'], $row['app_section'], $row['app_department'], $row['app_adviser'], $row['app_scholar_type'], $row['app_status'], $row['app_year']);
                
                    // Execute the statement
                    $insert_result = $insert_stmt->execute();
                
                    // Insert data into admin_logs_tbl
                    $log_sql = "INSERT INTO admin_logs_tbl (teacher_id, a_fname, a_lname, department, time_log, activity, student_id_number, student_fname, student_lname, student_department) 
                                                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, 'Status Update - Denied', ?, ?, ?, ?)";
                
                    // Prepare the statement
                    $log_stmt = $con->prepare($log_sql);
                
                    // Bind parameters
                    $log_stmt->bind_param("ssssssss", $teacher_id_number, $a_fname, $a_lname, $department, $row['app_student_number'], $row['app_fname'], $row['app_lname'], $row['app_department']);
                
                    // Execute the statement
                    $log_result = $log_stmt->execute();
                
                    // Delete row from applicant_tbl
                    $delete_sql = "DELETE FROM applicant_tbl WHERE app_id = ? AND app_status = '$app_status'";
                
                    // Prepare the statement
                    $delete_stmt = $con->prepare($delete_sql);
                
                    // Bind parameter
                    $delete_stmt->bind_param("s", $app_id);
                
                    // Execute the statement
                    $delete_result = $delete_stmt->execute();
                
                    if ($insert_result && $log_result && $delete_result) {
                        // Insertions and deletions were successful
                        $response['admin_log'] = 'Admin log inserted successfully.';
                    } else {
                        $response['admin_log'] = 'Error inserting data: ' . $con->error;
                    }
                
                    echo json_encode($response);
                } else {
                    $response = array('status' => 'Error', 'message' => 'Error executing statement: ' . $stmt->error);
                    echo json_encode($response);
                }
            } else {
                echo "Error preparing the statement: " . $con->error;
            }
        } else {
            echo "No row found with the specified app_id and app_status.";
        }
    } else if ($app_status == 'Denied') {
        // Retrieve the row from applicant_tbl with the specified app_id and app_status
        $select_sql = "SELECT * FROM applicant_tbl WHERE app_id = '$app_id' AND app_status = '$app_status'";
        $select_result = mysqli_query($con, $select_sql);

        if ($select_result && mysqli_num_rows($select_result) > 0) {
            // Fetch the row data
            $row = mysqli_fetch_assoc($select_result);

            // Set the time zone to Asia/Manila
            date_default_timezone_set('Asia/Manila');

            $sent_time = date("H:i:s");
            $notifySql = "INSERT INTO notify_users_tbl (student_id_number, app_status, message, icon, sent_time, view_status) VALUES ('".$row['app_student_number']."', ?, 'your application is now', 'fa-check', ?, 1)";

            $stmt = $con->prepare($notifySql);

            if ($stmt) {
                // Assuming 's' is the correct data type for 'app_status' and 'sent_time'.
                // If 'sent_time' is of type TIME or DATETIME, you may need to adjust the data type specifier.
                $stmt->bind_param("ss", $app_status, $sent_time);
                
                // Set the values for the placeholders
                $app_status = "Denied"; // Replace with the actual value

                if ($stmt->execute()) {
                    // Insertion into notify_users_tbl was successful
                    $response = array('status' => 'Success', 'message' => 'Row updated.');
                
                    // Use prepared statements to prevent SQL injection
                    $insert_sql = "INSERT INTO denied_applicants_tbl (app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_scholar_type, app_status, app_year)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                    // Prepare the statement
                    $insert_stmt = $con->prepare($insert_sql);
                
                    // Bind parameters
                    $insert_stmt->bind_param("ssssssssssss", $row['app_id'], $row['app_student_number'], $row['app_fname'], $row['app_lname'], $row['app_mobile'], $row['app_email'], $row['app_section'], $row['app_department'], $row['app_adviser'], $row['app_scholar_type'], $row['app_status'], $row['app_year']);
                
                    // Execute the statement
                    $insert_result = $insert_stmt->execute();
                
                    // Insert data into admin_logs_tbl
                    $log_sql = "INSERT INTO admin_logs_tbl (teacher_id, a_fname, a_lname, department, time_log, activity, student_id_number, student_fname, student_lname, student_department) 
                                                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, 'Status Update - Denied', ?, ?, ?, ?)";
                
                    // Prepare the statement
                    $log_stmt = $con->prepare($log_sql);
                
                    // Bind parameters
                    $log_stmt->bind_param("ssssssss", $teacher_id_number, $a_fname, $a_lname, $department, $row['app_student_number'], $row['app_fname'], $row['app_lname'], $row['app_department']);
                
                    // Execute the statement
                    $log_result = $log_stmt->execute();
                
                    // Delete row from applicant_tbl
                    $delete_sql = "DELETE FROM applicant_tbl WHERE app_id = ? AND app_status = '$app_status'";
                
                    // Prepare the statement
                    $delete_stmt = $con->prepare($delete_sql);
                
                    // Bind parameter
                    $delete_stmt->bind_param("s", $app_id);
                
                    // Execute the statement
                    $delete_result = $delete_stmt->execute();
                
                    if ($insert_result && $log_result && $delete_result) {
                        // Insertions and deletions were successful
                        $response['admin_log'] = 'Admin log inserted successfully.';
                    } else {
                        $response['admin_log'] = 'Error inserting data: ' . $con->error;
                    }
                
                    echo json_encode($response);
                } else {
                    $response = array('status' => 'Error', 'message' => 'Error executing statement: ' . $stmt->error);
                    echo json_encode($response);
                }
            } else {
                echo "Error preparing the statement: " . $con->error;
            }
        } else {
            echo "No row found with the specified app_id and app_status.";
        }
    } else if ($app_status == 'On-process') {
        // Retrieve the row from applicant_tbl with the specified app_id and app_status
        $select_sql = "SELECT * FROM applicant_tbl WHERE app_id = '$app_id' AND app_status = '$app_status'";
        $select_result = mysqli_query($con, $select_sql);

        if ($select_result && mysqli_num_rows($select_result) > 0) {
            // Fetch the row data
            $row = mysqli_fetch_assoc($select_result);

            // Set the time zone to Asia/Manila
            date_default_timezone_set('Asia/Manila');

            $sent_time = date("H:i:s");
            $notifySql = "INSERT INTO notify_users_tbl (student_id_number, app_status, message, icon, sent_time, view_status) VALUES ('".$row['app_student_number']."', ?, 'your application is now', 'fa-spinner', ?, 1)";

            $stmt = $con->prepare($notifySql);

            if ($stmt) {
                // Assuming 's' is the correct data type for 'app_status' and 'sent_time'.
                // If 'sent_time' is of type TIME or DATETIME, you may need to adjust the data type specifier.
                $stmt->bind_param("ss", $app_status, $sent_time);
                
                // Set the values for the placeholders
                $app_status = "On-process"; // Replace with the actual value

                if ($stmt->execute()) {
                    // Insertion into notify_users_tbl was successful
                    $response = array('status' => 'Success', 'message' => 'Row updated.');
            
                    // Insert data into admin_logs_tbl
                    $log_sql = "INSERT INTO admin_logs_tbl (teacher_id, a_fname, a_lname, department, time_log, activity, student_id_number, student_fname, student_lname, student_department) 
                                                    VALUES ('$teacher_id_number', '$a_fname', '$a_lname', '$department', CURRENT_TIMESTAMP, 'Status Update - On-process', '".$row['app_student_number']."', '".$row['app_fname']."', '".$row['app_lname']."','".$row['app_department']."')";
            
                    if (mysqli_query($con, $log_sql)) {
                        // Insertion into admin_logs_tbl was successful
                        $response['admin_log'] = 'Admin log inserted successfully.';
                    } else {
                        $response['admin_log'] = 'Error inserting data into admin_logs_tbl: ' . mysqli_error($con);
                    }
            
                    echo json_encode($response);
                } else {
                    $response = array('status' => 'Error', 'message' => 'Error inserting data into notify_users_tbl: ' . $stmt->error);
                    echo json_encode($response);
                }
            } else {
                echo "Error preparing the statement: " . $con->error;
            }
        } else {
            echo "No row found with the specified app_id and app_status.";
        }
    } else {
        echo "Row status is neither 'Approved' nor 'Denied'.";
    }
} else {
    // If the app_id or status parameters are not set, return an error response
    echo "Error: app_id or status parameters not set";
}
?>