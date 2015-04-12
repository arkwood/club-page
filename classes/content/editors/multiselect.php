<?php

class CMSMultiSelect extends CMSEditor {
    
	public $values;
	
    function __construct($values) {
        $this->type = "multiselect";
        $this->adminview = 'admin/views/cmseditor/multiselect';
        $this->values = $values;
    }
}

?>