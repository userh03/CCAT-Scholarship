<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's email address from the form
    $email = $_POST['email'];

    // Database check
    include("connection.php");

    // Prepare the SQL statement to check multiple tables using UNION
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM (
            SELECT email FROM students_tbl WHERE email = :email
            UNION ALL
            SELECT email FROM admin_tbl WHERE email = :email
            UNION ALL
            SELECT email FROM superadmin_tbl WHERE email = :email
        ) AS emails
    ");
    $stmt->bindValue(':email', $email);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $emailExists = (bool) $stmt->fetchColumn();

    if ($emailExists) {
        // Check if the email exists and is valid in the password reset tokens table
        $stmt = $pdo->prepare("SELECT * FROM password_reset_tokens_temp WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $tokenRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tokenRow && $tokenRow['is_valid'] == 1) {
            // A valid token exists and is valid, stop sending mail
            echo '<script>
                        window.location.href = "../forgot-password.php?error=true";
                    </script>';
        } else {
            // Generate a random password reset token (you can use any method you prefer)
            $token = bin2hex(random_bytes(32));

            if ($tokenRow) {
                // Update the existing token with the new one
                $stmt = $pdo->prepare("UPDATE password_reset_tokens_temp SET token = :token, is_valid = 1 WHERE email = :email");
            } else {
                // Insert a new row with the token
                $stmt = $pdo->prepare("INSERT INTO password_reset_tokens_temp (email, token, is_valid) VALUES (:email, :token, 1)");
            }
            
            $stmt->execute([
                ':email' => $email,
                ':token' => $token
            ]);

            // Send the password reset email
            try {
                $mail = new PHPMailer();

                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com';
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;
                $mail->Username = 'ccat-scholarship@cvsu-rosario-scholarship.online';
                $mail->Password = 'Lokomoko123$';
        
                $mail->setFrom('ccat-scholarship@cvsu-rosario-scholarship.online', 'CCAT Scholarship');
                $mail->addAddress($email);
        
                $resetLink = 'https://cvsu-rosario-scholarship.online/reset-password.php?token=' . $token;
                $subject = 'Password Reset Request';
                
               // HTML body
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <title>Reset Password</title>
                    <!-- Include Bootstrap CSS -->
                    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        /* Add any additional custom styles here */
                    </style>
                </head>
                <body style="font-family: \'Montserrat\', sans-serif; text-align: center; background-color: #f8f9fa;">
                    <div class="container" style="margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <p>Hello!</p>
                        <p>You have requested to reset your password. Please click the button below to reset your password:</p>
                        <p class="text-center">
                            <a href="' . $resetLink . '" style="text-decoration: none;"><button style="color: white; font-weight: 700; background: green; border-radius: 5px; border-style: none; padding: 10px 15px;">Reset Now</button></a>
                        </p>
                        <p>If you didn\'t request this, you can ignore this email.</p>
                        <p>Best regards,<br><strong>CCAT Scholarship</strong><br><small>Cavite State University<br>Scholarship Office</small></p>
                    </div>

                    <!-- Include Bootstrap JS and Popper.js if needed -->
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                </body>
                </html>
                ';

                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->isHTML(true);

                if ($mail->send()) {
                    // Email sent successfully
                    echo '<script>
                                window.location.href = "../forgot-password.php?success=true";
                            </script>';
                    
                } else {
                    // Error sending email
                    echo 'An error occurred while sending the email. Please try again.';
                }
            } catch (Exception $e) {
                // Exception occurred while sending email
                echo 'An error occurred while sending the email. Please try again.';
            }
        }
    } else {
        // No email match found
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
            <title>No Email Found</title>
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
              <h1><i class="fa fa-exclamation-circle"></i> Error: No Email Found</h1>
              <p>There was no email for the link to be sent.</p>
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
}
?>
