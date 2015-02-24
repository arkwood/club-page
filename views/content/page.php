<?php
require_once 'loader.php';

$page = false;
$pageId = 0;

// determine page to show
if ($_GET["pageid"]) {
    $pageId = $_GET["pageid"];
    $page = new Page($pageId);
}
else {
    $page = Page::getDefaultPage();
    $pageId = $page->ID;
}

$sections = $page->getSections();

$html = '<div class="row">';
foreach ($sections as $section) {
    $layoutClass = ($section->view == 'views/content/page') ? "no-padding" : "";
   
    $html .= '<div class="col-xs-12 col-md-' . $section->width . ' ' . $layoutClass . '">';
    $html .= includeView($section->view . '.php', $twig, $section);
    $html .= '</div>';
}

addScriptContent($html);
require_once 'index.php';
?>