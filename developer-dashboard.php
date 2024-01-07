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
  $query2 = "SELECT * FROM year_tbl";
  $result2 = mysqli_query($con, $query2);
  $row2 = mysqli_fetch_assoc($result2);
  $year = $row2['year'];
?>
<?php
include 'php/connection.php';

// Fetch the current year from the year_tbl
$sql_get_current_year = "SELECT year FROM year_tbl LIMIT 1";
$result_get_current_year = mysqli_query($con, $sql_get_current_year);
$row_get_current_year = mysqli_fetch_assoc($result_get_current_year);

// Convert the varchar year to an integer
$currentYear = intval($row_get_current_year['year']);

// Rest of your code remains unchanged
$lastFiveYears = [];
for ($i = 4; $i >= 0; $i--) {
    $year = $currentYear - $i . '-' . ($currentYear - $i + 1);
    $lastFiveYears[] = $year;

    // Check if entry for the year already exists
    $sql_check_entry = "SELECT * FROM number_applicants_yearly WHERE year = '$year'";
    $result_check_entry = mysqli_query($con, $sql_check_entry);
    $row_check_entry = mysqli_fetch_assoc($result_check_entry);

    // Get counts for the specific year
    $sql_total_applicants = "SELECT COUNT(*) as total_applicants FROM applicant_tbl WHERE app_year = '$year'";
    $result_total_applicants = mysqli_query($con, $sql_total_applicants);
    $row_total_applicants = mysqli_fetch_assoc($result_total_applicants);
    $total_applicants = $row_total_applicants['total_applicants'];

    $sql_total_approved = "SELECT COUNT(*) as total_approved FROM approved_applicants_tbl WHERE app_year = '$year'";
    $result_total_approved = mysqli_query($con, $sql_total_approved);
    $row_total_approved = mysqli_fetch_assoc($result_total_approved);
    $total_approved = $row_total_approved['total_approved'];

    $sql_total_denied = "SELECT COUNT(*) as total_denied FROM denied_applicants_tbl WHERE app_year = '$year'";
    $result_total_denied = mysqli_query($con, $sql_total_denied);
    $row_total_denied = mysqli_fetch_assoc($result_total_denied);
    $total_denied = $row_total_denied['total_denied'];

    // Calculate the total counts
    $total = $total_applicants + $total_approved + $total_denied;

    if (empty($row_check_entry)) {
        // If entry doesn't exist, insert a new record
        $sql_insert = "INSERT INTO number_applicants_yearly (number, year) VALUES ('$total', '$year')";
        mysqli_query($con, $sql_insert);
    } else {
        // If entry already exists, compare counts and update if needed
        if ($total != $row_check_entry['number']) {
            $sql_update = "UPDATE number_applicants_yearly SET number = '$total' WHERE year = '$year'";
            mysqli_query($con, $sql_update);
        }
    }

    // Populate $totalCounts array
    $totalCounts[] = $total;
}

// Build the associative array for Chart.js data
$dataFromPHP = [
    'labels' => $lastFiveYears,
    'datasets' => [
        [
            'label' => 'Total Counts',
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'borderWidth' => 1,
            'data' => $totalCounts,
        ],
    ],
];

// Close database connection
mysqli_close($con);
?>


<?php
include("php/connection.php");

// get total rows in students_tbl where all specified columns are not null
$sql_students = "SELECT COUNT(*) as total_students FROM students_tbl WHERE 
                 IFNULL(s_id, '') <> '' AND
                 IFNULL(student_id_number, '') <> '' AND
                 IFNULL(fname, '') <> '' AND
                 IFNULL(lname, '') <> '' AND
                 IFNULL(username, '') <> '' AND
                 IFNULL(password, '') <> '' AND
                 IFNULL(department, '') <> '' AND
                 IFNULL(section, '') <> '' AND
                 IFNULL(mobile, '') <> '' AND
                 IFNULL(email, '') <> ''";

$result_students = mysqli_query($con, $sql_students);
$row_students = mysqli_fetch_assoc($result_students);
$total_students = $row_students['total_students'];

// display the total rows
$total_rows = $total_students;

?>
<?php
  $query2 = "SELECT * FROM semester_checker";
  $result2 = mysqli_query($con, $query2);
  $row2 = mysqli_fetch_assoc($result2);
  $sem = $row2['sem'];
?>

<?php
    include("php/connection.php");

    // get total rows in applicant_tbl
    $sql_total_applicant = "SELECT COUNT(*) as total_students FROM applicant_tbl";
    $result_total_applicant = mysqli_query($con, $sql_total_applicant);
    $row_total_applicant = mysqli_fetch_assoc($result_total_applicant);
    $total_applicants = $row_total_applicant['total_students'];

    // get total rows in applicant_tbl where app_status is Approved
    $sql_total_approved = "SELECT COUNT(*) as total_approved FROM approved_applicants_tbl";
    $result_total_approved = mysqli_query($con, $sql_total_approved);
    $row_total_approved = mysqli_fetch_assoc($result_total_approved);
    $total_approved = $row_total_approved['total_approved'];

    // get total rows in applicant_tbl where app_status is Denied
    $sql_total_denied = "SELECT COUNT(*) as total_denied FROM denied_applicants_tbl";
    $result_total_denied = mysqli_query($con, $sql_total_denied);
    $row_total_denied = mysqli_fetch_assoc($result_total_denied);
    $total_denied = $row_total_denied['total_denied'];

    // Close database connection
    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Dashboard</title>
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
    <header id="hNav" style="background: rgb(255, 255, 255);" class="app-header"><a id="bLogo" class="app-header__logo" href="developer-dashboard.php" style="cursor: pointer;"><img class="iMg" src="images/logo2.png"></a>
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
          /* Media query for mobile view */
          @media screen and (max-width: 767px) {
                #divProfile {
                  display: none;
                }
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
    <aside id="sNav" style="background: rgb(255, 255, 255);" class="app-sidebar">
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
      <?php include 'php/navbar.php' ?>
    </aside>
    <main class="app-content">
      <div class="app-title">
        <div id="divProfile">
          <h1><i class="fa fa-dashboard"></i> Scholarship Dashboard</h1>
          <p>CvSU CCAT CAMPUS Scholarship Portal</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="developer-dashboard.php"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
            <div class="info">
              <h4><a style="color:black !important; font-weight: 600;" href="developer-add-edit-students.php">Registered Students</a></h4>
              <p><b><?php echo " $total_rows";?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
            <div class="info">
              <h4><a style="color:black !important; font-weight: 600;" href="developer-tables.php">Applicants</a></h4>
              <p><b><?php echo " $total_applicants";?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small primary coloured-icon"><i class="icon fa fa-star fa-3x"></i>
            <div class="info">
              <h4><a style="color:black !important; font-weight: 600;" href="developer-tables-approved.php">Approved</a></h4>
              <p><b><?php echo "$total_approved";?></b></p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="widget-small danger coloured-icon"><i class="icon fa fa-ban fa-3x"></i>
            <div class="info">
              <h4><a style="color:black !important; font-weight: 600;" href="developer-tables-denied.php">Denied</a></h4>
              <p><b><?php echo "$total_denied";?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Yearly Applicants<br><small style="font-size: 18px;">Total</small></h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="myBarChart"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Overall Ranking of Students</h3>
            <h5 style="display: inline-block; margin: 0 5px;">Filter</h5>
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
              <br><br>
              <div class="table-responsive">
              <table class="table table-hover table-bordered" id="sampleTables">
                <thead>
                  <tr style="font-size: 16px; text-align: center;">
                    <th scope="col">Rank</th>
                    <th scope="col">Name</th>
                    <th scope="col">Department</th>
                    <th scope="col">Section</th>
                    <th scope="col">Semester & Year</th>
                    <th scope="col">GPA</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- PHP Database Display -->
                  <?php
                    $sql = "SELECT * FROM top_ten_students ORDER BY average ASC LIMIT 10";
                    $result = mysqli_query($con, $sql);
                    $count = 1; // Counter for numbering the rows

                    while ($row = mysqli_fetch_assoc($result)) {
                      $t_fname = $row['t_fname'];
                      $t_lname = $row['t_lname'];
                      $t_department = $row['t_department'];
                      $t_section = $row['t_section'];
                      $average = $row['average'];
                      
                      echo '<tr style="font-size: 15px;">
                              <td hidden><strong>Rank:</strong> ' . $count . '</td>
                              <td><strong>Rank:</strong> ' . $count . '</td>
                              <td><strong>Name:</strong> ' . $t_lname . ', ' . $t_fname . '</td>
                              <td><strong>Department:</strong> ' . $t_department . '</td>
                              <td><strong>Section:</strong> ' . $t_section . '</td>
                              <td><strong>Semester & Year:</strong> ' . $sem . ', '.$year.'</td>
                              <td><strong>GPA:</strong> ' . $average . '</td>
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
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript">$('#sampleTables').DataTable();</script>
    <script type="text/javascript" src="js/plugins/select2.min.js"></script>
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
              if (
                selectedCategories.length === 0 ||
                selectedCategories.some(category =>
                  fieldText.includes(category.toLowerCase())
                )
              ) {
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

          // Renumber the visible rows
          var visibleRows = $('#sampleTables tbody tr:visible');
          visibleRows.each(function(index) {
            $(this)
              .find('td:first-child')
              .text(index + 1);
          });
        }

        // Filter the table when the select2 value changes
        $('#demoSelect').on('change', filterTable);

        // Call the filterTable function initially to show the appropriate rows
        filterTable();
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      // Get PHP data
      var dataFromPHP = <?php include("php/connection.php"); echo json_encode($dataFromPHP); ?>;

      // Create Chart.js chart
      var ctx = document.getElementById('myBarChart').getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: dataFromPHP.labels,
              datasets: dataFromPHP.datasets,
          },
          options: {
              scales: {
                  y: {
                      beginAtZero: true
                  }
              }
          }
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
        <script src="js/plugins/bootstrap-notify.min.js"></script>
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