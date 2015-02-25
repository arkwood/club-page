<?php
$html = "";
if (User::checkPermission('cms', 1)) {
	// retrieve list of pages
	$page = new Page($_GET["pageid"]);
	
	$html = $twig->render('admin/cms/page.html', array("page" => $page));
    addScriptContent($html);    
}
else {
    addScriptContent('No Permission');
}
?>