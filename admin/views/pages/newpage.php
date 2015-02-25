<?php
$html = "";
if (User::checkPermission('cms', 1)) {
	// retrieve list of pages
	$pages = array();
	
	$query = "select id from page where rootpage = 1 order by name asc";
	if ($result = mysql_query($query)) {
		while ($data = mysql_fetch_array($result)) {
			array_push($pages, new Page($data["id"]));
		}
	}
	
	$html = $twig->render('admin/cms/newpage.html', array("rootPages" => $pages));
    addScriptContent($html);    
}
else {
    addScriptContent('No Permission');
}
?>