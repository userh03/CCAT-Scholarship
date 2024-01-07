<?php
session_start();

include("php/connection.php");

// check if session is not set
if (!isset($_SESSION['a_id'])) {
    header("location: index.php");
    exit();
}

$a_id = $_SESSION['a_id'];
$query = "SELECT * FROM admin_tbl WHERE a_id = '$a_id'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$a_fname = $row['a_fname'];
$a_lname = $row['a_lname'];
$username = $row['username'];
$department = $row['department'];
$profile_picture = $row['profile_picture'];
$isValid = $row['isValid'];

// Function to check and modify the department
function modifyDepartment($department)
{
    if ($department === "Department օf Computer Studies") {
        $department = "DCS - Teacher";
    }
    else if ($department === "Department օf Engineering"){
        $department = "DE - Teacher";
    }
    else if ($department === "Department օf Industrial Technology"){
        $department = "DIT - Teacher";
    }
    else if ($department === "Department օf Management Studies"){
        $department = "DMS - Teacher";
    }
    else if ($department === "Department օf Teacher Education"){
        $department = "DTE - Teacher";
    }
    return $department;
}

// Modify the department if necessary
$department = modifyDepartment($department);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Account Settings - Scholarship</title>
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
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script>
        if (<?php echo $isValid; ?> === 0) {
            swal({
                title: "You have been disconnected",
                text: "You will logout in this session",
                type: "warning",
                showCancelButton: false,
                confirmButtonText: "Okay",
                cancelButtonText: "",
                closeOnConfirm: true,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                  window.location.href="index.php";
                }
            });
        }
    </script>
    <!-- Navbar-->
    <header id="hNav" style="background: rgb(255, 255, 255); z-index: 9999;" class="app-header"><a id="bLogo" class="app-header__logo" href="coordinator-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
          }        /* Remove arrow spinners for number input */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }

        input[type="number"] {
          -moz-appearance: textfield; /* Firefox */
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
                  window.location.href = 'coordinator-tables.php';
                }
              },
              });
            }
          </script>
            <!-- User Menu-->
          <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
              <li><a class="dropdown-item" href="coordinator-page-user.php"><i class="fa fa-user fa-lg"></i> Profile</a></li>
              <li><a class="dropdown-item" href="user-settings-coordinator.php"><i class="fa fa-cog fa-lg"></i> Account Settings</a></li>
              <li><a class="dropdown-item" onclick="logOut();" href="#"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
          </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside id="sNav" style="background: rgb(255, 255, 255); z-index: 9998;" class="app-sidebar">
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>" alt="">
        <div style="color:black;">
        <?php
            $truncated_name = $a_fname;

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
          <p class="app-sidebar__user-name"><?php echo $designation_name." ". $a_lname ?></p>
          <p class="app-sidebar__user-designation"><?php echo $department ?> <br> Coordinator</p>
        </div>
      </div>
      <?php include 'php/coordinator-navbar.php'; ?>
    </aside>
    <main class="app-content">
      <div class="app-title">
        <div id="divProfile">
          <h1 id="homee"><i class="fa fa-cog"></i> Account Settings</h1>
          <p></p>
        </div>
        <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="coordinator-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#">Account Settings</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile" style="margin: auto; justify-content: center;">
            <div class="tile-body">
              <div class="container">
                <div class="row justify-content-center">
                  <div class="col-md-8 mb-4">
                    <h3 class="card-title text-center">Change Password</h3>
                    
                    <label>Username</label>
                    <input class="form-control" type="text" disabled value="<?php echo $username ?>">
                  </div>

                  <form id="passwordChangeForm" class="col-md-8 mb-4">
                    <div class="form-group">
                      <label for="password">New Password</label>
                      <div class="input-group">
                        <input class="form-control" type="password" name="password" onkeyup="checkPassword()" id="password" required placeholder="Password">
                        <div class="input-group-append">
                          <span class="input-group-text password-toggle-icon" onclick="togglePassword('password', 'togglePasswordIcon')">
                            <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                          </span>
                        </div>
                      </div>
                      <div id="passwordCheck" class="mt-2"></div>
                    </div>

                    <div class="form-group">
                      <label for="confirmPassword">Confirm Password</label>
                      <div class="input-group">
                        <input type="password" name="confirm_password" class="form-control" id="confirmPassword" onkeyup="validatePassword()" required placeholder="Confirm your new password">
                        <div class="input-group-append">
                          <span class="input-group-text password-toggle-icon" onclick="togglePasswordVisibility('confirmPassword', 'showConfirmPasswordBtn')">
                            <i class="fa fa-eye-slash" id="showConfirmPasswordBtn"></i>
                          </span>
                        </div>
                      </div>
                      <div id="passwordError" class="mt-2"></div>
                    </div>

                    <button class="btn btn-primary" type="button" disabled id="submitButton" onclick="submitForm()">
                      <i class="fa fa-fw fa-lg fa-check-circle"></i> Save
                    </button>
                  </form>
                </div>
              </div>
            <style>
              /* Media query for mobile view */
              @media screen and (max-width: 767px) {
                #divProfile {
                  display: none;
                }
              }
              .accountform {
                margin: auto;
                max-width: 400px; /* Adjust the maximum width as needed */
              }

              .form-control {
                width: 100%;
                box-sizing: border-box;
                margin-bottom: 10px;
              }
              .password-toggle-icon{
                cursor: pointer;
              }
              @media only screen and (max-width: 600px) {
                /* Adjust styles for devices with a maximum width of 600 pixels */
                .accountform {
                  max-width: 100%;
                  padding-top: 50px !important;
                  padding-bottom: 30px !important;
                }
              }
               /* Disable password toggle in all browsers */
                input[type="password"]::-ms-reveal,
                input[type="password"]::-ms-clear {
                    display: none;
                }

                input[type="password"]::-webkit-reveal,
                input[type="password"]::-webkit-clear-button {
                    display: none !important;
                }

                /* Disable password toggle in Microsoft Edge */
                input[type="password"]::-ms-reveal {
                    display: none !important;
                }
            </style>
            </div>
              <style>
                footer {
                  padding: 40px 0;
                  width: 100%;
                }

                footer h3 {
                  color: #333;
                  font-size: 18px;
                  margin-top: 40px;
                  margin-bottom: 20px;
                }

                footer ul {
                  list-style: none;
                  margin: 0;
                  padding: 0;
                }

                footer ul li {
                    margin-bottom: 10px;
                }

                footer ul li a {
                    color: #666;
                }

                footer ul.social-media {
                    display: flex;
                    justify-content: center;
                }

                footer ul.social-media li {
                    margin-right: 10px;
                }

                footer ul.social-media li:last-child {
                    margin-right: 0;
                }

                footer ul.social-media li a {
                    color: #666;
                    font-size: 24px;
                }

                footer ul.social-media{
                  margin-left: -240px;
                }

                footer ul.social-media li a:hover {
                    color: #333;
                }

                footer hr {
                    border-color: #ddd;
                    margin-top: 30px;
                    margin-bottom: 30px;
                }

                footer p {
                    color: #666;
                    font-size: 14px;
                    margin: 0;
                    text-align: center;
                }

              </style>
          </div>
        </div>
      </div>
            <!-- Modal 2 -->
            <div class="modal fade" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal 2 Header -->
      <div class="modal-header">
        <h4 class="modal-title">Frequently Asked Questions</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal 2 Body -->
      <div class="modal-body">
        <style>
          .card-body {
            font-size: 18px !important;
            text-align: justify;
          }

          .card-header button {
            font-size: 18px !important;
          }
          /* Add CSS to change the color of the notification message text to white */
          .notification-white-text .notify-message {
            color: white;
          }
          /* Add CSS to adjust the width of the notifications on mobile view */
          .notification-mobile-width {
            width: 80%;
          }

          /* Media query for mobile devices */
          @media (max-width: 767px) {
            .notification-mobile-width {
              width: 80% !important;
            }
          }

        </style>
        <div id="faqAccordion">
        <?php
              include("php/connection.php");
              $query = "SELECT * FROM faq_tbl";
              $result = mysqli_query($con, $query);

              if (mysqli_num_rows($result) > 0) {
                  $modalCount = 0; // Variable to track the modal identifiers
                  while ($row = mysqli_fetch_assoc($result)) {
                      $id = $row['id'];
                      $answer = $row['answer'];
                      $question = $row['question'];

                      ?>
                      <div class="card">
                        <div class="card-header" id="question1">
                          <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#answer<?php echo $id ?>" aria-expanded="false" aria-controls="answer<?php echo $id ?>">
                              <?php echo $question ?>
                            </button>
                          </h5>
                        </div>
                        <div id="answer<?php echo $id ?>" class="collapse" aria-labelledby="question<?php echo $id ?>" data-parent="#faqAccordion">
                          <div class="card-body">
                            <?php echo $answer ?>
                          </div>
                        </div>
                      </div>
                      <?php
                  }
              } else {
                  echo "No data found.";
              }
              ?>
        </div>
      </div>

      <!-- Modal 2 Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
  <!-- Modal 3 -->
  <div class="modal fade" id="myModal3">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal 3 Header -->
        <div class="modal-header">
          <h3 class="modal-title">About</h3>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal 3 Body -->
        <div class="modal-body">
        <h4 style="color: darkgreen; text-align:center;">University Vision</h4>
        <hr style="background: darkgreen; width: 50%; height: 5px;">
        <p style="text-align: center; font-size: 20px;">The Premier University in historic Cavite recognized for excellence in the development of globally competitive and morally upright individuals.</p>
        
        <h4 style="color: darkgreen; text-align:center;">University Mission</h4>
        <hr style="background: darkgreen; width: 50%; height: 5px;">
        <p style="text-align: center; font-size: 20px;">CAVITE STATE UNIVERSITY shall provide excellent, equitable and relevant educational opportunities in the arts, sciences, and technology through quality instruction and responsive research and development activities. It shall produce professional skilled and morally upright individuals for global competitiveness.</p>
        
        <h4 style="color: darkgreen; text-align: center;">Objectives</h4>
        <hr style="background: darkgreen; width: 50%; height: 5px;">
          <p style="text-align: justify; font-size: 20px;">
          • To attract the best and brightest secondary education graduates to study in CvSU.<br>
          • To provide scholarship and financial assistance to qualified and underprivileged individuals.<br>
          • To enable the CvSU benefactors, donors, alumni, and friends to share their resources in helping and provide education opportunities to deserving and capable students
              </p>
        </div>

        <!-- Modal 3 Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>
  </div>
    <footer>
      <div class="container" style="margin: auto;">
          <div class="row">
              <div class="col-md-4">
                  <h3>Contact Us</h3>
                  <ul>
                      <li>CV38+C99, EM's Barrio, <br>Barangay Tejeros Convention</li>
                      <li>Rosario, Cavite 4106</li>
                      <li>Phone: (046) 437-9505 to 9508</li>
                      <li>Website: <a href="https://cvsu-rosario.edu.ph" target="_blank">cvsu-rosario.edu.ph</a></li>
                  </ul>
              </div>
              <div class="col-md-4">
                  <h3>Site Map</h3>
                  <ul>
                      <li><a data-toggle="modal" data-target="#myModal3" href="#">About Us</a></li>
                      <li><a data-toggle="modal" data-target="#myModal2" href="#">FAQ</a></li>
                  </ul>
              </div>
              <div class="col-md-4">
                  <h3>Follow Us</h3>
                  <ul class="social-media">
                      <li><a href="https://www.facebook.com/cvsuccatscholarship" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  </ul>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <hr>
                  <p style="text-align: center;">&copy; 2023 CCAT - CAMPUS. All rights reserved.</p>
              </div>
          </div>
      </div>
      <div class="row">
  
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
    <script type="text/javascript">
        function logOut(a_id) {
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
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/plugins/bootstrap-notify.min.js"></script>

<script>
  function togglePassword(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var togglePasswordIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePasswordIcon.classList.remove("fa-eye-slash");
      togglePasswordIcon.classList.add("fa-eye");
    } else {
      passwordInput.type = "password";
      togglePasswordIcon.classList.remove("fa-eye");
      togglePasswordIcon.classList.add("fa-eye-slash");
    }
  }
</script>
<script>
  function togglePasswordVisibility(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var togglePasswordIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePasswordIcon.classList.remove("fa-eye-slash");
      togglePasswordIcon.classList.add("fa-eye");
    } else {
      passwordInput.type = "password";
      togglePasswordIcon.classList.remove("fa-eye");
      togglePasswordIcon.classList.add("fa-eye-slash");
    }
  }
</script>

<script>
  function toggleConfirmPasswordVisibility() {
    var confirmPasswordInput = document.getElementById("confirmPassword");
    var showConfirmPasswordBtn = document.getElementById("showConfirmPasswordBtn");

    if (confirmPasswordInput.type === "password") {
      confirmPasswordInput.type = "text";
      showConfirmPasswordBtn.innerHTML = '<i class="fa fa-eye"></i>';
    } else {
      confirmPasswordInput.type = "password";
      showConfirmPasswordBtn.innerHTML = '<i class="fa fa-eye-slash"></i>';
    }
  }
</script>

<script>
  function validatePassword() {
    const submitButton = document.getElementById('submitButton');
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const passwordError = document.getElementById("passwordError");

    // Call checkPassword for detailed validation
    const isPasswordValid = checkPassword();

    if (confirmPassword === "") {
      passwordError.innerHTML = '<i></i>';
      submitButton.disabled = true;
      return;
    }

    if (password === confirmPassword && isPasswordValid) {
      passwordError.innerHTML = "<i class='text text-success'>Passwords match.</i>";
      submitButton.disabled = false;
    } else {
      passwordError.innerHTML = "<i class='text text-danger'>Passwords do not match or are invalid.</i>";
      submitButton.disabled = true;
    }
  }

  function checkPassword() {
    const passwordInput = document.getElementById('password');
    const passwordCheck = document.getElementById('passwordCheck');

    // Regular expressions for password validation
    const regexUpperCase = /^(?=.*[A-Z])/;
    const regexLowerCase = /^(?=.*[a-z])/;
    const regexNumber = /^(?=.*[0-9])/;
    const regexSpecialChar = /^(?=.*[!@#$%^&*()\-=_+[\]{};':"\\|,.<>/?~`])/;
    const regexLength = /^(?=.{8,})/;

    const password = passwordInput.value;

    if (password == "") {
      passwordCheck.innerHTML = '<i></i>';
      submitButton.disabled = true;
      return false;
    }

    if (!password.match(regexUpperCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one uppercase letter.</i>';
      submitButton.disabled = true;
      return false;
    }

    if (!password.match(regexLowerCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one lowercase letter.</i>';
      submitButton.disabled = true;
      return false;
    }

    if (!password.match(regexNumber)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one number.</i>';
      submitButton.disabled = true;
      return false;
    }

    if (!password.match(regexSpecialChar)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one special character.</i>';
      submitButton.disabled = true;
      return false;
    }

    if (!password.match(regexLength)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must be at least 8 characters long.</i>';
      return false;
    }

    passwordCheck.innerHTML = '<i class="text-success">Password is valid.</i>';
    return true; // Added to indicate that the password is valid
  }
</script>

<script>
    function submitForm() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        // Perform client-side validation
        if (password === confirmPassword) {
            // AJAX submission
            $.ajax({
                type: 'POST',
                url: 'php/adminupdate-password.php?updateid=<?php echo $a_id ?>',
                data: { 
                    password: password
                },
                success: function(response) {
                    // Handle the response from userupdate-password.php if needed
                    console.log(response); // Log the response to the browser console

                    // Display SweetAlert if the response is successful
                    if (response === 'success') {
                        swal({
                            type: 'success',
                            title: 'Success!',
                            text: 'Password changed.',
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
        } else {
            // Display error if passwords don't match
            swal({
                title: "Password does not match.",
                text: "",
                type: "error",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true,
                closeOnCancel: true
            });
        }
    }
</script>
  </body>
</html>