<?php
class Page extends DBObject {
        
    public $root = false;
    public $name = "";
    public $default = false;
    
    
    function __construct($id) {
        $this->ID = $id;
        $query = "select name, rootpage, defaultpage from page where id = " . $id;
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $this->name = $data["name"];
                $this->default = ($data["defaultpage"] == 1) ? true : false;
                $this->root = ($data["rootpage"] == 1);
            }
        }
    }
    
    
    static function getDefaultPage() {
        $query = "select id from page where defaultpage = 1";
        if ($result = mysql_query($query)) {
            while ($data = mysql_fetch_array($result)) {
                return new Page($data["id"]);
            }
        }
        return false;
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