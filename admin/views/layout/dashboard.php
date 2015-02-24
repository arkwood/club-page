<?php
require_once dirname(__FILE__) . '/../../../loader.php';

$html = "";
$user = User::getCurrentUser();
if ($user === false) {
    $html = $twig->render('admin/layout/login.html', array("result" => false));
}
else {
    $html = '<div class="container">
                <div class="col-xs-12 col-md-2">';
    $html .= $twig->render('admin/layout/navigation.html');
    $html .= '  </div>
                <div class="col-xs-12 col-md-10">';
    addScriptContent($html);
    if ($_GET["view"] != "") {
        require 'views/' . $_GET["view"] . '.php';
    }
    $html = '   </div>
             </div>';
    addScriptContent($html);
}

addScriptContent($html);
require_once dirname(__FILE__) . '/../../index.php';
?>