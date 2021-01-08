<?php
require('./services/session.php');
require('./services/connect.php');

//select data
$sql = "SELECT * FROM product_add";
$res = $mysqli->query($sql);
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
                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card ">
                            <div class="card-body">
                                <?php if (isset($_SESSION['msg'])) : ?>
                                    <div class="alert alert-primary"><?= $_SESSION['msg']; ?></div>
                                <?php unset($_SESSION['msg']);
                                endif; ?>
                                </p>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Image </th>
                                            <th> Product Name </th>
                                            <th> Price </th>
                                            <th> Quantity </th>
                                            <th colspan="2"> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($res->num_rows > 0) : while ($row = $res->fetch_assoc()) : ?>
                                                <tr>
                                                    <td class="py-1">
                                                        <img src="<?php echo './upload/product/' . $row['myfile'];  ?>">
                                                    </td>

                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php echo $row['price']; ?> </td>
                                                    <td><?php echo $row['quantity']; ?></td>
                                                    <td>
                                                        <a href="product_view.php?id=<?= $row['id'] ?>"> <button class="btn btn-primary btn-xs">View</button></a>
                                                        <a href="product_edit.php?id=<?= $row['id'] ?>"><button class="btn btn-primary btn-xs">Edit</button></a>
                                                        <a href="product_delete.php?id=<?= $row['id'] ?>"><button class="btn btn-primary btn-xs">Delete</button></a>
                                                    </td>
                                                </tr>
                                        <?php endwhile;
                                        endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--include footer--->
                <?php include('./include/footer.php'); ?>
            </div>
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

    <!-- container-scroller -->
    <?php include('./include/javascript.php'); ?>
</body>

</html>