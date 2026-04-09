<?php

$programs = array("js","java","cpp","python","html","php","nodejs","go");

echo count($programs);
$arrlen = count($programs);

echo "<br>";

echo "the elements of the array are ";
echo "<br>";
for($num = 0; $num < $arrlen; $num++) {
    sort($programs);
    echo "$programs[$num] <br>";
}

$ass_arr = array("nahom" => "35", "melat" => "45");
echo $ass_arr["nahom"];
echo "<br>";
asort($ass_arr);
foreach($ass_arr as $name => $age) {
    echo $name . "<br>";
}

$code = array(
    array("php","html","css","js"),
    array("cpp","python","java","rust")
);


// echo $code[0][0];

for($row = 0; $row < 2; $row++) {
    for($col = 0; $col < 4; $col++) {
        echo $code[$row][$col] . "<br>";
    }
}
?>