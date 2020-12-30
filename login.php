<?php
session_start();
require './services/connect.php';
if (isset($_POST['login'])) {
  if (!empty($_POST['userName']) && !empty($_POST['password'])) {
    $username = $_POST['userName'];
    $password = $_POST['password'];
    $password = md5($password);
    $sql = "SELECT * from  admin where  userName='$username' and password ='$password' limit 0,1";
    $res = $mysqli->query($sql);

    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $_SESSION['is_logged_in'] = true;
      $_SESSION['uid'] = $row['id'];
      $_SESSION['msg'] ="login successfully";
      
    } else {
      $_SESSION['msg'] = "invalid username and password";
    }
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
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <?php if (isset($_SESSION['msg'])) : ?>
                <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
              <?php unset($_SESSION['msg']);
              endif; ?>


              <form class="pt-3" method="POST">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="userName" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                </div>
                <div class="mt-3">
                  <input class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn" type="submit" name="login">
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
                <div class="mb-2">
                  <button type="button" class="btn btn-block btn-facebook auth-form-btn">
                    <i class="mdi mdi-facebook mr-2"></i>Connect using facebook </button>
                </div>
                <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="register.php" class="text-primary">Create</a>
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
</body>

</html>