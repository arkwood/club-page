<?php
$html = "";
if (User::checkPermission('cms', 1)) {
    addScriptContent("test pagelist.php");    
}
else {
    addScriptContent('No Permission');
}
?>