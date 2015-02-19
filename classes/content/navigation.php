<?php
class NavigationEntry extends DBObject {
    
    public $label = '';
    public $icon = 'fa-home';
    public $target = '';
    public $subNavigation = false;
    public $active = false;
    public $parentId = false;
    public $position = 9999;
    public $defaultNav = false;
    
    function __construct($id) {
        $this->ID = $id;
        
        $query = "select label, icon, target, active, parent, position, defaultnav from navigationentry where id = " . $id;
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $this->label = $data["label"];
                $this->icon = (strlen($data["icon"]) > 0) ? $data["icon"] : false;
                $this->target = $data["target"];
                $this->active = ($data["active"] == 1);
                $this->parentId = (strlen($data["parent"]) > 0) ? $data["parent"] : false;
                $this->position = $data["position"];
                $this->defaultNav = ($data["defaultnav"] == 1); 
                
                // handle sub-navigation entries
                $query2 = "select id from navigationentry where parent = " . $id;
                if ($datasrc2 = mysql_query($query2)) {
                    while ($data2 = mysql_fetch_array($datasrc2)) {
                        if (!$this->subNavigation) {
                            $this->subNavigation = array();
                        }
                        array_push($this->subNavigation, new NavigationEntry($data2["id"]));
                    }
                }
            }
        }
    }

    function getParent() {
        return new NavigationEntry($this->parentId);
    }
}



class Navigation {
    
    public $entries;
    
    function __construct() {
        $this->entries = $this->loadEntries();
    }
    
    function loadEntries() {
        $entries = array();
        
        $query = "select id from navigationentry where active = 1 and parent is null order by position asc";
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