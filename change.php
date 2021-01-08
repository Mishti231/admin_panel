<?php
include './services/session.php';
$uid = $_SESSION["uid"];
require './services/connect.php';
function redirect()
{
  header('Location:change.php');
}
if (isset($_POST['submit'])) {
/*  Declare variables   */
  $oldpassword = $_POST['oldpassword'];
  $oldpassword = md5($oldpassword);
  $npassword = $_POST['newPassword'];
  $cpassword = $_POST['confirmPassword'];

 //check validation
  if (!preg_match("/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/", $npassword)) {
    $_SESSION['msg'] = "password does not fullfill the condition";
    redirect();
    exit;
  }


//check that new password is similar to confirm password
  if ($npassword == $cpassword) {
//select password
    $sql = "SELECT password from admin  where id='$uid'";
    $res = $mysqli->query($sql);

    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      $password   = $row['password'];
      $npassword = md5($npassword);
  //check that old password is similar to password    
      if ($oldpassword == $password) {
        $sql = "UPDATE admin SET password = '$npassword' where id='$uid'";//update new password
        if($mysqli->query($sql)){
          $_SESSION['msg'] = 'Password has been changed successfully';
        }
        else{
          $_SESSION['msg'] = 'Unable to update new password into the database';
        }
        
      } else {
        $_SESSION['msg'] = 'Password does not match with oldpassword';
      }
    }
  }
  else{
    $_SESSION['msg'] = 'Confirm Password does not match';
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> Admin Panel</title>
  <?php include('./include/css.php'); ?>
</head>

<body>
  <div class="container-scroller">
    <!-- include navbar -->
    <?php include('./include/navbar.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- include sidebar --->
      <?php include('./include/sidebar.php'); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card m-auto">
              <div class="card">
                <div class="card-body">
                  <p class="card-description"> Change Password</p>
                  <?php if (isset($_SESSION['msg'])) : ?>
                    <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
                  <?php unset($_SESSION['msg']);
                  endif; ?>
                  <form class="forms-sample" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group">
                      <label for="">Old Password</label>
                      <input type="password" class="form-control" id="" placeholder="Password" name="oldpassword">
                    </div>
                    <div class="form-group">
                      <label for="">New Password</label>
                      <input type="password" class="form-control" id="" placeholder="New password" name="newPassword">
                    </div>
                    <div class="form-group">
                      <label for="">Confirm Password</label>
                      <input type="password" class="form-control" id="" placeholder="Confirm Password" name="confirmPassword">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary mr-2" name="submit">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php include('./include/javascript.php'); ?>
</body>

</html>