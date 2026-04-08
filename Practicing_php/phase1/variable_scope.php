<?php

$global = "i am the global variable";

echo $global;
function v_scope() {
    echo "</br>";
    $local = "i am the local variable";
    echo $local;
    global $global;
    echo "</br>";
    echo $global," now i can be accesed with in the function";

}

function st () {
    static $x = 1 ;
    $x++;
    echo $x;
    echo "</br>";
}
v_scope();
echo "</br>";
st();

st();
?>