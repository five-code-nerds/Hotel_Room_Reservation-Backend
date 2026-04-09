<?php

$connection = mysqli_connect("localhost","root","","db");

if($connection){
    // echo "connected successfully";
}else{
    echo "connection failed".mysqli_connect_error();
}


?>