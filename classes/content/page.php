<?php
class Page extends DBObject {
        
    public $standalone = false;
    public $name;
    
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select name, standalone from page where id = " . $id;
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array()) {
                $this->name = $data["name"];
                $this->standalone = ($data["standalone"] == 1);
            }
        }
    }
    
    
    function getSections() {
        $list = array();
        
        $query = "select id from section where pageid = " . $this->ID . " order by position";
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                array_push($list, new Section($data["id"]));
            }
        }
        
        return $list;
    }
}
?>