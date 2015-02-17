<?php
function addScriptContent($html) {	
	if (isset($GLOBALS[GLOBALSCRIPTCONTENT]))
		$GLOBALS[GLOBALSCRIPTCONTENT] .= $html;
	else 
		$GLOBALS[GLOBALSCRIPTCONTENT] = $html;	
}
?>