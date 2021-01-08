<?php
include './services/session.php';
require './services/connect.php';
$uid = $_SESSION["uid"];
if ($_SESSION['is_logged_in']) {
    //update data   
    if (isset($_POST['update'])) {
        $is_file_uploaded = false;
        $error = false;
        //uploading user profile
        if (isset($_FILES["myfile"]) && $_FILES["myfile"]["error"] == 0) {
            $allowedfiletypes = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif");

            $filename = $_FILES["myfile"]["name"];
            $filetype = $_FILES["myfile"]["type"];
            $filesize = $_FILES["myfile"]["size"];
            //verify extension
            $ext = pathinfo($filename, PATHINFO_EXTENSION);


            if (!array_key_exists($ext, $allowedfiletypes)) {
                $_SESSION['msg'] = "File type not acceptable";
                $error = true;
            }
            $maxsize = 5 * 1024 * 1024;
            if ($filesize > $maxsize) {
                $_SESSION['msg'] = "Please upload a file of size less than 5 mb";
                $error = true;
            }
            if (file_exists("./upload/profile/" . $filename)) {
                $_SESSION['msg'] = "File already exists";
                $error = true;
            } else {
                if (move_uploaded_file($_FILES["myfile"]["tmp_name"], "./upload/profile/" . $filename)) {
                    $is_file_uploaded = true;
                } else {
                    $_SESSION['msg'] = "Unable to update file";
                    $error = true;
                }
            }
        }


        if (!$error) {


            $userName = $_POST['userName'];
            $userEmail = $_POST['userEmail'];
            
            $country = $_POST['country'];
            $mobile = $_POST['mobile'];
            $state = $_POST['state'];
            $city = $_POST['city'];
            $address = $_POST['address'];
            $pin_code = $_POST['pin_code'];
            $profession = $_POST['profession'];
            $additional_field = $is_file_uploaded ? ",file_upload='" . $filename . "'" : "";

            //validation
            

            //user name validation
            if($userName=="")
            {
                $_SESSION['msg'] = "Name field is required";
                header('Location:profile.php');
                exit;
            }
            if (!preg_match("/^[a-zA-z]*$/", $userName)) {
                $_SESSION['msg'] = "Name field containes only alphabets and whitespace are allowed.";
                header('Location:profile.php');
                exit;
              }

              //mobile validation

              if($mobile=="")
              {
                  $_SESSION['msg']="Mobile field is required";
                  header('Location:profile.php');
                   exit;
              }
                 
              
             if(!preg_match('/^[0-9]{10}+$/', $mobile)) 
              {
                $_SESSION['msg'] = "Please enter 10 digit mobile number";
                header('Location:profile.php');
                exit;
              }

              // state validation
              if($state=="")
              {
                  $_SESSION['msg'] = "State field is required";
                  header('Location:profile.php');
                  exit;
              }
              if(!preg_match("/^[a-zA-z]*$/", $state))
              {
                  $_SESSION['msg'] = "State field only contines alphabets and whitespace";
                  header('Location:profile.php');
                  exit;
              }
            //   //city validation
              if($city=="")
              {
                  $_SESSION['msg'] = "City field is required";
                  header('Location:profile.php');
                  exit;
              }
              if(!preg_match("/^[a-zA-z]*$/", $city))
              {
                  $_SESSION['msg'] = "City field only contines alphabets and whitespace";
                  header('Location:profile.php');
                  exit;
              }
            //   //address validation
              if($address=="")
              {
                  $_SESSION['msg'] = "Address field is required";
                  header('Location:profile.php');
                  exit;
              }
                  if (!preg_match("/^[a-zA-z]*$/", $address)) {
                $_SESSION['msg'] = "Address field containes only alphabets and whitespace are allowed.";
                header('Location:profile.php');
                exit;
              }

               //Pin code validation
               if($pin_code=="")
            {
                $_SESSION['msg'] = "Pin code field is required";
                header('Location:profile.php');
                exit;
            }
            if(!is_numeric($pin_code))
            {
                $_SESSION['msg']="Pin code must contain only numbers";
                header('Location:profile.php');
                exit;
        

            }

            //Profession validation

            if($profession=="")
            {
                $_SESSION['msg']="Profession field is required";
                header('Location:profile.php');
                exit;
            }
            if(!preg_match("/^[a-zA-z]*$/", $profession))
            {
                $_SESSION['msg'] = "Profession field only contines alphabets and whitespace";
                header('Location:profile.php');
                exit;
            }

             
          


           
            


            $sql = "UPDATE admin SET userName='$userName', mobile='$mobile', country='$country'
         ,state='$state', city='$city',address='$address',pin_code='$pin_code',profession='$profession'" . $additional_field . " where id='$uid'";

            if ($mysqli->query($sql) === True) {
                $_SESSION['msg'] = "Record updated successfully";
                
                if($is_file_uploaded){
                    $_SESSION['profile']='./upload/profile/'.$filename;
                }
                if(!empty($profession)){
                    $_SESSION['profession'] = $profession;
                }
                if(!empty($userName)){
                    $_SESSION['name'] = $userName;
                }

            } else {
                $_SESSION['msg'] = "Unable to update data";
            }
        }
    }



    //select data
    $sql = "SELECT userName, userEmail, mobile, country, state, city, address, pin_code, profession,file_upload FROM admin where id='$uid' limit 0,1";
    $res = $mysqli->query($sql);
    if ($res->num_rows > 0) {
        // output data of each row

        $row = $res->fetch_assoc();

        $userName    = $row['userName'];
        $userEmail = $row['userEmail'];
        $mobile = $row['mobile'];
        $country = $row['country'];
        $state = $row['state'];
        $city = $row['city'];
        $address = $row['address'];
        $pin_code = $row['pin_code'];
        $profession = $row['profession'];
      
    }
}


$mysqli->close()



?>






<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Purple Admin</title>
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
                <div class="row content-wrapper">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center">Profile</h4>
                                <?php if (isset($_SESSION['msg'])) : ?>
                                    <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
                                <?php unset($_SESSION['msg']);
                                endif; ?>
                                <form class="form-sample mt-5" method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label ">Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="userName" value="<?php echo $row['userName']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="email" disabled class="form-control" name="userEmail" value="<?php echo $row['userEmail']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Mobile</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" name="mobile" value="<?php echo $row['mobile']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Country</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="country">
                                                        <option value=null <?php echo $row['country'] == null ? 'selected' : ''; ?>>Country</option>
                                                        <option value="india" <?php echo $row['country'] == 'india' ? 'selected' : ''; ?>>India</option>
                                                        <option value="japan" <?php echo $row['country'] == 'japan' ? 'selected' : ''; ?>>Japan</option>
                                                        <option value="america" <?php echo $row['country'] == 'america' ? 'selected' : ''; ?>>America</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">State</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="state" value="<?php echo $row['state']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">City</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="city" value="<?php echo $row['city']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Address</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" placeholder="Address" name="address" value="<?php echo $row['address']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Pin code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="pin_code" value="<?php echo $row['pin_code']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Profession</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" placeholder="Profession" name="profession" value="<?php echo $row['profession']; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <input type="file" name="myfile" class="file-upload-default">
                                               
                                                <div class="input-group col-xs-12">
                                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                    <span class="input-group-append">
                                                        <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                                        
                                                    </span>
                    

                                                </div>
                                                <?php if(isset($row['file_upload'])): ?><p class="apre"><a target="_blank" href="<?=  './upload/profile/'.$row['file_upload'] 
                                                ; ?>"  class="pre" >Preview</a></p><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label>File upload</label>
                                        <input type="file" name="myfile" class="file-upload-default">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                            </span>
                                        </div>
                                    </div> -->
                                    <button type="submit" class="btn btn-gradient-primary mb-2" name="update">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--include footer--->
                <?php include('./include/footer.php'); ?>

                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php include('./include/javascript.php'); ?>
</body>

</html>