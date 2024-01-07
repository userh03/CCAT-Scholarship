<?php
include("connection.php");

// SQL query to retrieve data from the table
$sql = "SELECT app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_status 
        FROM approved_applicants_tbl 
        UNION 
        SELECT app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_status 
        FROM denied_applicants_tbl";
$result = $con->query($sql);

// Check for query execution error
if (!$result) {
    die("Query failed: " . $con->error);
}

// File path and name
$filename = 'list_approved_students.csv';

// Open the file in write mode
$file = fopen($filename, 'w');

if (!$file) {
    die("Failed to create CSV file.");
}

// Data for the CSV
$data = array();
$data[] = array('app_id', 'app_student_number', 'app_fname', 'app_lname', 'app_mobile', 'app_email', 'app_section', 'app_department', 'app_adviser', 'app_scholar_type', 'app_status');

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            $row["app_id"],
            $row["app_student_number"],
            $row["app_fname"],
            $row["app_lname"],
            $row["app_mobile"],
            $row["app_email"],
            $row["app_section"],
            $row["app_department"],
            $row["app_adviser"],
            $row["app_scholar_type"],
            $row["app_status"]
        );
    }

    // Loop through the data and write to the CSV
    foreach ($data as $row) {
        fputcsv($file, $row);
    }

    // Close the file
    fclose($file);

    // Provide the file to the user for download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($filename));
    readfile($filename);
    
    // Delete the file after download (optional)
    unlink($filename);
} else {
    // No data found in the table
    echo '<!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="../images/logo.ico" />
        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/custom.css">
        <!-- Font-icon css-->
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/style.styl">
        <title>No Data Found</title>
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
            @media (max-width: 768px) {
              .navbar-brand img {
                height: 50px !important;
                margin: auto !important;
                display: block;
              }
    
              .navbar-brand {
                display: flex;
                justify-content: center;
              }
            }
        </style>
      <body>
        <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top" style="z-index:9998;">
          <a class="navbar-brand" href="#"><img style="height:70px;" src="../images/logo2.png" alt=""></a>
        </nav>
        <div class="page-error tile">
          <h1><i class="fa fa-exclamation-circle"></i> Error: No Data Found</h1>
          <p>There was no data to generate.</p>
          <p><a style="color: white !important;" class="btn btn-primary" href="javascript:window.history.back();">Go Back</a></p>
        </div>
      <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p>&copy; 2023 CCAT - CAMPUS. All rights reserved.</p>
                </div>
            </div>
        </div>
      </footer>
    </body>
        <!-- Essential javascripts for application to work-->
        <script src="../js/jquery-3.3.1.min.js"></script>
        <script src="../js/popper.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/main.js"></script>
    </html>
    ';
}

// Close the database connection
$con->close();
?>


