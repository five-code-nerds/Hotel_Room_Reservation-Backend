<?php

require "connection.php";

// Prepare the statement

// $sql = "insert into student (fname,lname,sex) values ('teme','belay','male')";
$sql = "INSERT INTO student (fname, lname, sex) VALUES (?, ?, ?)";
$stmt = $connection->prepare($sql);

// Check if prepare succeeded
if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

// Bind parameters
$stmt->bind_param("sss", $fname, $lname, $sex);

// First row
$fname = "tsega";
$lname = "desalegn";
$sex = "female";
$stmt->execute();

// Second row
$fname = "nina";
$lname = "dereje";
$sex = "female";
$stmt->execute();

echo "Data inserted successfully";

// Close statement and connection
$stmt->close();
$connection->close();

?>