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
  $query3 = "SELECT * FROM year_tbl";
  $result3 = mysqli_query($con, $query3);
  $row3 = mysqli_fetch_assoc($result3);

  $g_year = $row3['year'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Add/Edit Students</title>
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
    <script src="js/jquery-3.3.1.min.js"></script>
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
          <h1><i class="fa fa-th-list"></i> Add/Edit Grades</h1>
          <p>Grades List</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"> Grades Settings</li>
          <li class="breadcrumb-item active"><a href="#">Add/Edit Grades</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            <button type="button" style="float:right; margin-bottom: 5px;" id="addstud" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
              Add New Grades Info
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
                      <h2 class="modal-title" id="myModalLabel">Add Student Grade</h2>
                    </div>
                  <div class="modal-body">
                  <form method="POST" action="php/superinsertgrade.php">
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <br><label>Student ID #</label>
                        <input class="form-control" type="number" name="student_id_number" placeholder="Enter student #" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>First Name</label>
                        <input class="form-control" type="text" name="first_name" placeholder="Enter first name" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Last Name</label>
                        <input class="form-control" type="text" name="last_name" placeholder="Enter last name" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Subject Code</label>
                        <input class="form-control" type="text" name="subject_code" placeholder="Enter subject code" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Subject Name</label>
                        <input class="form-control" type="text" name="subject_name" placeholder="Enter subject name" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Semester</label>
                      <?php 
                            include("php/connection.php");
                            $query = "SELECT * FROM semester_checker";
                            $result = mysqli_query($con, $query);
                            $row = mysqli_fetch_assoc($result);

                            $semester = $row['sem'];
                        ?>
                        <select class="form-control" name="semester">
                          <optgroup label="Select Semester">
                            <option value="First Semester" <?php echo ($semester == "First Semester" ? ' selected' : '') ?>>First Semester</option>
                            <option value="Second Semester" <?php echo ($semester == "Second Semester" ? ' selected' : '') ?>>Second Semester</option>
                            <option value="Midyear" <?php echo ($semester == "Midyear" ? ' selected' : '') ?>>Midyear</option>
                          </optgroup>
                        </select>                       
                      </div>
                      <div class="col-md-6">
                      <br><label>Grade</label>
                        <input class="form-control" type="text" name="grade" placeholder="Enter grade" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Year</label>
                      <select class="form-control" name="s_year">
                          <optgroup label="School Year">
                            <?php
                            $currentYear = date("Y");
                            $twoYearsAgo = $currentYear - 2;
                            $twoYearsAhead = $currentYear + 2;
                            for ($year = $twoYearsAgo; $year <= $twoYearsAhead; $year++) :
                                $schoolYearOption = $year . '-' . ($year + 1);
                            ?>
                                <option value="<?php echo $schoolYearOption; ?>" <?php echo ($currentYear == $year ? 'selected' : ''); ?>>
                                    <?php echo $schoolYearOption; ?>
                                </option>
                            <?php endfor; ?>
                          </optgroup>
                      </select>
                      </div>
                      <div class="col-md-6">
                      <br><label>Units</label>
                        <input class="form-control" type="number" name="units" placeholder="Enter units" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-default" data-dismiss="modal">Close</button>
                      <button class="btn btn-primary" id="submitButton" type="submit" name="isubmit">Save</button>
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
                      <th scope="col">Student ID #</th>
                      <th scope="col">Student Name</th>
                      <th scope="col">Operation</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    // Your existing PHP code for fetching students
                    $sql = "SELECT *
                            FROM docs_tbl
                            GROUP BY student_id_number";

                    $result_students = mysqli_query($con, $sql);

                    while ($row = mysqli_fetch_assoc($result_students)) {
                        $student_id_number = $row['student_id_number'];
                        $fname = $row['first_name'];
                        $lname = $row['last_name'];

                        echo '<tr style="font-size: 15px;">
                                <td scope="row"><strong>Student ID #: </strong>' . $student_id_number . '</td>
                                <td><strong>Student Name: </strong>' . $lname . ', ' . $fname . '</td>
                                <!-- Display other essential information here -->
                                <td><strong>Operation: </strong>
                                    <div id="operation-field" style="word-spacing: 5px;">
                                        <button type="button" class="btn btn-primary" style="margin-bottom: 5px;" data-toggle="modal" data-target="#myModal' . $student_id_number . '">
                                            View
                                        </button>
                                        <div class="modal fade" id="myModal' . $student_id_number . '" tabindex="-1" role="dialog" style="z-index: 9900;" aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModalLabel">Update Student Grade</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST">
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <br><label for="">Student ID Number:</label>
                                                                    <input type="text" style="text-align: center;" disabled="" class="form-control" value="' . htmlspecialchars($student_id_number) . '">
                                                                </div>
                                                            </div>
                                                            <div class="row mb-4">
                                                                <div class="col-md-12">
                                                                    <br><label for="">Student Name:</label>
                                                                    <input type="text" style="text-align: center;" disabled="" class="form-control" value="' . htmlspecialchars($fname) . ', ' . htmlspecialchars($lname) . '">
                                                                </div>
                                                            </div>
                                                            <div class="table-responsive">
                                                            <table class="table">
                                                              <thead>
                                                                <tr>
                                                                  <td>Subject Code</td>
                                                                  <td>Subject Name</td>
                                                                  <td>Year & Semester</td>
                                                                  <td>Grade</td>
                                                                  <td>Units</td>
                                                                </tr>
                                                              </thead>
                                                              <tbody>';
                                                              // Fetch all subjects for the current student
                                                              $subjects_query = "SELECT id, subject_code, subject_name, grade, units, semester
                                                                                FROM docs_tbl
                                                                                WHERE student_id_number = '$student_id_number'";
                                                                                $result_subjects = mysqli_query($con, $subjects_query);

                                                              while ($subject_row = mysqli_fetch_assoc($result_subjects)) {
                                                                $ggid = $subject_row['id'];
                                                                $subject_code = $subject_row['subject_code'];
                                                                $subject_name = $subject_row['subject_name'];
                                                                $semester = $subject_row['semester'];
                                                                $grade = $subject_row['grade'];
                                                                $units = $subject_row['units'];
                                                            
                                                                echo '<tr>
                                                                    <td><strong>Subject Code: </strong>' . htmlspecialchars($subject_code) . '</td>
                                                                    <td><strong>Subject Name: </strong>' . htmlspecialchars($subject_name) . '</td>
                                                                    <td><strong>Year & Semester: </strong>' . "(" . htmlspecialchars($g_year) . ")" . "<br>" . htmlspecialchars($semester) .'</td>
                                                                    <td><strong>Grade: </strong>
                                                                        <span class="display-mode">
                                                                            <!-- Display the grade value -->
                                                                            <span class="grade-value" style="display: block;">'.htmlspecialchars($grade).'</span>
                                                                        </span>
                                                                        <form action="" id="formGrade">
                                                                            <span class="edit-mode" style="display: none;">
                                                                                <!-- Input field for editing -->
                                                                                <input hidden name="student_id_number" value="'. htmlspecialchars($student_id_number) .'">
                                                                                <input class="form-control" style="width: 60px; margin: auto;" name="grade" type="number" value="'. htmlspecialchars($grade) .'">
                                                                            </span>
                                                                            <i class="fa fa-pencil-square-o edit-icon" data-ggid="' . $ggid . '" style="cursor: pointer;"></i>
                                                                            <i class="fa fa-times cancel-icon" style="cursor: pointer; display: none;"></i>
                                                                            <i class="fa fa-floppy-o save-icon" data-ggid="' . $ggid . '" style="cursor: pointer; display: none;"></i>
                                                                        </form>
                                                                    </td>
                                                                    <td><strong>Units: </strong>' . htmlspecialchars($units) . '</td>
                                                                </tr>';
                                                            }
                                                  echo '</tbody>
                                                        </table>
                                                    </div>';

                        // Modify the table class and ID here
                        echo '<div class="modal-footer">
                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </form>
                          </div>
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
              <script>
                  $(document).ready(function () {
                      // Handle click on edit icon
                      $('.edit-icon').click(function () {
                          var row = $(this).closest('tr');
                          var displayMode = row.find('.display-mode');
                          var editMode = row.find('.edit-mode');
                          var ggid = $(this).data('ggid'); // Get the ggid from the data attribute

                          console.log(ggid);

                          // Toggle display and edit modes
                          displayMode.hide();
                          editMode.show();

                          // Show cancel and save icons
                          row.find('.cancel-icon').show();
                          row.find('.save-icon').show();
                          row.find('.grade-value').hide();
                          row.find('.edit-icon').hide();
                      });

                      // Handle click on cancel icon
                      $('.cancel-icon').click(function () {
                          var row = $(this).closest('tr');
                          var displayMode = row.find('.display-mode');
                          var editMode = row.find('.edit-mode');

                          // Toggle display and edit modes
                          displayMode.show();
                          editMode.hide();

                          // Hide cancel and save icons
                          row.find('.cancel-icon').hide();
                          row.find('.save-icon').hide();
                          row.find('.grade-value').show();
                          row.find('.edit-icon').show();
                      });

                     // Handle click on save icon
                      $('.save-icon').click(function () {
                          var row = $(this).closest('tr');
                          var form = row.find('form');
                          var newGrade = $(this).closest('tr').find('input[name="grade"]').val();
                          var displayMode = row.find('.display-mode');
                          var editMode = row.find('.edit-mode');
                          var ggid = $(this).data('ggid'); // Get the ggid from the data attribute
                          var studentIdNumber = row.find('input[name="student_id_number"]').val(); // Get the student_id_number

                          // Manually append student_id_number and grade to the form data
                          var formData = form.serialize() + '&ggid=' + ggid + '&grade=' + newGrade + '&student_id_number=' + studentIdNumber;

                          // Perform AJAX request
                          $.ajax({
                              url: 'php/dev-grade-update.php',
                              type: 'POST',
                              data: formData,
                              dataType: 'json',
                              success: function (response) {
                                  if (response.success) {
                                      // Update the displayed value
                                      displayMode.text(newGrade); // or displayMode.val(newGrade);

                                      // Toggle display and edit modes
                                      displayMode.show();
                                      editMode.hide();

                                      // Hide cancel and save icons
                row.find('.cancel-icon, .save-icon').hide();
                
                // Show edit icon
                row.find('.edit-icon').show();

                                      swal({
                                          title: "Success",
                                          text: "The grade has been updated.",
                                          type: "success",
                                          showCancelButton: false,
                                          confirmButtonText: "OK",
                                          closeOnConfirm: true,
                                          closeOnCancel: true
                                      });
                                  } else {
                                      swal("Error", "Update failed. " + response.message, "error");
                                  }
                              },
                              error: function (xhr, status, error) {
                                  console.error(xhr.responseText); // Log the full response for debugging
                                  swal("Error", "Failed to communicate with the server. " + error, "error");
                              }
                          });
                      });
                  });
              </script>
  
              <style>
                strong{
                  display: none;
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

                /* CSS media query for mobile devices */
                @media (max-width: 767px) {
                  #addstud{
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
                  #sampleTables thead, tr{
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
    <!-- Page Delete Prompt-->
    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
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
  const successParams = new URLSearchParams(window.location.search);
  const success = successParams.get('success');
  if (success === 'true') {
    // display a SweetAlert notification to the user
    swal({
      title: "Grade added.",
      text: "Student grade has been added.",
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