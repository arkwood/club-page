<?php

class CMSSingleSelect extends CMSEditor {
    
	public $values;
	
    function __construct($values) {
        $this->type = "singleselect";
        $this->adminview = 'cmseditor/singleselect';
        $this->values = $values;
    }
}

?>