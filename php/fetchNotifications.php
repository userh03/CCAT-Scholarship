<?php
// Include necessary files and establish a database connection if needed
include("connection.php");

// Check if student_id_number is set in the POST request
if (isset($_POST['student_id_number']) && isset($_POST['lname'])) {
    // Sanitize input to prevent SQL injection
    $student_id_number = mysqli_real_escape_string($con, $_POST['student_id_number']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);

    // Fetch all notifications
    $query = "SELECT * FROM notify_users_tbl WHERE student_id_number = '$student_id_number'";
    $result = mysqli_query($con, $query);

    // Initialize counters
    $all_notification_count = 0;
    $new_notification_count = 0;

    // Output the notification count
    while ($row = mysqli_fetch_assoc($result)) {
        $all_notification_count++;

        // Count only notifications with view_status equal to 1
        if ($row['view_status'] == 1) {
            $new_notification_count++;
        }
    }

    echo '<li class="app-notification__title">You have <span id="notification-count">' . $new_notification_count . '</span> new notification(s)</li>';
    echo '<span hidden id="new-notification-count">' . $new_notification_count . '</span>';

    // Output the notification content
    if ($all_notification_count > 0) {
        // Reset the result pointer to the beginning
        mysqli_data_seek($result, 0);

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $app_status = $row['app_status'];
            $view_status = $row['view_status'];
            $message = $row['message'];
            $icon = $row['icon'];

            // Set the timezone to Asia/Manila for both sent_time and current_time
            $sent_time = new DateTime($row['sent_time'], new DateTimeZone('Asia/Manila'));
            $current_time = new DateTime('now', new DateTimeZone('Asia/Manila'));

            // Calculate the time difference
            $interval = $current_time->getTimestamp() - $sent_time->getTimestamp();

            // Calculate time ago
            $time_ago = '';
            if ($interval < 60) {
                $time_ago = $interval . ' seconds ago';
            } elseif ($interval < 3600) {
                $minutes_ago = round($interval / 60);
                $time_ago = $minutes_ago . ' minute' . ($minutes_ago > 1 ? 's' : '') . ' ago';
            } elseif ($interval < 86400) {
                $hours_ago = round($interval / 3600);
                $time_ago = $hours_ago . ' hour' . ($hours_ago > 1 ? 's' : '') . ' ago';
            } else {
                $days_ago = round($interval / 86400);
                $time_ago = $days_ago . ' day' . ($days_ago > 1 ? 's' : '') . ' ago';
            }

            // Set the font-weight based on the view_status
            $fontWeight = ($view_status == 1) ? 'font-weight: 700;' : '';
?>
            <li>
                <a class="app-notification__item" href="#">
                    <div>
                        <div style="display: flex; align-items: center;">
                            <span class="app-notification__icon">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa <?php echo $icon ?> fa-stack-1x fa-inverse"></i>
                                </span>
                            </span>
                            <p class="app-notification__message" data-id="<?php echo $id ?>" style="<?php echo $fontWeight ?>"><?php echo 'Mr/Ms' . ' ' . $lname ?> <?php echo $message ?> <?php echo $app_status ?></p>
                        </div>
                        <p class="app-notification__meta" data-id="<?php echo $id ?>" style="margin-left: 50px;"><?php echo $time_ago ?></p>
                    </div>
                </a>
            </li>
<?php
        }
    } else {
        // Output a message if there are no notifications
    }

    // Close the database connection
    mysqli_close($con);
} else {
    // Handle the case where student_id_number is not set in the POST request
    echo 'Error: student_id_number is not set in the POST request.';
}
?>
