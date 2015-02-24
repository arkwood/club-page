<?php
require_once 'news.php';

class NewsCatgory extends DBObject {
	
	public $name;

	function __construct($id) {
		$this->ID = $id;
		$sql = "select name from newscategory where id = " . $id;
		
		if ($result = mysql_query($sql)) {
			while ($data = mysql_fetch_array($result)) {
				$this->name = $data["name"];
			}
		}
	}
	
	
	function getNews($isPublished) {
		$query = "select id from news where categoryid = " . $this->ID;
		if ($isPublished) {
			$query .= " and published > now()";
		}
		
		$list = array();
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				array_push(new News($data["id"]));
			}
		}
		
		return $list;
	}
	
}
?>