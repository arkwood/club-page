<?php
class Section extends DBObject {
    
    public $view;
    public $parameters;
    public $width;
    public $position;
    
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select width, position, sectiontype from section where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->width = $data["width"];
                $this->position = $data["position"];
                $this->view = $data["sectiontype"];
                
                $this->parameters = $this->getParameters();
            }
        }
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
