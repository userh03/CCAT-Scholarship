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
    $department = $row['department'];
    $section = $row['section'];
    $student_id_number = $row['student_id_number'];
    $profile_picture = $row['profile_picture'];
    $isValid = $row['isValid'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="shortcut icon" href="images/logo.ico" />
    <title>Student Profile</title>
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
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a id="bLogo" class="app-header__logo" href="user.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
                /* Media query for mobile view */
              @media screen and (max-width: 767px) {
                #divProfile {
                  display: none;
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
            <h1 id="homee"><i class="fa fa-cog"></i> Profile</h1>
            <p></p>
          </div>
          <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="user.php"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Profile</a></li>
          </ul>
        </div>
      <div>
      <div class="tile">
        <div class="tile-body">
          <div class="container">
          <div class="row user">
        <div class="col-md-12">
            <div class="infos"><img class="user-img" src="<?php echo $profile_picture ?>">
              <h4><?php echo $fname." ". $lname ?></h4>
              <p><?php echo $department ?> - Student</p>
            </div>
        </div>
        <div class="col-md-12">
          <h4 class="line-head">Profile</h4>
            <form id="uploadForm" method="post" action="" enctype="multipart/form-data">
              <label>Upload Profile Picture</label><br>
              <div class="col-md-8">
                  <input class="form-control" type="file" name="fileToUpload" id="fileToUpload" accept=".png, .jpg, .jpeg" required>
                  <br>
                  <button class="form-control btn btn-primary" type="button" onclick="uploadProfilePicture()">
                      <i class="fa fa-fw fa-lg fa-upload"></i> Upload
                  </button>
              </div>
            </form>
                <form method="POST" action="php/userupdate.php?updateid=<?php echo $s_id ?>" style="padding-bottom: 40px;">
                  <div class="row mb-4">
                    <div class="col-md-8">
                      <br><label>Student ID #</label>
                      <input class="form-control" readonly type="text" value="<?php echo $row['student_id_number'] ?>" disabled="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <label>First Name</label>
                      <input class="form-control" readonly type="text" value="<?php echo $row['fname'] ?>" disabled="">
                    </div>
                    <div class="col-md-4">
                      <label>Last Name</label>
                      <input class="form-control" readonly type="text" value="<?php echo $row['lname'] ?>" disabled="">
                    </div>
                    <div class="col-md-8 mb-4">
                      <br><label>Department</label>
                      <input class="form-control" readonly type="text" value="<?php echo $row['department'] ?>" disabled="">
                    </div>
                    <div class="col-md-8 mb-4">
                      <label>Section</label>
                      <input class="form-control" readonly type="text" value="<?php echo $row['section'] ?>" disabled="">
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-8 mb-4">
                      <label>Mobile</label>
                      <input class="form-control" type="number" name="mobile" value="<?php echo $row['mobile'] ?>">
                    </div>
                    <div class="col-md-8 mb-4">
                      <label>Email</label>
                      <input class="form-control" type="text" value="<?php echo $row['email'] ?>" disabled="">
                    </div>
                  </div>
                  <div class="row mb-10">
                    <div class="col-md-12">
                      <button class="btn btn-primary" name="submit" id="submitButton" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
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
    <script src="js/plugins/bootstrap-notify.min.js"></script>
    <script>
    function uploadProfilePicture() {
        // Get the file input element
        var inputFile = $('#fileToUpload')[0];

        // Check if a file is selected
        if (inputFile.files.length === 0) {
            // Display notification for empty form
            $.notify({
                title: "Upload Failed.",
                message: "<br> The form cannot be empty.",
                icon: 'fa fa-exclamation-circle'
            }, {
                type: "warning",
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: {
                    x: 10,
                    y: 120
                },
                delay: 3000,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title" style="font-size: 20px; font-weight: 700;">{1}</span> ' +
                '<span data-notify="message" style="font-size: 18px;">{2}</span> ' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
            });

            return; // Stop execution if the form is empty
        }

        // Check if the file format is allowed
        var allowedFormats = ['jpg', 'jpeg', 'png'];
        var fileName = inputFile.files[0].name.toLowerCase();
        var fileExtension = fileName.split('.').pop();

        if (allowedFormats.indexOf(fileExtension) === -1) {
            // Display notification for invalid image format
            $.notify({
                title: "Invalid Image Format.",
                message: "<br> Please upload a JPEG, JPG, or PNG image.",
                icon: 'fa fa-exclamation-circle'
            }, {
                type: "danger",
                placement: {
                    from: "top",
                    align: "right"
                },
                offset: {
                    x: 10,
                    y: 120
                },
                delay: 3000,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title" style="font-size: 20px; font-weight: 700;">{1}</span> ' +
                '<span data-notify="message" style="font-size: 18px;">{2}</span> ' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
            });

            return; // Stop execution if the format is invalid
        }

        // Display verification notification
        var verificationNotification = $.notify({
            title: "Uploading your profile picture...",
            message: "",
            icon: 'fa fa-spinner fa-spin'
        }, {
            type: "info",
            placement: {
                from: "top",
                align: "right"
            },
            offset: {
                x: 10,
                y: 120
            },
            style: 'bootstrap',
            className: 'info',
            delay: 8000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title" style="font-size: 20px; font-weight: 700;">{1}</span> ' +
                '<span data-notify="message" style="font-size: 18px;">{2}</span> ' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });

        // Set a delay using setTimeout before making the Ajax request
        setTimeout(function () {
            // Perform the Ajax form submission
            $.ajax({
                type: 'POST',
                url: 'php/student_profile.php',
                data: new FormData($('#uploadForm')[0]),
                contentType: false,
                processData: false,
                success: function (response) {
                    // Hide verification notification
                    verificationNotification.close();

                    // Display success notification
                    $.notify({
                        title: "Upload Successful",
                        message: "<br> You can now refresh the page.",
                        icon: 'fa fa-check-circle'
                    }, {
                        type: "success",
                        placement: {
                            from: "top",
                            align: "right"
                        },
                        offset: {
                            x: 10,
                            y: 120
                        },
                        delay: 3000,
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        },
            template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title" style="font-size: 20px; font-weight: 700;">{1}</span> ' +
                '<span data-notify="message" style="font-size: 18px;">{2}</span> ' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
                    });
                        // Reload the window after 3 seconds
                        setTimeout(function () {
                          window.location.reload(true);
                    }, 1500);
                },

                error: function (error) {
                    // Handle error (you may want to display an error notification)
                    console.log(error);
                }
            });
        }, 4000);
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
      const updateParams = new URLSearchParams(window.location.search);
      const update = updateParams.get('update');
      if (update === 'true') {
          // display a SweetAlert notification to the user
          swal({
              title: "Profile has been updated!",
              text: "",
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
              }, 1000);
      }
    </script>
  </body>
</html>