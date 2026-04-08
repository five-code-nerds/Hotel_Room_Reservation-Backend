<?php

require "connection.php";
$sql = "select id,fname,lname,sex from student ";
$result = mysqli_query($connection,$sql);

// while($row = mysqli_fetch_assoc($result)) {
//     echo $row["id"]." ".$row["fname"]." ".$row["lname"]." ".$row["sex"]."<br>";
// }

echo "<table  border='1'> 
<tr>
<th>id</th>
<th>fname</th>
<th>lname</th>
<th>sex</th>
</tr>";

while($row= mysqli_fetch_assoc($result)) {
    echo "<tr>
    <td>".$row["id"]."</td>
    <td>".$row["fname"]."</td>
    <td>".$row["lname"]."</td>
    <td>".$row["sex"]."</td>
    </tr>";
}

echo "</table>";

?>