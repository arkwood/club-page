<?php
// creates a page and then includes the page list
$name = $_POST["name"];
$parent = $_POST["parent"];

$insert = "insert into page (name, rootpage, defaultpage, active, parentpageid) values ('" . $name . "', " . ($parent == "" ? "1" : "0") . ", 0, 0, " . ($parent == "" ? "null" : $parent) . ")";
mysql_query($insert);

require dirname(__FILE__) . '/pagelist.php';
?>