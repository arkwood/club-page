<?php

class SectionSubPage extends SectionType {

	public static $PARAMETER_PAGEID = "pageid";
	
	function __construct() {
		array_push($this->parameters, new SectionTypeParameter('Sub Page', SectionSubPage::$PARAMETER_PAGEID, new CMSSingleSelect($this->getSubPages())));
		$this->identifier = "subpage";
		$this->label = "Sub Page";
		$this->view = 'content/page';
	}
	
	
	function getSubPages() {
		$list = array();
		$query = "select id, name from page where rootpage = 0 order by name asc";
		
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				$list[$data["id"]] = $data["name"];
			}
		}
		
		return $list;
	}
}


CMSRegistry::register(new SectionSubPage());

?>
