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
          <h1><i class="fa fa-th-list"></i> Add/Edit Students</h1>
          <p>Student List</p>
        </div>
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item">Student Settings</li>
          <li class="breadcrumb-item active"><a href="#">Add/Edit Students</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            <button type="button" style="float:right; margin-bottom: 5px;" id="addstud" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
              Add New Student Info
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
                      <h2 class="modal-title" id="myModalLabel">Add New Student Info</h2>
                    </div>
                  <div class="modal-body">
                  <form method="POST" action="php/developerinsertstud.php">
                    <div class="row mb-4">
                      <div class="col-md-12">
                        <br><label>Student ID #</label>
                        <input class="form-control" type="number" name="student_id_number" placeholder="Enter student #" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>First Name</label>
                        <input class="form-control" type="text" name="fname" placeholder="Enter first name" required>
                      </div>
                      <div class="col-md-6">
                      <br><label>Last Name</label>
                        <input class="form-control" type="text" name="lname" placeholder="Enter last name" required>
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6 mb-4">
                    <label for="department">Department:</label>
                    <select class="form-control" type="text" id="department" name="department" placeholder="Enter department" required>
                        <option value="">Select Department</option>
                        <optgroup label="Select Department">
                            <option value="Department of Computer Studies">Department of Computer Studies</option>
                            <option value="Department of Engineering">Department of Engineering</option>
                            <option value="Department of Industrial Technology">Department of Industrial Technology</option>
                            <option value="Department of Management Studies">Department of Management Studies</option>
                            <option value="Department of Teacher Education">Department of Teacher Education</option>
                            <option value="others">Others</option>
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-6 mb-4">
                    <label for="department">Section:</label>
                    <select name="section" class="form-control" id="section" required>
                        <option placeholder="Enter Section" value="">Select Section</option>
                    </select>
                </div>

                <script>
                    // Get the department and section elements
                    var departmentSelect = document.getElementById('department');
                    var sectionSelect = document.getElementById('section');

                    // Define the sections for each department
                    var departmentSections = {
                        'Department of Computer Studies'        : ['BSINFOTECH','BSCOS'],
                        'Department of Engineering'             : ['BSEE', 'BSCPE'],
                        'Department of Industrial Technology'   : ['BSIT'],
                        'Department of Management Studies'      : ['BSBM', 'BSHM'],
                        'Department of Teacher Education'       : ['BSE', 'BTVTED'],
                        'others'                                : ['OTHERS'],
                        // Add more departments and sections as needed
                    };

                    // Function to update the section options based on the selected department
                    function updateSections() {
                        var selectedDepartment = departmentSelect.value;
                        var sections = departmentSections[selectedDepartment];

                        // Clear the current section options
                        sectionSelect.innerHTML = '';

                        // Add the new section options based on the selected department
                        if (sections) {
                            for (var i = 0; i < sections.length; i++) {
                                var optgroup = document.createElement('optgroup');
                                optgroup.label = sections[i];

                                // Add section options based on the selected department
                                if (sections[i] === 'BSBM') {
                                    addBSBMSections(optgroup);
                                } else if (sections[i] === 'BSINFOTECH') {
                                    addBSINFOTECHSections(optgroup);
                                } else if (sections[i] === 'BSCOS') {
                                    addBSCOSSections(optgroup);
                                } else if (sections[i] === 'BSEE') {
                                    addBSEESections(optgroup);
                                } else if (sections[i] === 'BSE') {
                                    addBSESections(optgroup);
                                } else if (sections[i] === 'BSIT') {
                                    addBSITSections(optgroup);
                                } else if (sections[i] === 'BSHM') {
                                    addBSHMSections(optgroup);
                                } else if (sections[i] === 'BTVTED') {
                                    addBTVTEDSections(optgroup);
                                } else if (sections[i] === 'BSCPE') {
                                    addBSCPESections(optgroup);
                                } else if (sections[i] === 'OTHERS') {
                                    addOTHERSSections(optgroup);
                                }

                                sectionSelect.appendChild(optgroup);
                            }
                        }
                    }

                    // Function to add BSBM sections
                    function addOTHERSSections(optgroup) {
                        var othersSections = [
                          'RESIDENCY',
                          'TCP A',
                          'TCP B'
                            // Add more others sections as needed
                        ];

                        for (var i = 0; i < othersSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = othersSections[i];
                            option.textContent = othersSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSBM sections
                    function addBSBMSections(optgroup) {
                        var bsbmSections = [
                            'BSBM 102A',
                            'BSBM 102B',
                            'BSBM 102C',
                            'BSBM 102D',
                            'BSBM 102E',
                            'BSBM 102F',
                            'BSBM 102G',
                            'BSBM 102H',
                            'BSBM 102I',
                            'BSBM 102J',
                            'BSBM 202A',
                            'BSBM 202B',
                            'BSBM 202C',
                            'BSBM 202D',
                            'BSBM 202E',
                            'BSBM 202F',
                            'BSBM 302A',
                            'BSBM 302B',
                            'BSBM 302C',
                            'BSBM 302D',
                            'BSBM 302E',
                            'BSBM 302F',
                            'BSBM 302G',
                            'BSBM 402A',
                            'BSBM 402B',
                            'BSBM 402C',
                            'BSBM 402D',
                            'BSBM 402E',
                            'BSBM 402F',
                            'BSBM 402G'

                            // Add more BSBM sections as needed
                        ];

                        for (var i = 0; i < bsbmSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bsbmSections[i];
                            option.textContent = bsbmSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSHM sections
                    function addBSHMSections(optgroup) {
                        var bshmSections = [
                            'BSHM 102A',
                            'BSHM 102B',
                            'BSHM 102C',
                            'BSHM 102D',
                            'BSHM 102E',
                            'BSHM 202A',
                            'BSHM 202B',
                            'BSHM 202C',
                            'BSHM 202D',
                            'BSHM 202E',
                            'BSHM 302A',
                            'BSHM 302B',
                            'BSHM 302C',
                            'BSHM 302D',
                            'BSHM 302E',
                            'BSHM 402A',
                            'BSHM 402B',
                            'BSHM 402C',
                            'BSHM 402D',
                            'BSHM 402E'

                            // Add more BSHM sections as needed
                        ];

                        for (var i = 0; i < bshmSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bshmSections[i];
                            option.textContent = bshmSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSINFOTECH sections
                    function addBSINFOTECHSections(optgroup) {
                        var bsinfotechSections = [
                            'BSINFOTECH 102A',
                            'BSINFOTECH 102B',
                            'BSINFOTECH 102C',
                            'BSINFOTECH 102D',
                            'BSINFOTECH 102E',
                            'BSINFOTECH 202A',
                            'BSINFOTECH 202B',
                            'BSINFOTECH 202C',
                            'BSINFOTECH 202D',
                            'BSINFOTECH 202E',
                            'BSINFOTECH 302A',
                            'BSINFOTECH 302B',
                            'BSINFOTECH 302C',
                            'BSINFOTECH 302D',
                            'BSINFOTECH 302E',
                            'BSINFOTECH 402A',
                            'BSINFOTECH 402A 2018',
                            'BSINFOTECH 402B',
                            'BSINFOTECH 402B 2018',
                            'BSINFOTECH 402C',
                            'BSINFOTECH 402C 2018',
                            'BSINFOTECH 402D',
                            'BSINFOTECH 402D 2018',
                            'BSINFOTECH 402E 2018'
                            // Add more BSINFOTECH sections as needed
                        ];

                        for (var i = 0; i < bsinfotechSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bsinfotechSections[i];
                            option.textContent = bsinfotechSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSCOS sections
                    function addBSCOSSections(optgroup) {
                        var bscosSections = [
                            'BSCOS 102A',
                            'BSCOS 102B',
                            'BSCOS 102C',
                            'BSCOS 102D',
                            'BSCOS 202A',
                            'BSCOS 202B',
                            'BSCOS 302A',
                            'BSCOS 302B',
                            'BSCOS 402A',
                            'BSCOS 402B',
                            'BSCOS 402_PETITION'
                            // Add more BSCOS sections as needed
                        ];

                        for (var i = 0; i < bscosSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bscosSections[i];
                            option.textContent = bscosSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSEE sections
                    function addBSEESections(optgroup) {
                        var bseeSections = [
                            'BSEE 102A',
                            'BSEE 102B',
                            'BSEE 102C',
                            'BSEE 202A',
                            'BSEE 202B',
                            'BSEE 202C',
                            'BSEE 202D',
                            'BSEE 302A',
                            'BSEE 302B',
                            'BSEE 302C',
                            'BSEE 302C PETITION',
                            'BSEE 402 PETITION_EENG 200C',
                            'BSEE 402A',
                            'BSEE 402A PETITION_EENG 197A',
                            'BSEE 402B',
                            'BSEE 402B PETITION_EENG 197A',
                            'BSEE 402C',
                            'BSEE 402C PETITION_EENG 197A'
                            // Add more BSEE sections as needed
                        ];

                        for (var i = 0; i < bseeSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bseeSections[i];
                            option.textContent = bseeSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSCPE sections
                    function addBSCPESections(optgroup) {
                        var bscpeSections = [
                            'BSCPE 102A',
                            'BSCPE 102A NYT',
                            'BSCPE 102B',
                            'BSCPE 102B NYT',
                            'BSCPE 102C',
                            'BSCPE 202A',
                            'BSCPE 202A NYT',
                            'BSCPE 202B',
                            'BSCPE 202B NYT',
                            'BSCPE 202C',
                            'BSCPE 302A',
                            'BSCPE 302B',
                            'BSCPE 402A',
                            'BSCPE 402B',
                            'BSCPE 402C'

                            // Add more BSCPE sections as needed
                        ];

                        for (var i = 0; i < bscpeSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bscpeSections[i];
                            option.textContent = bscpeSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSIT sections
                    function addBSITSections(optgroup) {
                        var bsitSections = [
                            'BSIT 102A AUTO',
                            'BSIT 102A DRAF',
                            'BSIT 102A ELEC',
                            'BSIT 102A ELEX',
                            'BSIT 102A FAT',
                            'BSIT 102A HVAC-R',
                            'BSIT 102A MECH',
                            'BSIT 102A SMTE',
                            'BSIT 102A WAFT',
                            'BSIT 102B AUTO',
                            'BSIT 102B DRAF',
                            'BSIT 102B ELEC',
                            'BSIT 102B ELEX',
                            'BSIT 202A AUTO',
                            'BSIT 202A DRAF',
                            'BSIT 202A ELEC',
                            'BSIT 202A ELEX',
                            'BSIT 202A FAT',
                            'BSIT 202A HVAC-R',
                            'BSIT 202A MECH',
                            'BSIT 202A SMTE',
                            'BSIT 202A WAFT',
                            'BSIT 302A AUTO',
                            'BSIT 302A DRAF',
                            'BSIT 302A ELEC',
                            'BSIT 302A ELEX',
                            'BSIT 302A FAT',
                            'BSIT 302A HVAC-R',
                            'BSIT 302A MECH',
                            'BSIT 302A SMTE',
                            'BSIT 302A WAFT',
                            'BSIT 402 AUTO OJT_BATCH 2017',
                            'BSIT 402 DRAF OJT_BATCH 2017',
                            'BSIT 402 ELEC OJT_BATCH 2017',
                            'BSIT 402 ELEX OJT_BATCH 2017',
                            'BSIT 402 MECH OJT_BATCH 2017',
                            'BSIT 402 RAC OJT_BATCH 2017',
                            'BSIT 402 SMTE OJT_BATCH 2017',
                            'BSIT 402 WAFT OJT_BATCH 2017',
                            'BSIT 402A AUTO',
                            'BSIT 402A AUTO 2018',
                            'BSIT 402A DRAF',
                            'BSIT 402A DRAF 2018',
                            'BSIT 402A ELEC',
                            'BSIT 402A ELEC 2018',
                            'BSIT 402A ELEX',
                            'BSIT 402A ELEX 2018',
                            'BSIT 402A FAT',
                            'BSIT 402A FAT 2018',
                            'BSIT 402A HVAC-R',
                            'BSIT 402A HVAC-R 2018',
                            'BSIT 402A MECH',
                            'BSIT 402A MECH 2018',
                            'BSIT 402A SMTE',
                            'BSIT 402A SMTE 2018',
                            'BSIT 402A WAFT',
                            'BSIT 402A WAFT 2018',
                            'BSIT 402B AUTO',
                            'BSIT 402B DRAF',
                            'BSIT 402B ELEC',
                            'BSIT 402B ELEC 2018'
                            // Add more BSIT sections as needed
                        ];

                        for (var i = 0; i < bsitSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bsitSections[i];
                            option.textContent = bsitSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Function to add BSCPE sections
                    function addBSESections(optgroup) {
                        var bseSections = [
                            'BSE 102 MATH',
                            'BSE 102 SCIENCE',
                            'BSE 102A ENGL',
                            'BSE 102B ENGL',
                            'BSE 102C ENGL',
                            'BSE 202 MATH',
                            'BSE 202 SCIENCE',
                            'BSE 202A ENGL',
                            'BSE 202B ENGL',
                            'BSE 202C ENGL',
                            'BSE 302 MATH',
                            'BSE 302 SCIENCE',
                            'BSE 302A ENGL',
                            'BSE 302B ENGL',
                            'BSE 302C ENGL',
                            'BSE 402 MATH',
                            'BSE 402 SCIENCE',
                            'BSE 402A ENGL',
                            'BSE 402B ENGL'

                            // Add more BSE sections as needed
                        ];

                        for (var i = 0; i < bseSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = bseSections[i];
                            option.textContent = bseSections[i];
                            optgroup.appendChild(option);
                        }
                    }
                    

                    // Function to add BTVTED sections
                    function addBTVTEDSections(optgroup) {
                        var btvtedSections = [
                            'BTVTED 102A',
                            'BTVTED 102B',
                            'BTVTED 202A AUTO',
                            'BTVTED 202A ELEC',
                            'BTVTED 202A FSM',
                            'BTVTED 202A GFD',
                            'BTVTED 202A WAFT',
                            'BTVTED 202B AUTO',
                            'BTVTED 202B ELEC',
                            'BTVTED 202B FSM',
                            'BTVTED 202B GFD',
                            'BTVTED 202B WAFT',
                            'BTVTED 302A AUTO',
                            'BTVTED 302A ELEC',
                            'BTVTED 302A ELEX',
                            'BTVTED 302A GFD',
                            'BTVTED 302A HRS',
                            'BTVTED 302A MECH',
                            'BTVTED 302A WAFT',
                            'BTVTED 302B AUTO',
                            'BTVTED 302B ELEC',
                            'BTVTED 302B ELEX',
                            'BTVTED 302B GFD',
                            'BTVTED 302B HRS',
                            'BTVTED 302B MECH',
                            'BTVTED 302B WAFT',
                            'BTVTED 402 AUTO 2018',
                            'BTVTED 402 ELEC 2018',
                            'BTVTED 402 ELEX 2018',
                            'BTVTED 402 FGD 2018',
                            'BTVTED 402 HRS 2018',
                            'BTVTED 402 MECH 2018',
                            'BTVTED 402 WAFT 2018',
                            'BTVTED 402A AUTO',
                            'BTVTED 402A ELEC',
                            'BTVTED 402A ELEX',
                            'BTVTED 402A GFD',
                            'BTVTED 402A HRS',
                            'BTVTED 402A MECH',
                            'BTVTED 402A WAFT'
                            // Add more BTVTED sections as needed
                        ];

                        for (var i = 0; i < btvtedSections.length; i++) {
                            var option = document.createElement('option');
                            option.value = btvtedSections[i];
                            option.textContent = btvtedSections[i];
                            optgroup.appendChild(option);
                        }
                    }

                    // Add event listener to the department select element
                    departmentSelect.addEventListener('change', updateSections);

                    // Initial update of section options
                    updateSections();
                </script>
                       
                      <div class="clearfix"></div>
                      <div class="col-md-6 mb-4">
                        <label>Mobile</label>
                        <input class="form-control" type="number" name="mobile" placeholder="Enter mobile number" required>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-6 mb-4">
                        <label>Email</label>
                        <input class="form-control" id="emailInput" onkeyup="checkEmail()" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" required><small class="form-text text-muted" id="emailHelp">We'll never share your email with anyone else.</small>
                        <div id="emailError" class="mt-2"></div> 
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-default" data-dismiss="modal">Close</button>
                      <button class="btn btn-primary" id="submitButton" type="submit" name="isubmit">Save changes</button>
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
                      <th scope="col">Section</th>
                      <th scope="col">Department</th>
                      <th scope="col">Mobile</th>
                      <th scope="col">Email</th>
                      <th scope="col">Operation</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!--PHP Database Display-->
                    <?php
                    $sql = "SELECT *
                            FROM students_tbl
                            WHERE IFNULL(username, '') <> '' AND IFNULL(password, '') <> '' AND isValid = 1";
                      $result = mysqli_query($con, $sql);
                      while ($row = mysqli_fetch_assoc($result)){
                        $s_id = $row['s_id'];
                        $student_id_number = $row['student_id_number'];
                        $fname = $row['fname'];
                        $lname = $row['lname'];
                        $username = $row['username'];
                        $password = $row['password'];
                        $section = $row['section'];
                        $s_department = $row['department'];
                        $mobile = $row['mobile'];
                        $email = $row['email'];

                        echo '<tr style="font-size: 15px;">
                                <td scope = "row"><strong>Student ID #: </strong>'.$student_id_number.'</td>
                                    <td><strong>Student Name: </strong>'.$lname.', '.$fname.'</td>
                                    <td><strong>Section: </strong>'.$section.'</td>
                                    <td><strong>Department: </strong>'.$s_department.'</td>
                                    <td><strong>Mobile: </strong>'.$mobile.'</td>
                                    <td><strong>Email: </strong>'.$email.'</td>
                                    <td>
                                      <div id="operation-field" style="word-spacing: 5px;">
                                        <i class="btn btn-primary fa fa-pencil-square-o" data-toggle="modal" data-target="#myModal'.$s_id.'"></i>

                                      <div class="modal fade" id="myModal'.$s_id.'" tabindex="-1" role="dialog" style="z-index: 9900;" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h4 class="modal-title" id="myModalLabel">Update Student Info</h4>
                                            </div>
                                            <div class="modal-body">
                                            <form method="POST" action="php/developerupdatestud.php?updateid='.$s_id.'">
                                                  <div class="row mb-4">
                                                    <div class="col-md-12">
                                                      <br><label for="a_fname">Student ID Number:</label>
                                                      <input type="text" style="text-align: center;" disabled="" class="form-control" value="' . htmlspecialchars($student_id_number) . '">
                                                    </div>
                                                    <div class="col-md-6">
                                                      <br><label for="a_fname">First Name:</label>
                                                      <input type="text" style="text-align: center;" class="form-control" name="fname" value="' . htmlspecialchars($fname) . '">
                                                    </div>
                                                    <div class="col-md-6">
                                                      <br><label for="a_lname">Last Name:</label>
                                                      <input type="text" style="text-align: center;" class="form-control" name="lname" value="' . htmlspecialchars($lname) . '">
                                                    </div>
                                                    <div class="col-md-12">
                                                      <br><label for="username">Username:</label>
                                                      <input type="text" style="text-align: center;" class="form-control" name="username" value="' . htmlspecialchars($username) . '">
                                                    </div>
                                                    <div class="col-md-6">
                                                    <br>
                                                    <label for="password">Password:</label>
                                                    <div class="input-group">
                                                      <input class="form-control"style="text-align: center;"  type="password" onkeyup="checkPassword2(' . $s_id . ')" name="password" id="password2_' . $s_id . '" placeholder="Password">
                                                      <div class="input-group-append">
                                                      <span class="input-group-text password-toggle-icon" onclick="togglePassword2(\'password2_' . $s_id . '\', \'togglePasswordIcon2_' . $s_id . '\')">
                                                        <i class="fa fa-eye-slash" id="togglePasswordIcon2_' . $s_id . '"></i>
                                                      </span>
                                                      </div>
                                                    </div>
                                                    <div id="passwordCheck2_' . $s_id . '" class="mt-2"></div>
                                                  </div>
                                                  <div class="col-md-6"><br>
                                                  <label>Confirm Password</label>
                                                    <div class="input-group">
                                                      <input type="password"style="text-align: center;"  name="confirmPassword" class="form-control" id="confirmPassword2_' . $s_id . '" onkeyup="validatePassword2(' . $s_id . ')" placeholder="Confirm your new password">
                                                      <div class="input-group-append">
                                                        <span class="input-group-text password-toggle-icon" onclick="togglePasswordVisibility2(\'confirmPassword2_' . $s_id . '\', \'showConfirmPasswordBtn2_' . $s_id . '\')">
                                                          <i class="fa fa-eye-slash" id="showConfirmPasswordBtn2_' . $s_id . '"></i>
                                                        </span>
                                                      </div>
                                                    </div>
                                                    <div id="passwordError2_' . $s_id . '" class="mt-2"></div>
                                                  </div>
                                                  </div>
                                                  <div class="row mb-4">
                                                    <div class="col-md-6 mb-4">
                                                      <label for="department">Section:</label>
                                                      <select name="section" style="text-align: center;" class="form-control" id="section" required>
                                                        <optgroup label="BSBM">
                                                          <option value="BSBM 102A"' . ($section == "BSBM 102A" ? ' selected' : '') . '>BSBM 102A</option>
                                                          <option value="BSBM 102B"' . ($section == "BSBM 102B" ? ' selected' : '') . '>BSBM 102B</option>
                                                          <option value="BSBM 102C"' . ($section == "BSBM 102C" ? ' selected' : '') . '>BSBM 102C</option>
                                                          <option value="BSBM 102D"' . ($section == "BSBM 102D" ? ' selected' : '') . '>BSBM 102D</option>
                                                          <option value="BSBM 102E"' . ($section == "BSBM 102E" ? ' selected' : '') . '>BSBM 102E</option>
                                                          <option value="BSBM 102F"' . ($section == "BSBM 102F" ? ' selected' : '') . '>BSBM 102F</option>
                                                          <option value="BSBM 102G"' . ($section == "BSBM 102G" ? ' selected' : '') . '>BSBM 102G</option>
                                                          <option value="BSBM 102H"' . ($section == "BSBM 102H" ? ' selected' : '') . '>BSBM 102H</option>
                                                          <option value="BSBM 102I"' . ($section == "BSBM 102I" ? ' selected' : '') . '>BSBM 102I</option>
                                                          <option value="BSBM 102J"' . ($section == "BSBM 102J" ? ' selected' : '') . '>BSBM 102J</option>
                                                          <option value="BSBM 202A"' . ($section == "BSBM 202A" ? ' selected' : '') . '>BSBM 202A</option>
                                                          <option value="BSBM 202B"' . ($section == "BSBM 202B" ? ' selected' : '') . '>BSBM 202B</option>
                                                          <option value="BSBM 202C"' . ($section == "BSBM 202C" ? ' selected' : '') . '>BSBM 202C</option>
                                                          <option value="BSBM 202D"' . ($section == "BSBM 202D" ? ' selected' : '') . '>BSBM 202D</option>
                                                          <option value="BSBM 202E"' . ($section == "BSBM 202E" ? ' selected' : '') . '>BSBM 202E</option>
                                                          <option value="BSBM 202F"' . ($section == "BSBM 202F" ? ' selected' : '') . '>BSBM 202F</option>
                                                          <option value="BSBM 302A"' . ($section == "BSBM 302A" ? ' selected' : '') . '>BSBM 302A</option>
                                                          <option value="BSBM 302B"' . ($section == "BSBM 302B" ? ' selected' : '') . '>BSBM 302B</option>
                                                          <option value="BSBM 302C"' . ($section == "BSBM 302C" ? ' selected' : '') . '>BSBM 302C</option>
                                                          <option value="BSBM 302D"' . ($section == "BSBM 302D" ? ' selected' : '') . '>BSBM 302D</option>
                                                          <option value="BSBM 302E"' . ($section == "BSBM 302E" ? ' selected' : '') . '>BSBM 302E</option>
                                                          <option value="BSBM 302F"' . ($section == "BSBM 302F" ? ' selected' : '') . '>BSBM 302F</option>
                                                          <option value="BSBM 302G"' . ($section == "BSBM 302G" ? ' selected' : '') . '>BSBM 302G</option>
                                                          <option value="BSBM 402A"' . ($section == "BSBM 402A" ? ' selected' : '') . '>BSBM 402A</option>
                                                          <option value="BSBM 402B"' . ($section == "BSBM 402B" ? ' selected' : '') . '>BSBM 402B</option>
                                                          <option value="BSBM 402C"' . ($section == "BSBM 402C" ? ' selected' : '') . '>BSBM 402C</option>
                                                          <option value="BSBM 402D"' . ($section == "BSBM 402D" ? ' selected' : '') . '>BSBM 402D</option>
                                                          <option value="BSBM 402E"' . ($section == "BSBM 402E" ? ' selected' : '') . '>BSBM 402E</option>
                                                          <option value="BSBM 402F"' . ($section == "BSBM 402F" ? ' selected' : '') . '>BSBM 402F</option>
                                                          <option value="BSBM 402G"' . ($section == "BSBM 402G" ? ' selected' : '') . '>BSBM 402G</option>
                                                        </optgroup>
                                                        <optgroup label="BSCOS">
                                                          <option value="BSCOS 102A"' . ($section == "BSCOS 102A" ? ' selected' : '') . '>BSCOS 102A</option>
                                                          <option value="BSCOS 102B"' . ($section == "BSCOS 102B" ? ' selected' : '') . '>BSCOS 102B</option>
                                                          <option value="BSCOS 102C"' . ($section == "BSCOS 102C" ? ' selected' : '') . '>BSCOS 102C</option>
                                                          <option value="BSCOS 102D"' . ($section == "BSCOS 102D" ? ' selected' : '') . '>BSCOS 102D</option>
                                                          <option value="BSCOS 202A"' . ($section == "BSCOS 202A" ? ' selected' : '') . '>BSCOS 202A</option>
                                                          <option value="BSCOS 202B"' . ($section == "BSCOS 202B" ? ' selected' : '') . '>BSCOS 202B</option>
                                                          <option value="BSCOS 302A"' . ($section == "BSCOS 302A" ? ' selected' : '') . '>BSCOS 302A</option>
                                                          <option value="BSCOS 302B"' . ($section == "BSCOS 302B" ? ' selected' : '') . '>BSCOS 302B</option>
                                                          <option value="BSCOS 402A"' . ($section == "BSCOS 402A" ? ' selected' : '') . '>BSCOS 402A</option>
                                                          <option value="BSCOS 402B"' . ($section == "BSCOS 402B" ? ' selected' : '') . '>BSCOS 402B</option>
                                                          <option value="BSCOS 402_PETITION">BSCOS 402_PETITION</option>
                                                        </optgroup>
                                                        <optgroup label="BSCPE">
                                                          <option value="BSCPE 102A"' . ($section == "BSCPE 102A" ? ' selected' : '') . '>BSCPE 102A</option>
                                                          <option value="BSCPE 102A NYT"' . ($section == "BSCPE 102A NYT" ? ' selected' : '') . '>BSCPE 102A NYT</option>
                                                          <option value="BSCPE 102B"' . ($section == "BSCPE 102B" ? ' selected' : '') . '>BSCPE 102B</option>
                                                          <option value="BSCPE 102B NYT"' . ($section == "BSCPE 102B NYT" ? ' selected' : '') . '>BSCPE 102B NYT</option>
                                                          <option value="BSCPE 102C"' . ($section == "BSCPE 102C" ? ' selected' : '') . '>BSCPE 102C</option>
                                                          <option value="BSCPE 202A"' . ($section == "BSCPE 202A" ? ' selected' : '') . '>BSCPE 202A</option>
                                                          <option value="BSCPE 202A NYT"' . ($section == "BSCPE 202A NYT" ? ' selected' : '') . '>BSCPE 202A NYT</option>
                                                          <option value="BSCPE 202B"' . ($section == "BSCPE 202B" ? ' selected' : '') . '>BSCPE 202B</option>
                                                          <option value="BSCPE 202B NYT"' . ($section == "BSCPE 202B NYT" ? ' selected' : '') . '>BSCPE 202B NYT</option>
                                                          <option value="BSCPE 202C"' . ($section == "BSCPE 202C" ? ' selected' : '') . '>BSCPE 202C</option>
                                                          <option value="BSCPE 302A"' . ($section == "BSCPE 302A" ? ' selected' : '') . '>BSCPE 302A</option>
                                                          <option value="BSCPE 302B"' . ($section == "BSCPE 302B" ? ' selected' : '') . '>BSCPE 302B</option>
                                                          <option value="BSCPE 402A"' . ($section == "BSCPE 402A" ? ' selected' : '') . '>BSCPE 402A</option>
                                                          <option value="BSCPE 402B"' . ($section == "BSCPE 402B" ? ' selected' : '') . '>BSCPE 402B</option>
                                                          <option value="BSCPE 402C"' . ($section == "BSCPE 402C" ? ' selected' : '') . '>BSCPE 402C</option>
                                                        </optgroup>
                                                        <optgroup label="BSE">
                                                          <option value="BSE 102 MATH"' . ($section == "BSE 102 MATH" ? ' selected' : '') . '>BSE 102 MATH</option>
                                                          <option value="BSE 102 SCIENCE"' . ($section == "BSE 102 SCIENCE" ? ' selected' : '') . '>BSE 102 SCIENCE</option>
                                                          <option value="BSE 102A ENGL"' . ($section == "BSE 102A ENGL" ? ' selected' : '') . '>BSE 102A ENGL</option>
                                                          <option value="BSE 102B ENGL"' . ($section == "BSE 102B ENGL" ? ' selected' : '') . '>BSE 102B ENGL</option>
                                                          <option value="BSE 102C ENGL"' . ($section == "BSE 102C ENGL" ? ' selected' : '') . '>BSE 102C ENGL</option>
                                                          <option value="BSE 202 MATH"' . ($section == "BSE 202 MATH" ? ' selected' : '') . '>BSE 202 MATH</option>
                                                          <option value="BSE 202 SCIENCE"' . ($section == "BSE 202 SCIENCE" ? ' selected' : '') . '>BSE 202 SCIENCE</option>
                                                          <option value="BSE 202A ENGL"' . ($section == "BSE 202A ENGL" ? ' selected' : '') . '>BSE 202A ENGL</option>
                                                          <option value="BSE 202B ENGL"' . ($section == "BSE 202B ENGL" ? ' selected' : '') . '>BSE 202B ENGL</option>
                                                          <option value="BSE 202C ENGL"' . ($section == "BSE 202C ENGL" ? ' selected' : '') . '>BSE 202C ENGL</option>
                                                          <option value="BSE 302 MATH"' . ($section == "BSE 302 MATH" ? ' selected' : '') . '>BSE 302 MATH</option>
                                                          <option value="BSE 302 SCIENCE"' . ($section == "BSE 302 SCIENCE" ? ' selected' : '') . '>BSE 302 SCIENCE</option>
                                                          <option value="BSE 302A ENGL"' . ($section == "BSE 302A ENGL" ? ' selected' : '') . '>BSE 302A ENGL</option>
                                                          <option value="BSE 302B ENGL"' . ($section == "BSE 302B ENGL" ? ' selected' : '') . '>BSE 302B ENGL</option>
                                                          <option value="BSE 302C ENGL"' . ($section == "BSE 302C ENGL" ? ' selected' : '') . '>BSE 302C ENGL</option>
                                                          <option value="BSE 402 MATH"' . ($section == "BSE 402 MATH" ? ' selected' : '') . '>BSE 402 MATH</option>
                                                          <option value="BSE 402 SCIENCE"' . ($section == "BSE 402 SCIENCE" ? ' selected' : '') . '>BSE 402 SCIENCE</option>
                                                          <option value="BSE 402A ENGL"' . ($section == "BSE 402A ENGL" ? ' selected' : '') . '>BSE 402A ENGL</option>
                                                          <option value="BSE 402B ENGL"' . ($section == "BSE 402B ENGL" ? ' selected' : '') . '>BSE 402B ENGL</option>
                                                        </optgroup>
                                                        <optgroup label="BSEE">
                                                          <option value="BSEE 102A"' . ($section == "BSEE 102A" ? ' selected' : '') . '>BSEE 102A</option>
                                                          <option value="BSEE 102B"' . ($section == "BSEE 102B" ? ' selected' : '') . '>BSEE 102B</option>
                                                          <option value="BSEE 102C"' . ($section == "BSEE 102C" ? ' selected' : '') . '>BSEE 102C</option>
                                                          <option value="BSEE 202A"' . ($section == "BSEE 202A" ? ' selected' : '') . '>BSEE 202A</option>
                                                          <option value="BSEE 202B"' . ($section == "BSEE 202B" ? ' selected' : '') . '>BSEE 202B</option>
                                                          <option value="BSEE 202C"' . ($section == "BSEE 202C" ? ' selected' : '') . '>BSEE 202C</option>
                                                          <option value="BSEE 202D"' . ($section == "BSEE 202D" ? ' selected' : '') . '>BSEE 202D</option>
                                                          <option value="BSEE 302A"' . ($section == "BSEE 302A" ? ' selected' : '') . '>BSEE 302A</option>
                                                          <option value="BSEE 302B"' . ($section == "BSEE 302B" ? ' selected' : '') . '>BSEE 302B</option>
                                                          <option value="BSEE 302C"' . ($section == "BSEE 302C" ? ' selected' : '') . '>BSEE 302C</option>
                                                          <option value="BSEE 302C PETITION"' . ($section == "BSEE 302C PETITION" ? ' selected' : '') . '>BSEE 302C PETITION</option>
                                                          <option value="BSEE 402 PETITION_EENG 200C"' . ($section == "BSEE 402 PETITION_EENG 200C" ? ' selected' : '') . '>BSEE 402 PETITION_EENG 200C</option>
                                                          <option value="BSEE 402A"' . ($section == "BSEE 402A" ? ' selected' : '') . '>BSEE 402A</option>
                                                          <option value="BSEE 402A PETITION_EENG 197A"' . ($section == "BSEE 402A PETITION_EENG 197A" ? ' selected' : '') . '>BSEE 402A PETITION_EENG 197A</option>
                                                          <option value="BSEE 402B"' . ($section == "BSEE 402B" ? ' selected' : '') . '>BSEE 402B</option>
                                                          <option value="BSEE 402B PETITION_EENG 197A"' . ($section == "BSEE 402B PETITION_EENG 197A" ? ' selected' : '') . '>BSEE 402B PETITION_EENG 197A</option>
                                                          <option value="BSEE 402C"' . ($section == "BSEE 402C" ? ' selected' : '') . '>BSEE 402C</option>
                                                          <option value="BSEE 402C PETITION_EENG 197A"' . ($section == "BSEE 402C PETITION_EENG 197A" ? ' selected' : '') . '>BSEE 402C PETITION_EENG 197A</option>
                                                        </optgroup>
                                                        <optgroup label="BSHM">
                                                          <option value="BSHM 102A"' . ($section == "BSHM 102A" ? ' selected' : '') . '>BSHM 102A</option>
                                                          <option value="BSHM 102B"' . ($section == "BSHM 102B" ? ' selected' : '') . '>BSHM 102B</option>
                                                          <option value="BSHM 102C"' . ($section == "BSHM 102C" ? ' selected' : '') . '>BSHM 102C</option>
                                                          <option value="BSHM 102D"' . ($section == "BSHM 102D" ? ' selected' : '') . '>BSHM 102D</option>
                                                          <option value="BSHM 102E"' . ($section == "BSHM 102E" ? ' selected' : '') . '>BSHM 102E</option>
                                                          <option value="BSHM 202A"' . ($section == "BSHM 202A" ? ' selected' : '') . '>BSHM 202A</option>
                                                          <option value="BSHM 202B"' . ($section == "BSHM 202B" ? ' selected' : '') . '>BSHM 202B</option>
                                                          <option value="BSHM 202C"' . ($section == "BSHM 202C" ? ' selected' : '') . '>BSHM 202C</option>
                                                          <option value="BSHM 202D"' . ($section == "BSHM 202D" ? ' selected' : '') . '>BSHM 202D</option>
                                                          <option value="BSHM 202E"' . ($section == "BSHM 202E" ? ' selected' : '') . '>BSHM 202E</option>
                                                          <option value="BSHM 302A"' . ($section == "BSHM 302A" ? ' selected' : '') . '>BSHM 302A</option>
                                                          <option value="BSHM 302B"' . ($section == "BSHM 302B" ? ' selected' : '') . '>BSHM 302B</option>
                                                          <option value="BSHM 302C"' . ($section == "BSHM 302C" ? ' selected' : '') . '>BSHM 302C</option>
                                                          <option value="BSHM 302D"' . ($section == "BSHM 302D" ? ' selected' : '') . '>BSHM 302D</option>
                                                          <option value="BSHM 302E"' . ($section == "BSHM 302E" ? ' selected' : '') . '>BSHM 302E</option>
                                                          <option value="BSHM 402A"' . ($section == "BSHM 402A" ? ' selected' : '') . '>BSHM 402A</option>
                                                          <option value="BSHM 402B"' . ($section == "BSHM 402B" ? ' selected' : '') . '>BSHM 402B</option>
                                                          <option value="BSHM 402C"' . ($section == "BSHM 402C" ? ' selected' : '') . '>BSHM 402C</option>
                                                          <option value="BSHM 402D"' . ($section == "BSHM 402D" ? ' selected' : '') . '>BSHM 402D</option>
                                                          <option value="BSHM 402E"' . ($section == "BSHM 402E" ? ' selected' : '') . '>BSHM 402E</option>
                                                        </optgroup>
                                                        <optgroup label="BSINFOTECH">
                                                          <option value="BSINFOTECH 102A"' . ($section == "BSINFOTECH 102A" ? ' selected' : '') . '>BS - INFOTECH 102A</option>
                                                          <option value="BSINFOTECH 102B"' . ($section == "BSINFOTECH 102B" ? ' selected' : '') . '>BS - INFOTECH 102B</option>
                                                          <option value="BSINFOTECH 102C"' . ($section == "BSINFOTECH 102C" ? ' selected' : '') . '>BS - INFOTECH 102C</option>
                                                          <option value="BSINFOTECH 102D"' . ($section == "BSINFOTECH 102D" ? ' selected' : '') . '>BS - INFOTECH 102D</option>
                                                          <option value="BSINFOTECH 102E"' . ($section == "BSINFOTECH 102E" ? ' selected' : '') . '>BS - INFOTECH 102E</option>
                                                          <option value="BSINFOTECH 202A"' . ($section == "BSINFOTECH 202A" ? ' selected' : '') . '>BS - INFOTECH 202A</option>
                                                          <option value="BSINFOTECH 202B"' . ($section == "BSINFOTECH 202B" ? ' selected' : '') . '>BS - INFOTECH 202B</option>
                                                          <option value="BSINFOTECH 202C"' . ($section == "BSINFOTECH 202C" ? ' selected' : '') . '>BS - INFOTECH 202C</option>
                                                          <option value="BSINFOTECH 202D"' . ($section == "BSINFOTECH 202D" ? ' selected' : '') . '>BS - INFOTECH 202D</option>
                                                          <option value="BSINFOTECH 202E"' . ($section == "BSINFOTECH 202E" ? ' selected' : '') . '>BS - INFOTECH 202E</option>
                                                          <option value="BSINFOTECH 302A"' . ($section == "BSINFOTECH 302A" ? ' selected' : '') . '>BS - INFOTECH 302A</option>
                                                          <option value="BSINFOTECH 302B"' . ($section == "BSINFOTECH 302B" ? ' selected' : '') . '>BS - INFOTECH 302B</option>
                                                          <option value="BSINFOTECH 302C"' . ($section == "BSINFOTECH 302C" ? ' selected' : '') . '>BS - INFOTECH 302C</option>
                                                          <option value="BSINFOTECH 302D"' . ($section == "BSINFOTECH 302D" ? ' selected' : '') . '>BS - INFOTECH 302D</option>
                                                          <option value="BSINFOTECH 302E"' . ($section == "BSINFOTECH 302E" ? ' selected' : '') . '>BS - INFOTECH 302E</option>
                                                          <option value="BSINFOTECH 402A"' . ($section == "BSINFOTECH 402A" ? ' selected' : '') . '>BS - INFOTECH 402A</option>
                                                          <option value="BSINFOTECH 402A 2018"' . ($section == "BSINFOTECH 402A 2018" ? ' selected' : '') . '>BS - INFOTECH 402A 2018</option>
                                                          <option value="BSINFOTECH 402B"' . ($section == "BSINFOTECH 402B" ? ' selected' : '') . '>BS - INFOTECH 402B</option>
                                                          <option value="BSINFOTECH 402B 2018"' . ($section == "BSINFOTECH 402A 2018" ? ' selected' : '') . '>BS - INFOTECH 402B 2018</option>
                                                          <option value="BSINFOTECH 402C"' . ($section == "BSINFOTECH 402C" ? ' selected' : '') . '>BS - INFOTECH 402C</option>
                                                          <option value="BSINFOTECH 402C 2018"' . ($section == "BSINFOTECH 402A 2018" ? ' selected' : '') . '>BS - INFOTECH 402C 2018</option>
                                                          <option value="BSINFOTECH 402D"' . ($section == "BSINFOTECH 402D" ? ' selected' : '') . '>BS - INFOTECH 402D</option>
                                                          <option value="BSINFOTECH 402D 2018"' . ($section == "BSINFOTECH 402A 2018" ? ' selected' : '') . '>BS - INFOTECH 402D 2018</option>
                                                          <option value="BSINFOTECH 402E 2018"' . ($section == "BSINFOTECH 402A 2018" ? ' selected' : '') . '>BS - INFOTECH 402E 2018</option>
                                                        </optgroup>
                                                        <optgroup label="BSIT">
                                                          <option value="BSIT 102A AUTO"' . ($section == "BSIT 102A AUTO" ? ' selected' : '') . '>BSIT 102A AUTO</option>
                                                          <option value="BSIT 102A DRAF"' . ($section == "BSIT 102A DRAF" ? ' selected' : '') . '>BSIT 102A DRAF</option>
                                                          <option value="BSIT 102A ELEC"' . ($section == "BSIT 102A ELEC" ? ' selected' : '') . '>BSIT 102A ELEC</option>
                                                          <option value="BSIT 102A ELEX"' . ($section == "BSIT 102A ELEX" ? ' selected' : '') . '>BSIT 102A ELEX</option>
                                                          <option value="BSIT 102A FAT"' . ($section == "BSIT 102A FAT" ? ' selected' : '') . '>BSIT 102A FAT</option>
                                                          <option value="BSIT 102A HVAC-R"' . ($section == "BSIT HVAC-R" ? ' selected' : '') . '>BSIT 102A HVAC-R</option>
                                                          <option value="BSIT 102A MECH"' . ($section == "BSIT 102A MECH" ? ' selected' : '') . '>BSIT 102A MECH</option>
                                                          <option value="BSIT 102A SMTE"' . ($section == "BSIT 102A SMTE" ? ' selected' : '') . '>BSIT 102A SMTE</option>
                                                          <option value="BSIT 102A WAFT"' . ($section == "BSIT 102A WAFT" ? ' selected' : '') . '>BSIT 102A WAFT</option>
                                                          <option value="BSIT 102B AUTO"' . ($section == "BSIT 102B AUTO" ? ' selected' : '') . '>BSIT 102B AUTO</option>
                                                          <option value="BSIT 102B DRAF"' . ($section == "BSIT 102B DRAF" ? ' selected' : '') . '>BSIT 102B DRAF</option>
                                                          <option value="BSIT 102B ELEC"' . ($section == "BSIT 102B ELEC" ? ' selected' : '') . '>BSIT 102B ELEC</option>
                                                          <option value="BSIT 102B ELEX"' . ($section == "BSIT 102B ELEX" ? ' selected' : '') . '>BSIT 102B ELEX</option>
                                                          <option value="BSIT 202A AUTO"' . ($section == "BSIT 202A AUTO" ? ' selected' : '') . '>BSIT 202A AUTO</option>
                                                          <option value="BSIT 202A DRAF"' . ($section == "BSIT 202A DRAF" ? ' selected' : '') . '>BSIT 202A DRAF</option>
                                                          <option value="BSIT 202A ELEC"' . ($section == "BSIT 202A ELEC" ? ' selected' : '') . '>BSIT 202A ELEC</option>
                                                          <option value="BSIT 202A ELEX"' . ($section == "BSIT 202A ELEX" ? ' selected' : '') . '>BSIT 202A ELEX</option>
                                                          <option value="BSIT 202A FAT"' . ($section == "BSIT 202A FAT" ? ' selected' : '') . '>BSIT 202A FAT</option>
                                                          <option value="BSIT 202A HVAC-R"' . ($section == "BSIT HVAC-R" ? ' selected' : '') . '>BSIT 202A HVAC-R</option>
                                                          <option value="BSIT 202A MECH"' . ($section == "BSIT 202A MECH" ? ' selected' : '') . '>BSIT 202A MECH</option>
                                                          <option value="BSIT 202A SMTE"' . ($section == "BSIT 202A SMTE" ? ' selected' : '') . '>BSIT 202A SMTE</option>
                                                          <option value="BSIT 202A WAFT"' . ($section == "BSIT 202A WAFT" ? ' selected' : '') . '>BSIT 202A WAFT</option>
                                                          <option value="BSIT 302A AUTO"' . ($section == "BSIT 302A AUTO" ? ' selected' : '') . '>BSIT 302A AUTO</option>
                                                          <option value="BSIT 302A DRAF"' . ($section == "BSIT 302A DRAF" ? ' selected' : '') . '>BSIT 302A DRAF</option>
                                                          <option value="BSIT 302A ELEC"' . ($section == "BSIT 302A ELEC" ? ' selected' : '') . '>BSIT 302A ELEC</option>
                                                          <option value="BSIT 302A ELEX"' . ($section == "BSIT 302A ELEX" ? ' selected' : '') . '>BSIT 302A ELEX</option>
                                                          <option value="BSIT 302A FAT"' . ($section == "BSIT 302A FAT" ? ' selected' : '') . '>BSIT 302A FAT</option>
                                                          <option value="BSIT 302A HVAC-R"' . ($section == "BSIT HVAC-R" ? ' selected' : '') . '>BSIT 302A HVAC-R</option>
                                                          <option value="BSIT 302A MECH"' . ($section == "BSIT 302A MECH" ? ' selected' : '') . '>BSIT 302A MECH</option>
                                                          <option value="BSIT 302A SMTE"' . ($section == "BSIT 302A SMTE" ? ' selected' : '') . '>BSIT 302A SMTE</option>
                                                          <option value="BSIT 302A WAFT"' . ($section == "BSIT 302A WAFT" ? ' selected' : '') . '>BSIT 302A WAFT</option>
                                                          <option value="BSIT 402 AUTO OJT_BATCH 2017"' . ($section == "BSIT 402 AUTO OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 AUTO OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 DRAF OJT_BATCH 2017"' . ($section == "BSIT 402 DRAF OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 DRAF OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 ELEC OJT_BATCH 2017"' . ($section == "BSIT 402 ELEC OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 ELEC OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 ELEX OJT_BATCH 2017"' . ($section == "BSIT 402 ELEX OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 ELEX OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 MECH OJT_BATCH 2017"' . ($section == "BSIT 402 MECH OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 MECH OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 RAC OJT_BATCH 2017"' . ($section == "BSIT 402 RAC OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 RAC OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 SMTE OJT_BATCH 2017"' . ($section == "BSIT 402 SMTE OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 SMTE OJT_BATCH 2017</option>
                                                          <option value="BSIT 402 WAFT OJT_BATCH 2017"' . ($section == "BSIT 402 WAFT OJT_BATCH 2017" ? ' selected' : '') . '>BSIT 402 WAFT OJT_BATCH 2017</option>
                                                          <option value="BSIT 402A AUTO"' . ($section == "BSIT 402A AUTO" ? ' selected' : '') . '>BSIT 402A AUTO</option>
                                                          <option value="BSIT 402A AUTO 2018"' . ($section == "BSIT 402A AUTO 2018" ? ' selected' : '') . '>BSIT 402A AUTO 2018</option>
                                                          <option value="BSIT 402A DRAF"' . ($section == "BSIT 402A DRAF" ? ' selected' : '') . '>BSIT 402A DRAF</option>
                                                          <option value="BSIT 402A DRAF 2018"' . ($section == "BSIT 402A DRAF 2018" ? ' selected' : '') . '>BSIT 402A DRAF 2018</option>
                                                          <option value="BSIT 402A ELEC"' . ($section == "BSIT 402A ELEC" ? ' selected' : '') . '>BSIT 402A ELEC</option>
                                                          <option value="BSIT 402A ELEC 2018"' . ($section == "BSIT 402A ELEC 2018" ? ' selected' : '') . '>BSIT 402A ELEC 2018</option>
                                                          <option value="BSIT 402A ELEX"' . ($section == "BSIT 402A ELEX" ? ' selected' : '') . '>BSIT 402A ELEX</option>
                                                          <option value="BSIT 402A ELEX 2018"' . ($section == "BSIT 402A ELEX 2018" ? ' selected' : '') . '>BSIT 402A ELEX 2018</option>
                                                          <option value="BSIT 402A FAT"' . ($section == "BSIT 402A FAT" ? ' selected' : '') . '>BSIT 402A FAT</option>
                                                          <option value="BSIT 402A FAT 2018"' . ($section == "BSIT 402A FAT 2018" ? ' selected' : '') . '>BSIT 402A FAT 2018</option>
                                                          <option value="BSIT 402A HVAC-R"' . ($section == "BSIT 402A HVAC-R" ? ' selected' : '') . '>BSIT 402A HVAC-R</option>
                                                          <option value="BSIT 402A HVAC-R 2018"' . ($section == "BSIT 402A HVAC-R 2018" ? ' selected' : '') . '>BSIT 402A HVAC-R 2018</option>
                                                          <option value="BSIT 402A MECH"' . ($section == "BSIT 402A MECH" ? ' selected' : '') . '>BSIT 402A MECH</option>
                                                          <option value="BSIT 402A MECH 2018"' . ($section == "BSIT 402A MECH 2018" ? ' selected' : '') . '>BSIT 402A MECH 2018</option>
                                                          <option value="BSIT 402A SMTE"' . ($section == "BSIT 402A SMTE" ? ' selected' : '') . '>BSIT 402A SMTE</option>
                                                          <option value="BSIT 402A SMTE 2018"' . ($section == "BSIT 402A SMTE 2018" ? ' selected' : '') . '>BSIT 402A SMTE 2018</option>
                                                          <option value="BSIT 402A WAFT"' . ($section == "BSIT 402A WAFT" ? ' selected' : '') . '>BSIT 402A WAFT</option>
                                                          <option value="BSIT 402A WAFT 2018"' . ($section == "BSIT 402A WAFT 2018" ? ' selected' : '') . '>BSIT 402A WAFT 2018</option>
                                                          <option value="BSIT 402B AUTO"' . ($section == "BSIT 402B AUTO" ? ' selected' : '') . '>BSIT 402B AUTO</option>
                                                          <option value="BSIT 402B DRAF"' . ($section == "BSIT 402B DRAF" ? ' selected' : '') . '>BSIT 402B DRAF</option>
                                                          <option value="BSIT 402B ELEC"' . ($section == "BSIT 402B ELEC" ? ' selected' : '') . '>BSIT 402B ELEC</option>
                                                          <option value="BSIT 402B ELEC 2018"' . ($section == "BSIT 402B ELEC 2018" ? ' selected' : '') . '>BSIT 402B ELEC 2018</option>
                                                        </optgroup>
                                                        <optgroup label="BTVTED">
                                                          <option value="BTVTED 102A"' . ($section == "BTVTED 102A" ? ' selected' : '') . '>BTVTED 102A</option>
                                                          <option value="BTVTED 102B"' . ($section == "BTVTED 102B" ? ' selected' : '') . '>BTVTED 102B</option>
                                                          <option value="BTVTED 202A AUTO"' . ($section == "BTVTED 202A AUTO" ? ' selected' : '') . '>BTVTED 202A AUTO</option>
                                                          <option value="BTVTED 202A ELEC"' . ($section == "BTVTED 202A ELEC" ? ' selected' : '') . '>BTVTED 202A ELEC</option>
                                                          <option value="BTVTED 202A FSM"' . ($section == "BTVTED 202A FSM" ? ' selected' : '') . '>BTVTED 202A FSM</option>
                                                          <option value="BTVTED 202A GFD"' . ($section == "BTVTED 202A GFD" ? ' selected' : '') . '>BTVTED 202A GFD</option>
                                                          <option value="BTVTED 202A WAFT"' . ($section == "BTVTED 202A WAFT" ? ' selected' : '') . '>BTVTED 202A WAFT</option>
                                                          <option value="BTVTED 202B AUTO"' . ($section == "BTVTED 202B AUTO" ? ' selected' : '') . '>BTVTED 202B AUTO</option>
                                                          <option value="BTVTED 202B ELEC"' . ($section == "BTVTED 202B ELEC" ? ' selected' : '') . '>BTVTED 202B ELEC</option>
                                                          <option value="BTVTED 202B FSM"' . ($section == "BTVTED 202B FSM" ? ' selected' : '') . '>BTVTED 202B FSM</option>
                                                          <option value="BTVTED 202B GFD"' . ($section == "BTVTED 202B GFD" ? ' selected' : '') . '>BTVTED 202B GFD</option>
                                                          <option value="BTVTED 202B WAFT"' . ($section == "BTVTED 202B WAFT" ? ' selected' : '') . '>BTVTED 202B WAFT</option>
                                                          <option value="BTVTED 302A AUTO"' . ($section == "BTVTED 302A AUTO" ? ' selected' : '') . '>BTVTED 302A AUTO</option>
                                                          <option value="BTVTED 302A ELEC"' . ($section == "BTVTED 302A ELEC" ? ' selected' : '') . '>BTVTED 302A ELEC</option>
                                                          <option value="BTVTED 302A ELEX"' . ($section == "BTVTED 302A ELEX" ? ' selected' : '') . '>BTVTED 302A ELEX</option>
                                                          <option value="BTVTED 302A GFD"' . ($section == "BTVTED 302A GFD" ? ' selected' : '') . '>BTVTED 302A GFD</option>
                                                          <option value="BTVTED 302A HRS"' . ($section == "BTVTED 302A HRS" ? ' selected' : '') . '>BTVTED 302A HRS</option>
                                                          <option value="BTVTED 302A MECH"' . ($section == "BTVTED 302A MECH" ? ' selected' : '') . '>BTVTED 302A MECH</option>
                                                          <option value="BTVTED 302A WAFT"' . ($section == "BTVTED 302A WAFT" ? ' selected' : '') . '>BTVTED 302A WAFT</option>
                                                          <option value="BTVTED 302B AUTO"' . ($section == "BTVTED 302B AUTO" ? ' selected' : '') . '>BTVTED 302B AUTO</option>
                                                          <option value="BTVTED 302B ELEC"' . ($section == "BTVTED 302B ELEC" ? ' selected' : '') . '>BTVTED 302B ELEC</option>
                                                          <option value="BTVTED 302B ELEX"' . ($section == "BTVTED 302B ELEX" ? ' selected' : '') . '>BTVTED 302B ELEX</option>
                                                          <option value="BTVTED 302B GFD"' . ($section == "BTVTED 302B GFD" ? ' selected' : '') . '>BTVTED 302B GFD</option>
                                                          <option value="BTVTED 302B HRS"' . ($section == "BTVTED 302B HRS" ? ' selected' : '') . '>BTVTED 302B HRS</option>
                                                          <option value="BTVTED 302B MECH"' . ($section == "BTVTED 302B MECH" ? ' selected' : '') . '>BTVTED 302B MECH</option>
                                                          <option value="BTVTED 302B WAFT"' . ($section == "BTVTED 302B WAFT" ? ' selected' : '') . '>BTVTED 302B WAFT</option>
                                                          <option value="BTVTED 402 AUTO 2018"' . ($section == "BTVTED 402 AUTO 2018" ? ' selected' : '') . '>BTVTED 402 AUTO 2018</option>
                                                          <option value="BTVTED 402 ELEC 2018"' . ($section == "BTVTED 402 ELEC 2018" ? ' selected' : '') . '>BTVTED 402 ELEC 2018</option>
                                                          <option value="BTVTED 402 ELEX 2018"' . ($section == "BTVTED 402 ELEX 2018" ? ' selected' : '') . '>BTVTED 402 ELEX 2018</option>
                                                          <option value="BTVTED 402 FGD 2018"' . ($section == "BTVTED 402 FGD 2018" ? ' selected' : '') . '>BTVTED 402 FGD 2018</option>
                                                          <option value="BTVTED 402 HRS 2018"' . ($section == "BTVTED 402 HRS 2018" ? ' selected' : '') . '>BTVTED 402 HRS 2018</option>
                                                          <option value="BTVTED 402 MECH 2018"' . ($section == "BTVTED 402 MECH 2018" ? ' selected' : '') . '>BTVTED 402 MECH 2018</option>
                                                          <option value="BTVTED 402 WAFT 2018"' . ($section == "BTVTED 402 WAFT 2018" ? ' selected' : '') . '>BTVTED 402 WAFT 2018</option>
                                                          <option value="BTVTED 402A AUTO"' . ($section == "BTVTED 402A AUTO" ? ' selected' : '') . '>BTVTED 402A AUTO</option>
                                                          <option value="BTVTED 402A ELEC"' . ($section == "BTVTED 402A ELEC" ? ' selected' : '') . '>BTVTED 402A ELEC</option>
                                                          <option value="BTVTED 402A ELEX"' . ($section == "BTVTED 402A ELEX" ? ' selected' : '') . '>BTVTED 402A ELEX</option>
                                                          <option value="BTVTED 402A GFD"' . ($section == "BTVTED 402A GFD" ? ' selected' : '') . '>BTVTED 402A GFD</option>
                                                          <option value="BTVTED 402A HRS"' . ($section == "BTVTED 402A HRS" ? ' selected' : '') . '>BTVTED 402A HRS</option>
                                                          <option value="BTVTED 402A MECH"' . ($section == "BTVTED 402A MECH" ? ' selected' : '') . '>BTVTED 402A MECH</option>
                                                          <option value="BTVTED 402A WAFT"' . ($section == "BTVTED 402A WAFT" ? ' selected' : '') . '>BTVTED 402A WAFT</option>
                                                        </optgroup>
                                                        <optgroup label="OJT">
                                                          <option value="RESIDENCY"' . ($section == "RESIDENCY" ? ' selected' : '') . '>RESIDENCY</option>
                                                          <option value="TCP A"' . ($section == "TCP A" ? ' selected' : '') . '>TCP A</option>
                                                          <option value="TCP B"' . ($section == "TCP B" ? ' selected' : '') . '>TCP B</option>
                                                        </optgroup>
                                                      </select>                                          
                                                    </div>
                                                    <div class="col-md-6 mb-4">
                                                      <label for="department">Department:</label>
                                                      <select class="form-control"style="text-align: center;"  type="text" id="" name="department">
                                                          <optgroup label="Select Department">
                                                          <option value="Department of Computer Studies"' . ($s_department == "Department of Computer Studies" ? ' selected' : '') . '>Department of Computer Studies</option>
                                                          <option value="Department of Engineering"' . ($s_department == "Department of Engineering" ? ' selected' : '') . '>Department of Engineering</option>
                                                          <option value="Department of Industrial Technology"' . ($s_department == "Department of Industrial Technology" ? ' selected' : '') . '>Department of Industrial Technology</option>
                                                          <option value="Department of Management Studies"' . ($s_department == "Department of Management Studies" ? ' selected' : '') . '>Department of Management Studies</option>
                                                          <option value="Department of Teacher Education"' . ($s_department == "Department of Teacher Education" ? ' selected' : '') . '>Department of Teacher Education</option></optgroup>
                                                          <option value="others"' . ($s_department == "others" ? ' selected' : '') . '>Others</option></optgroup>
                                                        </select>  
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-md-12 mb-4 w-100">
                                                      <label for="mobile">Mobile:</label>
                                                      <input type="number" style="text-align: center;" class="form-control" type="number" name="mobile" value="' . htmlspecialchars($mobile) . '">
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-md-12">
                                                      <label for="email">Email:</label>
                                                      <input type="email" style="text-align: center;"  class="form-control" disabled="" value="' . htmlspecialchars($email) . '">
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <button class="btn btn-primary" id="submitButton2_' . $s_id . '" type="submit" name="submit">Save changes</button>
                                                  </div>
                                                </form>
                                              </div>
                                          </div>
                                        </div>
                                      </div>
                                        <i class="btn btn-danger fa fa-trash-o" onclick="demoSwal('.$s_id.', \''.$fname.'\', \''.$lname.'\', \''.$student_id_number.'\', \''.$s_department.'\');"></i>
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
    <script type="text/javascript">
    function demoSwal(s_id, fname, lname, student_id_number, s_department) {
        var sa_fname = "<?php echo $d_fname ?>";  
        var sa_lname = "<?php echo $d_lname ?>";  
        var developer_id_number = "<?php echo $developer_id_number ?>";  
        var department = "<?php echo $designation ?>";  

        swal({
            title: "Are you sure?",
            text: "This data will be moved to Account Bin.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
          if (isConfirm) {
                // Use Ajax to send a request to the PHP script
                $.ajax({
                    url: 'php/developerdeletestud-temp.php',
                    type: 'POST',
                    data: {
                        deleteid: s_id,
                        sa_fname: sa_fname,
                        sa_lname: sa_lname,
                        developer_id_number: developer_id_number,
                        fname: fname,
                        lname: lname,
                        department: department,
                        student_id_number: student_id_number,
                        s_department: s_department
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
  const successParams = new URLSearchParams(window.location.search);
  const success = successParams.get('success');
  if (success === 'true') {
    // display a SweetAlert notification to the user
    swal({
      title: "Success!",
      text: "Student may now register.",
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

  // check if the row_filled query parameter is present in the URL
  const row_filledParams = new URLSearchParams(window.location.search);
  const row_filled = row_filledParams.get('row_filled');
  if (row_filled === 'true') {
    // display a SweetAlert notification to the user
    swal({
      title: "The student id number you provided is already in use!",
      text: "Double check the student id number.",
      type: "error",
      showCancelButton: false,
      confirmButtonText: "OK",
      closeOnConfirm: true,
      closeOnCancel: true
    });

    // Remove the row_filled URL parameter after 1 second
    setTimeout(function() {
      // Create a new URL without the row_filled parameter
      const newUrl = window.location.origin + window.location.pathname;
      window.history.replaceState({}, document.title, newUrl);
    }, 1000);
  }
   // check if the row_email uery parameter is present in the URL
   const row_emailParams = new URLSearchParams(window.location.search);
          const row_email = row_emailParams.get('row_email');
          if (row_email === 'true') {
              // display a SweetAlert notification to the user
              swal({
                  title: "The email you provided is already in use!",
                  text: "Double check the email.",
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
  function validatePassword2(s_id) {
    const submitButton2 = document.getElementById('submitButton2_'+s_id);
    const password2 = document.getElementById("password2_"+s_id).value;
    const confirmPassword2 = document.getElementById("confirmPassword2_"+s_id).value;
    const passwordError2 = document.getElementById("passwordError2_"+s_id);

    // Call checkPassword for detailed validation
    const isPasswordValid2 = checkPassword2(<?php echo $s_id ?>);

    if (confirmPassword2 === "") {
      passwordError2.innerHTML = '<i></i>';
      submitButton2.disabled = true;
      return;
    }

    if (password2 === confirmPassword2 && isPasswordValid2) {
      passwordError2.innerHTML = "<i class='text text-success'>Passwords match.</i>";
      submitButton2.disabled = false;
    } 
    else if (password2 === "" && isPasswordValid2) {
      passwordError2.innerHTML = "<i class='text text-success'>Passwords do not match or are invalid.</i>";
      submitButton2.disabled = true;
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

  function checkPassword2(s_id) {
    const passwordInput = document.getElementById('password2_'+s_id);
    const passwordCheck = document.getElementById('passwordCheck2_'+s_id);
    const submitButton2 = document.getElementById('submitButton2_'+s_id);

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