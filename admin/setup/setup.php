<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../config.php';

mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
mysql_select_db(MYSQL_DATABASE);

foreach (scandir('datamodel') as $entry) {
    // skip current directory and parent directory
    if ($entry === "." || $entry === "..")
                continue;
    
    require_once 'datamodel/' . $entry; 
}
?>