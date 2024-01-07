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

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Add/Edit Admin</title>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
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
      <div class="app-sidebar__user"><img id="uProfile" class="app-sidebar__user-avatar" src="<?php echo $profile_picture ?>" alt="">
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
              
          </style>
          <h1><i class="fa fa-th-list"></i> Add/Edit Admin</h1>
          <p>Admin List</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item">Admin Settings</li>
          <li class="breadcrumb-item active"><a href="#">Add/Edit Admin</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            <button type="button" style="float:right; margin-bottom: 5px;" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
              Add New Admin
            </button>
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
              .password-toggle-icon {
                  cursor: pointer;
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
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                      <h2 class="modal-title" id="myModalLabel">Add New Admin</h2>
                    </div>
                  <div class="modal-body">
                  <form method="POST" action="php/developeradmininsert.php">
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <label>Admin ID #</label>
                        <input class="form-control" type="number" id="" name="superadmin_id_number" placeholder="Enter admin ID #" required>
                      </div>
                      <div class="col-md-6">
                        <br><label>First Name</label>
                        <input class="form-control" type="text" id="" name="sa_fname" placeholder="Enter first name" required>
                      </div>
                      <div class="col-md-6">
                        <br><label>Last Name</label>
                        <input class="form-control" type="text" id="" name="sa_lname" placeholder="Enter last name" required><br>
                      </div>
                      <div class="col-md-12 mb-4">
                        <label>Username</label>
                        <input class="form-control" type="text" id="" name="username" placeholder="Enter username" required>
                      </div>
                      <div class="col-md-6 mb-4">
                      <label>Password</label>
                        <div class="input-group">
                          <input class="form-control" type="password" onkeyup="checkPassword();" name="password" id="password" required placeholder="Password">
                            <div class="input-group-append">
                              <span class="input-group-text password-toggle-icon" onclick="togglePassword('password', 'togglePasswordIcon')">
                                <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                              </span>
                            </div>
                          </div>
                        <div id="passwordCheck" class="mt-2"></div>
                      </div>
                      <div class="col-md-6 mb-4">
                      <label>Confirm Password</label>
                        <div class="input-group">
                          <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" onkeyup="validatePassword()" required placeholder="Confirm your new password">
                          <div class="input-group-append">
                            <span class="input-group-text password-toggle-icon" onclick="togglePasswordVisibility('confirmPassword', 'showConfirmPasswordBtn')">
                              <i class="fa fa-eye-slash" id="showConfirmPasswordBtn"></i>
                            </span>
                          </div>
                        </div>
                        <div id="passwordError" class="mt-2"></div>
                      </div>
                      <div class="col-md-6 mb-4">
                        <label>Department</label>
                        <select class="form-control" type="text" id="" name="sa_building" placeholder="Enter department" required>
                        <option value="">Select Department</option>
                          <optgroup label="Select Department">
                          <option value="Department օf Computer Studies">Department of Computer Studies</option>
                          <option value="Department օf Engineering">Department of Engineering</option>
                          <option value="Department օf Industrial Technology">Department of Industrial Technology</option>
                          <option value="Department օf Management Studies">Department of Management Studies</option>
                          <option value="Department օf Teacher Education">Department of Teacher Education</option></optgroup>
                        </select>
                      </div>
                      <div class="col-md-6 mb-4 w-100">
                        <label>Mobile</label>
                        <input class="form-control" id="" type="number" name="mobile" placeholder="Enter mobile number" required>
                      </div>
                      <div class="col-md-12 mb-4">
                        <label for="exampleInputEmail1">Email address</label>
                        <input class="form-control" id="emailInput" onkeyup="checkEmail()" type="email" aria-describedby="emailHelp" name="email" placeholder="Enter email" required><small class="form-text text-muted" id="emailHelp">We'll never share your email with anyone else.</small>
                      <div id="emailError" class="mt-2"></div></div>
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
                  <tr style="font-size: 16px; text-align: center;">
                    <th scope="col">Admin ID #</th>
                    <th scope="col">Admin Name</th>
                    <th scope="col">Building</th>
                    <th scope="col">Mobile</th>
                    <th scope="col">Email</th>
                    <th scope="col">Operation</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- PHP Database Display -->
                  <?php
                  $sql = "SELECT * FROM superadmin_tbl WHERE isValid = 1";
                  $result = mysqli_query($con, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                    $sa_id = $row['sa_id'];
                    $superadmin_id_number = $row['superadmin_id_number'];
                    $sa_fname = $row['sa_fname'];
                    $sa_lname = $row['sa_lname'];
                    $username = $row['username'];
                    $password = $row['password'];
                    $sa_building = $row['sa_building'];
                    $mobile = $row['mobile'];
                    $email = $row['email'];

                    echo '<tr style="font-size: 15px;">
                            <td><strong>Admin ID #:</strong> ' . $superadmin_id_number . '</td>
                            <td><strong>Admin Name:</strong> ' . $sa_lname . ', ' . $sa_fname . '</td>
                            <td><strong>Building:</strong> ' . $sa_building . '</td>
                            <td><strong>Mobile:</strong> ' . $mobile . '</td>
                            <td><strong>Email:</strong> ' . $email . '</td>
                            <td>
                              <div id="operation-field" style="word-spacing: 5px;">
                              <i class="btn btn-primary fa fa-pencil-square-o" data-toggle="modal" data-target="#myModal' . $sa_id . '"></i>

                                <div class="modal fade" id="myModal' . $sa_id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Update Admin Info</h4>
                                      </div>
                                      <div class="modal-body">
                                        <form method="POST" action="php/developeradminupdate.php?updateid=' . $sa_id . '">
                                          <div class="row mb-4">
                                            <div class="col-md-12">
                                              <br><label for="a_fname">Admin ID Number:</label>
                                              <input style="text-align: center;" type="text" disabled="" class="form-control" value="' . htmlspecialchars($superadmin_id_number) . '">
                                            </div>
                                            <div class="col-md-6">
                                              <br><label for="a_fname">First Name:</label>
                                              <input type="text" class="form-control" style="text-align: center;" name="sa_fname" value="' . htmlspecialchars($sa_fname) . '">
                                            </div>
                                            <div class="col-md-6">
                                              <br><label for="a_lname">Last Name:</label>
                                              <input type="text" class="form-control" style="text-align: center;" name="sa_lname" value="' . htmlspecialchars($sa_lname) . '">
                                            </div>
                                            <div class="col-md-12">
                                              <br><label for="username">Username:</label>
                                              <input type="text" class="form-control" style="text-align: center;" id="username" name="username" value="' . htmlspecialchars($username) . '">
                                            </div>
                                            <div class="col-md-6">
                                              <br>
                                              <label for="password">Password:</label>
                                              <div class="input-group">
                                              <input class="form-control" type="password" style="text-align: center;" onkeyup="checkPassword2(' . $sa_id . ')" name="password" id="password2_' . $sa_id . '" placeholder="Password">
                                                <div class="input-group-append">
                                                  <span class="input-group-text password-toggle-icon" onclick="togglePassword2(\'password2_' . $sa_id . '\', \'togglePasswordIcon2_' . $sa_id . '\')">
                                                    <i class="fa fa-eye-slash" id="togglePasswordIcon2_' . $sa_id . '"></i>
                                                  </span>
                                                </div>
                                              </div>
                                              <div id="passwordCheck2_' . $sa_id . '" class="mt-2"></div>                                            
                                            </div>
                                            <div class="col-md-6"><br>
                                            <label>Confirm Password</label>
                                              <div class="input-group">
                                                <input type="password" name="confirmPassword" style="text-align: center;" class="form-control" id="confirmPassword2_' . $sa_id . '" onkeyup="validatePassword2(' . $sa_id . ')" placeholder="Confirm your new password">
                                                <div class="input-group-append">
                                                  <span class="input-group-text password-toggle-icon" onclick="togglePasswordVisibility2(\'confirmPassword2_' . $sa_id . '\', \'showConfirmPasswordBtn2_' . $sa_id . '\')">
                                                    <i class="fa fa-eye-slash" id="showConfirmPasswordBtn2_' . $sa_id . '"></i>
                                                  </span>
                                                </div>
                                              </div>
                                              <div id="passwordError2_' . $sa_id . '" class="mt-2"></div>
                                            </div>
                                            <div class="col-md-6">
                                              <br><label for="department">Building:</label>
                                              <select class="form-control" style="text-align: center;" type="text" id="" name="sa_building">
                                                <optgroup label="Select Department">
                                                  <option value="Department օf Computer Studies"' . ($sa_building == "Department օf Computer Studies" ? ' selected' : '') . '>Department of Computer Studies</option>
                                                  <option value="Department օf Engineering"' . ($sa_building == "Department օf Engineering" ? ' selected' : '') . '>Department of Engineering</option>
                                                  <option value="Department օf Industrial Technology"' . ($sa_building == "Department օf Industrial Technology" ? ' selected' : '') . '>Department of Industrial Technology</option>
                                                  <option value="Department օf Management Studies"' . ($sa_building == "Department օf Management Studies" ? ' selected' : '') . '>Department of Management Studies</option>
                                                  <option value="Department օf Teacher Education"' . ($sa_building == "Department օf Teacher Education" ? ' selected' : '') . '>Department of Teacher Education</option>
                                                </optgroup>
                                              </select>                                              
                                            </div>
                                            <div class="col-md-6">
                                              <br><label for="mobile">Mobile:</label>
                                              <input type="number" class="form-control" style="text-align: center;" name="mobile" value="' . htmlspecialchars($mobile) . '">
                                            </div>
                                            <div class="col-md-12">
                                              <br><label for="email">Email:</label>
                                              <input type="email" style="text-align: center;" class="form-control" disabled value="' . htmlspecialchars($email) . '">
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button class="btn btn-primary" id="submitButton2_' . $sa_id . '" type="submit" name="submit">Save changes</button>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <i class="btn btn-danger fa fa-trash-o" onclick="demoSwal('.$sa_id.', \''.$sa_fname.'\', \''.$sa_lname.'\', \''.$superadmin_id_number.'\');"></i>
                                </div>
                            </td>
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
                        /* Remove arrow spinners for number input */
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
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
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
     <!-- Page Delete Prompt -->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script type="text/javascript">
    function demoSwal(sa_id, sa_fname, sa_lname, superadmin_id_number) {
      var admin_fname = "<?php echo $d_fname ?>";
      var admin_lname = "<?php echo $d_lname ?>";
      var sa_building = "<?php echo $sa_building ?>";
      swal({
            title: "Are you sure?",
            text: "This data will be moved to Account Bin.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                // Use Ajax to send a request to the PHP script
                $.ajax({
                    url: 'php/devadmindelete-temp.php',
                    type: 'POST',
                    data: {
                        deleteid: sa_id,
                        admin_fname: admin_fname,
                        admin_lname: admin_lname,
                        superadmin_id_number: superadmin_id_number,
                        sa_fname: sa_fname,
                        sa_lname: sa_lname,
                        sa_building: sa_building
                    },
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
        <script>
      // check if the logout query parameter is present in the URL
      const updateParams = new URLSearchParams(window.location.search);
      const update = updateParams.get('update');
      if (update === 'true') {
          // display a SweetAlert notification to the user
          swal({
              title: "Data has been updated!",
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
    <script>
        // check if the success query parameter is present in the URL
        const matchedParams = new URLSearchParams(window.location.search);
        const matched = matchedParams.get('matched');
        if (matched === 'false') {
          // display a SweetAlert notification to the user
          swal({
            title: "Registration Failed!",
            text: "Password is not matched.",
            type: "error",
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

        // check if the success query parameter is present in the URL
        const successParams = new URLSearchParams(window.location.search);
        const success = successParams.get('success');
        if (success === 'true') {
            // display a SweetAlert notification to the user
            swal({
                title: "Registration Success!",
                text: "Admin account has been added.",
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
        // check if the username_exists query parameter is present in the URL
        const username_existsParams = new URLSearchParams(window.location.search);
        const username_exists = username_existsParams.get('username_exists');
        if (username_exists === 'true') {
            // display a SweetAlert notification to the user
            swal({
                title: "The username you provided is already in use!",
                text: "Please choose another one.",
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
              }, 1000);
        }
          // check if the row_filled query parameter is present in the URL
          const row_filledParams = new URLSearchParams(window.location.search);
          const row_filled = row_filledParams.get('row_filled');
          if (row_filled === 'true') {
              // display a SweetAlert notification to the user
              swal({
                  title: "The teacher id number you provided is already in use!",
                  text: "Double check your teacher id number.",
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
              }, 1000); 
          }
    </script>
<script>
  function togglePassword2(inputId, iconId) {
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
  function togglePasswordVisibility2(inputId, iconId) {
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
  function validatePassword2(sa_id) {
    const submitButton2 = document.getElementById('submitButton2_'+sa_id);
    const password2 = document.getElementById("password2_"+sa_id).value;
    const confirmPassword2 = document.getElementById("confirmPassword2_"+sa_id).value;
    const passwordError2 = document.getElementById("passwordError2_"+sa_id);

    // Call checkPassword for detailed validation
    const isPasswordValid2 = checkPassword2(<?php echo $sa_id ?>);

    if (confirmPassword2 === "") {
      passwordError2.innerHTML = '<i></i>';
      submitButton2.disabled = true;
      return;
    }

    if (password2 === confirmPassword2 && isPasswordValid2) {
      passwordError2.innerHTML = "<i class='text text-success'>Passwords match.</i>";
      submitButton2.disabled = false;
    } else {
      passwordError2.innerHTML = "<i class='text text-danger'>Passwords do not match or are invalid.</i>";
      submitButton2.disabled = true;
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

  function checkPassword2(sa_id) {
    const passwordInput = document.getElementById('password2_'+sa_id);
    const passwordCheck = document.getElementById('passwordCheck2_'+sa_id);
    const submitButton2 = document.getElementById('submitButton2_'+sa_id);

    // Regular expressions for password validation
    const regexUpperCase = /^(?=.*[A-Z])/;
    const regexLowerCase = /^(?=.*[a-z])/;
    const regexNumber = /^(?=.*[0-9])/;
    const regexSpecialChar = /^(?=.*[!@#$%^&*()\-=_+[\]{};':"\\|,.<>/?~`])/;
    const regexLength = /^(?=.{8,})/;

    const password = passwordInput.value;

    if (password == "") {
      passwordCheck.innerHTML = '<i></i>';
      submitButton2.disabled = false;
      return false;
    }

    if (!password.match(regexUpperCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one uppercase letter.</i>';
      submitButton2.disabled = true;
      return false;
    }

    if (!password.match(regexLowerCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one lowercase letter.</i>';
      submitButton2.disabled = true;
      return false;
    }

    if (!password.match(regexNumber)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one number.</i>';
      submitButton2.disabled = true;
      return false;
    }

    if (!password.match(regexSpecialChar)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one special character.</i>';
      submitButton2.disabled = true;
      return false;
    }

    if (!password.match(regexLength)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must be at least 8 characters long.</i>';
      submitButton2.disabled = true;
      return false;
    }

    passwordCheck.innerHTML = '<i class="text-success">Password is valid.</i>';
    return true; // Added to indicate that the password is valid
  }
</script>
<script>
  const emailInput = document.getElementById('emailInput');
  const emailError = document.getElementById('emailError');

  function checkEmail() {
    const email = emailInput.value;
    const pattern = /^[A-Za-z0-9._%+-]+@cvsu\.edu\.ph$/;

    if (email == "") {
      emailError.innerHTML = '<i></i>';
      submitButton.disabled = true;
      return;
    }
    if (pattern.test(email)) {
      emailError.innerHTML = '<i class="text-success">Email is valid!</i>';
      submitButton.disabled = false;
    } else {
      emailError.innerHTML = '<i class="text-danger">Please enter a valid email using "@cvsu.edu.ph".</i>';
      submitButton.disabled = true;
    }
  };
</script>
  </body>
</html>