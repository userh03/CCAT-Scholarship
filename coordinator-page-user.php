<?php
    session_start();

    include("php/connection.php");

    // check if session is not set
    if(!isset($_SESSION['a_id']))
    {
      header("location: index.php");
      exit();
    }
    
    $a_id = $_SESSION['a_id'];
    $query = "SELECT * FROM admin_tbl WHERE a_id = '$a_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $teacher_id_number = $row['teacher_id_number'];
    $a_fname = $row['a_fname'];
    $a_lname = $row['a_lname'];
    $department = $row['department'];
    $username = $row['username'];
    $email = $row['email'];
    $mobile = $row['mobile'];
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
  <link rel="shortcut icon" href="images/logo.ico" />
    <title>Coordinator Profile</title>
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
      <!-- import navbar -->
      <?php include 'php/coordinator-navbar.php'; ?>
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
              input[type="number"]::-webkit-inner-spin-button,
              input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
              }

              input[type="number"] {
                -moz-appearance: textfield; /* Firefox */
              }
          </style>            <h1 id="homee"><i class="fa fa-cog"></i> Profile</h1>
            <p></p>
          </div>
          <ul id="ulBreadcrumb" class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="coordinator-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="#">Profile</a></li>
          </ul>
        </div>
      <div>
    <div>
      <div class="tile">
        <div class="tile-body">
          <div class="container">
          <div class="row user">
        <div class="col-md-12">
            <div class="infos">
              <img class="user-img" src="<?php echo $profile_picture ?>">
              <h4><?php echo $a_fname." ". $a_lname ?></h4>
              <p><?php echo $department ?> - Coordinator</p>
            </div>
        </div>
        <div class="col-md-9" style="margin: auto;"><br>
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
                <form method="POST" action="php/adminupdate.php?updateid=<?php echo $a_id ?>" style="padding-bottom: 40px;">
                  <div class="row mb-4">
                    <div class="col-md-8">
                      <br><label>Teacher ID #</label>
                      <input class="form-control" disabled="" type="text" value="<?php echo $teacher_id_number ?>">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 mb-4">
                      <label>First Name</label>
                      <input class="form-control" disabled type="text" name="a_fname" value="<?php echo $a_fname ?>">
                    </div>
                    <div class="col-md-4 mb-4">
                      <label>Last Name</label>
                      <input class="form-control" disabled type="text" name="l_fname" value="<?php echo $a_lname ?>">
                    </div>
                    <div class="col-md-8 mb-4">
                      <label>Department</label>
                      <input class="form-control" readonly type="text" name="department" value="<?php echo $department ?>">
                    </div>
                    <div class="col-md-8 mb-4">
                      <label>Mobile</label>
                      <input class="form-control" type="number" name="mobile" value="<?php echo $mobile?>">
                    </div>
                    <div class="col-md-8 mb-4">
                      <label>Email</label>
                      <input class="form-control" type="text" name="email" disabled="" value="<?php echo $email ?>">
                    </div>
                  </div>
                  <div class="row mb-10">
                    <div class="col-md-12">
                      <button class="btn btn-primary" id="submitButton" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i> Save</button>
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
    <!-- Logout Prompt -->
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
                url: 'php/admin_profile.php',
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
        }, 7000);
    }
</script>
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