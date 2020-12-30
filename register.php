<?php
session_start();
require './services/connect.php';
require 'vendor/autoload.php';

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function redirect()
{
  header('Location:register.php');
}

if (isset($_POST['submit'])) {

  $name = $_POST['userName'];
  $email = $_POST['userEmail'];
  $country = $_POST['country'];
  $password = $_POST['password'];
  $term_condition = $_POST['termCondition'];



  //Data validation
  if ($name == "") {
    $_SESSION['msg'] = "Name field is required";
    redirect();
    exit;
  }
  if (!preg_match("/^[a-zA-z]*$/", $name)) {
    $_SESSION['msg'] = "Only alphabets and whitespace are allowed.";
    redirect();
    exit;
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['msg'] = "please enter valid email";
    redirect();
    exit;
  }
  if ($country == "null") {
    $_SESSION['msg'] = "Please select an option from the select box.";
    redirect();
    exit;
  }
  /**
   * 
   * Minimum eight and maximum 10 characters, at least one uppercase letter,
   * one lowercase letter, one number and one special character:
   * 
   */
  if (!preg_match("/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/", $password)) {
    $_SESSION['msg'] = "password does not fullfill the condition";
    redirect();
    exit;
  }
  if (!isset($term_condition)) {
    $_SESSION['msg'] = "please cheked the term and condition";
    redirect();
    exit;
  }


  //hashing password
  $password = md5($password);




  if ($name != "" and $email != "" and $country != "" and $password != "") {
    //insert data into database
    $res = "INSERT INTO admin (userName,userEmail,country,password)
  VALUES ('$name','$email','$country','$password')";
    if (!$mysqli->query($res)) {
      $_SESSION['msg'] = "Error:could not able to insert data." . mysqli_error($mysqli);
      redirect();
      exit;
    }
    $to = "pbhaumik200@gmail.com"; //admin email
    $toUser = "$email"; //user email

    $subject = "New query submitted"; //admin subject
    $userSubject = "Thank you for submitting your query"; //user subject

    //admin message
    $message = "Please find the details below:
                      Name: " . $name . ";
                      Email:" . $email . ";
                      Mobile:" . $mob . ";
                      Date:" . $date . ";
                      Course:" . $course . ";
                      Gender:" . $gender . ";
                      Address:" . $address . ";
                      Additional_details:" . $additional_details;


    $userMessage = "Thank you for submitted your query"; //user message           

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->isSMTP();
      $mail->Host       = 'smtp.hostinger.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'support@sonuhaircut.com';
      $mail->Password   = 'poojaT@89';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

      //Recipients
      $mail->setFrom('support@sonuhaircut.com', 'Mailer');
      $mail->addAddress($to, 'Admin');    // Add a recipient


      // Content
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = $message;
      $isAdminMailSent = $mail->send();


      $mail->ClearAllRecipients();
      //for user
      $mail->addAddress($toUser, $name);    // Add a recipient
      $mail->Subject = $userSubject;
      $mail->Body    = $userMessage;
      $isUserMailSent = $mail->send();

      $_SESSION['msg'] = "Query Submitted successfully";
    } catch (Exception $e) {
      $_SESSION['msg'] = "Error while submitting query";
    }

    header('Location:register.php');
  } else {
    $_SESSION['msg'] = "Error while submitting query";
    header('Location:register.php');
  }
}
$mysqli->close();


?>










<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Purple Admin</title>
  <!-- include:css -->
  <?php include('./include/css.php'); ?>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="./assets/images/logo.svg">
              </div>
              <h4>New here?</h4>
              <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
           
              <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
              <?php unset($_SESSION['msg']);
              endif; ?>

              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="userName" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" name="userEmail" placeholder="Email">
                </div>
                <div class="form-group">
                  <select class="form-control form-control-lg" name="country">
                    <option value=null>Country</option>
                    <option value="united states of america">United States of America</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="India">India</option>
                    <option value="Germany">Germany</option>
                    <option value="Argentina">Argentina</option>
                  </select>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                </div>
                <div class="mb-4">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input" name="termCondition" value="yes"> I agree to all Terms & Conditions </label>
                  </div>
                </div>
                <div class="mt-3">
                  <input class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="submit">
                </div>
                <div class="text-center mt-4 font-weight-light"> Already have an account? <a href="login.php" class="text-primary">Login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- include:js -->
  <?php include('./include/javascript.php'); ?>
  <!-- endinject -->
</body>

</html>