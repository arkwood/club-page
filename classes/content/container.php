<?php
class Container extends DBObject {
	
	public $position;
	public $pageId;
	
	
	public $layout;
	
	public $sections = array();
	
	
	/** Computed field holding the layout root rows */
	public $rows;
	
	
	function __construct($id) {
		$this->ID = $id;
		$query = "select position, layoutidentifier, pageid from container where id = " . $id;
		
		if ($datasrc = mysql_query($query)) {
			while ($data = mysql_fetch_array($datasrc)) {
				$this->position = $data["position"];
				$this->pageId = $data["pageid"];
				
				$this->layout = CMSRegistry::getLayoutByIdentifier($data["layoutidentifier"]);
			}
		}
		$this->sections = $this->getSections();
		$this->rows = $this->computeRows($this->layout->rawRows);
		
	}
	
	function computeRows($layout) {
		$result = array();
		
		foreach($layout as $rowJson) {
			array_push($result, new CMSRow($rowJson, $this->sections));
		}
		
		return $result;
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
	
	
	function getSectionByPosition($position) {
		return $this->sections[$position];
	}
	
	
}

?>