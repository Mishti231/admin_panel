<?php

require('./services/session.php');
require('./services/connect.php');
$id = $_GET['id'];
if(!isset($id))
{
    header('Location:error.php');
    exit;
}
// sql to delete a record
$sql = "DELETE FROM product WHERE id=$id";

if ($mysqli->query($sql) === TRUE) {
  $_SESSION['msg'] ="Record deleted successfully";
} else {
    $_SESSION['msg'] ="Error deleting record: " . $mysqli->error;
}

$mysqli->close();
?>