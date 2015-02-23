<?php
require_once 'loader.php';

// load content
$html = includeView('views/content/page.php', $twig, $html, false);

// render content
addScriptContent($html);
require_once 'index.php';
?>
