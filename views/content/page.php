<?php
require_once 'loader.php';

$page = false;
$pageId = 0;

// determine page to show
if ($section instanceof SectionSubPage) {
	$pageId = $section->getParameter(SectionSubPage::PARAMETER_PAGEID);
	$page = new Page($pageId);
}
else if ($_GET["pageid"]) {
    $pageId = $_GET["pageid"];
    $page = new Page($pageId);
}
else {
    $page = Page::getDefaultPage();
    $pageId = $page->ID;
}


$html .= $twig->render('cms/page.html', array(
	"page" => $page
));
/*
foreach ($containers as $container) {
	$html .= '<div class="row">';
	foreach ($container->getSections() as $section) {
		$layoutClass = ($section->view == 'views/content/page') ? "no-padding" : "";
		 
		$html .= '<div class="col-xs-12 col-md-' . $section->width . ' ' . $layoutClass . '">';
		$html .= includeView($section->view . '.php', $twig, $section);
		$html .= '</div>';
	}
	$html .= '</div>';
}
*/


addScriptContent($html);
require_once 'index.php';
?>