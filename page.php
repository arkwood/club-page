<?php
require_once 'loader.php';

// load content
$html = "";
require 'views/content/page.php';

// render content
addScriptContent($html);
require_once 'index.php';
?>
