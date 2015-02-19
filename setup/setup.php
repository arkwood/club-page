<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

$drop = ($drop === true);
if ($drop !== true) 
    $drop = ($_GET["drop"] == 'true');

$demo = ($demo === true);
if ($demo !== true) 
    $demo = ($_GET["demo"] == 'true');

mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);

foreach (scandir('datamodel') as $entry) {
    // skip current directory and parent directory
    if ($entry === "." || $entry === "..")
                continue;
    
    require_once 'datamodel/' . $entry; 
}
?>