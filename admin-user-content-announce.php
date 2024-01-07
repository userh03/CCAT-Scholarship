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

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Content Management</title>
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
          </style>          <h1><i class="fa fa-file-text"></i> Announcements</h1>
          <p>Edit Contents on User Page</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="admin-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#">Content Page</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <button type="button" style="float:right; margin-bottom: 5px;" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
              Add New Announcement Slide
            </button>
            <div class="tile-body">
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="myModalLabel">New Announcements</h2>
                      </div>
                    <div class="modal-body">
                    <form method="POST" action="php/supercontent-insert-announce.php" enctype="multipart/form-data">
                      <div class="row mb-4">
                        <div class="col-md-12">
                          <br><label>Title</label>
                          <input class="form-control" type="text" id="" name="title" placeholder="Enter announcement title" required>
                        </div>
                        <div class="col-md-12">
                          <br><label>Content</label>
                          <textarea class="form-control" type="text" id="" name="content" placeholder="Enter announcement content" required></textarea><br>
                        </div>
                        <div class="col-md-12">
                          <br><label>Image</label>
                            <img id="imgModal" style="max-height: 100%; width: 100%;" name="images" src="">
                            <input id="inputModal" required type="file" class="form-control inputModal" name="images">                      
                        </div>
                      </div>                      
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="submitButton" type="submit" name="asubmit">Save changes</button>
                      </div>
                    </form>  
                    </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="sampleTables">
                  <thead>
                    <tr style="text-align: center; font-size: 18px;">
                      <th scope="col">Contents</th>
                      <th scope="col">Title</th>
                      <th scope="col">Thumbnails</th>
                      <th scope="col">Operation</th>

                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                      $sql = "SELECT * FROM announceupdate_tbl";
                      $result = mysqli_query($con, $sql);
                      while ($row = mysqli_fetch_assoc($result)){
                          $id = $row['id'];
                          $content = $row['content'];
                          $images = $row['images'];
                          $title = $row['title'];

                          // Truncate the content if it exceeds the maximum number of characters
                          $truncatedContent = implode(' ', array_slice(explode(' ', $content), 0, 50));

                          echo '<tr>
                              <td style="text-align: justify; font-size: 15px;"><strong>Contents</strong>'.$truncatedContent.'</td>
                              <td id="title" style="width: 20%; font-size: 18px; text-align: center;">                                    
                                <strong>Title</strong>'. $title .'
                              </td>
                              <td>
                                  <strong>Thumbnail</strong>
                                  <img id="imgdisp" style="height: 100%; width: 100%;" src="'.$images.'">
                              </td>
                              <td id="opfield" style="width: 150px;">
                                  <div id="operation-field">
                                      <button type="button" class="btn btn-secondary" style="margin-bottom: 5px;" data-toggle="modal" data-target="#myModal'.$id.'" onclick="setupModal('.$id.')">
                                          Edit
                                      </button>
                                      <div class="modal fade" id="myModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                          <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4 class="modal-title" id="myModalLabel">Update Slides</h4>
                                                  </div>
                                                  <div class="modal-body">
                                                      <form method="POST" action="php/supercontent-announce.php?updateid=' . $id . '" enctype="multipart/form-data">
                                                          <div class="row mb-4">
                                                              <div class="col-md-12">
                                                                  <br><label for="a_fname">Title:</label>
                                                                  <input class="form-control" name="title" rows="5" value="' . htmlspecialchars($title) . '">
                                                              </div>
                                                          </div>
                                                          <div class="row mb-4">
                                                              <div class="col-md-12">
                                                                  <br><label for="a_fname">Content:</label>
                                                                  <textarea class="form-control" name="content" rows="5">' . htmlspecialchars($content) . '</textarea>
                                                              </div>
                                                          </div>
                                                          <div class="row mb-4">
                                                              <div class="col-md-12">
                                                                  <br><label for="">Thumbnails:</label>
                                                                  <img id="imgModal'.$id.'" style="max-height: 100%; width: 100%;" name="images" src="' . $images . '">
                                                                  <input id="inputModal'.$id.'" type="file" class="form-control inputModal" name="image_upload">
                                                              </div>
                                                          </div>
                                                          <div class="modal-footer">
                                                              <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                              <button class="btn btn-primary" type="submit" name="submit">Save changes</button>
                                                          </div>
                                                      </form>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <button style="margin-bottom: 5px;" class="btn btn-danger" onclick="demoSwal(' . $id . ');" name="delete">Delete</button>
                                  </div>
                              </td>
                          </tr>';
                      }
                      ?>

                      <script>
                        function setupModal(id) {
                          const image = document.getElementById('imgModal' + id);
                          const input = document.getElementById('inputModal' + id);

                          input.addEventListener('change', () => {
                            const file = input.files[0];
                            const reader = new FileReader();

                            reader.onload = function(e) {
                              image.src = e.target.result;
                            }

                            reader.readAsDataURL(file);
                          });
                        }
                      </script>


                  </tbody>
                </table>    
                <style>
                  strong{
                    display: none;
                  }
                  /* CSS media query for mobile devices */
                  @media (max-width: 767px) {
                    strong{
                      display: block;
                      text-align: center;
                      margin: 0 5px;
                    }
                    #imgdisp{
                      height: 100% !important;
                      width: 100% !important;
                    }
                    #opfield{
                      width: 100% !important; 
                    }
                    #operation-field{
                      text-align: center;
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

                    #title{
                      width: 100% !important;
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
    <script>
      const image = document.getElementById('imgModal');
      const input = document.getElementById('inputModal');

      input.addEventListener('change', () => {
        image.src = URL.createObjectURL(input.files[0]);
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
    <!-- Logout Prompt -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
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
        <script type="text/javascript">
    function demoSwal(id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this Slide!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            swal({
                title: "Deleted!",
                text: "The slide has been deleted.",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function() {
                window.location = "php/supercontentdelete-announce.php?deleteid=" + id;
            });
        }
    });
    }
    </script>
        <script>
      // check if the success query parameter is present in the URL
      const successParams = new URLSearchParams(window.location.search);
      const success = successParams.get('success');
      if (success === 'true') {
        // display a SweetAlert notification to the user
        swal({
          title: "Success!",
          text: "New announcement has been added.",
          type: "success",
          showCancelButton: false,
          confirmButtonText: "OK",
          closeOnConfirm: true,
          closeOnCancel: true
        });

        // Remove the success URL parameter after 1 second
        setTimeout(function() {
          // Create a new URL without the success parameter
          const newUrl = window.location.origin + window.location.pathname;
          window.history.replaceState({}, document.title, newUrl);
        }, 1000);
      }

      // check if the update query parameter is present in the URL
      const updateParams = new URLSearchParams(window.location.search);
      const update = updateParams.get('update');
      if (update === 'true') {
        // display a SweetAlert notification to the user
        swal({
          title: "Success!",
          text: "Announcement Slide has been updated.",
          type: "success",
          showCancelButton: false,
          confirmButtonText: "OK",
          closeOnConfirm: true,
          closeOnCancel: true
        });

        // Remove the success URL parameter after 1 second
        setTimeout(function() {
          // Create a new URL without the success parameter
          const newUrl = window.location.origin + window.location.pathname;
          window.history.replaceState({}, document.title, newUrl);
        }, 1000);
      }
    </script>
  </body>
</html>