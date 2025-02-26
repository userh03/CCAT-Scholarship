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
    $developer_id_number = $row['developer_id_number'];
    $d_position = $row['d_position'];
    $profile_picture = $row['profile_picture'];
?>
<?php 
  $query3 = "SELECT * FROM year_tbl";
  $result3 = mysqli_query($con, $query3);
  $row3 = mysqli_fetch_assoc($result3);

  $year = $row3['year'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Applicant List</title>
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
          </style>          <h1><i class="fa fa-th-list"></i> Applicants List</h1>
          <p>List of Applicants</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item active"><a href="#">Applicants List</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
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
              <select id="demoSelect" multiple="">
                <optgroup label="Select Categories">
                  <option>Department օf Computer Studies</option>
                  <option>Department օf Engineering</option>
                  <option>Department օf Industrial Technology</option>
                  <option>Department օf Management Studies</option>
                  <option>Department օf Teacher Education</option>
                  <option>OTHERS</option>
                </optgroup>
              </select>
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
                      <th scope="col">Status</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                      $sql = "SELECT * FROM applicant_tbl WHERE app_status = 'Pending' OR app_status = 'On-process'";
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
                      
                          $selectStats = empty($app_status) ? '<span id="selectStats">Select Status</span>' : '<span style="display:none;" id="selectStats">Select Status</span>';
                      
                          echo '<tr style="font-size: 15px;">
                              <td scope="row"><strong>Applicant Student ID #:</strong>'.$app_student_number.'</td>
                              <td><strong>Applicant Name:</strong>'.$app_lname.', '.$app_fname.'</td>
                              <td><strong>Applicant Mobile:</strong>'.$app_mobile.'</td>
                              <td><strong>Applicant Email:</strong>'.$app_email.'</td>
                              <td><strong>Applicant Section:</strong>'.$app_section.'</td>
                              <td><strong>Scholar Type:</strong>'.$app_scholar_type.'</td>
                              <td><strong>Applicant Department:</strong>'.$app_department.'</td>
                              <td><strong>Applicant Adviser:</strong>'.$app_adviser.'</td>
                              <td><strong>Status:</strong>'.$app_status.'</td>
                              <td>
                                  <div id="operation-field" style="word-spacing: 5px;">
                                    <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#viewModal' . $app_id . '">
                                      View
                                    </button>

                                    <div class="modal fade" id="viewModal' . $app_id . '" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="viewModalLabel">Applicant Details</h3>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" id="viewModalBody">
                                                    <!-- Content of the inner page will be loaded here -->
                                                    <button onclick="updateStatus('.$app_id.', \'Approved\')" class="btn btn-primary">Approve</button>
                                                    <button onclick="updateStatus('.$app_id.', \'Denied\')" class="btn btn-danger">Denied</button>
                                                    <button onclick="updateStatus('.$app_id.', \'On-process\')" class="btn btn-warning">On-process</button><br><hr>
                                                    <h4 class="viewP">Student Number: <span>'.$app_student_number.'</span></h4>
                                                    <h4 class="viewP">Student Name: <span>'.$app_fname.' '.$app_lname.'</span></h4>
                                                    <h4 class="viewP">Section: <span>'.$app_section.'</span></h4>
                                                    <h4 class="viewP">Scholar: <span>'.$app_scholar_type.'</span></h4>';

                                $imageDirectory = "uploaded_docs/" .$app_student_number. "-".$year ."/";
                                // Check if the directory exists
                                if (is_dir($imageDirectory)) {
                                    // Open a directory, and read its contents
                                    if ($dh = opendir($imageDirectory)) {
                                        while (($file = readdir($dh)) !== false) {
                                            // Ignore current directory and parent directory entries
                                            if ($file != '.' && $file != '..') {
                                                // Display each image
                                                echo "<img class='img-fluid' src='$imageDirectory/$file' alt='Image'>";
                                            }
                                        }
                                        closedir($dh);
                                    }
                                }

                                echo '</div>
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
              <style>
                .viewP{
                  text-align: left;
                  font-weight: 700;
                }
                .viewP span{
                  font-weight: 500;
                }
                strong{
                  display: none;
                }
                /* CSS media query for mobile devices */
                @media (max-width: 767px) {
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
          searching: false
        });
      });
    </script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
    <script>
      $(document).ready(function() {
        // Initialize select2 plugin
        $('#demoSelect').select2({
          tags: true, // Allow custom tags
        });

        // Function to filter the table rows
        function filterTable() {
          var selectedCategories = $('#demoSelect').val(); // Get the selected categories

          // Show/hide rows based on selected categories
          $('#sampleTables tbody tr').each(function() {
            var row = $(this);
            var fields = row.find('td'); // Get all the fields in the row

            var found = false;
            fields.each(function() {
              var fieldText = $(this).text().trim().toLowerCase(); // Convert field text to lowercase

              // Check if any field matches the selected categories (case-insensitive)
              if (selectedCategories.length === 0 || selectedCategories.some(category => fieldText.includes(category.toLowerCase()))) {
                found = true;
                return false; // Exit the loop if a match is found
              }
            });

            if (found) {
              row.show(); // Show the row
            } else {
              row.hide(); // Hide the row
            }
          });
        }

        // Filter the table when the select2 value changes
        $('#demoSelect').on('change', filterTable);

        // Call the filterTable function initially to show the appropriate rows
        filterTable();
      });
    </script>

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
    <!-- UPDATE STATUS ALERT -->
    <script>
      function updateStatus(app_id, status) {
        var a_id = "<?php echo $dev_id ?>";
        var a_fname = "<?php echo $d_fname ?>";
        var a_lname = "<?php echo $d_lname ?>";
        var teacher_id_number = "<?php echo $developer_id_number ?>";
        var department = "<?php echo $designation ?>";
        swal({
            title: "Are you sure?",
            text: "You are going to update the status of this applicant.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, Update!",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
              $.ajax({
                  url: 'php/superupdate-status.php',
                  method: 'POST',
                  data: { app_id: app_id, 
                          status: status,
                          teacher_id_number: teacher_id_number,
                          a_fname: a_fname,
                          a_lname: a_lname,
                          department: department
                        },
                  dataType: 'json',  // Specify that the expected response is JSON
                  success: function (response) {
                      // Check the 'status' field in the JSON response
                      if (response.status === 'Success') {
                          window.location.href='php/successdd.php';
                          // Add your logic here for a successful response
                      } else {
                          console.log('Error:', response.message);
                          // Handle the error case
                      }
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                      console.log(textStatus, errorThrown);
                      swal("Error", "An error occurred while updating the status.", "error");
                  }
              });
            }
        });
      }
    </script>
    <script>
    // check if the success query parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    if (success === 'true') {
        // display a SweetAlert notification to the user
        swal({
            title: "Status Updated!",
            text: "The status has been updated successfully.",
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