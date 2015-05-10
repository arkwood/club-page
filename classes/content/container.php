<?php
class Container extends DBObject {
	
	/**
	 * The position on the current page
	 * 
	 * @var Integer
	 */
	public $position;
	
	/**
	 * The ID of the page this container belongs to
	 * 
	 * @var Integer
	 */
	public $pageId;
	
	/**
	 * Complex object storing layout information.
	 * 
	 * @var Layout
	 */
	public $layout;
	
	/**
	 * Holding the section instances used within the layout
	 * 
	 * @var Array of Section
	 */
	public $sections = array();
	
	/**
	 * Computed field holding the layout root rows
	 * 
	 * @var Array of Rows
	 */
	public $rows;
	
	
	/**
	 * Reads a container object from the database and computes the sections and rows.
	 * 
	 * @param Integer $id
	 */
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
		// first load the sections (they are required to compute the rows)
		$this->sections = $this->getSections();
		// compute rows
		$this->rows = $this->computeRows($this->layout->rawRows);
		
	}
	
	/**
	 * Computes the layout tree using CMS objects which can be used to render in the template.
	 * 
	 * @param Layout $layout
	 * @return Array of CMSRow
	 */
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
	
	
	/**
	 * Stores a new layout type for this container and re-computes the rows.
	 * 
	 * @param String $newLayout
	 */
	function updateLayout($newLayout) {
		// save changes in DB
		mysql_query("update container set layoutidentifier = '" . $newLayout . "' where id = " . $this->ID);
		
		// update object
		$this->layout = CMSRegistry::getLayoutByIdentifier($newLayout);
		// re-compute layout/rows/sections
		$this->rows = $this->computeRows($this->layout->rawRows);
	}
	
}

?>