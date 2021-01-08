<?php
require('./services/session.php');
require('./services/connect.php');
$id = $_GET['id'];
if(!isset($id))
{
    header('Location:error.php');
    exit;
}

//select data

$sql = "SELECT * FROM product where id='$id' limit 0,1 ";
$res = $mysqli->query($sql);
if ($res->num_rows > 0) {
    // output data of each row
    $row = $res->fetch_assoc();
}

$mysqli->close();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>My Admin Panel</title>
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
                                <form class="form-sample" action="" method="POST">

                                    <p class="card-description">View Products</p>
                                    <div class="row">
                                        <div class="col-sm-12 ">
                                            <div id="view-img">
                                                  <img src=<?php echo './upload/product/'.$row['myfile'];  ?> >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" disabled placeholder="Name" class="form-control" name="name" value="<?= $row['name']; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Price</label>
                                                <div class="col-sm-9">
                                                    <input type="number" disabled class="form-control" placeholder="Price" name="price" value="<?= $row['price']; ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Quantity</label>
                                                <div class="col-sm-9">
                                                    <input type="number" disabled class="form-control" placeholder="Quantity" name="quantity" value="<?=$row['quantity'];?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Description</label>
                                                <div class="col-sm-9">
                                                    <input type="text" disabled class="form-control" placeholder="Description" name="description" value="<?=$row['description'];?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="product_edit.php?id=<?= $id; ?>"><button type="button" class="btn btn-gradient-primary mb-2" name="edit">Edit</button></a>
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