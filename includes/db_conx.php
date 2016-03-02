<?php
define("DB_HOST", "localhost");
define("DB_USER", "fth3u4n3_audt394");
define("DB_PASS", "2iy!@avk&6$1");
define("DB_NAME", "fth3u4n3_dt394j");
$db_conx = mysqli_connect("localhost", "fth3u4n3_audt394", "2iy!@avk&6$1", "fth3u4n3_dt394j");
// Evaluate the connection
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} 
?>