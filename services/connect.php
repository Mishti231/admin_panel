<?php

     $mysqli = new mysqli('localhost','root','','admin_panel');
    if($mysqli->connect_errno)
    {
        echo "failed to connect mysqli:" .$mysqli->connect_error;
        die;
    }
     





?>