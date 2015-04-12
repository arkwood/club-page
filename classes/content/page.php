<?php
class Page extends DBObject {
        
    public $root = false;
    public $name = "";
    public $default = false;
    public $active = false;
    public $parentPageId = false;
    
    
    public $containers = array();
    public $subPages = array();
    
    
    
    function __construct($id) {
        $this->ID = $id;
        $query = "select name, rootpage, defaultpage, active, parentpageid from page where id = " . $id;

        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $this->name = $data["name"];
                $this->default = ($data["defaultpage"] == 1) ? true : false;
                $this->root = ($data["rootpage"] == 1);
                $this->active = ($data["active"] == 1);
                $this->parentPageId = ($data["parentpageid"] == "") ? false : $data["parentpageid"];
                
            }
        }
        
        $this->subPages = $this->getSubPages();
        $this->containers = $this->getContainers();
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
    
    static function getRootPages() {
    	$pages = array();
    	
    	$query = "select id from page where rootpage = 1 order by name asc";
    	if ($result = mysql_query($query)) {
    		while ($data = mysql_fetch_array($result)) {
    			array_push($pages, new Page($data["id"]));
    		}
    	}
    	return $pages;
    }
    
    function getContainers() {
        $list = array();
        
        $query = "select id from container where pageid = " . $this->ID . " order by position";
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                array_push($list, new Container($data["id"]));
            }
        }
        
        return $list;
    }
    
    function getSubPages() {
    	$list = array();
    	$query = "select id from page where parentpageid = " . $this->ID;
    	if ($result = mysql_query($query)) {
    		while ($data = mysql_fetch_array($result)) {
    			array_push($list, new Page($data["id"]));
    		}
    	}
    	return $list;
    }
    
    function getParent() {
    	if ($this->parentPageId === false) {
    		return false;
    	}
    	else {
    		return new Page($this->parentPageId);
    	}
    }
    
    
    function fixPosition() {
    	$this->sections = $this->getSections();
    	$position = 1;
    	foreach ($this->sections as $section) {
    		$section->updatePosition($position);
    		$position++;
    	}
    }
}
?>