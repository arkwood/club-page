<?php
class CMSRow {
	
	public $position;
	public $children;
	
	public $totalRows;
	
	function __construct($jsonRow, $sections) {
		$this->position = $jsonRow->position;
		$this->children = $this->computeChildren($jsonRow->children, $sections);
		$this->totalRows = sizeof($this->children);
	}
	
	
	function computeChildren($childrenJson, $sections) {
		$result = array();
		foreach ($childrenJson as $child) {
			array_push($result, new CMSChild($child, $sections));
		}
		
		return $result;
	}
}



class CMSChild {
	
	public $position;
	public $width;
	public $rowFlag;
	
	public $row = null;
	public $section = null;
	public $placeholder = null;
	
	
	function __construct($childJson, $sections) {
		$this->position = $childJson->position;
		$this->width = $childJson->width;
		$this->rowFlag = $childJson->isRow;
		
		if ($this->rowFlag) {
			$this->row = new CMSRow($childJson->row, $sections);
		}
		else {
			$this->placeholder = $childJson->placeholder;
			$this->section = $this->computeSectionByPlaceholder($sections);
		}
	}
	
	
	function computeSectionByPlaceholder($sections) {
		foreach ($sections as $section) {
			if ($section->placeholder == $this->placeholder) {
				return $section;
			}
		}
	}
	

	function isRow() {
		return $this->rowFlag;
	}
	
	function getRender() {
		
	}
}