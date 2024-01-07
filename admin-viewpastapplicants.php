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
    $sa_building = $row['sa_building'];
    $profile_picture = $row['profile_picture'];
?>
<?php 
  $query3 = "SELECT * FROM year_tbl";
  $result3 = mysqli_query($con, $query3);
  $row3 = mysqli_fetch_assoc($result3);

  $pyear = $row3['past_year'];
?>
<?php 
  $query2 = "SELECT * FROM section_courses";
  $result2 = mysqli_query($con, $query2);
  $row2 = mysqli_fetch_assoc($result2);

  $s_course = $row2['s_course'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Past Applicant List</title>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  </head>
  <body class="app sidebar-mini">
    <!-- Navbar-->
    <header id="hNav" style="background: rgb(255, 255, 255); z-index: 9999;" class="app-header"><a id="bLogo" class="app-header__logo" href="admin-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
    <aside id="sNav" style="background: rgb(255, 255, 255); z-index: 9998;" class="app-sidebar">
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>">
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
              #pastYearSelect, #courseSelect {
                  display: inline-block;
                  margin-right: 10px; /* Optional: Add some space between the selects */
              }
          </style>          <h1><i class="fa fa-th-list"></i> Past Applicants List</h1>
          <p>List of Past Applicants</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="admin-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item active"><a href="#">Past Applicants List</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">           
            <?php
              $phpVersion = phpversion();

              if (version_compare($phpVersion, '7.4.8', '<=')) {
                  echo '<button onclick="generateCSV()" id="apprv" style="float:right; margin-bottom: 5px;" class="btn btn-primary">Generate CSV</button>';
              } elseif (version_compare($phpVersion, '8.2.0', '>=')) {
                  echo '<button onclick="generateExcel()" id="apprv" style="float:right; margin-bottom: 5px;" class="btn btn-primary">Generate Excel</button>';
              } else {
                  echo "Unsupported PHP version";
              }
            ?>

<div id="filter2">
    <form id="myForm" style="display: flex; flex-wrap: wrap; align-items: center;">
        <h5 style="margin: 0 5px;">Filter</h5>

        <?php
        include("php/connection.php");
        $query = "SELECT * FROM year_tbl";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);

        $past_year = $row['past_year'];
        ?>
        <?php
        $currentYear = date("Y");
        ?>

        <select id="pastYearSelect" class="form-control col-md-2" name="past_year">
            <option disabled selected>Select</option>
            <?php for ($i = 0; $i <= 9; $i++) { // Reverse the loop to start from 0 ?>
                <?php
                $startYear = $currentYear - $i;
                $endYear = $startYear + 1;
                $yearRange = $startYear . '-' . $endYear;
                $isSelected = ($past_year == $yearRange) ? ' selected' : '';
                ?>
                <option value="<?php echo $yearRange; ?>" <?php echo $isSelected; ?>>
                    <?php echo $yearRange; ?>
                </option>
            <?php } ?>
        </select>

        <select id="courseSelect" class="form-control col-md-2" name="s_course">
            <?php
            $courses = array("BSINFOTECH", "BSCOS", "BSIT", "BSEE", "BSE", "BSHM", "BSBM", "BSCPE", "BTVTED");

            foreach ($courses as $course) {
                $isSelected = ($s_course == $course) ? ' selected' : '';
                echo "<option value=\"$course\" $isSelected>$course</option>";
            }
            ?>
        </select>

        <button type="submit" class="btn btn-primary" id="submitButton" name="submit">Save</button>
    </form>
</div>


              </div>
              <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTables">
                  <thead>
                    <tr style="font-size: 16px; text-align: center;">
                      <th scope="col">Applicant Student ID #</th>
                      <th scope="col">Applicant Name</th>
                      <th scope="col">Applicant Mobile</th>
                      <th scope="col">Applicant Email</th>
                      <th scope="col">Applicant Section</th>
                      <th scope="col">Scholar Type</th>
                      <th scope="col">Applicant Department</th>
                      <th scope="col">Applicant Adviser</th>
                      <th scope="col">Year</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                      $sql = "SELECT * FROM approved_applicants_tbl
                              WHERE app_year = '$pyear' AND app_status = 'Approved' AND app_section LIKE '%$s_course%'
                              UNION
                              SELECT * FROM denied_applicants_tbl
                              WHERE app_year = '$pyear' AND app_status = 'Denied' AND app_section LIKE '%$s_course%'";
                      $result = mysqli_query($con, $sql);
                      while ($row = mysqli_fetch_assoc($result)){
                          $app_id = $row['app_id'];
                          $app_student_number = $row['app_student_number'];
                          $app_fname = $row['app_fname'];
                          $app_lname = $row['app_lname'];
                          $app_mobile = $row['app_mobile'];
                          $app_email = $row['app_email'];
                          $app_section = $row['app_section'];
                          $app_scholar_type = $row['app_scholar_type'];
                          $app_adviser = $row['app_adviser'];
                          $app_department = $row['app_department'];
                          $app_status = $row['app_status'];
                          $year = $row['app_year'];
                                            
                          echo '<tr style="font-size: 15px;">
                              <td scope="row"><strong>Applicant Student ID #:</strong>'.$app_student_number.'</td>
                              <td><strong>Applicant Name:</strong>'.$app_lname.', '.$app_fname.'</td>
                              <td><strong>Applicant Mobile:</strong>'.$app_mobile.'</td>
                              <td><strong>Applicant Email:</strong>'.$app_email.'</td>
                              <td><strong>Applicant Section:</strong>'.$app_section.'</td>
                              <td><strong>Scholar Type:</strong>'.$app_scholar_type.'</td>
                              <td><strong>Applicant Department:</strong>'.$app_department.'</td>
                              <td><strong>Applicant Adviser:</strong>'.$app_adviser.'</td>
                              <td><strong>Year:</strong><span>'.$year.'</span></td>
                              <td><strong>Application Status:</strong><span style="font-weight: 700;">'.$app_status.'</span></td>
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
    <script type="text/javascript">$('#sampleTables').DataTable();</script>
    <script>
      $(document).ready(function() {
        var table = $('#sampleTables').DataTable();
        table.destroy();

        // Now you can reinitialize the DataTable
        $('#sampleTables').DataTable({
          searching: true
        });
      });
    </script>
     <script>
      $(document).ready(function() {
          $('#myForm').submit(function(e) {
              e.preventDefault();

              var selectedPastYear = $('#myForm select[name="past_year"]').val();
              var selectedCourse = $('#myForm select[name="s_course"]').val();

              $.ajax({
                  type: 'POST',
                  url: 'php/switch_past_years.php',
                  data: { 
                      past_year: selectedPastYear,
                      s_course: selectedCourse
                  },
                  success: function(response) {
                      // Handle the response from switch.php if needed
                      console.log(response); // You can log the response to the browser console

                      // Display SweetAlert if the response is successful
                      if (response === 'success') {
                          swal({
                              type: 'success',
                              title: 'Success!',
                              text: 'Data has been generated successfully.',
                              showCancelButton: false,
                              confirmButtonText: "OK",
                              closeOnConfirm: false,
                              closeOnCancel: false
                          }, function (isConfirm){
                            if (isConfirm){
                              location.reload();
                            }
                          });
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error(error); // Log any errors to the console
                  }
              });
          });
      });
    </script>
    <!-- Page specific javascripts-->
    <!-- Data table plugin-->
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
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
    <script>
        function generateCSV() {
            window.location.href = 'php/csv_generate_all.php';
        }

        function generateExcel() {
            window.location.href = 'php/generate_report_all.php';
        }
    </script>
  </body>
</html>