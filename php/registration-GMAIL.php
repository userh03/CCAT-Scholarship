<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$response = array(); // Initialize an array to hold the response data

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user's student_id_number from the form
    $student_id_number = $_POST['student_id_number'];

    // Database check
    include("connection.php");

    // Prepare the SQL statement to check if student_id_number exists and retrieve the username and password
    $stmt = $pdo->prepare("SELECT * FROM students_tbl WHERE student_id_number = :student_id_number");
    $stmt->bindValue(':student_id_number', $student_id_number);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($studentData) {
        // Student with the provided student_id_number exists

        // Check if the username and password already have data
        if (!empty($studentData['username']) && !empty($studentData['password'])) {
            // Username and password already exist, so end the execution
            $response['alreadyRegistered'] = true;
            echo json_encode($response);
        } else {
            // Get the email from the student record
            $email = $studentData['email'];
            
            // Generate a random registration token (you can use any method you prefer)
            $token = bin2hex(random_bytes(32));

            // Check if a token already exists for this email
            $stmt = $pdo->prepare("SELECT * FROM registration_tokens_temp WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $tokenRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tokenRow) {
                // Update the existing token with the new one
                $stmt = $pdo->prepare("UPDATE registration_tokens_temp SET token = :token, is_valid = 1 WHERE email = :email");
            } else {
                // Insert a new row with the token
                $stmt = $pdo->prepare("INSERT INTO registration_tokens_temp (email, token, is_valid) VALUES (:email, :token, 1)");
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

                $resetLink = 'https://cvsu-rosario-scholarship.online/register.php?token='.$token;
                $subject = 'Account Registration';

               // HTML body
                $body = '
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <title>Registration</title>
                    <!-- Include Bootstrap CSS -->
                    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        /* Add any additional custom styles here */
                    </style>
                </head>
                <body style="font-family: \'Montserrat\', sans-serif; text-align: center; background-color: #f8f9fa;">
                    <div class="container" style="margin: 20px auto; padding: 20px; background-color: #ffffff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <p>Hello!</p>
                        <p>You have requested to register in our scholarship portal. Please click the button below to register:</p>
                        <p class="text-center">
                            <a href="' . $resetLink . '" style="text-decoration: none;"><button style="color: white; font-weight: 700; background: green; border-radius: 5px; border-style: none; padding: 10px 15px;">Register Now</button></a>
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
                        $response['emailSent'] = true;
                        echo json_encode($response);         
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
        // No student found with the provided student_id_number
        $response['noStudent'] = true;
        echo json_encode($response);

    }
}
?>
