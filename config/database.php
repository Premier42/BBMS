<?php

$DB_HOST ="localhost";
$DB_USER ="root";
$DB_PASS ="";
$DB_NAME ="blood_bank";
$CONN = "";
$CONN = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if($CONN )
    ECHO "connected";
else
    ECHO "not connected";

?>
