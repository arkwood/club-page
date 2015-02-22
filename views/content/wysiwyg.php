<?php
$html .= $twig->render('cms/wysiwyg.html', array("wysiwyg" => $section->getParameterValue("wysiwyg")));
?>