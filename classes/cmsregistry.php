<?php

class CMSRegistry {
    public static $register = array();
    public static $layouts = array();
    
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
    
    
    static function getLayoutByIdentifier($identifier) {
    	$layouts = CMSRegistry::getLayouts();
    	return $layouts[$identifier];
    }
    
    static function getLayouts() {
    	$list = array();
    	$query = "select id from layout order by position";
    
    	if ($result = mysql_query($query)) {
    		while ($data = mysql_fetch_array($result)) {
    			$layout = new Layout($data["id"]);
    			$list[$layout->identifier] = $layout;
    		}
    	}
    
    	return $list;
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
    
    function render($parameter, $parameterType, $section) {
    	$twig = $GLOBALS["twig"];
    	return  $twig->render('admin/cms/editors/wysiwyg.html', array(
    			"parameter" => $parameter,
    			"parameterType" => $parameterType,
    			"section" => $section
    	));
    }
    
}

?>