<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config.php';

function dropTables($tables) {
    $result = array();
    foreach ($tables as $table) {
        $sql = "DROP TABLE IF EXISTS`" . $table . "`";
        array_push($result, $sql);
    }
    executeSQL($result);
}

function executeSQL($sql) {
    foreach ($sql as $query) {
        mysql_query($query);
        echo '<li>' . $query . '</li>';
        $error = mysql_error();
        if ($error) {
            echo '<ul><li style="color: #f00;">' . $error . '</li></ul>';
        }
    }
}

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
    echo '  <h3 style="border-bottom: 1px solid #900; color: #900;">datamodel/' . $entry . '</h3>
            <p style="font-family: Courier;">';    
    require_once 'datamodel/' . $entry;
    echo '  </p>'; 
}

?>