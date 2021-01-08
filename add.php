<?php
require('./services/session.php');
require('./services/connect.php');
$uid = $_SESSION["uid"];
function redirect()
{
    header('Location:add.php');
}
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
   

    //data validation in every field
    if ($name == "") {
        $_SESSION['msg'] = "Name field is required";
        redirect();
        exit;
    }
    if (!preg_match("/^[a-zA-z]*$/", $name)) {
        $_SESSION['msg'] = "Only alphabets and whitespace are allowed.";
        print_r($_SESSION['msg']);
        redirect();
        exit;
    }
    if (strlen($name) < 3) {
        $_SESSION['msg'] = "Name field must contain 3 characters atleast";
        redirect();
        exit;
    }
    if ($price == "") {
        $_SESSION['msg'] = "Price field is required";
        redirect();
        exit;
    }

    if (!is_numeric($price)) {
        $_SESSION['msg'] = "Price enter was not numberic";
        redirect();
        exit;
    }
    if ($quantity == "") {
        $_SESSION['msg'] = "Quantity field is required";
        redirect();
        exit;
    }

    if (!is_numeric($quantity)) {
        $_SESSION['msg'] = "Quentity enter was not numberic";
        redirect();
        exit;
    }

    if ($description == "") {
        $_SESSION['msg'] = "Description field is required";
        redirect();
        exit;
    }
    if (!preg_match("/[a-zA-z]/", $description)) {
        $_SESSION['msg'] = "Description field Only containes alphabets and whitespace.";
        redirect();
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
        if (file_exists("./upload/product/" . $filename)) {
            $_SESSION['msg'] = "File already exists";
            $error = true;
        } else {
            if (move_uploaded_file($_FILES["myfile"]["tmp_name"], "./upload/product/" . $filename)) {
                $is_file_uploaded = true;
            } else {
                $_SESSION['msg'] = "Unable to update file";
                $error = true;
            }
        }
    }

    //insert data into database
    $res = "INSERT INTO product (name,price,quantity,description,myfile	)
    VALUES ('$name','$price','$quantity','$description','$filename')";
    if (!$mysqli->query($res)) {
        $_SESSION['msg'] = "Error:could not able to insert data." . mysqli_error($mysqli);
        redirect();
        exit;
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
                <div class="content-wrapper">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="form-sample" action="" method="POST" enctype="multipart/form-data">
                                    <?php if (isset($_SESSION['msg'])) : ?>
                                        <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
                                    <?php unset($_SESSION['msg']);
                                    endif; ?>
                                    <p class="card-description"> Add Products </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" name="name" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Price</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" name="price" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Quantity</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" placeholder="Quantity" name="quantity" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Description</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" placeholder="Description" name="description" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>File upload</label>
                                        <input type="file" class="file-upload-default" name="myfile">
                                        <div class="input-group ">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                                            </span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-gradient-primary mb-2" name="add">Add</button>
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