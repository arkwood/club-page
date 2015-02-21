<?php
/**
 * Entry of the navigation tree. Entries can have sub-entries and are sorted within their
 * respective container (root navigation items, sub-navigation items are sorted within 
 * their parent entry).
 */
class NavigationEntry extends DBObject {
    
    /*
     * meta information
     */
    /** the label of the link when it is rendered */
    public $label = '';
    /** the icon to be displayed next to the label (defined as font-awesome class) 
        default is "fa-home", overwritten in the constructor (false), if no icon required */
    public $icon = 'fa-home';
    
    /*
     * relations to other DBObjects
     */
    /** list of navigation entries 1 level deeper in the navigation tree */
    public $subNavigation = false;
    /** if this is at least 2nd level nav, this provides a link to the parent entry (1 level up) */
    public $parentId = false;
    
    /*
     * functional data of the entry
     */
    /** if this is relevant to the frontend or not */
    public $active = false;
    /** the position in the current navigation container (level) */
    public $position = 9999;
    /** this is the default navigation (only set for a single level 1 entry) */
    public $defaultNav = false;
    /** public flag passed into the constructor */
    public $publicFlag = true;
    
    /*
     * target of the navigation
     */
    /** href */
    public $target = '';
    /** the target points to an external web site */
    public $external = false;
    /** the page to display in case it is an internal link */
    public $page = false;
    
    /**
     * $id = the DB ID column (primary key)
     * $isPublic = determines if sub-elements are filtered by their active flag.
     */
    function __construct($id, $isPublic) {
        $this->ID = $id;
        $this->publicFlag = $isPublic;
        
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
                $query2 = "select id from navigationentry where parent = " . $id . ($isPublic ? ' and active = 1' : '');
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

    /**
     * Retrieves the parent element or false, if there is no parent.
     */
    function getParent() {
        if ($this->parentId) {
            return new NavigationEntry($this->parentId, $this->publicFlag);    
        }
        else {
            return false;
        }
    }
}


/**
 * Container holding all navigation entries. The container an be initialized 
 * with a context, which is either public or admin. Public will only load 
 * navigation entries, which are visible in the front end. If the public flag 
 * is not set, all navigation entries will be loaded as needed in the backend.
 */
class Navigation {
    
    /** List of all top-level navigation entries. 
     * The top-level entries hold references to their sub-entries */
    public $entries;
    /** public flag as passed into the constructor. */
    public $publicFlag = true;
    
    /** 
     * $isPublic determines wheter all elements are loaded or only activated ones.
     * true -> load only active entries; false -> load all entries
     */
    function __construct($isPublic) {
        $this->publicFlag = $isPublic;
        $this->entries = $this->getEntries();
    }
    
    /** Load the entries from the database using the public flag */
    function getEntries() {
        $entries = array();
        
        $query = "select id from navigationentry where " . ($this->isPublic() ? "active = 1 and " : "") . "parent is null order by position asc";
        if ($datasrc = mysql_query($query)) {
            while ($data = mysql_fetch_array($datasrc)) {
                $entry = new NavigationEntry($data["id"]);
                array_push($entries, $entry);
            }
        }
        
        return $entries;
    }
    
    
    /**
     * Getter for the public flag.
     */
    function isPublic() {
        return $this->publicFlag;
    }
}
?>