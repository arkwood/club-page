<?php

class CMSTextEditor extends CMSEditor {
    
    function __construct() {
        $this->type = "wysiwyg";
        $this->adminview = 'cmseditor/wysiwyg';
    }
}

?>