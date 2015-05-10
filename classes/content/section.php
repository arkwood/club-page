<?php
class Section extends DBObject {
    
    public $view;
    public $label;
    public $containerId;
    public $parameters;
    public $position;
    public $sectionType;
    public $sectionColor;
    public $placeholder;
    
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select position, sectionview, sectiontype, containerid, label, placeholder from section where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->position = $data["position"];
                $this->view = $data["sectionview"];
                $this->sectionType = CMSRegistry::getType($data["sectiontype"]);
                $this->containerId = $data["containerid"];
                $this->parameters = $this->getParameters();
                $this->label = $data["label"];
                $this->placeholder = $data["placeholder"];
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
    

    function updateParameter($name, $value) {
    	$parameter = $this->getParameter($name);
    	$parameter->updateValue($value);
    }
    
    function updateLabel($newLabel) {
    	mysql_query("update section set label = '" . $newLabel . "' where id = " . $this->ID);
    	$this->label = $newLabel;
    }
    
    
    function updatePosition($newPosition) {
    	mysql_query("update section set position = " . $newPosition . " where id = " . $this->ID);
    	$this->position = $newPosition;
    }
    
    /**
     * Rendering function for the front end (renders the entire section)
     */
    function getRender() {
    	$twig = $GLOBALS["twig"];
    	return includeView($this->view . '.php', $twig, $this);
    }
}


class SectionParameter extends DBObject {
    
    public $name;
    public $value;
    public $sectionParameterType;
    public $editor;
    public $sectionId;
    
    public $valueField;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select name, value, textvalue, datevalue, sectionid from sectionparameter where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->name = $data["name"];
                $this->value = ($data["value"] == "") ? ($data["datevalue"] == "" ? $data["textvalue"] : $data["datevalue"]) : $data["value"];
                $this->valueField = ($data["value"] == "") ? ($data["datevalue"] == "" ? 'textvalue' : 'datevalue') : 'value';
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
    
    
    /**
     * Updates the value of this section parameter.
     * 
     * @param Object $newValue a string or date
     */
    function updateValue($newValue) {
    	mysql_query("update sectionparameter set " . $this->valueField . " = '" . $newValue . "' where id = " . $this->ID);
    	$this->value = $newValue;
    }
    
    /**
     * Rendering function for the back end (renders the editor into the form)
     */
    function getRender() {
    	return $this->editor->render($this, $this->sectionParameterType, new Section($this->sectionId));
    }
}
?>
