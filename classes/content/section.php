<?php
class Section extends DBObject {
    
    public $view;
    public $parameters;
    public $width;
    public $position;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select width, position, viewtype from section where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->width = $data["width"];
                $this->position = $data["position"];
                $this->view = $data["viewtype"];
                
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
}


class SectionParameter extends DBObject {
    
    public $name;
    public $value;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select name, value from sectionparameter where id = " . $id;
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                $this->name = $data["name"];
                $this->value = $data["value"];
            }
        }
    }
}
?>
