<?php
class Section extends DBObject {
    
    public $view;
    public $label;
    public $containerId;
    public $parameters;
    public $position;
    public $sectionType;
    public $sectionColor;
    
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select position, sectionview, sectiontype, containerid, label from section where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->position = $data["position"];
                $this->view = $data["sectionview"];
                $this->sectionType = CMSRegistry::getType($data["sectiontype"]);
                $this->containerId = $data["containerid"];
                $this->parameters = $this->getParameters();
                $this->label = $data["label"];
            }
        }
    }
    
    function getPage() {
    	$container = $this->getContainer();
    	return $container->getPage();
    }
    
    function getContainer() {
    	return new Container($this->containerId);
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
    public $sectionParameterType;
    public $editor;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select name, value, textvalue, datevalue, sectionid from sectionparameter where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->name = $data["name"];
                $this->value = ($data["value"] == "") ? ($data["datevalue"] == "" ? $data["textvalue"] : $data["datevalue"]) : $data["value"];
                $this->sectionId = $data["sectionid"];
            }
        }
        
        $query = "select sectiontype from section where id = " . $this->sectionId;
        if ($result = mysql_query($query)) {
        	while ($data = mysql_fetch_array($result)) {
        		$this->sectionParameterType = CMSRegistry::getType($data["sectiontype"]);
        		foreach ($this->sectionParameterType->parameters as $parameter) {
        			if ($parameter->name == $this->name) {
        				$this->editor = $parameter->editor;
        			}
        		}
        	}
        }
    }
    
    
    function getRender() {
    	return $this->editor->render($this, $this->sectionParameterType);
    }
}
?>
