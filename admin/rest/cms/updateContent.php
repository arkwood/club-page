<?php
if (array_key_exists('data', $_POST)) {
	
	
	$data = $_POST['data'];
	$labels = $_POST['labels'];

	foreach ($data as $parameter) {
		$section = new Section($parameter["section"]);
		$section->updateParameter($parameter["name"], $parameter["value"]);
	}
	
	foreach (array_keys($labels) as $sectionId) {
		$section = new Section($sectionId);
		$newLabel = $labels[$sectionId];
		if ($section->label != $newLabel) {
			$section->updateLabel($newLabel);
		}
	}
	
	addScriptContent('{ result: 1 }');
}	

?>