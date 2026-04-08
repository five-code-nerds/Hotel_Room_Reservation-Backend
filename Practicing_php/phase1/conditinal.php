<?php

$num1 = 24;
if($num1 % 2 == 0) {
    $iseven = TRUE;
}else if($num1 % 2 == 1) {
    $iseven = FALSE;

}
else{
    echo "it is not a number";
}

switch($iseven) {
    case tRUE:
        echo "it is an even number ";
        break;
    case FALSE:
        echo "it is not an even number";
        break;
}

?>