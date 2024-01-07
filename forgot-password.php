<?php
    session_start();
    session_unset();
    session_destroy();
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
    
    <title>Forgot Password? - Scholarship</title>
  </head>
  <body style="overflow: hidden;">

    <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top" style="z-index:9998;">
      <a class="navbar-brand" href="#" onclick="window.location.reload()"><img style="height: 70px;" src="images/logo2.png" alt=""></a>
    </nav>
 
<style>
  @media screen and (max-width: 768px){
    .container{
      margin-top: 50% !important;
    }
  }
  .container{
    margin-top: 10%;
  }

</style>

    </head>
    <body>
    <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card mt-5">
          <div class="card-body">
            <h3 class="card-title text-center">Forgot Password</h3>
            <form method="POST" action="php/forgot-password.php">
              <div class="form-group">
                <label for="email">Email address</label>
                <input class="form-control" type="email" required name="email" id="emailInput" onkeyup="checkEmail()" placeholder="Enter your email">
                <div id="emailError" class="mt-2"></div> 
              </div>
              <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
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

</body>
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
<script src="js/plugins/sweetalert2.all.js"></script>
<script>
  $(document).ready(function() {
         // Check if the token query parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    if (success === 'true'){
         // Display a SweetAlert notification for successful password change
       swal.fire({
           icon: 'success',
           title: 'Reset link has been sent successfully',
           allowOutsideClick: false,
           showConfirmButton: true
       });
    }
  });
</script>
<script>
  $(document).ready(function() {
         // Check if the token query parameter is present in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error === 'true'){
         // Display a SweetAlert notification for successful password change
       swal.fire({
           icon: 'error',
           title: 'You still have a <br> unused reset link',
           allowOutsideClick: false,
           showConfirmButton: true
       });
       // Remove the success URL parameter after 5 seconds
      setTimeout(function() {
                // Create a new URL without the success parameter
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
              }, 1000);
    }
  });
</script>
</html>