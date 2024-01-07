<?php include 'php/sessionChecker.php' ?>
<?php include 'php/connection.php' ?>
<?php 
  $query3 = "SELECT * FROM year_tbl";
  $result3 = mysqli_query($con, $query3);
  $row3 = mysqli_fetch_assoc($result3);

  $year = $row3['year'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/logo.ico" />
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom.css">    
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - Scholarship</title>
  </head>
  <body style="overflow: hidden;">
    <iframe id="loader" style="z-index: 99999999;" src="landing.html" frameborder="0"></iframe>
    <style>
            body, html {
            margin: 0;
            padding: 0;
            height: 100%;
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

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            opacity: 1;
            transition: opacity 0.5s ease; /* Add fading transition */
        }
        #loader.fade-out {
            opacity: 0;
        }
  .navbar-light .navbar-nav .nav-link:hover {
    background-color: #E4E4E4;
  }
        footer {
          background-color: #f5f5f5;
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

        

      .login-form {
        display: block;
      }
      .forget-form {
        display: none;
      }
      .flipped .login-form {
        display: none;
      }
      .flipped .forget-form {
        display: block;
      }
      
    </style>

    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top" style="z-index:9999999;">
    <style>
      /* Existing styles remain unchanged */
      @media (max-width: 768px) {
        .navbar-brand img {
          height: 40px !important;
        }
      }

      .navbar-brand img {
        height: 70px;
      }

      /* New styles for the underline effect */
      .nav-link-underline {
        position: relative;
        text-decoration: none;
        color: #009688; /* Change the color to match your design */
      }

      .nav-link-underline:before {
        content: '';
        position: absolute;
        width: 0;
        height: 2px; /* Set the height of the underline */
        bottom: 0;
        left: 0;
        background-color: #009688; /* Change the color to match your design */
        transition: width 0.3s ease;
      }

      .nav-link-underline:hover:before {
        width: 100%;
      }
    </style>
      <a class="navbar-brand" href="#" onclick="window.location.reload()">
        <img id="logo" src="images/logo2.png" alt="">
      </a>      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link nav-link-underline" href="#" onclick="scrollToSection('announcee')">Announcements</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-underline" href="#" onclick="scrollToSection('newss')">News and Updates</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-underline" href="#" onclick="scrollToSection('reqq')">Requirements</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-underline" data-toggle="modal" data-target="#myModal3" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-link-underline" style="margin-right: 25px;" data-toggle="modal" data-target="#myModal2" href="#">FAQ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-success" data-toggle="modal" data-target="#myModal" href="#">Login</a>
          </li>
        </ul>
      </div>
    </nav>
    <script>
      function scrollToSection(sectionId) {
          $('html, body').animate({
              scrollTop: $("#" + sectionId).offset().top
          }, 500);
      }
    </script>
    <section class="login-content">
      <div class="logo">
        <h1></h1>
      </div>

      <!-- Modal 2 -->
      <div class="modal fade" id="myModal2">
  <div class="modal-dialog" style="margin-top: -10px;">
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

          .card-header h5,
          button {
            font-size: 18px !important;
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

<style>
  @media only screen and (max-width: 768px) {
    #myModal3{
      margin-top: 20px !important;
      margin-left: 0px !important;
    }
    #myModal3 p{
      font-size: 15px !important;
    }

    #myModal2 button{
      font-size: 16px !important;
    }
  }
  #myModal3{
    z-index: 999999;
  }

  #myModal2{
    z-index: 999999;
  }

  #myModal{
    z-index: 999999;
  }
</style>

  <!-- Modal 3 -->
  <div class="modal fade" id="myModal3">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: -20px;">
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

 <!-- NEW LOGIN -->
      <style>
        .password-toggle-icon{
          cursor: pointer;
        }
      </style>
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2 class="modal-title" id="myModalLabel">SIGN IN</h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" class="login-form" id="login-form">
                <!-- form inputs -->
                <div class="form-group">
                    <label class="control-label">USERNAME</label>
                    <input class="form-control" type="text" name="username" placeholder="Username" required autofocus>
                  </div>
                  <div class="form-group">
                    <label class="control-label">PASSWORD</label>
                    <div class="input-group">
                      <input class="form-control" type="password" name="password" id="password1" required placeholder="Password">
                      <div class="input-group-append">
                        <span class="input-group-text password-toggle-icon" onclick="togglePassword('password1', 'togglePasswordIcon')">
                          <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="utility">
                      <p class="semibold-text mb-2" style="float: right; bottom: 5%; right: 40px; position: absolute;"><span style="margin-left: 20px;">Click here to </span><a href="#" data-toggle="flip" style="text-decoration: underline;">Register</a></p>
                    </div>
                  </div>
                  <div class="form-group btn-container">
                    <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
                    <p class="semibold-text mb-2" style="float: right; bottom: 5%; position: absolute; text-align: center;"><a href="forgot-password.php">Forgot password?</a></p><br><br>
                  </div>
              </form>
              <form class="forget-form" id="register-form" method="POST" action="">
                <!-- form inputs -->            
                <div class="row mb-4">
                  <div class="col-md-12">
                    <label for="">Enter ID Number:</label>
                    <input class="form-control" type="number" name="id_number" required placeholder="Enter your id number">
                  </div>
                </div>
                <div class="form-group btn-container">
                  <button class="btn btn-primary btn-block" id="submitButton" type="submit" name="rsubmit"><i class="fa fa-key fa-lg fa-fw"></i>Submit</button>
                </div>
                <div class="form-group mt-3">
                  <p class="semibold-text mb-0"><a href="#" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    <style>
      @media (max-width: 768px) {
        .carousel-inner img {
          max-height: 100%; /* Reset max-height for mobile view */
          max-width: 90%;
        }
        .carousel-caption {
          top: 15%; /* Adjust top position for mobile view */
          max-height: 40% !important;
        }
        .truncated-content {
          display: none; /* Hide truncated content on mobile */
        }
        .read-more-button {
          display: inline-block; /* Show the "Read More" button on mobile */
        }
        #white-tile{
          border-color: darkgreen !important;
          background-color: white !important;
          color: black;
        }
        #carrd{
          margin-left: 0 !important;
          width: 100% !important;
          background-color: rgba(255, 255, 255, 0.7);
        }
        #accordionExample{
          width: 80% !important;
        }
      }
      #accordionExample{
        width: 60%;
      }
     
      .carousel-caption {
        max-height: 20%;
        top: 0; /* Position at the top */
        left: 0;
        right: 0;
        background-color: rgba(255, 255, 255, 0.5);
        padding: 10px;
        color: black;
        z-index: 999;
        padding-top: 20px;
      }
      
      /* Read more button style */
      .read-more-button {
        cursor: pointer;
        font-weight: 800;
      }
      .card{
        border-radius: 10px;
        height: 100%;
      }
      #carrd{
        margin: auto;
        background-color: rgba(255, 255, 255, 0.7);
      }
    </style>
  <div id="announcee" style="height: 50px; background-color: #f0f0f0;"></div>
    <!-- ANNOUNCEMENTS --><br><br><br>
    <h2 style="text-align: center;">ANNOUNCEMENTS</h2>
    <hr style="width: 50%; background: green;"><br><br>
    <!-- Carousel wrapper -->
    <div id="announceCarou" class="carousel slide text-center carousel-dark" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            include("php/connection.php");
            $sql = "SELECT * FROM announceupdate_tbl";
            $result = $con->query($sql);
            $active = true;
            $indicatorIndex = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $activeClass = $active ? "active" : "";
                    echo '<li data-target="#announceCarousel" data-slide-to="' . $indicatorIndex . '" class="' . $activeClass . '"></li>';
                    $active = false;
                    $indicatorIndex++;
                }
            }
            ?>
          </ol>
          
          <div class="carousel-inner">
            <?php
            include("php/connection.php");
            $sql = "SELECT * FROM announceupdate_tbl";        
            $result = $con->query($sql);
            $active = true;

            if ($result->num_rows > 0) {
                $slideCounter = 0; // Initialize the modal counter

                while ($row = $result->fetch_assoc()) {
                    $imageURL = $row['images'];
                    $title = $row['title'];
                    $description = $row['content'];
                    $activeClass = $active ? "active" : "";

                    // Truncate the content to 50 words
                    $truncatedContent = implode(' ', array_slice(explode(' ', $description), 0, 20));

                    echo '
                    <div class="carousel-item ' . $activeClass . '">
                      <img src="' . $imageURL . '" alt="' . $title . '" class="img-fluid carou-img" style="border-radius: 10px;">
                      <div class="carousel-caption">
                        <h3>' . $title . '</h3>
                        <p class="truncated-content">'.$truncatedContent.'</p>
                        <a target="_blank" href="viewAnnouncement.php?title='. urlencode($title) .'&description='. urlencode($description).'&image='. urlencode($imageURL).'" class="read-more-button">Read More</a>
                      </div>
                    </div>';

                    $active = false;
                    $slideCounter++;
                }
            }
            ?>
          </div>
        <!-- Controls -->
        <a class="carousel-control-prev" href="#announceCarou" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#announceCarou" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

    <!-- NEWS AND UPDATES -->
    <br><br>
    <div id="newss" style="height: 100px; background-color: #e0e0e0;"></div>
    <h2 style="text-align: center;">NEWS AND UPDATES</h2>
    <hr style="width: 50%; background: green;"><br><br>
    <style>
      .truncated-content {
          overflow: hidden;
      }

    </style>
    <div id="newsCarou" class="carousel slide text-center carousel-dark" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <?php
        include("php/connection.php");
        $sql = "SELECT * FROM newsupdate_tbl";
        $result = $con->query($sql);
        $active = true;
        $indicatorIndex = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $activeClass = $active ? "active" : "";
                echo '<li data-target="#newsCarousel" data-slide-to="' . $indicatorIndex . '" class="' . $activeClass . '"></li>';
                $active = false;
                $indicatorIndex++;
            }
        }
        ?>
      </ol>
      
      <div class="carousel-inner">
        <?php
        include("php/connection.php");
        $sql = "SELECT * FROM newsupdate_tbl";        
        $result = $con->query($sql);
        $active = true;

        if ($result->num_rows > 0) {
            $slideCounter = 0; // Initialize the modal counter

            while ($row = $result->fetch_assoc()) {
                $imageURL = $row['images'];
                $title = $row['title'];
                $description = $row['content'];
                $activeClass = $active ? "active" : "";

                // Truncate the content to 50 words
                $truncatedContent = implode(' ', array_slice(explode(' ', $description), 0, 20));

                echo '
                <div class="carousel-item ' . $activeClass . '">
                  <img src="' . $imageURL . '" alt="' . $title . '" class="img-fluid" style="border-radius: 10px;">
                  <div class="carousel-caption">
                    <h3>' . $title . '</h3>
                    <p class="truncated-content">'.$truncatedContent.'</p>
                    <a target="_blank" href="viewNews.php?title='. urlencode($title) .'&description='. urlencode($description).'&image='. urlencode($imageURL).'" class="read-more-button">Read More</a>
                  </div>
                </div>';

                $active = false;
                $slideCounter++;
            }
        }
        ?>
      </div>
      <!-- Controls -->
      <a class="carousel-control-prev" href="#newsCarou" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#newsCarou" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

    <style>
      .accordtext{
        font-size: 14px;
        text-align: justify;
      }
    </style>
  <div id="reqq" style="height: 50px; background-color: #f0f0f0;"></div>
      <h2 id="announcements" style="padding-top: 40px; text-align: center;">REQUIREMENTS</h2>
      <hr style="width: 50%; background: green;"><br><br>
        <div id="benefit">
          <div id="carrd" class="card">
            <div class="card-body">
              <h1 class="card-title text-center mb-4">
                Benefits &amp; <span class="text-success">Privileges</span>
              </h1>
              <h5 class="card-text text-center">Full Academic scholars are given 100% free tuition and other school fees (SRF and SFDF).<br>Partial academic scholars are given 50% free tuition and other school fees.</h5>
            </div>
          </div>
        </div><br><br>

    <!-- ACCORDION -->
    <div class="accordion" id="accordionExample">
      <div class="card">
        <div class="card-header" id="headingOne">
          <h2 class="mb-0">
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
              Academic Scholarship?
            </button>
            <p class="accordtext"><b>Granted to those students who obtained a (GPA) of 1.75 or better in the previous semester.</b></p>
            <p class="accordtext"><b>Scholarship is classified as full if he/she obtained a GPA of 1.00 – 1.50 and partial scholarship if he/she obtained a GPA of 1.51 – 1.75.</b></p>
          </h2>
        </div>

        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
          <div class="card-body">
            <p style="font-size: 18px; text-align: justify;">
              • <b>Scholars must maintain a grade point average (GPA) of 1.50 or better and 1.51 – 1.75 for full and partial scholarship respectively;</b>
              <br><br>• <b>Enroll the regular load prescribed in a given curricular program (minimum of 18 units per semester);</b>
              <br><br>• <b>Must not have failing, conditional and incomplete grades in the previous semester (has no grade deficiency lower than 2.50;</b>
              <br><br>• <b>Must not have dropped any subject/s nor cancel enrollment for the period during which he/ she enjoys the scholarship;</b>
              <br><br>• <b>Comply with the rules and regulations incumbent upon recipients as per scholarship/ guidelines policies; and</b>
              <br><br>• <b>Have not violated the students’ norms of conduct and other standing policies of the University.</b>
            </p>          
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h2 class="mb-0">
            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Service Scholarship and Talent Scholarship?
            </button>
            <p class="accordtext"><b>Provide free tuition or tuition discounts to students who have rendered service to the University as members of varsity teams, members of socio-cultural groups or Editor-in-Chief of student publication, officers of the Central Student Government (CSG) and Reserved Officer Training Corps (ROTC).</b></p>
          </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
          <div class="card-body">
            <p style="font-size: 18px; text-align: justify;">
              • <b>Enroll the regular load prescribed in a given curricular program (minimum of 18 units per semester);</b>
              <br><br>• <b>Must not have failing, conditional and incomplete grades in the previous semester (has no grade deficiency lower than 2.50;</b>
              <br><br>• <b>Must not have dropped any subject/s nor cancel enrollment for the period during which he/ she enjoys the scholarship;</b>
              <br><br>• <b>Comply with the rules and regulations incumbent upon recipients as per scholarship/ guidelines policies; and</b>
              <br><br>• <b>Have not violated the students’ norms of conduct and other standing policies of the University.</b>
              <br><br>• <b>Recipient must meet the standards set by each organization where he/ she belong.</b>
            </p>          
          </div>
        </div>
      </div>
    </div><br><br>
  
      <h2 id="testi" style="padding-top: 80px; text-align: center;">Feedbacks</h2>
      <hr style="width: 50%; background: green;"><br><br>

<!-- Carousel wrapper -->
<div id="feedCarou" style="width: 100%;" class="carousel slide text-center carousel-dark" data-ride="carousel">
    <div class="carousel-inner">
    <?php
    // Include your PHP connection file here
    include("php/connection.php");

    // Fetch all rows from choose_stars_tbl
    $query2 = "SELECT number_stars FROM choose_stars_tbl";
    $result2 = mysqli_query($con, $query2);

    // Ensure $numberOfStars is within the valid range (1 to 5)
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $numberOfStars = $row2['number_stars'];
        $numberOfStars = max(1, min(5, $numberOfStars));

        // Check if $numberOfStars contains a range (e.g., "1-2")
        if (strpos($numberOfStars, '-') !== false) {
            // Assuming $numberOfStars is a string like "1-2"
            list($minStars, $maxStars) = explode('-', $numberOfStars);

            $query = "SELECT * FROM feedbacks_tbl 
                      WHERE (number_stars BETWEEN $minStars AND $maxStars) 
                      AND year_submitted = '$year' 
                      ORDER BY RAND() LIMIT 5";
        } else {
            // If it's a single value, not a range
            $query = "SELECT * FROM feedbacks_tbl 
                      WHERE number_stars = $numberOfStars 
                      AND year_submitted = '$year' 
                      ORDER BY RAND() LIMIT 5";
        }

        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            $firstItem = true;

            while ($row = mysqli_fetch_assoc($result)) {
                // Extract data from the row
                $id = $row['id'];
                $message = $row['message'];
                $image = $row['image'];
                $fname = $row['fname'];
                $lname = $row['lname'];
                $section = $row['section'];
                $number_stars = $row['number_stars'];

                // Masking the characters between the first and last letters of fname and lname with asterisks
                $masked_fname = substr($fname, 0, 1) . str_repeat('*', strlen($fname) - 1);
                $masked_lname = substr($lname, 0, 1) . str_repeat('*', strlen($lname) - 1);

                // Check if it's the first item and add the 'active' class accordingly
                $activeClass = $firstItem ? 'active' : '';
                $firstItem = false;
                ?>
                <div class="carousel-item <?php echo $activeClass; ?>">
                    <img class="rounded-circle shadow-1-strong mb-4"
                        src="<?php echo $image ?>" alt="avatar"
                        style="width: 150px;" />
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <h5 style="color: black;" class="mb-3"><?php echo $masked_fname .' '. $masked_lname ?></h5>
                            <p style="color: black; margin-top: -15px; font-size: 14px;"><i><?php echo $year ?></i></p>
                            <p class="text-muted" style="width: 80%; margin: auto;">
                                <i class="fa fa-quote-left"></i>
                                <?php echo $message ?>
                            </p>
                        </div>
                    </div>
                    <?php
                      // Ensure $number_stars is within the valid range (1 to 5)
                      $number_stars = max(1, min(5, $number_stars));

                      // Print filled stars
                      echo '<ul class="list-unstyled d-flex justify-content-center text-warning mb-0">';
                      for ($i = 1; $i <= $number_stars; $i++) {
                          echo '<li><i class="fa fa-star"></i></li>';
                      }

                      // Print empty stars based on the difference
                      $empty_stars = 5 - $number_stars;
                      for ($i = 1; $i <= $empty_stars; $i++) {
                          echo '<li><i class="fa fa-star-o"></i></li>';
                      }

                      echo '</ul>';
                    ?>
                </div>
                <?php
            }
        } else {
            echo "No feedbacks found.";
        }
    }
?>


</div>
</div>
<style>
    /* Default styles for notification */
    .alert {
        position: fixed;
        z-index: 9999;
    }

    /* Styles for mobile view */
    @media (max-width: 767px) {
        .alert {
            margin-top: 60vh !important;
        }
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

<br><br>
    <footer>
      <div class="container">
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
                      <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                  </ul>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <hr>
                  <p>&copy; 2023 CCAT - CAMPUS. All rights reserved.</p>
              </div>
          </div>
      </div>
    </footer>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        setTimeout(function() {
            var loader = document.getElementById("loader");
            loader.classList.add("fade-out"); // Apply fade-out class to initiate fading effect
            setTimeout(function() {
                loader.style.display = "none";
                document.body.style.overflow = "auto"; /* Enable scrolling */
            }, 1000); // Hide the loader after the fade-out transition (500 milliseconds)
        }, 3000); // Hide the loader after 3.5 seconds
    </script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <script>
  $(document).ready(function() {
    $('[data-toggle="flip"]').click(function() {
      $('.modal-dialog').toggleClass('flipped');
      var currentTitle = $('#myModalLabel').text();
      var newTitle = '';
      if (currentTitle === 'SIGN IN') {
        newTitle = 'REGISTER';
      } else {
        newTitle = 'SIGN IN';
      }
      $('#myModalLabel').text(newTitle);
    });
  });
</script>
<script>
    $(document).ready(function () {
        // Add an event listener to the form submission
        $("#login-form").submit(function (event) {
            // Prevent the form from submitting the traditional way
            event.preventDefault();

            // Get form data
            var formData = $(this).serialize();

            // Make AJAX request
            $.ajax({
                type: "POST",
                url: "php/login.php",
                data: formData,
                dataType: "json", // Specify that the response is expected to be JSON
                success: function (response) {
                    // Handle the success response here
                      if (response.redirect) {
                        window.location.href = response.redirect;
                    } else if (response.trash) {
                        // Check for 'trash' condition
                        // Notify about the account being deleted
                        $.notify({
                            title: "Something went wrong with your account.",
                            message: "Need help? Contact Mr. Jimple Jay Maligro.",
                            icon: 'fa fa-exclamation-circle'
                        }, {
                            type: "warning",
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
                            delay: 5000, // 3 seconds delay
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
                    } else if (response.wrongpass) {
                        // Check for 'wrong' condition
                        // Notify about incorrect username/password
                        $.notify({
                            title: "You entered an incorrect username/password",
                            message: "",
                            icon: 'fa fa-exclamation-circle'
                        }, {
                            type: "warning",
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
                            delay: 5000, // 3 seconds delay
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                            template: '<div data-notify="container" style="width: 90% !important;" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                                '<span data-notify="icon"></span> ' +
                                '<span data-notify="title" style="font-size: 20px; font-weight: 700;">{1}</span> ' +
                                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                                '</div>'
                        });
                    }

                },
                error: function (error) {
                    // Handle the error here
                    console.log("AJAX Error:", error);
                }
            });
        });
    });
</script>
<script>
$(document).ready(function () {

    function showNotification(title, message, icon, type) {
        $.notify({
            title: title,
            message: message,
            icon: icon
        }, {
            type: type,
            placement: {
                from: "top",
                align: "right"
            },
            offset: {
                x: 10,
                y: 120
            },
            style: 'bootstrap',
            className: type,
            delay: 2000,
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
    }

    $("#register-form").submit(function (event) {
        event.preventDefault();
        var formData = $(this).serialize();

        // Show the verifying notification before the AJAX request is sent
        showNotification("Verifying your id number...", "", 'fa fa-spinner fa-spin', 'info');

        // Delay for 4 seconds before making the AJAX request
        setTimeout(function () {
            $.ajax({
                type: "POST",
                url: "php/registration.php",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response && response.emailSent) {
                        showNotification("A link has been sent to your email.", "Check your email to register", 'fa fa-exclamation-circle', 'success');
                    } else if (response.noStudent) {
                        showNotification("Your id number is not recorded in the database.", "Need help? Contact Mr. Jimple Jay Maligro.", 'fa fa-exclamation-circle', 'warning');
                    } else if (response.alreadyRegistered) {
                        showNotification("You already have an account.", "Need help? Contact Mr. Jimple Jay Maligro.", 'fa fa-exclamation-circle', 'warning');
                    } else if (response.tokenStillValid) {
                        showNotification("You still have an unused registration link.", "Check your email.", 'fa fa-exclamation-circle', 'warning');
                    } else {
                        // If none of the conditions match, show a generic error message
                        showNotification("An error occurred.", "Please try again later.", 'fa fa-exclamation-circle', 'danger');
                    }
                },
                error: function (error) {
                    console.log("AJAX Error:", error);
                }
            });
        }, 3000); // 3000 milliseconds = 4 seconds
    });
});
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
  function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var showPasswordBtn = document.getElementById("showPasswordBtn");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      showPasswordBtn.innerHTML = '<i class="fa fa-eye"></i>';
    } else {
      passwordInput.type = "password";
      showPasswordBtn.innerHTML = '<i class="fa fa-eye-slash"></i>';
    }
  }
</script>

<script>
  // Function to open a modal and close any previously opened modal
  function openModal(modalId) {
    // Close any open modals
    $('.modal').modal('hide');
    
    // Open the requested modal
    $(modalId).modal('show');
  }

  $(document).ready(function() {
    $('#myModal').on('show.bs.modal', function() {
      openModal('#myModal');
    });
    
    $('#myModal2').on('show.bs.modal', function() {
      openModal('#myModal2');
    });
    
    $('#myModal3').on('show.bs.modal', function() {
      openModal('#myModal3');
    });
  });
</script>


<script>
  // Function to close the navbar in mobile view
  function closeNavbar() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
      navbarToggler.classList.add('collapsed');
      navbarCollapse.classList.remove('show');
    }
  }
  
  // Add event listeners to the nav items
  const navItems = document.querySelectorAll('.navbar-nav .nav-link');
  navItems.forEach(function(navItem) {
    navItem.addEventListener('click', closeNavbar);
  });
</script>

    <script type="text/javascript" src="js/plugins/sweetalert.min.js"></script>
    <script src="js/plugins/bootstrap-notify.min.js"></script>

 <!-- Auto next and loop script -->
 <script>
    $(document).ready(function() {
      $('#carouselExampleControls').carousel({
        interval: 2500 // Change slide every 3 seconds
      });

      $('#carouselExampleControls').on('slid.bs.carousel', function () {
        // Get the active slide index
        var activeIndex = $('.carousel-item.active').index();

        // Get the total number of slides
        var totalSlides = $('.carousel-item').length;

        // Check if it's the last slide, then go to the first slide
        if (activeIndex === totalSlides - 1) {
          $('#carouselExampleControls').carousel('pause');
          $('#carouselExampleControls').carousel(3);
          $('#carouselExampleControls').carousel('cycle');
        }
      });
    });
 </script>
<script>
$(document).ready(function() {
  $.ajax({
    url: 'php/updateDept.php', // URL of your PHP script
    type: 'POST', // or 'POST' if you prefer
    success: function(response) {
      // This function will be called when the Ajax request succeeds.
      // You can process the response from your PHP script here.
    },
    error: function(xhr, status, error) {
      // This function will be called if the Ajax request encounters an error.
      console.error('Ajax request error:', error);
    }
  });
});
</script>
<script>
      // check if the logout query parameter is present in the URL
      const logoutParams = new URLSearchParams(window.location.search);
      const logout = logoutParams.get('logout');
      if (logout === 'true') {
        $.notify({
          title: "You have successfully logged out!",
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
            y: 120
          },
          style: 'bootstrap',
          className: 'success',
          delay: 3000, // 3 seconds delay
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

      // check if the logout query parameter is present in the URL
      const successParams = new URLSearchParams(window.location.search);
      const success = successParams.get('success');
      if (success === 'true') {
        swal({
                title: "You are now registered.",
                text: "You can now login with your account.",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Okay",
                closeOnConfirm: false,
                closeOnCancel: false
            });
  // Remove the success URL parameter after 5 seconds
  setTimeout(function() {
                // Create a new URL without the success parameter
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
              }, 1000);
    }


</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64a555c7cc26a871b0267f4e/1h4ite5cn';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>