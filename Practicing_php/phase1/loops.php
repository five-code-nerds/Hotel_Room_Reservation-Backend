<?php

$sum = 0 ;
for($x = 1; $x <= 100; $x++) {
    $sum += $x;
    
}
echo "the sum of the numbers from 1 up to 100 is  $sum";
echo "</br>";


$num2 = 1;
$sum2 = 0;

while($num2 <= 100) {
    $sum2 += $num2;
    $num2++;

}
echo "the sum of the numbers from 1 up to 100 by using while loop  is  $sum2";

$pro_lan = array("php","c++","html","java","js");

foreach($pro_lan as $langs) {
    echo "$langs </br>";
}
?>
