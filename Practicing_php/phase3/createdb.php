<?php

require "connection.php";
$sql = "create database IF NOT EXISTS db";

if(mysqli_query($connection,$sql)) {
    echo "data base is created successfully";
}else{
    echo "unable to  create the database".mysqli_error($connection);
}

mysqli_close($connection);
?>