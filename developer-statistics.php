<?php
    session_start();

    include("php/connection.php");

    // check if session is not set
    if(!isset($_SESSION['dev_id']))
    {
      header("location: index.php");
      exit();
    }
    
    $dev_id = $_SESSION['dev_id'];
    $query = "SELECT * FROM devs_tbl WHERE dev_id = '$dev_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $d_fname = $row['d_fname'];
    $d_lname = $row['d_lname'];
    $d_position = $row['d_position'];
    $profile_picture = $row['profile_picture'];
?>
<?php
include("php/connection.php");

// Function to count rows in a table
function countRows($con, $table)
{
  $sql = "SELECT COUNT(*) as count FROM $table";
  $result = $con->query($sql);

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['count'];
  } else {
      return 0;
  }
}

// Count rows in each table
$applicantCount = countRows($con, 'applicant_tbl');
$approvedCount = countRows($con, 'approved_applicants_tbl');
$deniedCount = countRows($con, 'denied_applicants_tbl');

// Close the connection
$con->close();
?>
<?php
include("php/connection.php");

// Count rows in each table
$tables = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$count = 0;

foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE app_scholar_type = 'Academic Scholarship Presidential'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count += $row['count'];
    }
}

// Close the connection
$con->close();

?>
<?php
include("php/connection.php");

// Count rows in each table
$tables = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$count2 = 0;

foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE app_scholar_type = 'Academic Scholarship Vice Presidential'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count2 += $row['count'];
    }
}

// Close the connection
$con->close();

?>

<?php
include("php/connection.php");

// Count rows in each table
$tables = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$count5 = 0;

foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE app_scholar_type = 'Talent Scholarship'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count5 += $row['count'];
    }
}

// Close the connection
$con->close();

?>
<?php
include("php/connection.php");

// Count rows in each table
$tables = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$count6 = 0;

foreach ($tables as $table) {
    $sql = "SELECT COUNT(*) as count FROM $table WHERE app_scholar_type = 'Service Scholarship'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count6 += $row['count'];
    }
}

// Close the connection
$con->close();

?>

<?php
include("php/connection.php");

// Specify the table
$table_minus_1 = 'denied_applicants_tbl';
$targetSections_minus_1 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts_minus_1 = array_fill_keys($targetSections_minus_1, 0);

foreach ($targetSections_minus_1 as $section_minus_1) {
    $sectionWildcard_minus_1 = "%$section_minus_1%";
    
    $sql_minus_1 = "SELECT COUNT(*) as count FROM $table_minus_1 WHERE app_status = 'Denied' AND app_section LIKE ?";
    
    // Use prepared statement
    $stmt_minus_1 = $con->prepare($sql_minus_1);

    // Bind parameter
    $stmt_minus_1->bind_param('s', $sectionWildcard_minus_1);

    // Execute the query
    $stmt_minus_1->execute();

    // Get result
    $result_minus_1 = $stmt_minus_1->get_result();

    if ($result_minus_1->num_rows > 0) {
        $row_minus_1 = $result_minus_1->fetch_assoc();
        $sectionCounts_minus_1[$section_minus_1] += $row_minus_1['count'];
    }

    // Close the statement
    $stmt_minus_1->close();
}

// Close the connection
$con->close();

// Access the counts for each section
$denied_bsInfotechCount = $sectionCounts_minus_1['BSINFOTECH'];
$denied_bsCosCount = $sectionCounts_minus_1['BSCOS'];
$denied_bsItCount = $sectionCounts_minus_1['BSIT'];
$denied_bsEeeCount = $sectionCounts_minus_1['BSEE'];
$denied_bsECount = $sectionCounts_minus_1['BSE'];
$denied_bsHmCount = $sectionCounts_minus_1['BSHM'];
$denied_bsBmCount = $sectionCounts_minus_1['BSBM'];
$denied_bscpeCount = $sectionCounts_minus_1['BSCPE'];
$denied_btvtedCount = $sectionCounts_minus_1['BTVTED'];
?>

<?php
include("php/connection.php");

// Specify the table
$table0 = 'approved_applicants_tbl';
$targetSections0 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts0 = array_fill_keys($targetSections0, 0);

foreach ($targetSections0 as $section0) {
    $sectionWildcard0 = "%$section0%";
    
    $sql0 = "SELECT COUNT(*) as count FROM $table0 WHERE app_status = 'Approved' AND app_section LIKE ?";
    
    // Use prepared statement
    $stmt0 = $con->prepare($sql0);

    // Bind parameter
    $stmt0->bind_param('s', $sectionWildcard0);

    // Execute the query
    $stmt0->execute();

    // Get result
    $result0 = $stmt0->get_result();

    if ($result0->num_rows > 0) {
        $row0 = $result0->fetch_assoc();
        $sectionCounts0[$section0] += $row0['count'];
    }

    // Close the statement
    $stmt0->close();
}

// Close the connection
$con->close();

// Access the counts for each section
$approved_bsInfotechCount0 = $sectionCounts0['BSINFOTECH'];
$approved_bsCosCount0 = $sectionCounts0['BSCOS'];
$approved_bsItCount0 = $sectionCounts0['BSIT'];
$approved_bsEeeCount0 = $sectionCounts0['BSEE'];
$approved_bsECount0 = $sectionCounts0['BSE'];
$approved_bsHmCount0 = $sectionCounts0['BSHM'];
$approved_bsBmCount0 = $sectionCounts0['BSBM'];
$approved_bscpeCount0 = $sectionCounts0['BSCPE'];
$approved_btvtedCount0 = $sectionCounts0['BTVTED'];
?>

<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts = array_fill_keys($targetSections, 0);

foreach ($tables as $table) {
    foreach ($targetSections as $section) {
        $sectionWildcard = "%$section%";
        
        $sql = "SELECT COUNT(*) as count FROM $table WHERE app_scholar_type = 'Academic Scholarship Presidential' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt = $con->prepare($sql);

        // Bind parameter
        $stmt->bind_param('s', $sectionWildcard);

        // Execute the query
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sectionCounts[$section] += $row['count'];
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount = $sectionCounts['BSINFOTECH'];
$bsCosCount = $sectionCounts['BSCOS'];
$bsItCount = $sectionCounts['BSIT'];
$bsEeeCount = $sectionCounts['BSEE'];
$bsECount = $sectionCounts['BSE'];
$bsHmCount = $sectionCounts['BSHM'];
$bsBmCount = $sectionCounts['BSBM'];
$bscpeCount = $sectionCounts['BSCPE'];
$btvtedCount = $sectionCounts['BTVTED'];
?>
<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables2 = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections2 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts2 = array_fill_keys($targetSections2, 0);

foreach ($tables2 as $table2) {
    foreach ($targetSections2 as $section2) {
        $sectionWildcard2 = "%$section2%";
        
        $sql2 = "SELECT COUNT(*) as count FROM $table2 WHERE app_scholar_type = 'Academic Scholarship Vice Presidential' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt2 = $con->prepare($sql2);

        // Bind parameter
        $stmt2->bind_param('s', $sectionWildcard2);

        // Execute the query
        $stmt2->execute();

        // Get result
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            $row2 = $result2->fetch_assoc();
            $sectionCounts2[$section2] += $row2['count'];
        }

        // Close the statement
        $stmt2->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount2 = $sectionCounts2['BSINFOTECH'];
$bsCosCount2 = $sectionCounts2['BSCOS'];
$bsItCount2 = $sectionCounts2['BSIT'];
$bsEeeCount2 = $sectionCounts2['BSEE'];
$bsECount2 = $sectionCounts2['BSE'];
$bsHmCount2 = $sectionCounts2['BSHM'];
$bsBmCount2 = $sectionCounts2['BSBM'];
$bscpeCount2 = $sectionCounts2['BSCPE'];
$btvtedCount2 = $sectionCounts2['BTVTED'];
?>

<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables3 = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections3 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts3 = array_fill_keys($targetSections3, 0);

foreach ($tables3 as $table3) {
    foreach ($targetSections3 as $section3) {
        $sectionWildcard3 = "%$section3%";
        
        $sql3 = "SELECT COUNT(*) as count FROM $table3 WHERE app_scholar_type = 'Talent Scholarship' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt3 = $con->prepare($sql3);

        // Bind parameter
        $stmt3->bind_param('s', $sectionWildcard3);

        // Execute the query
        $stmt3->execute();

        // Get result
        $result3 = $stmt3->get_result();

        if ($result3->num_rows > 0) {
            $row3 = $result3->fetch_assoc();
            $sectionCounts3[$section3] += $row3['count'];
        }

        // Close the statement
        $stmt3->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount3 = $sectionCounts3['BSINFOTECH'];
$bsCosCount3 = $sectionCounts3['BSCOS'];
$bsItCount3 = $sectionCounts3['BSIT'];
$bsEeeCount3 = $sectionCounts3['BSEE'];
$bsECount3 = $sectionCounts3['BSE'];
$bsHmCount3 = $sectionCounts3['BSHM'];
$bsBmCount3 = $sectionCounts3['BSBM'];
$bscpeCount3 = $sectionCounts3['BSCPE'];
$btvtedCount3 = $sectionCounts3['BTVTED'];
?>

<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables4 = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections4 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts4 = array_fill_keys($targetSections4, 0);

foreach ($tables4 as $table4) {
    foreach ($targetSections4 as $section4) {
        $sectionWildcard4 = "%$section4%";
        
        $sql4 = "SELECT COUNT(*) as count FROM $table4 WHERE app_scholar_type = 'Service Scholarship' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt4 = $con->prepare($sql4);

        // Bind parameter
        $stmt4->bind_param('s', $sectionWildcard4);

        // Execute the query
        $stmt4->execute();

        // Get result
        $result4 = $stmt4->get_result();

        if ($result4->num_rows > 0) {
            $row4 = $result4->fetch_assoc();
            $sectionCounts4[$section4] += $row4['count'];
        }

        // Close the statement
        $stmt4->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount4 = $sectionCounts4['BSINFOTECH'];
$bsCosCount4 = $sectionCounts4['BSCOS'];
$bsItCount4 = $sectionCounts4['BSIT'];
$bsEeeCount4 = $sectionCounts4['BSEE'];
$bsECount4 = $sectionCounts4['BSE'];
$bsHmCount4 = $sectionCounts4['BSHM'];
$bsBmCount4 = $sectionCounts4['BSBM'];
$bscpeCount4 = $sectionCounts4['BSCPE'];
$btvtedCount4 = $sectionCounts4['BTVTED'];
?>

<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables5 = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections5 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts5 = array_fill_keys($targetSections5, 0);

foreach ($tables5 as $table5) {
    foreach ($targetSections5 as $section5) {
        $sectionWildcard5 = "%$section5%";
        
        $sql5 = "SELECT COUNT(*) as count FROM $table5 WHERE app_scholar_type = 'TES Scholarship' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt5 = $con->prepare($sql5);

        // Bind parameter
        $stmt5->bind_param('s', $sectionWildcard5);

        // Execute the query
        $stmt5->execute();

        // Get result
        $result5 = $stmt5->get_result();

        if ($result5->num_rows > 0) {
            $row5 = $result5->fetch_assoc();
            $sectionCounts5[$section5] += $row5['count'];
        }

        // Close the statement
        $stmt5->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount5 = $sectionCounts5['BSINFOTECH'];
$bsCosCount5 = $sectionCounts5['BSCOS'];
$bsItCount5 = $sectionCounts5['BSIT'];
$bsEeeCount5 = $sectionCounts5['BSEE'];
$bsECount5 = $sectionCounts5['BSE'];
$bsHmCount5 = $sectionCounts5['BSHM'];
$bsBmCount5 = $sectionCounts5['BSBM'];
$bscpeCount5 = $sectionCounts5['BSCPE'];
$btvtedCount5 = $sectionCounts5['BTVTED'];
?>

<?php
include("php/connection.php");

// Count rows in each table for specified sections
$tables6 = ['applicant_tbl', 'approved_applicants_tbl', 'denied_applicants_tbl'];
$targetSections6 = ['BSINFOTECH', 'BSCOS', 'BSIT', 'BSEE', 'BSE', 'BSHM', 'BSBM', 'BSCPE', 'BTVTED'];

// Initialize section counts
$sectionCounts6 = array_fill_keys($targetSections6, 0);

foreach ($tables6 as $table6) {
    foreach ($targetSections6 as $section6) {
        $sectionWildcard6 = "%$section6%";
        
        $sql6 = "SELECT COUNT(*) as count FROM $table6 WHERE app_scholar_type = 'TWP Scholarship' AND app_section LIKE ?";
        
        // Use prepared statement
        $stmt6 = $con->prepare($sql6);

        // Bind parameter
        $stmt6->bind_param('s', $sectionWildcard6);

        // Execute the query
        $stmt6->execute();

        // Get result
        $result6 = $stmt6->get_result();

        if ($result6->num_rows > 0) {
            $row6 = $result6->fetch_assoc();
            $sectionCounts6[$section6] += $row6['count'];
        }

        // Close the statement
        $stmt6->close();
    }
}

// Close the connection
$con->close();

// Access the counts for each section
$bsInfotechCount6 = $sectionCounts6['BSINFOTECH'];
$bsCosCount6 = $sectionCounts6['BSCOS'];
$bsItCount6 = $sectionCounts6['BSIT'];
$bsEeeCount6 = $sectionCounts6['BSEE'];
$bsECount6 = $sectionCounts6['BSE'];
$bsHmCount6 = $sectionCounts6['BSHM'];
$bsBmCount6 = $sectionCounts6['BSBM'];
$bscpeCount6 = $sectionCounts6['BSCPE'];
$btvtedCount6 = $sectionCounts6['BTVTED'];
?>

<!DOCTYPE html>
  <head>
    <title>Statistics</title>
    <link rel="shortcut icon" href="images/logo.ico" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css"> 
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a id="bLogo" class="app-header__logo" href="developer-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
      <!-- Sidebar toggle button-->
      <a id="sSide" style="background: white;" class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
                <!--Notification Menu-->
                <style>
          .red-dot {
            position: absolute;
            background-color: red;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            top: 15px;
            right: 15px;
          }
        </style>
          <?php
              include("php/connection.php");
              // Execute the SQL query to count new notifications with view_status = 1
              $query = "SELECT COUNT(*) AS status_count FROM notify_tbl WHERE view_status = 1";
              $result = mysqli_query($con, $query);

              // Check if the query was successful
              if ($result) {
                  // Fetch the result as an associative array
                  $row = mysqli_fetch_assoc($result);
                  $notification_count = $row['status_count'];
                  
              } else {
                  // Handle the case where the query fails
                  $notification_count = 0;
              }
            ?>
          <li class="dropdown">
            <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
              <i class="fa fa-bell-o fa-lg">
                <?php if ($notification_count > 0): ?>
                  <span class="red-dot"></span>
                <?php endif; ?>
              </i>
            </a>
            <ul class="app-notification dropdown-menu dropdown-menu-right">
              <li class="app-notification__title">You have <?php echo $notification_count ?> new notification(s).</li>
              <div class="app-notification__content" style="width: 260px;">
                <?php
                  include("php/connection.php");
                  $query = "SELECT * FROM notify_tbl";
                  $result = mysqli_query($con, $query);

                  if (mysqli_num_rows($result) > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $id = $row['id'];
                          $app_fname = $row['app_fname'];
                          $app_lname = $row['app_lname'];
                          $view_status = $row['view_status'];

                          // Skip notifications with view_status 0
                          if ($view_status == 0) {
                              continue;
                          }

                          // Set the timezone to Asia/Manila for both sent_time and current_time
                          $sent_time = new DateTime($row['sent_time'], new DateTimeZone('Asia/Manila'));
                          $current_time = new DateTime('now', new DateTimeZone('Asia/Manila'));

                          // Calculate the time difference
                          $interval = $current_time->getTimestamp() - $sent_time->getTimestamp();

                          // Calculate time ago
                          if ($interval < 60) {
                              $time_ago = $interval . ' seconds ago';
                          } elseif ($interval < 3600) {
                              $minutes_ago = round($interval / 60);
                              $time_ago = $minutes_ago . ' minutes ago';
                          } else {
                              $hours_ago = round($interval / 3600);
                              $time_ago = $hours_ago . ' hour(s) ago';
                          }

                          // Rest of your code remains the same

                          // Set the font-weight based on the view_status
                          $fontWeight = ($view_status == 1) ? 'font-weight: 700;' : '';
                ?>
                          <li>
                              <a class="app-notification__item" onclick="handleNotificationClick(event)" href="#" data-id="<?php echo $id ?>">
                                <div data-id="<?php echo $id ?>">
                                    <div style="display: flex; align-items: center;" data-id="<?php echo $id ?>">
                                    <span class="app-notification__icon" data-id="<?php echo $id ?>">
                                      <span class="fa-stack fa-lg" data-id="<?php echo $id ?>">
                                        <i class="fa fa-circle fa-stack-2x text-primary" data-id="<?php echo $id ?>"></i>
                                        <i class="fa fa-envelope fa-stack-1x fa-inverse" data-id="<?php echo $id ?>"></i>
                                      </span>
                                    </span>
                                    <p class="app-notification__message" data-id="<?php echo $id ?>" style="<?php echo $fontWeight ?>"><?php echo $app_fname .' '. $app_lname ?> sent an Application</p>
                                    </div>
                                    <p class="app-notification__meta" data-id="<?php echo $id ?>" style="margin-left: 50px;"><?php echo $time_ago ?></p>
                            
                                </div>
                              </a>
                          </li>
                  <?php
                      }
                  } else {
                  }
                ?>
              </div>
            </ul>
          </li>
          <script>
            function handleNotificationClick(event) {
              event.preventDefault(); // Prevent the default navigation behavior
              
              // Get the notification ID from the data-id attribute
              var notificationId = $(event.target).data('id'); // Use $(event.target) to access the clicked element
              
              // Send an AJAX request to update the view_status
              $.ajax({
                type: 'POST',
                url: 'php/notifyStatusUpdate.php', // Replace with the actual URL to your PHP script
                data: { id: notificationId },
                success: function(response) {
                // Check if the response is equal to "success"
                if (response === 'Success') {                  
                  // Optionally, you can redirect the user to a new page after the update
                  window.location.href = 'developer-tables.php';
                }
              },
              });
            }
          </script>
            <!-- User Menu-->
          <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
              <li><a class="dropdown-item" href="developer-page-user.php"><i class="fa fa-user fa-lg"></i> Profile</a></li>
              <li><a class="dropdown-item" href="developer-user-settings.php"><i class="fa fa-cog fa-lg"></i> Account Settings</a></li>
              <li><a class="dropdown-item" onclick="logOut();" href="#"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
          </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside id="sNav" style="background: rgb(255, 255, 255);" class="app-sidebar">
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>" alt="">
        <div style="color:black;">
        <?php
            $truncated_name = $d_fname;

            // Split the string into an array of words
            $words = explode(' ', $truncated_name);

            // Check if the array has at least four words
            if (count($words) >= 2) {
                // Combine the first three words and join the rest with a new line
                $designation_name = implode(' ', array_slice($words, 0, 2)) . '<br>' . implode(' ', array_slice($words, 2));
            } else {
                // If there are fewer than four words, keep the original string
                $designation_name = $truncated_name;
            }
          ?>
        <p class="app-sidebar__user-name"><?php echo $designation_name." ". $d_lname ?></p>
          <?php
            $building = $d_position;

            // Split the string into an array of words
            $words = explode(' ', $building);

            // Check if the array has at least four words
            if (count($words) >= 4) {
                // Combine the first three words and join the rest with a new line
                $designation = implode(' ', array_slice($words, 0, 3)) . '<br>' . implode(' ', array_slice($words, 3));
            } else {
                // If there are fewer than four words, keep the original string
                $designation = $building;
            }
          ?>
          <p class="app-sidebar__user-designation"><?php echo $designation ?></p>
        </div>
      </div>
      <!-- NAVBAR IMPORT -->
      <?php include 'php/navbar.php' ?>
    </aside>
    <main class="app-content">
      <div class="app-title">
      <div id="divProfile">
          <style>
            /* Media query for mobile view */
            @media screen and (max-width: 767px) {
                #divProfile {
                  display: none;
                }
              }
          </style>          
          <h1><i class="fa fa-pie-chart"></i> Statistics</h1>
          <p>Statistical Data of Scholars</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item active"><a href="#">Statistics</a></li>
        </ul>
      </div>  
      <div class="container-fluid">
        <div class="row">
          <!-- Side Panel -->
          <div class="col-lg-3 side-panel" id="sidePanel">
            <div class="legend-box">
              <div class="legend-item">
                <div class="legend-color" style="background-color: #F7464A; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">Total Applicants</div><br>
                <div class="legend-color" style="background-color: #ffa500; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSINFOTECH</div><br>
                <div class="legend-color" style="background-color: #cf5c36; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSCOS</div><br>
                <div class="legend-color" style="background-color: #023e8a; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSIT</div><br>
                <div class="legend-color" style="background-color: #ffd966; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSEE</div><br>
                <div class="legend-color" style="background-color: #ccad51; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSE</div><br>
                <div class="legend-color" style="background-color: #d5a6bd; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSHM</div><br>
                <div class="legend-color" style="background-color: #dc6ba2; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSBM</div><br>
                <div class="legend-color" style="background-color: #b6d7a8; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BSCPE</div><br>
                <div class="legend-color" style="background-color: #76a5af; display: inline-block; width: 20px; height: 20px;"></div>
                <div class="legend-description" style="display: inline-block; font-size: 16px;">BTVTED</div><br>
                <!-- Add other legend items here -->
              </div>
            </div>
          </div>
          <!-- Toggle Button Arrow -->
          <div class="arrow" id="toggleButton"><span class="prevent-select">>></span></div>
        </div>
      </div>

      <style>
        /* Custom styling */
        .prevent-select {
          -webkit-user-select: none; /* Safari */
          -ms-user-select: none; /* IE 10 and IE 11 */
          user-select: none; /* Standard syntax */
        }
        .legend-box {
            padding: 20px;
        }
        .side-panel {
          margin-top: 20vh;
          background-color: #333;
          color: #fff;
          position: fixed;
          z-index: 99999;
          right: -100vh;
          transition: right 0.3s ease-in-out;
          padding: 20px;
          background-color: rgba(0, 0, 0, 0.6);
        }

        .side-panel a {
          color: #fff;
          text-decoration: none;
          display: block;
          z-index: 99999;
          margin-bottom: 10px;
        }

        .arrow {
          position: fixed;
          right: 0px;
          top: 45%;
          background-color: #333;
          color: #fff;
          padding: 10px;
          cursor: pointer;
          z-index: 99999;
          transition: right 0.3s ease-in-out;
        }
      </style>

      <script>
        // Add JavaScript for toggling the side panel
        document.getElementById('toggleButton').addEventListener('click', function () {
          var sidePanel = document.getElementById('sidePanel');
          var sidePanelRight = parseInt(getComputedStyle(sidePanel).right);

          if (sidePanelRight === 0) {
            sidePanel.style.right = '-100vh';
          } else {
            sidePanel.style.right = '0';
          }
        });
      </script>
        <button style="width: 10%; float: right;" class="btn btn-primary" onclick="printCharts()">Print</button><br><br>
      <div class="row" id="chartss">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Full Academic Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Partial Academic Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo2"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Talent Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo3"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Service Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo6"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">TES Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo7"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">TWP Scholars</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo8"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Denied Applicants</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo4"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Approved Applicants</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="pieChartDemo5"></canvas>
            </div>
          </div>
        </div>
      </div>
      <footer>
        <div class="container" style="margin: auto;">
          <div class="row">
              <div class="col-md-12">
                  <hr>
                  <p style="text-align: center;">&copy; 2023 CCAT - CAMPUS. All rights reserved.</p>
              </div>
          </div>
        </div>
      </footer>
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/chart.js"></script>
    <script type="text/javascript">
      var pdata = [
      	{//Presidential Scholars
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
      	{
      		value: <?php echo $bsInfotechCount ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata2 = [
      	{// Vice Presidential Scholars
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
        {
      		value: <?php echo $bsInfotechCount2 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount2 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount2 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount2 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount2 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount2 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount2 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount2 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount2 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata3 = [
      	{//Talent Scholar
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
        {
      		value: <?php echo $bsInfotechCount3 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount3 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount3 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount3 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount3 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount3 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount3 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount3 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount3 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata4 = [
      	{//Denied Applicants
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
        {
      		value: <?php echo $denied_bsInfotechCount ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $denied_bsCosCount ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $denied_bsItCount ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $denied_bsEeeCount ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $denied_bsECount ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $denied_bsHmCount ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $denied_bsBmCount ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $denied_bscpeCount ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $denied_btvtedCount ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata5 = [
        {//Approved Applicants
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
        {
      		value: <?php echo $approved_bsInfotechCount0 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $approved_bsCosCount0 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $approved_bsItCount0 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $approved_bsEeeCount0 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $approved_bsECount0 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $approved_bsHmCount0 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $approved_bsBmCount0 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $approved_bscpeCount0 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $approved_btvtedCount0 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata6 = [
      	{//Service Scholars
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
      	{
      		value: <?php echo $bsInfotechCount4 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount4 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount4 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount4 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount4 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount4 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount4 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount4 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount4 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata7 = [
      	{//TES Scholars
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
      	{
      		value: <?php echo $bsInfotechCount5 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount5 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount5 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount5 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount5 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount5 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount5 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount5 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount5 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      var pdata8 = [
      	{//TWP Scholars
      		value: <?php echo $applicantCount + $approvedCount + $deniedCount ?>,
      		color:"#F7464A",
      		highlight: "#FF5A5E",
      		label: "Total Number of Students"
      	},
      	{
      		value: <?php echo $bsInfotechCount6 ?>,
      		color: "#ffa500",
      		highlight: "#ffc04c",
      		label: "BSINFOTECH"
      	},
      	{
      		value: <?php echo $bsCosCount6 ?>,
      		color: "#cf5c36",
      		highlight: "#fc814a",
      		label: "BSCOS"
      	},
      	{
      		value: <?php echo $bsItCount6 ?>,
      		color: "#023e8a",
      		highlight: "#00b4d8",
      		label: "BSIT"
      	},
      	{
      		value: <?php echo $bsEeeCount6 ?>,
      		color: "#ffd966",
      		highlight: "#ffecb2",
      		label: "BSEE"
      	},
      	{
      		value: <?php echo $bsECount6 ?>,
      		color: "#ccad51",
      		highlight: "#e0cd96",
      		label: "BSE"
      	},
      	{
      		value: <?php echo $bsHmCount6 ?>,
      		color: "#d5a6bd",
      		highlight: "#e7c7d6",
      		label: "BSHM"
      	},
      	{
      		value: <?php echo $bsBmCount6 ?>,
      		color: "#dc6ba2",
      		highlight: "#b17592",
      		label: "BSBM"
      	},
      	{
      		value: <?php echo $bscpeCount6 ?>,
      		color: "#b6d7a8",
      		highlight: "#b9c7b3",
      		label: "BSCPE"
      	},
      	{
      		value: <?php echo $btvtedCount6 ?>,
      		color: "#76a5af",
      		highlight: "#a8ccd4",
      		label: "BTVTED"
      	}
      ]
      
      var ctxp = $("#pieChartDemo").get(0).getContext("2d");
      var pieChart = new Chart(ctxp).Pie(pdata);

      var ctxp2 = $("#pieChartDemo2").get(0).getContext("2d");
      var pieChart2 = new Chart(ctxp2).Pie(pdata2);

      var ctxp3 = $("#pieChartDemo3").get(0).getContext("2d");
      var pieChart3 = new Chart(ctxp3).Pie(pdata3);

      var ctxp4 = $("#pieChartDemo4").get(0).getContext("2d");
      var pieChartDemo4 = new Chart(ctxp4).Pie(pdata4);

      var ctxp5 = $("#pieChartDemo5").get(0).getContext("2d");
      var pieChartDemo5 = new Chart(ctxp5).Pie(pdata5);

      var ctxp6 = $("#pieChartDemo6").get(0).getContext("2d");
      var pieChartDemo6 = new Chart(ctxp6).Pie(pdata6);

      var ctxp7 = $("#pieChartDemo7").get(0).getContext("2d");
      var pieChartDemo7 = new Chart(ctxp7).Pie(pdata7);

      var ctxp8 = $("#pieChartDemo8").get(0).getContext("2d");
      var pieChartDemo8 = new Chart(ctxp8).Pie(pdata8);

    </script>
    <!-- Google analytics script-->
    <script type="text/javascript">
      if(document.location.hostname == 'pratikborsadiya.in') {
      	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      	ga('create', 'UA-72504830-1', 'auto');
      	ga('send', 'pageview');
      }
    </script>
    <!-- Logout Prompt -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript">
        function logOut(dev_id) {
            swal({
                title: "Are you sure?",
                text: "You will logout in this session",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, logout now!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                if (isConfirm) {
                  window.location.href = 'php/logout.php';
                }
            });
        };
    </script>
<script>
  function printCharts() {
    console.log("Printing..");
    var printWindow = window.open('', 'Print');
    
    printWindow.document.write('<html><head><title>Statistics PDF</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"></head><body style="display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px;">');
    
    // Iterate through each chart and add the heading along with the chart image
    var charts = document.getElementById('chartss').getElementsByClassName('tile');
    for (var i = 0; i < charts.length; i++) {
      var chartTitle = charts[i].querySelector('.tile-title').innerText;
      printWindow.document.write('<div style="text-align: center;">');
      printWindow.document.write('<hr style="width: 100%; height: 20px;">');
      printWindow.document.write('<h3 class="tile-title">' + chartTitle + '</h3>');

      var imgData = charts[i].querySelector('canvas').toDataURL(); // Convert canvas to data URL
      printWindow.document.write('<img src="' + imgData + '" class="img-fluid" style="width: 80% !important;">');
      printWindow.document.write('</div>');
    }

    // Add legends (2 by 2 rows)
    printWindow.document.write('<div class="legend-box" style="display: grid; grid-template-rows: repeat(5, 1fr); grid-template-columns: repeat(2, 1fr); grid-gap: 10px;">');

    // Legends
    var legendColors = ['#F7464A', '#ccad51', '#ffa500', '#d5a6bd', '#cf5c36', '#dc6ba2', '#023e8a', '#b6d7a8', '#ffd966', '#76a5af'];
    var legendTitles = ['Total Applicants', 'BSE', 'BSINFOTECH', 'BSHM', 'BSCOS', 'BSBM', 'BSIT', 'BSCPE', 'BSEE', 'BTVTED'];

    for (var j = 0; j < legendColors.length; j++) {
      printWindow.document.write('<div class="legend-item" style="color: ' + legendColors[j] + ';">');
      printWindow.document.write('■ ' + legendTitles[j] + '<br>');
      printWindow.document.write('</div>');
    }

    printWindow.document.write('</div>'); // Close legend-box

    printWindow.document.write('</body></html>');

    setTimeout(() => {
      printWindow.print();
      printWindow.close();
    }, 150);
  }
</script>

  </body>
</html>