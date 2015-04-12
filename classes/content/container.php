<?php
class Container extends DBObject {
	
	public $position;
	public $pageId;
	public $rawLayout;
	
	
	public $layout;
	public $layoutIdentifier;
	public $layoutSections;
	public $sections = array();
	
	
	function __construct($id) {
		$this->ID = $id;
		$query = "select position, layout, layoutidentifier, pageid from container where id = " . $id;
		
		if ($datasrc = mysql_query($query)) {
			while ($data = mysql_fetch_array($datasrc)) {
				$this->position = $data["position"];
				$this->pageId = $data["pageid"];
				$this->rawLayout = $data["layout"];
				$this->layoutIdentifier = $data["layoutidentifier"];
			}
		}
		
		$this->layout = json_decode($this->rawLayout);
		$this->sections = $this->getSections();
		$this->layoutSections = $this->getLayoutSections();
	}
	
	function getSections() {
		$list = array();
		
		$query = "select id from section where containerid = " . $this->ID . " order by position";
		if ($datasrc = mysql_query($query)) {
			while ($data = mysql_fetch_array($datasrc)) {
				array_push($list, new Section($data["id"]));
			}
		}
		
		return $list;
	}
	
	function getPage() {
		return new Page($this->pageId);
	}
	
	function getLayoutSections() {
		return new Layout($this);
	}
	
	function getSectionByPosition($position) {
		return $this->sections[$position];
	}
	
	
}
?>