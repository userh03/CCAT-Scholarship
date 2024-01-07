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

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Accounts Bin</title>
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
      <!-- Sidebar toggle button--><a id="sSide" style="background: white;" class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
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
          <h1><i class="fa fa-th-list"></i> Restore Accounts</h1>
          <p>Shows the deleted accounts</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item active"><a href="#">Account Bin</a></li>
        </ul>
      </div>
      <style>
      /* Media query for screens smaller than 768px (typical mobile screens) */
      @media screen and (max-width: 768px) {
        .iMg {
          margin-left: 40px;
        }
        #bLogo{
          font-size: 0px;;
        }
      }
    </style>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            <button type="button" style="float:right; margin-bottom: 5px;" id="restoreaccount" name="restoreall" class="btn btn-primary" onclick="confirmRestoreBin()">
                Restore all
            </button>  
            <button type="button" style="float:right; margin-bottom: 5px;" id="addstud" name="deleteall" class="btn btn-danger" onclick="confirmEmptyBin()">
                Empty Bin
            </button>  
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTables">
                  <thead>
                  <tr style="font-size: 16px; text-align: center;">
                      <th scope="col">Student ID #</th>
                      <th scope="col">Student Name</th>
                      <th scope="col">Section</th>
                      <th scope="col">Department</th>
                      <th scope="col">Mobile</th>
                      <th scope="col">Email</th>
                      <th scope="col">Operation</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                    $sql = "SELECT *
                            FROM students_tbl
                            WHERE IFNULL(username, '') <> '' AND IFNULL(password, '') <> '' AND isValid = 0";                      
                      $result = mysqli_query($con, $sql);
                      while ($row = mysqli_fetch_assoc($result)){
                        $s_id = $row['s_id'];
                        $student_id_number = $row['student_id_number'];
                        $fname = $row['fname'];
                        $lname = $row['lname'];
                        $section = $row['section'];
                        $department = $row['department'];
                        $mobile = $row['mobile'];
                        $email = $row['email'];

                        echo '<tr style="font-size: 15px;">
                                <td scope = "row"><strong>Student ID #: </strong>'.$student_id_number.'</td>
                                    <td><strong>Student Name: </strong>'.$lname.', '.$fname.'</td>
                                    <td><strong>Section: </strong>'.$section.'</td>
                                    <td><strong>Department: </strong>'.$department.'</td>
                                    <td><strong>Mobile: </strong>'.$mobile.'</td>
                                    <td><strong>Email: </strong>'.$email.'</td>
                                    <td>
                                      <div id="operation-field" style="word-spacing: 5px;">
                                      <button type="button" class="btn btn-primary" style="margin-bottom: 5px;" onclick="restore('.$s_id.')">
                                        Restore
                                      </button>
                                        <button style="margin-bottom: 5px;" class="btn btn-danger" onclick="demoSwal('.$s_id.');" name="delete">Delete</button>
                                      </div>
                                    </td>
                              </tr>';
                      }
                    ?>
                  </tbody>
                </table>
              </div>  
              <style>
                strong{
                  display: none;
                }
                #addstud, #restoreaccount{
                    margin-left: 5px;
                  }
                /* CSS media query for mobile devices */
                @media (max-width: 767px) {
                  #addstud, #restoreaccount{
                    font-size: smaller;
                    margin-left: 5px;
                  }
                  strong{
                    display: inline-block;
                    margin: 0 5px;
                  }
                  #sampleTables tr {
                    display: block;
                    margin-bottom: 15px;
                    border: none;
                  }
                  thead, tr{
                    display: none;
                    width: 100%;
                  }
                  #sampleTables td {
                    display: block;
                    text-align: left;
                  }

                  #sampleTables td:before {
                    content: attr(data-label);
                    float: left;
                    font-weight: bold;
                  }

                  #sampleTables th,
                  #sampleTables td:before {
                    width: 100%;
                  }

                  #sampleTables .modal-dialog {
                    max-width: 100%;
                    margin: 1rem;
                  }
                  .table{
                    margin: auto;
                  }
                }
              </style>              
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
    
    <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTables').DataTable();</script>
    <script>
      $(document).ready(function() {
        var table = $('#sampleTables').DataTable();
        table.destroy();

        // Now you can reinitialize the DataTable
        $('#sampleTables').DataTable({
          searching: false
        });
      });
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

    <!-- Page Delete Prompt-->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript">
        function demoSwal(s_id) {
            swal({
                title: "Are you sure?",
                text: "This data will be permanently deleted.",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: "Deleted!",
                        text: "The data has been deleted.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function() {
                        window.location.href = 'php/devdeletestud.php?deleteid=' + s_id;
                    });
                }
            });
        };
    </script>
    <script type="text/javascript">
        function restore(s_id) {
            swal({
                title: "Are you sure?",
                text: "This data will be restored.",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, restore it!",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: "Restored!",
                        text: "The data has been restored.",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonText: "OK",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function() {
                        window.location.href = 'php/devdeletestud-temp-restore.php?restoreid=' + s_id;
                    });
                }
            });
        };
    </script>
    <script>
        function confirmEmptyBin() {
            swal({
            title: "Are you sure?",
            text: "All data in the account bin will be deleted permanently.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete all!",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
            }, function (isConfirm) {
            if (isConfirm) {
                deleteAllData();
            } 
            });
        }

        function deleteAllData() {
            // Assuming you're using AJAX to call the PHP script
            // You can replace this with your actual PHP script URL
            var phpScriptUrl = 'php/superdeletestud-temp-all.php';

            // Use AJAX to send a request to the PHP script
            $.ajax({
            type: 'POST',
            url: 'php/superdeletestud-temp-all.php',
            data: { deleteall: true }, // Sending the deleteall parameter
            success: function (response) {
                swal({
                title: "Deleted!",
                text: "All data has been deleted.",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true,
                closeOnCancel: true
                }, function () {
                window.location.href = 'developer-rubbish.php';
                });
            },
            error: function () {
                swal("Error", "An error occurred while deleting data.", "error");
            }
            });
        }
    </script>
    <script>
        function confirmRestoreBin() {
            swal({
            title: "Are you sure?",
            text: "All data in the account bin will be restored.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, restore all!",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true,
            confirmButtonClass: "red-confirm-btn"
            }, function (isConfirm) {
            if (isConfirm) {
                restoreAllData();
            } 
            });
        }

        function restoreAllData() {
            // Assuming you're using AJAX to call the PHP script
            // You can replace this with your actual PHP script URL
            var phpScriptUrl = 'php/superrestorestud-temp-all.php';

            // Use AJAX to send a request to the PHP script
            $.ajax({
            type: 'POST',
            url: 'php/superrestorestud-temp-all.php',
            data: { restoreall: true }, // Sending the restoreall parameter
            success: function (response) {
                swal({
                title: "Restored!",
                text: "All data has been restored.",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true,
                closeOnCancel: true
                }, function () {
                window.location.href = 'developer-rubbish.php';
                });
            },
            error: function () {
                swal("Error", "An error occurred while restoring data.", "error");
            }
            });
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
  </body>
</html>