<?php
    session_start();

    include("php/connection.php");

    // check if session is not set
    if(!isset($_SESSION['s_id']))
    {
        header("location: index.php");
        exit();
    }

        $s_id = $_SESSION['s_id'];
        $query = "SELECT * FROM students_tbl WHERE s_id = '$s_id'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $fname = $row['fname'];
        $lname = $row['lname'];
        $section = $row['section'];
        $department = $row['department'];
        $mobile = $row['mobile'];
        $email = $row['email'];
        $username = $row['username'];
        $student_id_number = $row['student_id_number'];
        $profile_picture = $row['profile_picture'];
        $isValid = $row['isValid'];

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
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a href="user.php" id="bLogo" class="app-header__logo" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
      <!-- Sidebar toggle button--><a id="sSide" style="background: white;" class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <!-- User Menu-->
          <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
              <li><a class="dropdown-item" href="user-profile.php"><i class="fa fa-user fa-lg"></i> Profile</a></li>
              <li><a class="dropdown-item" href="user-settings.php"><i class="fa fa-cog fa-lg"></i> Account Settings</a></li>
              <li><a class="dropdown-item" onclick="logOut();" href="#"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
          </li>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside id="sNav" style="background: rgb(255, 255, 255);" class="app-sidebar">
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>" alt="">
        <div style="color:black;">
          <?php
            $truncated_name = $fname;

            // Split the string into an array of words
            $words = explode(' ', $truncated_name);

            // Check if the array has at least four words
            if (count($words) >= 2) {
                // Combine the first three words and join the rest with a new line
                $designation = implode(' ', array_slice($words, 0, 2)) . '<br>' . implode(' ', array_slice($words, 2));
            } else {
                // If there are fewer than four words, keep the original string
                $designation = $truncated_name;
            }
          ?>
          <p class="app-sidebar__user-name"><?php echo $designation." ". $lname ?></p>
          <p class="app-sidebar__user-designation"><?php echo $section ?> <br> Student</p>
        </div>
      </div>
      <ul class="app-menu" style="font-size: 16px;">
        <li><a class="app-menu__item" href="user.php"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></a></li>
        <li><a class="app-menu__item" data-toggle="modal" data-target="#FeedbackModal" href="#"><i class="app-menu__icon fa fa-pencil-square-o"></i><span class="app-menu__label">Feedback</span></a></li>
      </ul>
    </aside>
    <!-- Bootstrap Feedback Modal -->
    <div class="modal fade" id="FeedbackModal" tabindex="-1" role="dialog" aria-labelledby="FeedbackModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="FeedbackModalLabel">Feedback</h5>
            <button type="button" class="close" data-dismiss="modal" onclick="resetFeedbackForm()" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- Add your modal content here -->
            <form id="feedbackForm">
              <label for="">Message</label>
              <textarea class="form-control" name="message" placeholder="How's your experience?" rows="8"></textarea><br>
              <label for="">Rate us</label>
              <style>
                #feedUL {
                  list-style: none;
                  display: flex;
                  flex-direction: row;
                  justify-content: center;
                }

                .feedLI {
                  margin-right: 15px;
                }

                .feedLI label {
                  display: flex;
                  flex-direction: column; /* Change to column */
                  align-items: center;
                }

                .feedLI input {
                  margin-bottom: 5px; /* Adjust the spacing between radio buttons and spans */
                  width: 20px;
                }

                .feedLI input,
                .feedLI span {
                  white-space: nowrap;
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
              <ul id="feedUL">
                <li class="feedLI">
                  <label>
                    <input class="form-control" name="number_stars" value="1" type="radio">
                    <span>1 Star</span>
                  </label>
                </li>
                <li class="feedLI">
                  <label>
                    <input class="form-control" name="number_stars" value="2" type="radio">
                    <span>2 Stars</span>
                  </label>
                </li>
                <li class="feedLI">
                  <label>
                    <input class="form-control" name="number_stars" value="3" type="radio">
                    <span>3 Stars</span>
                  </label>
                </li>
                <li class="feedLI">
                  <label>
                    <input class="form-control" name="number_stars" value="4" type="radio">
                    <span>4 Stars</span>
                  </label>
                </li>
                <li class="feedLI">
                  <label>
                    <input class="form-control" name="number_stars" value="5" type="radio">
                    <span>5 Stars</span>
                  </label>
                </li>
              </ul>
              <small style="margin: auto;"><i style="font-style: 18px;">This will be displayed on the landing page as a rating</i></small>
            </form>
            <script>
                // Function to reset the feedback form
                function resetFeedbackForm() {
                  document.getElementById("feedbackForm").reset();
                }
                function hideFeedbackModalWithoutJQuery() {
                  var modal = document.getElementById('FeedbackModal');
                  if (modal) {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    var modalBackdrop = document.getElementsByClassName('modal-backdrop');
                    if (modalBackdrop.length > 0) {
                      modalBackdrop[0].parentNode.removeChild(modalBackdrop[0]);
                    }
                  }
                }
                function submitFeedback() {
                  // Get the message, star rating, fname, lname, and section values
                  var message = $("#feedbackForm textarea[name=message]").val();
                  var stars = $("#feedbackForm input[name=number_stars]:checked").val();
                  var fname = "<?php echo $fname ?>";
                  var lname = "<?php echo $lname ?>";  
                  var section = "<?php echo $section ?>";  
                  var image = "<?php echo $profile_picture ?>";
                  var f_student_number = "<?php echo $student_id_number ?>";

                  // Make an AJAX request
                  $.ajax({
                      type: "POST",
                      url: "php/sendFeedback.php",
                      data: {
                          message: message,
                          stars: stars,
                          fname: fname,
                          lname: lname,
                          section: section,
                          image: image,
                          f_student_number: f_student_number,
                          feedbackSub: true
                      },
                      dataType: 'json',
                      success: function (response) {
                          // Handle the success response
                          if (response.status === 'success') {
                              swal({
                                  title: "Feedback has been submitted!",
                                  text: "Thank you, " + response.fname + "!",
                                  type: "success",
                                  showCancelButton: false,
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true,
                                  closeOnCancel: true
                              });
                              resetFeedbackForm();
                              hideFeedbackModalWithoutJQuery();
                          } else if (response.status === 'error2' && response.message === 'f_student_number was counted 2 times.') {
                              swal({
                                  title: "You already have reach the maximum feedbacks.",
                                  text: "Maybe next time?",
                                  type: "error",
                                  showCancelButton: false,
                                  confirmButtonText: "OK",
                                  closeOnConfirm: true,
                                  closeOnCancel: true
                              });
                              resetFeedbackForm();
                              hideFeedbackModalWithoutJQuery();
                          } else {
                              console.error("Error submitting feedback:", response.message);
                          }
                      },
                      error: function (error) {
                          // Handle the error response
                          console.error("Error submitting feedback:", error);
                      }
                  });
                }
              </script>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="resetFeedbackForm()">Close</button>
            <button type="button" class="btn btn-primary" name="feedbackSub" onclick="submitFeedback()">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <main class="app-content">
      <div class="app-title">
        <div id="divProfile">
          <h1 id="homee"><i class="fa fa-cog"></i> Account Settings</h1>
          <p></p>
        </div>
        <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="user.php"><i class="fa fa-home fa-lg"></i></a></li>
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
<script>
  // check if the logout query parameter is present in the URL
  const successParams = new URLSearchParams(window.location.search);
  const success = successParams.get('success');
  if (success === 'true') {
    // display a SweetAlert notification to the user
    swal({
      title: "Application has been submitted!",
      text: "Please wait for further announcements.",
      type: "success",
      showCancelButton: false,
      confirmButtonText: "OK",
      closeOnConfirm: true,
      closeOnCancel: true
    });

    // Remove the success URL parameter after 5 seconds
    setTimeout(function() {
      // Create a new URL without the success parameter
      const newUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);
    }, 500);
  }

  // check if the scholar query parameter is present in the URL
  const errorParams = new URLSearchParams(window.location.search);
  const error = errorParams.get('error');
  if (error === 'exists') {
    // display a SweetAlert notification to the user
    swal({
      title: "Application failed!",
      text: "You already have this kind of application.",
      type: "error",
      showCancelButton: false,
      confirmButtonText: "OK",
      closeOnConfirm: true,
      closeOnCancel: true
    });

    // Remove the success URL parameter after 5 seconds
    setTimeout(function() {
      // Create a new URL without the success parameter
      const newUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);
    }, 500);
  }
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
      return false;
    }

    if (!password.match(regexUpperCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one uppercase letter.</i>';
      return false;
    }

    if (!password.match(regexLowerCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one lowercase letter.</i>';
      return false;
    }

    if (!password.match(regexNumber)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one number.</i>';
      return false;
    }

    if (!password.match(regexSpecialChar)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one special character.</i>';
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
                url: 'php/userupdate-password.php?updateid=<?php echo $s_id ?>',
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