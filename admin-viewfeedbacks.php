<?php
    session_start();

    include("php/connection.php");

    // check if session is not set
    if(!isset($_SESSION['sa_id']))
    {
      header("location: index.php");
      exit();
    }
    
    $sa_id = $_SESSION['sa_id'];
    $query = "SELECT * FROM superadmin_tbl WHERE sa_id = '$sa_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $sa_fname = $row['sa_fname'];
    $sa_lname = $row['sa_lname'];
    $superadmin_id = $row['superadmin_id_number'];
    $sa_building = $row['sa_building'];
    $username = $row['username'];
    $password = $row['password'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $profile_picture = $row['profile_picture'];

?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="shortcut icon" href="images/logo.ico" />
    <title>Feedbacks</title>
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
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a id="bLogo" class="app-header__logo" href="admin-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
                  window.location.href = 'admin-tables.php';
                }
              },
              });
            }
          </script>
            <!-- User Menu-->
          <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
              <li><a class="dropdown-item" href="admin-page-user.php"><i class="fa fa-user fa-lg"></i> Profile</a></li>
              <li><a class="dropdown-item" href="user-settings-admin.php"><i class="fa fa-cog fa-lg"></i> Account Settings</a></li>
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
            $truncated_name = $sa_fname;

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
        <p class="app-sidebar__user-name"><?php echo $designation_name." ". $sa_lname ?></p>
        <?php
            $building = $sa_building;

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
          <p class="app-sidebar__user-designation"><?php echo $designation ?> - Administrator</p>
        </div>
      </div>
      <?php include 'php/admin-navbar.php' ?>
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
            <h1 id="homee"><i class="fa fa-commenting"></i> Feedbacks</h1>
            <p>View Applicants Feedbacks</p>
          </div>
          <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="admin-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="#">View Feedbacks</a></li>
          </ul>
        </div>
      <div>
      <div>
        <div class="tile">
          <div class="tile-body">
          <h5 style="display: inline-block; margin: 0 5px;">Filter</h5>
            <style>
              #demoSelect {
                width: 100%;
                padding: 5px; /* Adjust the padding as needed */
                box-sizing: border-box; /* Ensure padding is included in the width */
              }

              @media (min-width: 768px) {
                #demoSelect {
                  width: 700px;
                }
              }
            </style>
              <select style="display: inline-block; margin: 0 5px;" class="form-control" id="demoSelect">
                <optgroup label="Rating">
                  <option value="All">All</option>
                  <option value="5">5 stars</option>
                  <option value="4">4 stars</option>
                  <option value="3">3 stars</option>
                  <option value="2">2 stars</option>
                  <option value="1">1 star</option>
                </optgroup>
              </select><br><br>
          <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTables">
                  <thead>
                    <tr style="font-size: 16px; text-align: center;">
                      <th scope="col">Name</th>
                      <th scope="col">Message</th>
                      <th scope="col">Year</th>
                      <th scope="col">Rating</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                      $sql = "SELECT * FROM feedbacks_tbl";
                      $result = mysqli_query($con, $sql);

                      while ($row = mysqli_fetch_assoc($result)) {
                          $id = $row['id'];
                          $f_student_number = $row['f_student_number'];
                          $fname = $row['fname'];
                          $lname = $row['lname'];
                          $message = $row['message'];
                          $year_submitted = $row['year_submitted'];
                          $number_stars = $row['number_stars'];

                          // Masking the characters between the first and last letters of fname and lname with asterisks
                          $masked_fname = substr($fname, 0, 1) . str_repeat('*', strlen($fname) - 1);
                          $masked_lname = substr($lname, 0, 1) . str_repeat('*', strlen($lname) - 1);

                          // Truncate the message to 20 words
                          $words = str_word_count($message, 1);
                          $truncated_message = implode(' ', array_slice($words, 0, 10));

                          echo '<tr style="font-size: 15px;">
                              <td><strong>Name:</strong>' . $masked_lname . ', ' . $masked_fname . '</td>
                              <td scope="row"><strong>Message:</strong>' . $truncated_message . '</td>
                              <td scope="row"><strong>Year:</strong>' . $year_submitted . '</td>
                              <td scope="row"><strong>Rating:</strong>' . $number_stars . ' <i class="fa fa-star"></i></td>
                              <td>
                                  <div id="operation-field" style="word-spacing: 5px;">
                                      <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#viewModal' . $id . '">
                                          View
                                      </button>
                                      <button class="btn btn-danger" type="button">
                                        <i class="fa fa-trash-o" style="margin: auto;" onclick="deleteFeedback('.$id.')" aria-hidden="true"></i>
                                      </button>

                                      <!-- Modal -->
                                      <div class="modal fade" id="viewModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel<?= $id ?>" aria-hidden="true">
                                          <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4 class="modal-title" id="viewModalLabel<?= $id ?>">Feedback Details</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                      </button>
                                                  </div>
                                                  <div class="modal-body">
                                                      <!-- Your modal content goes here -->
                                                      <p style="text-align: justify; float: left;"> '.$message.'</p>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                      <!-- Additional buttons or actions can be added here -->
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </td>
                          </tr>';
                      }
                      ?>
                  </tbody>
                </table>  
              </div> 
              <div id="noDataMessage" style="display: none; text-align: center; font-size: 18px; margin-top: -30px;">
                No data available
              </div>
              <style>
                strong{
                  display: none;
                }
                /* CSS media query for mobile devices */
                @media (max-width: 767px) {
                  #apprv, #dnd{
                    font-size: smaller;
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
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTables').DataTable();</script>
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
    <script>
      $(document).ready(function () {
        // Handle change event of the select dropdown
        $('#demoSelect').change(function () {
          var selectedRating = $(this).val();

          // Hide the "No data available" message initially
          $('#noDataMessage').hide();

          // Show all rows if "All" is selected
          if (selectedRating === 'All') {
            $('#sampleTables tbody tr').show();
          } else {
            // Hide all rows
            $('#sampleTables tbody tr').hide();

            // Show only rows with the selected rating
            var matchingRows = $('#sampleTables tbody tr').filter(function () {
              return $('td:nth-child(4)', this).text().includes(selectedRating);
            });

            // If matching rows found, show them; otherwise, display the "No data available" message
            if (matchingRows.length > 0) {
              matchingRows.show();
            } else {
              $('#noDataMessage').show();
            }
          }
        });
      });
    </script>
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script src="js/plugins/bootstrap-notify.min.js"></script>
    <!-- Logout Prompt -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript">
        function logOut(sa_id) {
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
<script type="text/javascript">
    function deleteFeedback(id) { 
        swal({
            title: "Are you sure?",
            text: "This feedback will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                // Use Ajax to send a request to the PHP script
                $.ajax({
                    url: 'php/deletefeedbacks.php',
                    type: 'POST',
                    data: { deleteid: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: "Deleted!",
                                text: "The data has been deleted.",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonText: "OK",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            }, function() {
                                // Redirect or perform any other actions
                                window.location.reload();
                            });
                        } else {
                            swal("Error", "An error occurred while deleting the data.", "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Failed to communicate with the server.", "error");
                    }
                });
            }
        });
    }
</script>

  </body>
</html>