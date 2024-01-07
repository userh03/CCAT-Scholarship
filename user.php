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
        $student_id_number = $row['student_id_number'];
        $profile_picture = $row['profile_picture'];
        $isValid = $row['isValid'];

?>

<?php 
  $query2 = "SELECT * FROM semester_checker";
  $result2 = mysqli_query($con, $query2);
  $row2 = mysqli_fetch_assoc($result2);

  $sem = $row2['sem'];
?>
<?php 
  $query3 = "SELECT * FROM year_tbl";
  $result3 = mysqli_query($con, $query3);
  $row3 = mysqli_fetch_assoc($result3);

  $year = $row3['year'];
?>
<?php 
  $query4 = "SELECT * FROM application_on_off";
  $result4 = mysqli_query($con, $query4);
  $row4 = mysqli_fetch_assoc($result4);

  $enable_disable = $row4['enable_disable'];
?>

<?php
  include("php/connection.php");

  $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
            UNION
            SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
            UNION
            SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'";
  $result = mysqli_query($con, $query);
  $student_appliedAlready = 0;

  if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $student_appliedAlready++;
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Welcome - Scholarship</title>
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
        if (<?php echo $isValid; ?> === 0 || <?php echo $isValid; ?> === 2) {
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
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a id="bLogo" class="app-header__logo" onclick="location.reload()" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
          $query = "SELECT COUNT(*) AS status_count FROM notify_users_tbl WHERE view_status = 1";
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
          <a class="app-nav__item" onclick="handleNotificationClick(event)" href="#" data-toggle="dropdown" aria-label="Show notifications">
            <i class="fa fa-bell-o fa-lg" id="notification-icon">
              <?php if ($notification_count > 0): ?>
                <span class="red-dot"></span>
              <?php endif; ?>
            </i>
          </a>
          <ul class="app-notification dropdown-menu dropdown-menu-right">
            <div class="app-notification__content" style="width: 260px;" id="notification-content">
                <!-- Notification content will be loaded here -->        
            </div>
          </ul>
        </li>
        <script>
          // Function to fetch and update the notification content
          function fetchNotificationContent() {
              var studentID = '<?php echo $student_id_number ?>';
              var lname = '<?php echo $lname ?>';
              $.ajax({
                  type: 'POST',
                  url: 'php/fetchNotifications.php', // Replace with the actual URL to your PHP script for fetching notifications
                  data: { student_id_number: studentID, lname: lname },
                  success: function(response) {
                      // Update the notification content
                      $('#notification-content').html(response);

                      // Update the notification count (assuming it's included in the response)
                      var newNotificationCount = $(response).filter('#new-notification-count').text();
                      $('#notification-count').text(newNotificationCount);

                      // Optionally, you can redirect the user to a new page after the update
                      // Remove the red dot
                      $('#notification-icon').find('.red-dot').remove();

                      // After updating the notification content, call the function to update the view_status
                      updateNotificationStatus();
                  },
                  error: function(xhr, status, error) {
                      // Handle AJAX errors
                      console.error('AJAX error:', status, error);
                  }
              });
          }

          // Function to update the view_status
          function updateNotificationStatus() {
              $.ajax({
                  type: 'POST',
                  url: 'php/notifyStatusUpdate_USER.php',
                  success: function(response) {
                      // Check if the response is equal to "Success"
                      if (response === 'Success') {
                          // Do any additional handling if needed
                      } else {
                          // Handle the case where the update was not successful
                          console.error('Error updating view_status:', response);
                      }
                  },
                  error: function(xhr, status, error) {
                      // Handle AJAX errors
                      console.error('AJAX error:', status, error);
                  }
              });
          }

          function handleNotificationClick(event) {
              event.preventDefault(); // Prevent the default navigation behavior

              // Fetch and update the notification content before updating view_status
              fetchNotificationContent();
          }
        </script>
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
        <li><a class="app-menu__item active" href="#"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></a></li>
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
                  flex-direction: column; 
                  align-items: center;
                }

                .feedLI input {
                  margin-bottom: 5px; 
                  width: 20px;
                }

                .feedLI input,
                .feedLI span {
                  white-space: nowrap;
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
          <h1 id="homee"><i class="fa fa-user"></i> Profile</h1>
          <p></p>
        </div>
        <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="user.php"><i class="fa fa-user fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#">Home Page</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <!-- TILE FOR APPLICATION -->
          
          <div class="alert-tile" style="text-align: center;">
              <span data-notify="icon"></span>
              <span data-notify="title" style="font-size: 18px;"></span>
              <a href="{3}" target="{4}" data-notify="url"></a>
          </div>
          
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            <style>
              table {
                width: 100%;
                text-align: center;
                margin-bottom: -20px;
              }
              
              tr, td {
                border: none;
                display:inline;
              }
              tr{
                margin: 80px;
              }
              .profile-info {
                padding: 10px;
                display: inline-block;
              }

              /* Media query for mobile view */
              @media screen and (max-width: 767px) {
                #divProfile {
                  display: none;
                }
              }

            </style>
            <table>
              <tr>
                <td class="profile-info">
                  <label id="proftitle" for="" style="font-weight: 600;">Student Number</label><br>
                  <span id="proftext"><?php echo $student_id_number ?></span><br><br>
                </td>
              </tr>
              <tr>
                <td class="profile-info">
                  <label id="proftitle" for="" style="font-weight: 600;">Name</label><br>
                  <span id="proftext"><?php echo $fname." ". $lname ?></span><br><br>
                </td>
              </tr>
              <tr>
                <td class="profile-info">
                  <label id="proftitle" for="" style="font-weight: 600;">Section</label><br>
                  <span id="proftext"><?php echo $section ?></span><br><br>
                </td>
              </tr>
            </table>
              <!-- Apply Now MODAL -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="myModalLabel">Apply for Scholarship</h2>
                      </div>
                    <div class="modal-body">

                      <form method="POST" action="php/scholarapply.php" enctype="multipart/form-data">
                        <input type="hidden" name="app_year" value="<?php echo $year ?>">
                      <div class="row mb-4">
                        <div class="col-md-12">
                          <br><label>Student ID #</label>
                          <input class="form-control" type="number" name="app_student_number" readonly disabled value="<?php echo "$student_id_number"; ?>">
                            <input type="hidden" name="app_student_number" value="<?php echo $student_id_number; ?>">
                        </div>
                        <div class="col-md-6">
                        <br><label>First Name</label>
                          <input class="form-control" type="text" name="app_fname" readonly value="<?php echo "$fname"; ?>">
                          <input type="hidden" name="app_fname" value="<?php echo $fname; ?>">
                        </div>
                        <div class="col-md-6">
                        <br><label>Last Name</label>
                          <input class="form-control" type="text" name="app_lname" readonly value="<?php echo "$lname"; ?>">
                          <input type="hidden" name="app_lname" value="<?php echo $lname; ?>">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6 mb-4">
                          <label>Department</label>
                          <input class="form-control" type="text" name="app_department" readonly value="<?php echo "$department"; ?>">
                          <input type="hidden" name="app_department" value="<?php echo $department; ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                          <label>Type of Scholar</label>
                          <select class="form-control" id="" name="app_scholar_type" required>
                            <?php
                            function scholarshipExistsInDB($scholarshipType, $year, $student_id_number) {
                                global $con;

                                $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_year = '$year' AND app_scholar_type LIKE '%$scholarshipType%'
                                          UNION
                                          SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year' AND app_scholar_type LIKE '%$scholarshipType%'
                                          UNION
                                          SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year' AND app_scholar_type LIKE '%$scholarshipType%'";

                                $result = mysqli_query($con, $query);

                                return mysqli_num_rows($result) > 0;
                            }

                            $query = "SELECT * FROM applicant_tbl
                                      UNION
                                      SELECT * FROM approved_applicants_tbl 
                                      UNION
                                      SELECT * FROM denied_applicants_tbl";

                            $result = mysqli_query($con, $query);
                            $row = mysqli_fetch_assoc($result);
                            $app_year = $row['app_year'];
                            ?>
                            <option value="">Select Scholarship</option>
                            <optgroup label="Select Scholarship">
                                <option value="Academic Scholarship" <?php if ($student_id_number && $year == $app_year && scholarshipExistsInDB('Academic Scholarship', $year, $student_id_number)) echo 'disabled'; ?>>Academic Scholarship</option>
                            </optgroup>
                        </select>

                        </div>

                        </div>
                        <div class="col-md-13 mb-4">
                          <label>Section</label>
                          <input class="form-control" type="text" id="" name="app_section" readonly disabled value="<?php echo "$section"; ?>">
                          <input type="hidden" name="app_section" value="<?php echo $section; ?>">
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-13 mb-4">
                          <label>Mobile</label>
                          <input class="form-control" type="number" name="app_mobile" readonly disabled="" value="<?php echo "$mobile"; ?>">
                        </div>
                        <div class="col-md-13 mb-4">
                          <label>Name of Adviser</label>
                          <input class="form-control" type="text" name="app_adviser" placeholder="Enter adviser name" required>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-13">
                          <label>Email</label>
                          <input class="form-control" id="exampleInputEmail1" name="app_email" type="email" aria-describedby="emailHelp" readonly value="<?php echo "$email"; ?>"><small class="form-text text-muted" id="emailHelp">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 mb-4">
                            <br>
                            <label>Supporting Documents</label>
                            <input required class="form-control" id="docs" name="files[]" type="file" multiple accept=".jpg, .jpeg, .png, .pdf">
                            <small class="form-text text-muted" id="uploadHelp">Insert your good moral certificate and certification of grades here in jpg, jpeg, png, or PDF format. (Multi-Select)</small>
                        </div>
                        <script>
                            document.getElementById('docs').addEventListener('change', function () {
                                var files = this.files;
                                var fileSizeLimit = 5 * 1024 * 1024; // 5MB in bytes

                                for (var i = 0; i < files.length; i++) {
                                    if (files[i].size > fileSizeLimit) {
                                        alert('File size exceeds the 5MB limit.');
                                        this.value = ''; // Clear the file input
                                        return;
                                    }

                                    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                                    if (!allowedExtensions.exec(files[i].name)) {
                                        alert('Invalid file type. Please select jpg, jpeg, png, or PDF files only.');
                                        this.value = ''; // Clear the file input
                                        return;
                                    }
                                }
                            });
                        </script>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit" name="isubmit">Submit</button>
                      </div>
                      </form>  
                    </div>
                  </div>
                </div>
              </div>
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
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body" style="text-align: center;">
          <style>
              .profile-info2 {
                margin: 10px;
                display: inline-block;
              }
          </style>
          <div class="profile-info2">
            <label id="proftitle" for="" style="font-weight: 600;">List of Applications</label><br><hr>

            <?php
              include("php/connection.php");

              $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'";
              $result = mysqli_query($con, $query);

              if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $app_id = $row['app_id'];
                      $app_scholar_type = $row['app_scholar_type'];
                      
                      echo '<span id="appscholar">' . $app_scholar_type . '</span><br><br>';
                  }
              } else {
                  echo "No application found.";
              }
            ?>
              <script>
                  // Function to update status
                  function updateStatus() {
                      // Assuming you have a variable like $app_student_number in your PHP code
                      var appStudentNumber = <?php echo json_encode($student_id_number); ?>;

                      // Make an AJAX request to get the latest status
                      $.ajax({
                          url: 'php/get_scholar.php',
                          type: 'POST',
                          data: { student_id_number: appStudentNumber },
                          success: function(response){
                              // Update the displayed scholar type on success
                              $("#appscholar").text(response);
                          },
                          error: function(error){
                              console.error('Error getting scholar type:', error);
                          }
                      });
                  }

                  // Update status every 2 seconds (adjust as needed)
                  setInterval(updateStatus, 2000);
              </script>
          </div>
          <div class="profile-info2">
            <label id="proftitle" for="" style="font-weight: 600;">Year</label><br><hr>

            <?php
              include("php/connection.php");

              $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'";
              $result = mysqli_query($con, $query);

              if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $app_id = $row['app_id'];
                      $yearrrrr = $row['app_year'];
                      
                      echo '<span id="proftext">' . $yearrrrr . '</span><br><br>';
                  }
              } else {
                  echo "None";
              }
            ?>

          </div>
          <div class="profile-info2">
            <label id="proftitle" for="" style="font-weight: 600;">Application Status</label><br><hr>
            <?php
              include("php/connection.php");

              $query = "SELECT * FROM applicant_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM approved_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'
                        UNION
                        SELECT * FROM denied_applicants_tbl WHERE app_student_number = $student_id_number AND app_year = '$year'";
              $result = mysqli_query($con, $query);

              if ($result && mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $app_id = $row['app_id'];
                      $app_status = $row['app_status'];
                      echo '<span id="appstatus">' . $app_status . '</span><br><br>';
                  }
              } else {
                  echo "No application found.";
              }
              ?>
              <script>
                  // Function to update status
                  function updateStatus() {
                      // Assuming you have a variable like $app_student_number in your PHP code
                      var appStudentNumber = <?php echo json_encode($student_id_number); ?>;

                      // Make an AJAX request to get the latest status
                      $.ajax({
                          url: 'php/get_status.php',
                          type: 'POST',
                          data: { student_id_number: appStudentNumber },
                          success: function(response){
                              // Update the displayed status on success
                              $("#appstatus").text(response);
                          },
                          error: function(error){
                              console.error('Error getting status:', error);
                          }
                      });
                  }

                  // Update status every 2 seconds (adjust as needed)
                  setInterval(updateStatus, 2000);
              </script>
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
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                  window.location.href = 'php/logout.php';
                } else {
                    swal.close();
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

<script src="js/plugins/bootstrap-notify.min.js"></script>
<?php
include("php/connection.php");

// Retrieve the student's grade and units from the docs_tbl table
$query = "SELECT grade, units FROM docs_tbl WHERE student_id_number = '$student_id_number' AND semester = '$sem' AND s_year = '$year'";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
  // Initialize an array to store the data
  $data = array();

  // Fetch each row from the result set
  while ($row = mysqli_fetch_assoc($result)) {
    // Store the grade and units in the data array
    $data[] = $row;
  }

  // Free the result set
  mysqli_free_result($result);

  // Close the database connection
  mysqli_close($con);
} else {
  // Handle the case when the query fails
  echo 'Failed to fetch data from the database.';
  // Close the database connection (if it was opened)
  mysqli_close($con);
  // Exit the script
  exit();
}
?>

<script>
window.addEventListener('load', function() {
  var enable_disable = "<?php echo $enable_disable?>";
  var yearr = "<?php echo $year ?>";
  var semesterr = "<?php echo $sem?>";

  // Function to calculate the weighted average
  function calculateWeightedAverage(data) {
    var totalGradeUnits = 0;
    var totalUnits = 0;
    var hasLowGrade = false;

    // Iterate through each data object
    data.forEach(function(row) {
      var grade = row.grade;
      var units = parseFloat(row.units);

      // Check if grade is "DROP"
      if (grade === "DROP") {
        // Set hasLowGrade to true
        hasLowGrade = true;
        return; // Skip further processing for this row
      }

      // Check if grade and units are valid numbers
      if (!isNaN(grade) && !isNaN(units)) {
        // Calculate the weighted value
        var weightedValue = parseFloat(grade) * units;

        // Accumulate the total grade units and total units
        totalGradeUnits += weightedValue;
        totalUnits += units;

        if (grade >= 2.75) {
          hasLowGrade = true;
        }
      }
    });

    // Check if totalUnits is not zero to avoid division by zero
    if (totalUnits !== 0) {
      // Calculate the weighted average
      var weightedAverage = totalGradeUnits / totalUnits;
      return {
        average: weightedAverage,
        hasLowGrade: hasLowGrade
      };
    }

    return {
      average: 0,
      hasLowGrade: hasLowGrade
    }; // Return 0 if totalUnits is zero to avoid NaN
  }
var isFoundID = '<?php echo $student_appliedAlready ?>';
     
  // Check if data was successfully fetched from the database
  <?php if ($result) : ?>
    // Calculate the weighted average using the retrieved data
    var data = <?php echo json_encode($data); ?>;
    var result = calculateWeightedAverage(data);
 
    // Make an AJAX request to send result.average to the PHP file
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/insert_average.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log('Result sent to PHP successfully.');
        } else {
          console.error('Error sending result to PHP.');
        }
      }
    };
    xhr.send('average=' + result.average.toFixed(2));
    if (result.average >= 1.51 && result.average <= 1.75) {
    // Make an AJAX request to execute the PHP file
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'php/scholarvpresi.php', true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          console.log('PHP script executed successfully.');
        } else {
          console.error('Error executing PHP script.');
        }
      }
    };
    xhr.send();
    }
    else if (result.average >= 1.00 && result.average <= 1.50){
    // Make an AJAX request to execute the PHP file
    var xhr = new XMLHttpRequest();
        xhr.open('GET', 'php/scholarpresi.php', true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              console.log('PHP script executed successfully.');
            } else {
              console.error('Error executing PHP script.');
            }
          }
        };
        xhr.send();
    }
    if (isFoundID == 1) {
          // Display an error notification using Bootstrap Notify
          var message = "You have already applied for scholarship this <strong>year " + yearr + "</strong>.";
          var icon = 'fa fa-exclamation-circle';

          // Replace placeholders with actual values
          var htmlTemplate = `<div data-notify="container" class="alert alert-warning">
              <span data-notify="icon"><i class="${icon}"></i></span>
              <span data-notify="title" style="font-size: 18px;">${message}</span>
              <a href="{3}" target="{4}" data-notify="url"></a>
          </div>`;

          // Create a jQuery element from the HTML template
          var notificationElement = $(htmlTemplate);

          // Append the notification to the "alert-tile" element
          $('.alert-tile').append(notificationElement);
          return;
          }
    // Check if the result meets the qualification criteria
// Check if the result meets the qualification criteria
if (enable_disable == 1) { // Add your condition here
  if (result.hasLowGrade) {
    // Display a failure notification using Bootstrap Notify
    var message = "You have a failing grade or DROP in <strong>" + semesterr + "</strong>, year <strong>" + yearr + "</strong>. You are NOT qualified for a scholarship.";
    var icon = 'fa fa-exclamation-circle';

    // Replace placeholders with actual values
    var htmlTemplate = `<div data-notify="container" class="alert alert-danger">
            <span data-notify="icon"><i class="${icon}"></i></span>
            <span data-notify="title" style="font-size: 18px;">${message}</span>
            <a href="{3}" target="{4}" data-notify="url"></a>
        </div>`;

        // Create a jQuery element from the HTML template
        var notificationElement = $(htmlTemplate);

        // Append the notification to the "alert-tile" element
        $('.alert-tile').append(notificationElement);

  }
  else if (result.average >= 1.0 && result.average <= 1.75) {
    var studentIdYes = '<?php echo $student_appliedAlready ?>';

    // Display a success notification using Bootstrap Notify
    var message = "Your GPA in <strong>" + semesterr + "</strong>, year <strong>" + yearr + "</strong> is " + result.average.toFixed(2) + ", you are qualified for a scholarship.";
    var icon = 'fa fa-check-circle';
    
    // Replace placeholders with actual values
    var htmlTemplate = `<div data-notify="container" class="alert alert-success">
            <span data-notify="icon"><i class="${icon}"></i></span>
            <span data-notify="title" style="font-size: 18px;">${message}</span>
            <a href="{3}" target="{4}" data-notify="url"></a>
        </div>`;

    // Create a jQuery element from the HTML template
    var notificationElement = $(htmlTemplate);

    // Append the notification to the "alert-tile" element
    $('.alert-tile').append(notificationElement);
    if (studentIdYes == 0) {
        // Create the <li> element
        var li = document.createElement("li");
        li.className = "treeview";

        // Create the <a> element
        var a = document.createElement("a");
        a.className = "app-menu__item";
        a.href = "#apply-now"; // Set the appropriate href value

        // Create the <i> element
        var icon = document.createElement("i");
        icon.className = "app-menu__icon fa fa-pencil-square-o";

        // Create the <span> element
        var label = document.createElement("span");
        label.className = "app-menu__label";
        label.textContent = "Apply Now";

        // Append the <i> and <span> elements to the <a> element
        a.appendChild(icon);
        a.appendChild(label);

        // Add data-toggle and data-target attributes to the <a> element
        a.setAttribute("data-toggle", "modal");
        a.setAttribute("data-target", "#myModal");

        // Set the background color of the <a> element to dark green
        a.style.backgroundColor = "darkgreen";

        // Set the font color of the <a> element to white
        a.style.color = "white";
        a.style.setProperty("color", "white", "important");

        // Append the <a> element to the <li> element
        li.appendChild(a);

        // Find the parent <ul> element
        var ul = document.querySelector("ul.app-menu");

        // Find the <li> element that represents "Requirements"
        var requirementsLi = ul.querySelector('li:nth-child(1)');

        // Insert the new <li> element after the "Requirements" <li> element
        requirementsLi.insertAdjacentElement("afterend", li);
    }
} else if(result.average == 0.0){
        // Display an error notification using Bootstrap Notify
        var message = "Your GPA is " + result.average.toFixed(2) + ", because you are not yet enrolled in any subjects in <strong>" + semesterr + "</strong>, year <strong>" + yearr + "</strong>.";
        var icon = 'fa fa-exclamation-circle';

        // Replace placeholders with actual values
        var htmlTemplate = `<div data-notify="container" class="alert alert-warning">
            <span data-notify="icon"><i class="${icon}"></i></span>
            <span data-notify="title" style="font-size: 18px;">${message}</span>
            <a href="{3}" target="{4}" data-notify="url"></a>
        </div>`;

        // Create a jQuery element from the HTML template
        var notificationElement = $(htmlTemplate);

        // Append the notification to the "alert-tile" element
        $('.alert-tile').append(notificationElement);

    } else {
        // Display an error notification using Bootstrap Notify
        var message = "Your GPA in <strong>" + semesterr + "</strong>, year <strong>" + yearr + "</strong> is " + result.average.toFixed(2) + ", you are NOT qualified for a scholarship.";
        var icon = 'fa fa-exclamation-circle';

        // Replace placeholders with actual values
        var htmlTemplate = `<div data-notify="container" class="alert alert-warning">
            <span data-notify="icon"><i class="${icon}"></i></span>
            <span data-notify="title" style="font-size: 18px;">${message}</span>
            <a href="{3}" target="{4}" data-notify="url"></a>
        </div>`;

        // Create a jQuery element from the HTML template
        var notificationElement = $(htmlTemplate);

        // Append the notification to the "alert-tile" element
        $('.alert-tile').append(notificationElement);
    }


    <?php endif; ?>

} else {
       // Display a failure notification using Bootstrap Notify
        var message = "The submission of scholarship application is currently not available. Try again next time.";
        var icon = 'fa fa-exclamation-circle';

        // Replace placeholders with actual values
        var htmlTemplate = `<div data-notify="container" class="alert alert-warning">
            <span data-notify="icon"><i class="${icon}"></i></span>
            <span data-notify="title" style="font-size: 18px;">${message}</span>
            <a href="{3}" target="{4}" data-notify="url"></a>
        </div>`;

        // Create a jQuery element from the HTML template
        var notificationElement = $(htmlTemplate);

        // Append the notification to the "alert-tile" element
        $('.alert-tile').append(notificationElement);

      }
  });
</script>
<script>
      // check if the logout query parameter is present in the URL
      const loginParams = new URLSearchParams(window.location.search);
      const login = loginParams.get('login');
      if (login === 'true') {
        $.notify({
          title: "You have successfully logged in.",
          message: "",
          icon: 'fa fa-check' 
        }, {
          type: "success",
          placement: {
            from: "top",
            align: "right"
          },
          offset: {
            x: 10,
            y: 120 // Adjust this value as needed
          },
          style: 'bootstrap', // Use Bootstrap styling
          className: 'success', // Apply Bootstrap success class
          delay: 3000, // 5 seconds delay
          animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
          },
          template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title" style="font-size: 18px;">{1}</span> ' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
  // Remove the success URL parameter after 5 seconds
  setTimeout(function() {
                // Create a new URL without the success parameter
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
              }, 1000);
    }
    </script>
  </body>
</html>