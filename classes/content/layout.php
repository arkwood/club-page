<?php
class Layout {
	
	public $rows = array();
	
	function __construct($container) {
		
		$GLOBALS["cms_layout_position"] = 0;
		
		foreach ($container->layout as $row) {
			array_push($this->rows, new LayoutRow($row, $container));
		}
		
		foreach ($this->rows as $row) {
			$row->totalRows = sizeof($this->rows);
		}
	}
}

class LayoutRow {
	
	
	public $cols = array();
	public $totalRows = 1;
	
	function __construct($jsonRow, $container) {
		foreach ($jsonRow->cols as $col) {
			array_push($this->cols, new LayoutColumn($col, $container));
		}
	}
	
}

class LayoutColumn {
	
	public $width;
	public $position;
	public $section;
	public $sectionType;
	public $backgroundColor;
	
	function __construct($jsonCol, $container) {
		$this->width = $jsonCol->width;
		$this->position = $GLOBALS["cms_layout_position"];
		$this->section = $container->getSectionByPosition($this->position);
		$this->sectionTypeLabel = $this->section->sectionType->label;
		$this->sectionType = $this->section->sectionType->identifier;
		$this->backgroundColor = $this->section->sectionType->color;
		
		$GLOBALS["cms_layout_position"]++;
		// TODO: handle include
	}
}


class LayoutTemplate extends DBObject {
	
	public $identifier;
	public $rawRows;
	public $isInclude = false;
	public $position;
	
	public $rows;
	public $totalSections = 1;
	public $totalRows = 1;
	
	function __construct($id) {
		$this->ID = $id;
		$query = "select identifier, rows, include, position from layout where id = " . $id;
		
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				$this->identifier = $data["identifier"];
				$this->rawRows = $data["rows"];
				$this->isInclude = $data["include"] == 1;
				$this->position = $data["position"];
			}
		}
		
		$this->rows = json_decode($this->rawRows);
		$this->totalRows = sizeof($this->rows);
	}
	
	function getTotalSectionCount() {
		$count = 0;
		
		foreach ($this->rows as $row) {
			foreach ($row->cols as $col) {
				if ($col->isInclude) {
					$count += $col->include->getTotalSectionCount();
				}
				else {
					$count++; 
				}
			}
		}
		
		return $count;
	}
	
	
	public static $INCLUDES = array();
	
	static function getIncludes() {
		
		if (sizeof(LayoutTemplate::$INCLUDES) == 0) {
			$query = "select id from layout where include = 1 order by position";
			
			if ($result = mysql_query($query)) {
				while ($data = mysql_fetch_array($result)) {
					$template = new LayoutTemplate($data["id"]);
					LayoutTemplate::$INCLUDES[$template->identifier] = $template; 
				}
			}	
		}
		
		return LayoutTemplate::$INCLUDES;
	}
	
	static function getTemplates() {
		$list = array();
		$query = "select id from layout where include = 0 order by position";
		
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				array_push($list, new LayoutTemplate($data["id"]));
			}
		}
		
		$includeMap = LayoutTemplate::getIncludes();
		
		foreach ($list as $template) {
			foreach ($template->rows as $row) {
				foreach ($row->cols as $col) {
					if (strlen($col->include) > 1) {
						$col->isInclude = true;
						$col->include = $includeMap[$col->include];
					}
				}
			}
		}
		
		foreach ($includeMap as $template) {
			$template->totalSections = $template->getTotalSectionCount();
		}
		
		return $list;
	}
}
?>