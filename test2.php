<?php

$switch = TRUE;
while (TRUE) {
    $switch = ($switch xor TRUE);
    $a[] = $switch;
}
print_r($a);
?>
