<?php
require_once CLASSFOLDER . 'dbobject.php';

class NavigationEntry extends DBObject {
    
    public $label = '';
    public $icon = 'fa-home';
    public $target = '';
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select label, icon, target from navigationentries where id = " . $id;
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $this->label = $data["label"];
                $this->icon = (strlen($data["icon"]) > 0) ? $data["icon"] : false;
                $this->target = $data["target"];
            }
        }
    }
}



class Navigation {
    
    public $entries;
    
    function __construct() {
        loadEntries();
    }
    
    function loadEntries() {
        $entries = array();
        
        $query = "select id from navigationentries where active = 1 order by position asc";
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $entry = new NavigationEntry($data["id"]);
                array_push($entries, $entry);
            }
        }
        
        return $entries;
    }
}



?>