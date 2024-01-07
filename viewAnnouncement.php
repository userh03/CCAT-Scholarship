<?php
  include("php/connection.php");
  $title = urldecode($_GET['title']);
  $description = urldecode($_GET['description']);
  $imageURL = urldecode($_GET['image']);
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
    <link rel="stylesheet" href="css/style.styl">
    <title>Announcement</title>
  </head>
  <style>
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
        h1{
            margin-top: 10%;
            text-align: center;
        }
        .center-align {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        #conIMG {
            margin: 0 auto;
            max-width: 100%; /* Adjust this value as needed */
            height: 600px;
            border-radius: 20px;
        }
        @media (max-width: 768px) {
            h1{
                margin-top: 30% !important;
            }
            #conIMG {
                height: 100% !important;
            }
        }
  </style>
  <body>
    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top" style="z-index:9998;">
      <a class="navbar-brand" href="#" onclick="window.close();"><img style="height:70px;" src="images/logo2.png" alt=""></a>
    </nav>
    <div class="container center-align">
        <h1><?php echo $title; ?></h1>
        <img id="conIMG" src="<?php echo $imageURL; ?>" alt="<?php echo $title; ?>" class="img-fluid"><br>
        <p style="text-align: justify;"><?php echo $description; ?></p>
    </div>


  <footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <p>&copy; 2023 CCAT - CAMPUS. All rights reserved.</p>
            </div>
        </div>
    </div>
  </footer>
</body>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</html>