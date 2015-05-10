<?php
if (array_key_exists('newLayout', $_POST)) {
	// update width of a section
	$newLayout = $_POST["newLayout"];
	$container = new Container($_POST["containerId"]);
	$container->updateLayout($newLayout);
	addScriptContent('{ result: 1 }');
}
else if (array_key_exists('id', $_POST)) {
	// delete section
	$id = $_POST["id"];
	$section = new Section($id);
	$page = $section->getPage();
	mysql_query('delete from section where id = ' . $id);
	
	$page->fixPosition();
	
	addScriptContent('{ result: 1 }');
}
else {
	addScriptContent('{ result: 0, message: "Invalid operation." }');	
}
?>