<?php
// Assuming you have established a database connection
include("php/connection.php");

// Check if form is submitted
if(isset($_POST['submit'])){
    // Retrieve form data
    $newPassword = $_POST['password'];

    // Get the token from the URL parameters
    if(isset($_GET['token'])){
        $token = $_GET['token'];

        // Select the email based on the token from the registration_tokens_temp table
        $selectTokenQuery = "SELECT email FROM registration_tokens_temp WHERE token = '$token'";
        $tokenResult = mysqli_query($con, $selectTokenQuery);

        if (mysqli_num_rows($tokenResult) > 0) {
            // Token found, get the email
            $row = mysqli_fetch_assoc($tokenResult);
            $email = $row["email"];

            // Check if the email exists in the students_tbl
            $selectStudentEmailQuery = "SELECT email FROM students_tbl WHERE email = '$email'";
            $studentEmailResult = mysqli_query($con, $selectStudentEmailQuery);

            // Check if the email exists in the admin_tbl
            $selectAdminEmailQuery = "SELECT email FROM admin_tbl WHERE email = '$email'";
            $adminEmailResult = mysqli_query($con, $selectAdminEmailQuery);

            if (mysqli_num_rows($studentEmailResult) > 0) {
                // Email found in students_tbl, update the password

                // Hash the new password using Bcrypt
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                $updatePasswordQuery = "UPDATE students_tbl SET password = '$hashedPassword' WHERE email = '$email'";
                mysqli_query($con, $updatePasswordQuery);

                // Update the username (replace 'username_column' with your actual column name)
                if (!empty($_POST['username'])) {
                    $newUsername = $_POST['username'];
                    $updateUsernameQuery = "UPDATE students_tbl SET username = '$newUsername' WHERE email = '$email'";
                    mysqli_query($con, $updateUsernameQuery);
                }

            } elseif (mysqli_num_rows($adminEmailResult) > 0) {
                // Email found in admin_tbl, update the password in admin_tbl

                // Hash the new password using Bcrypt
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                $updatePasswordQuery = "UPDATE admin_tbl SET password = '$hashedPassword' WHERE email = '$email'";
                mysqli_query($con, $updatePasswordQuery);

                // Update the username (replace 'username_column' with your actual column name)
                if (!empty($_POST['username'])) {
                  $newUsername = $_POST['username'];
                  $updateUsernameQuery = "UPDATE admin_tbl SET username = '$newUsername' WHERE email = '$email'";
                  mysqli_query($con, $updateUsernameQuery);
                }

            } else {
                echo "Email not found in the students_tbl or admin_tbl.";
            }

            // Update the is_valid column of the registration-token table
            $updateTokenQuery = "UPDATE registration_tokens_temp SET is_valid = 0 WHERE token = '$token'";
            mysqli_query($con, $updateTokenQuery);

            // Redirect to a success page (replace 'success.php' with your desired success page)
            header("Location: index.php?success=true");
            exit;

        } else {
            echo "Token not found in the registration_tokens_temp table.";
        }
    } else {
        echo "Token not found in the URL parameters.";
    }
}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="css/style.styl">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Signup - Scholarship</title>
  </head>
  <body style="overflow: hidden;">

    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top" style="z-index:9998;">
      <a class="navbar-brand" href="#" onclick="window.location.reload()"><img style="height: 70px;" src="images/logo2.png" alt=""></a>
    </nav>
 
<style>
  @media screen and (max-width: 768px){
    .container{
      margin-top:20% !important;
    }
  }
  .container{
    margin-top: 10%;
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

</head>
<body>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card mt-5">
          <div class="card-body">
            <h3 class="card-title text-center">Signup</h3>
            <form method="POST">
              <div class="col-md-12">
                <br><label for="username">Username:</label>
                <input class="form-control" type="text" name="username" required placeholder="Enter desired username">
              </div>
              <div class="col-md-12">
                  <br><label for="password">Password:</label>
                  <div class="input-group">
                    <input type="password" id="password" class="form-control" required name="password" onkeyup="checkPassword()" placeholder="Enter desired password">
                    <div class="input-group-append">
                      <span class="input-group-text password-toggle-icon" id="showPasswordBtn" onclick="togglePasswordVisibility()">
                        <i class="fa fa-eye-slash"></i>
                      </span>
                    </div>
                  </div>
                  <div id="passwordCheck" class="mt-2"></div>            
              </div>
              <br><button type="submit" name="submit" id="submitbutton" class="btn btn-primary btn-block">Register</button>
            </form>
            <br><a href="index.php">Go back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <script>
      // Function to toggle password visibility
function togglePassword(inputId, toggleIconId) {
  const passwordInput = document.getElementById(inputId);
  const toggleIcon = document.getElementById(toggleIconId);
  
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.remove("fa-eye-slash");
    toggleIcon.classList.add("fa-eye");
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.remove("fa-eye");
    toggleIcon.classList.add("fa-eye-slash");
  }
}

// Attach event listeners to toggle password icons
document.addEventListener("DOMContentLoaded", function() {
  const togglePasswordIcons = document.getElementsByClassName("password-toggle-icon");
  
  Array.prototype.forEach.call(togglePasswordIcons, function(icon) {
    icon.addEventListener("click", function() {
      const inputId = this.getAttribute("data-input");
      const toggleIconId = this.getAttribute("data-toggle-icon");
      
      togglePassword(inputId, toggleIconId);
    });
  });
});

</script>
<script src="js/plugins/sweetalert2.all.js"></script>
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
  function checkPassword() {
    const passwordInput = document.getElementById('password');
    const passwordCheck = document.getElementById('passwordCheck');
    const submitButton = document.getElementById('submitbutton'); // Assuming the submit button has an ID of "submitButton"

    // Regular expressions for password validation
    const regexUpperCase = /^(?=.*[A-Z])/;
    const regexLowerCase = /^(?=.*[a-z])/;
    const regexNumber = /^(?=.*[0-9])/;
    const regexSpecialChar = /^(?=.*[!@#$%^&*()\-=_+[\]{};':"\\|,.<>/?~`])/;
    const regexLength = /^(?=.{8,})/;

    const password = passwordInput.value;

    if (password == "") {
      passwordCheck.innerHTML = '<i></i>';
      submitButton.disabled = true;
      return;
    }

    if (!password.match(regexUpperCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one uppercase letter.</i>';
      submitButton.disabled = true;
      return;
    }

    if (!password.match(regexLowerCase)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one lowercase letter.</i>';
      submitButton.disabled = true;
      return;
    }

    if (!password.match(regexNumber)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one number.</i>';
      submitButton.disabled = true;
      return;
    }

    if (!password.match(regexSpecialChar)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must contain at least one special character.</i>';
      submitButton.disabled = true;
      return;
    }

    if (!password.match(regexLength)) {
      passwordCheck.innerHTML = '<i class="text-danger">Must be at least 8 characters long.</i>';
      submitButton.disabled = true;
      return;
    }

    passwordCheck.innerHTML = '<i class="text-success">Password is valid.</i>';
    submitButton.disabled = false;
  }  
</script>
<script>
  window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');

    if (token) {
      // Create an AJAX request to check_token.php
      const xhr = new XMLHttpRequest();

      // Set up the request
      xhr.open('POST', 'php/check_token_registration.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      // Create a Promise for the AJAX request
      const xhrPromise = new Promise(function(resolve, reject) {
        xhr.onload = function() {
          if (xhr.status === 200) {
            resolve(xhr.responseText);
          } else {
            reject(xhr.status);
          }
        };

        xhr.onerror = function() {
          reject(xhr.status);
        };
      });

      // Wait for 5 seconds
      const delay = new Promise(function(resolve) {
        setTimeout(resolve, 5000);
      });

      // Send the request with the token as a parameter
      xhr.send('token=' + encodeURIComponent(token));

      // Show loading spinner using SweetAlert
      const loadingAlert = swal.fire({
        title: 'Verifying your signup link',
        html: 'Please wait...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
          swal.showLoading();
        }
      });

      // Wait for both the AJAX request and the delay
      Promise.all([xhrPromise, delay])
        .then(function([response]) {
          // Check the response from the PHP file
          if (response === 'valid') {
            // Display success message using SweetAlert
            swal.fire({
              icon: 'success',
              title: 'Link verified',
              allowOutsideClick: false,
              showConfirmButton: true
            });

          } else {
            // Display error message using SweetAlert
            swal.fire({
              icon: 'error',
              title: 'Invalid link',
              allowOutsideClick: false,
              showConfirmButton: true
            }).then(function() {
              // Redirect to page-login.php after user clicks "OK"
              window.location.href = 'index.php';
            });
          }
        })
        .catch(function(error) {
          // Display error message using SweetAlert
          swal.fire({
            icon: 'error',
            title: 'An error occurred',
            text: 'Status code: ' + error,
            allowOutsideClick: false,
            showConfirmButton: true
          }).then(function() {
            // Redirect to page-login.php after user clicks "OK"
            window.location.href = 'index.php';
          });
        });
    }
  });
</script>
<script>
   // Check if the token query parameter is present in the URL
   const urlParams = new URLSearchParams(window.location.search);
   const token = urlParams.get('token');
   const success = urlParams.get('success');

   if (success === 'true') {
       // Display a SweetAlert notification for successful password change
       swal.fire({
           icon: 'success',
           title: 'Registration Success',
           allowOutsideClick: false,
           showConfirmButton: true
       }).then(function() {
           // Redirect to a specific page after user clicks "OK"
           window.location.href = 'index.php';
       });
   } else if (!token) {
       // Display a SweetAlert notification for invalid session
       swal.fire({
           icon: 'error',
           title: 'Invalid Session',
           text: 'The token is missing or invalid.',
           allowOutsideClick: false,
           showConfirmButton: true
       }).then(function() {
           // Redirect to a specific page after user clicks "OK"
           window.location.href = 'index.php';
       });
   }
</script>
</body>
</html>