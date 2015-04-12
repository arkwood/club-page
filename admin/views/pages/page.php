<?php
$html = "";
if (User::checkPermission('cms', 1)) {
	// retrieve list of pages
	$page = new Page($_GET["pageid"]);
	$pages = Page::getRootPages();
	
	$html = $twig->render('admin/cms/page.html', array(
			"page" => $page, 
			"rootPages" => $pages, 
			"sectionTypes" => CMSRegistry::$register,
			"layouts" => LayoutTemplate::getTemplates()
	));
    addScriptContent($html);    
}
else {
    addScriptContent('No Permission');
}
?>