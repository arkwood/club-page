<?php

require_once dirname(__FILE__) . '/newscategory.php';
require_once dirname(__FILE__) . '/../user/user.php';

class News extends DBObject {
	
	public $title;
	public $published = false;
	public $authorId;
	public $categoryId;
	public $text;
	
	function __construct($id) {
		$this->ID = $id;
		$query = "select title, published, authorid, categoryid, text from news where id = " . $id;
		
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				$this->title = $data["title"];
				$this->published = ($data["published"] == "") ? false : DateTime::createFromFormat(MYSQL_DATE_TIME_FORMAT, $data["published"]);
				$this->authorId = $data["authorid"];
				$this->categoryId = $data["categoryid"];
				$this->text = $data["text"];
			}
		}
	}
	
	
	function getCategory() {
		return new NewsCategory($this->categoryId);
	}
	
	function getAuthor() {
		return new User($this->authorId);
	}
}
?>