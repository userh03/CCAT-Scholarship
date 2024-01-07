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
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth = true;
                $mail->Username = 'ccatscholarship@gmail.com';
                $mail->Password = 'tymwwguicwflgtnl';
        
                $mail->setFrom('ccatscholarship@gmail.com', 'CCAT Scholarship');
                $mail->addAddress($email);
        
                $resetLink = 'http://cvsu-rosario-scholarship.online/reset-password.php?token=' . $token;
                $subject = 'Password Reset Request';
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
        echo 'Email not found';
    }
}
?>
