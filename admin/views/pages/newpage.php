<?php
$html = "";
if (User::checkPermission('cms', 1)) {
	// retrieve list of pages
	$pages = Page::getRootPages();
	
	$html = $twig->render('admin/cms/newpage.html', array("rootPages" => $pages));
    addScriptContent($html);    
}
else {
    addScriptContent('No Permission');
}
?>