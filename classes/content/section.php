<?php
class Section extends DBObject {
    
    public $view;
    public $pageId;
    public $parameters;
    public $width;
    public $position;
    public $sectionType;
    public $sectionColor;
    
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select width, position, sectiontype, pageid from section where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->width = $data["width"];
                $this->position = $data["position"];
                $this->view = $data["sectiontype"];
                $this->pageId = $data["pageid"];
                $this->parameters = $this->getParameters();
                
                // substr: views/content/wysiwyg -> content/wysiwyg
                $this->sectionType = CMSRegistry::getType($this->view);
            }
        }
    }
    
    function getPage() {
    	return new Page($this->pageId);
    }
    
    function getParameters() {
        $list = array();
        
        $query = "select id from sectionparameter where sectionid = " . $this->ID;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                array_push($list, new SectionParameter($data["id"]));
            }
        }
        
        return $list;
    }
    
    function getParameter($name) {
        $query = "select id from sectionparameter where sectionid = " . $this->ID . " and name = '" . $name . "'";
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                return new SectionParameter($data["id"]);
            }
        }
        
        return false;
    }
    
    function getParameterValue($name) {
        $parameter = $this->getParameter($name);
        return $parameter->value;
    }
    
    
    function updatePosition($newPosition) {
    	mysql_query('update section set position = ' . $newPosition . ' where id = ' . $this->ID);
    	$this->position = $newPosition;
    }
}


class SectionParameter extends DBObject {
    
    public $name;
    public $value;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select name, value, textvalue from sectionparameter where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->name = $data["name"];
                $this->value = ($data["value"] == "") ? $data["textvalue"] : $data["value"];
            }
        }
    }
}
?>
