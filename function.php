<?php
function addScriptContent($html) {	
	if (isset($GLOBALS[GLOBALSCRIPTCONTENT]))
		$GLOBALS[GLOBALSCRIPTCONTENT] .= $html;
	else 
		$GLOBALS[GLOBALSCRIPTCONTENT] = $html;	
}

function includeView($viewName, $twig, $section) {
    $html = "";
    $localDir = dirname(__FILE__);
    require $localDir . '/' . $viewName;
    return $html;
}
?>