<?php
include("connection.php");

$query3 = "SELECT * FROM year_tbl";
$result3 = mysqli_query($con, $query3);
$row3 = mysqli_fetch_assoc($result3);

$year = $row3['past_year'];

// Fetch data from the database
$sql = "SELECT app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_year, app_status 
        FROM approved_applicants_tbl WHERE app_year = '$year'
        UNION 
        SELECT app_id, app_student_number, app_fname, app_lname, app_mobile, app_email, app_section, app_department, app_adviser, app_year, app_status 
        FROM denied_applicants_tbl WHERE app_year = '$year'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Include the Composer autoload file
    require '../PhpSpreadsheet/vendor/autoload.php';

    // Create a new PhpSpreadsheet object
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    // Set the active sheet
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();

    // Add column headers
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Student Number');
    $sheet->setCellValue('C1', 'First Name');
    $sheet->setCellValue('D1', 'Last Name');
    $sheet->setCellValue('E1', 'Mobile');
    $sheet->setCellValue('F1', 'Email');
    $sheet->setCellValue('G1', 'Section');
    $sheet->setCellValue('H1', 'Department');
    $sheet->setCellValue('I1', 'Adviser');
    $sheet->setCellValue('J1', 'Year');
    $sheet->setCellValue('K1', 'Status');

    // Add data to the sheet
    $row = 2;
    while ($row_data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $row_data['app_id']);
        $sheet->setCellValue('B' . $row, $row_data['app_student_number']);
        $sheet->setCellValue('C' . $row, $row_data['app_fname']);
        $sheet->setCellValue('D' . $row, $row_data['app_lname']);
        $sheet->setCellValue('E' . $row, $row_data['app_mobile']);
        $sheet->setCellValue('F' . $row, $row_data['app_email']);
        $sheet->setCellValue('G' . $row, $row_data['app_section']);
        $sheet->setCellValue('H' . $row, $row_data['app_department']);
        $sheet->setCellValue('I' . $row, $row_data['app_adviser']);
        $sheet->setCellValue('J' . $row, $row_data['app_year']);
        $sheet->setCellValue('K' . $row, $row_data['app_status']);
        $row++;
    }

    // Set the header information for the Excel file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="past_applicants_list.xls"');
    header('Cache-Control: max-age=0');

    // Write the Excel file to the output buffer and send to the browser
    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
    $writer->save('php://output');

    // Clean up
    $con->close();
    exit;
} else {
    // Display the HTML content for "No Data Found"
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
    </html>';
}
?>
