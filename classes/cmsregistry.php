<?php

class CMSRegistry {
    public static $register = array();
    
    static function register($sectionType) {
        array_push(CMSRegistry::register, $sectionType);
    }
}

class SectionType {
    public $parameters = array();
    public $view;
    public $identifier;
    public $label;
    
    function __construct() {
        
    }
}

class SectionTypeParameter {
        
    public $label;
    public $name;
    public $editor;
    
    function __construct($label, $name, $editor) {
        $this->label = $label;
        $this->name = $name;
        $this->editor = $editor;
    }
}

class CMSEditor {
    public $type;
    public $adminview;
    
    function __construct() {
        
    }
}
?>