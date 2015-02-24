<?php

class SectionTypeNewsList extends SectionType {

	
	function __construct() {
		array_push($this->parameters, new SectionTypeParameter('Category', 'category', new CMSSingleSelect($this->getNewsCategories())));
	}
	
	
	function getNewsCategories() {
		$list = array();
		$query = "select id, name from newscategory order by name asc";
		
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				$list[$data["id"]] = $data["name"];
			}
		}
		
		return $list;
	}
}


CMSRegistry::register(new SectionTypeNewsList());

?>