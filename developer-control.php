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
  $query4 = "SELECT * FROM application_on_off";
  $result4 = mysqli_query($con, $query4);
  $row4 = mysqli_fetch_assoc($result4);

  $enable_disable = $row4['enable_disable'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Control Page</title>
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
    <header id="hNav" style="background: rgb(255, 255, 255); z-index: 9999;" class="app-header"><a id="bLogo" class="app-header__logo" href="developer-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
    <aside id="sNav" style="background: rgb(255, 255, 255); z-index: 9998;" class="app-sidebar">
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>">
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
          <h1><i class="fa fa-file-code-o"></i> Control Page</h1>
          <p>Control the logic</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item active"><a href="#">Control Page</a></li>
        </ul>
      </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body"> 
                    <?php
                      include("php/connection.php");

                      // Process form submission
                      if (isset($_POST['submit'])) {
                          // Get selected semester
                          $semester = $_POST['semester'];

                          // Update semester in semester_checker table
                          $updateSemesterQuery = "UPDATE semester_checker SET sem='$semester'";
                          mysqli_query($con, $updateSemesterQuery);

                          // Set timezone to Asia/Manila
                          date_default_timezone_set('Asia/Manila');

                          // Get current year
                          $currentYear = date("Y");

                          // Calculate next year for school year
                          $nextYear = $currentYear + 1;
                          $schoolYear = $currentYear . '-' . $nextYear;

                          // Fetch year from year_tbl table
                          $fetchYearQuery = "SELECT * FROM year_tbl";
                          $yearResult = mysqli_query($con, $fetchYearQuery);
                          $yearRow = mysqli_fetch_assoc($yearResult);
                          $storedYear = $yearRow['year'];

                          // Check if the stored year is different from the current school year
                          if ($storedYear !== $schoolYear) {
                              // Update year in year_tbl table
                              $updateYearQuery = "UPDATE year_tbl SET year='$schoolYear'";
                              mysqli_query($con, $updateYearQuery);
                          }
                      }

                      // Fetch semester from semester_checker table
                      $fetchSemesterQuery = "SELECT * FROM semester_checker";
                      $semesterResult = mysqli_query($con, $fetchSemesterQuery);
                      $semesterRow = mysqli_fetch_assoc($semesterResult);
                      $sem = $semesterRow['sem'];

                      // Get current year
                      $currentYear = date("Y");

                      // Calculate next year for school year
                      $nextYear = $currentYear + 1;
                      $schoolYear = $currentYear . '-' . $nextYear;

                      // Calculate two years ago and two years ahead
                      $twoYearsAgo = $currentYear - 2;
                      $twoYearsAhead = $currentYear + 2;
                    ?>

                    <form id="myForm" method="post">
                        <label for="">Change semester.</label><br>
                        <small><i><i class="fa fa-question-circle" aria-hidden="true"></i> for automatic computation of grades in students accounts.</i></small>
                        <select class="form-control col-md-2" name="semester">
                            <option disabled selected>Select</option>
                            <option value="First Semester" <?php echo ($sem == "First Semester" ? ' selected' : '') ?>>First Semester</option>
                            <option value="Second Semester" <?php echo ($sem == "Second Semester" ? ' selected' : '') ?>>Second Semester</option>
                            <option value="Midyear" <?php echo ($sem == "Midyear" ? ' selected' : '') ?>>Midyear</option>
                        </select><br>

                        <label for="">School year</label><br>
                        <select class="form-control col-md-2" name="year">
                            <?php for ($year = $twoYearsAgo; $year <= $twoYearsAhead; $year++) : ?>
                                <?php
                                $fetchYearQuery = "SELECT * FROM year_tbl";
                                $yearResult = mysqli_query($con, $fetchYearQuery);
                                $yearRow = mysqli_fetch_assoc($yearResult);
                                $storedYear = $yearRow['year'];
                                $schoolYearOption = $year . '-' . ($year + 1);
                                ?>
                                <option value="<?php echo $schoolYearOption; ?>" <?php echo ($storedYear == $schoolYearOption ? 'selected' : ''); ?>>
                                    <?php echo $schoolYearOption; ?>
                                </option>
                            <?php endfor; ?>
                        </select><br>


                        <button type="submit" class="btn btn-primary" name="submit">Save</button>
                    </form>

                    </div>
                  </div>
                  <div class="tile">
                    <div class="tile-body"> 
                      <form id="myForm">
                        <label for="">Turn submission of application on/off</label><br>
                        <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
                        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
                      
                        <div class="switch" onclick="saveData()" style="width: 5%;">
                            <label>
                                <input id="toggleButton" type="checkbox" <?php if($enable_disable ==1){echo "checked";} else if($enable_disable ==0){echo "unchecked";} ?> data-toggle="toggle"  data-size="sm">
                                <span class="lever"></span>
                            </label>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="tile">
                    <div class="tile-body"> 
                      <form id="myForm_stars">
                        <label for="">Choose the feedback based on stars you like to display in the landing page.</label><br>
                        <div>
                          <?php
                            include("php/connection.php");
                            $query = "SELECT number_stars FROM choose_stars_tbl";
                            $result = mysqli_query($con, $query);
                            $row = mysqli_fetch_assoc($result);

                            $number_stars = $row['number_stars'];
                          ?>
                          <select class="form-control col-md-2" name="number_stars">
                            <opgroup>
                              <option disabled selected>Show Single Rating</option>
                              <option value="1" <?php echo ($number_stars == "1" ? ' selected' : '') ?>>1 star</option>
                              <option value="2" <?php echo ($number_stars == "2" ? ' selected' : '') ?>>2 stars</option>
                              <option value="3" <?php echo ($number_stars == "3" ? ' selected' : '') ?>>3 stars</option>
                              <option value="4" <?php echo ($number_stars == "4" ? ' selected' : '') ?>>4 stars</option>
                              <option value="5" <?php echo ($number_stars == "5" ? ' selected' : '') ?>>5 stars</option>
                            </opgroup>
                            <opgroup>
                              <option disabled selected>Show Multiple Rating</option>
                              <option value="1-2" <?php echo ($number_stars == "1-2" ? ' selected' : '') ?>>1-2 stars</option>
                              <option value="2-3" <?php echo ($number_stars == "2-3" ? ' selected' : '') ?>>2-3 stars</option>
                              <option value="3-4" <?php echo ($number_stars == "3-4" ? ' selected' : '') ?>>3-4 stars</option>
                              <option value="4-5" <?php echo ($number_stars == "4-5" ? ' selected' : '') ?>>4-5 stars</option>
                              <option value="all" <?php echo ($number_stars == "all" ? ' selected' : '') ?>>All</option>
                            </opgroup>
                          </select><br>
                          <button type="submit" class="btn btn-primary" name="starsubmit">Save</button>
                        </div>
                      </form>
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
      $(document).ready(function() {
          $('#myForm').submit(function(e) {
              e.preventDefault();

              var selectedSemester = $('#myForm select[name="semester"]').val();
              var selectedYear = $('#myForm select[name="year"]').val();

              $.ajax({
                  type: 'POST',
                  url: 'php/switch.php',
                  data: { 
                      semester: selectedSemester,
                      year: selectedYear
                  },
                  success: function(response) {
                      // Handle the response from switch.php if needed
                      console.log(response); // You can log the response to the browser console

                      // Display SweetAlert if the response is successful
                      if (response === 'success') {
                          swal({
                              type: 'success',
                              title: 'Success!',
                              text: 'Semester computation has been switched successfully.',
                              showCancelButton: false,
                              confirmButtonText: "OK",
                              closeOnConfirm: false,
                              closeOnCancel: false
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
    <script>
      $(document).ready(function() {
          $('#myForm2').submit(function(e) {
              e.preventDefault();

              var selectedPastYear = $('#myForm2 select[name="past_year"]').val();

              $.ajax({
                  type: 'POST',
                  url: 'php/switch_past_years.php',
                  data: { 
                      past_year: selectedPastYear,
                  },
                  success: function(response) {
                      // Handle the response from switch.php if needed
                      console.log(response); // You can log the response to the browser console

                      // Display SweetAlert if the response is successful
                      if (response === 'success') {
                          swal({
                              type: 'success',
                              title: 'Success!',
                              text: 'Past year documents has been generated successfully.',
                              showCancelButton: false,
                              confirmButtonText: "OK",
                              closeOnConfirm: false,
                              closeOnCancel: false
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
    <script>
      $(document).ready(function() {
          $('#myForm_stars').submit(function(e) {
              e.preventDefault();

              var selectedStars = $('#myForm_stars select[name="number_stars"]').val();

              $.ajax({
                  type: 'POST',
                  url: 'php/switch_stars.php',
                  data: { 
                      stars: selectedStars,
                  },
                  success: function(response) {
                      // Handle the response from switch.php if needed
                      console.log(response); // You can log the response to the browser console

                      // Display SweetAlert if the response is successful
                      if (response === 'success') {
                          swal({
                              type: 'success',
                              title: 'Success!',
                              text: selectedStars + ' star(s) have been switch to be displayed successfully.',
                              showCancelButton: false,
                              confirmButtonText: "OK",
                              closeOnConfirm: false,
                              closeOnCancel: false
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
   <script>
      // Function to save data (similar to your existing saveData function)
      function saveData() {
        var toggleValue = document.getElementById('toggleButton').checked ? 0 : 1;

        fetch('php/appswitch.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ toggleValue: toggleValue }),
        })
            .then(response => response.json())
            .then(data => {
                // Handle the response from the server
                swal({
                    type: 'success',
                    title: 'Success!',
                    text: 'Application has been switched successfully.',
                    showCancelButton: false,
                    confirmButtonText: "OK",
                    closeOnConfirm: false,
                    closeOnCancel: false
                });
            })
            .catch((error) => {
                console.error('Error:', error);
            });
      }
    </script>
    
  </body>
</html>