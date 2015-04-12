<?php

class CMSRegistry {
    public static $register = array();
    
    static function register($sectionType) {
        array_push(CMSRegistry::$register, $sectionType);
    }
    
    static function getType($typeId) {
    	foreach (CMSRegistry::$register as $type) {
    		if ($type->identifier == $typeId) {
    			return $type;
    		}
    	}
    	
    	return false;
    }
}

class SectionType {
    public $parameters = array();
    public $view;
    public $identifier;
    public $label;
    public $color = "#ccc";
    
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
    public $template;
    
    function __construct() {
        
    }
    
    function render($parameter, $parameterType) {
    	$twig = $GLOBALS["twig"];
    	return  $twig->render('admin/cms/editors/wysiwyg.html', array(
    			"parameter" => $parameter,
    			"parameterType" => $parameterType
    	));
    }
    
}

?>