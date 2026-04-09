<?php

require "connection.php";
$sql = "create table student(
id int(6) unsigned auto_increment primary key,
fname varchar(30) not null,
lname varchar(10) not null,
sex varchar(10) not null,
date timestamp default current_timestamp on update current_timestamp
 )";


 if(!mysqli_query($connection,$sql)) {
    die("unable to create the table");
 }

 echo "table is created successfully";


 mysqli_close($connection);
?>