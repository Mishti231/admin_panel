<?php
require('./services/session.php');
require('./services/connect.php');
$id = $_GET['id'];
if(!isset($id))
{
    header('Location:error.php');
    exit;
}



if (isset($_POST['update'])) {
    $name = $_POST['name'];

    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $filename = $_FILES["myfile"]["name"];

    


    //validation

    //name validation
    if ($name == "") {
        $_SESSION['msg'] = "Name field is required";
        header('Location:product_edit.php');
        exit;
    }
    if (!preg_match("/^[a-zA-z]*$/", $name)) {
        $_SESSION['msg'] = "Name field containes only alphabets and whitespace are allowed.";
        header('Location:product_edit.php');
        exit;
    }

    if (strlen($name) < 3) {
        $_SESSION['msg'] = "Name field must contain 3 characters atleast";
        header('Location:product_edit.php');
        exit;
    }

    //price field validation
    if ($price == "") {
        $_SESSION['msg'] = "Price field is required";
        header('Location:product_edit.php');
        exit;
    }
    if (!is_numeric($price)) {
        $_SESSION['msg'] = "Price enter was not numberic";
        header('Location:product_edit.php');
        exit;
    }
    //quantity field validation
    if ($quantity == "") {
        $_SESSION['msg'] = "Quantity field is required";
        header('Location:product_edit.php');
        exit;
    }

    if (!is_numeric($quantity)) {
        $_SESSION['msg'] = "Quentity enter was not numberic";
        header('Location:product_edit.php');
        exit;
    }
    //uploading user profile
    $error = false;
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
         else {
            if (move_uploaded_file($_FILES["myfile"]["tmp_name"], "./upload/product/" . $filename)) {
                        
          
            } else {
                $_SESSION['msg'] = "Unable to update file";
                $error = true;
            }
        }
    }

    //update data 
    $sql = "UPDATE product SET name='$name', price='$price', quantity='$quantity' , description='$description' ,myfile='$filename' where id='$id'";
   
    if ($mysqli->query($sql)) {

       
        $_SESSION['msg'] = "Record updated successfully";
    } else {
        $_SESSION['msg'] = "Unable to update data";
    }
}
//select data
$sql = "SELECT * FROM product where id='$id' limit 0,1";
$res = $mysqli->query($sql);
if ($res->num_rows > 0) {
    // output data of each row

    $row = $res->fetch_assoc();

    $name   = $row['name'];
    $price = $row['price'];
    $quantity = $row['quantity'];
    $description= $row['description'];
    $myfile=$row['myfile'];
   

}

$mysqli->close();




?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pooja's blog </title>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="form-sample" method="POST"  enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id ?>">
                                    <?php if (isset($_SESSION['msg'])) : ?>
                                        <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
                                    <?php unset($_SESSION['msg']);
                                    endif; ?>
                                    <p class="card-description"> Edit Products </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Price</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" name="price" value="<?php echo $price; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Quantity</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" placeholder="Quantity" name="quantity" value="<?php echo $quantity; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Description</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" placeholder="Description" name="description"value="<?php echo $description; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>File upload</label>
                                        <input type="file" class="file-upload-default" name="myfile" value="<?php echo $myfile; ?>">
                                        <div class="input-group ">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if(isset($row['myfile'])): ?><p class="apre mt-1"><a target="_blank" href="<?=  './upload/product/'.$row['myfile'] 
                                                ; ?>"  class="pre" >Preview</a></p><?php endif; ?>
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