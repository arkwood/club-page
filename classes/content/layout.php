<?php
class Layout extends DBObject {

	/**
	 * The technical identifier for the layout 
	 * @var String
	 */
	public $identifier;
	/**
	 * Raw JSON for the rows
	 * @var JsonObject
	 */
	public $rawRows;
	
	
	/**
	 * Computed data
	 * @var Array
	 */
	public $rows;
	
	/*
	 * Template helpers
	 */ 
	public $totalRows = 1;
	
	function __construct($id) {
		$this->ID = $id;
		$query = "select identifier, rows from layout where id = " . $id;
	
		if ($result = mysql_query($query)) {
			while ($data = mysql_fetch_array($result)) {
				$this->identifier = $data["identifier"];
				$this->rawRows = json_decode($data["rows"]);
			}
		}
	
		$this->rows = $this->computeRows($this->rawRows);
		$this->totalRows = sizeOf($this->rows); 
	}
	
	function computeRows($rowsJson) {
		$result = array();
		
		foreach ($rowsJson as $row) {
			
			array_push($result, new CMSRow($row, array()));
		}
		return $result;
	}
}
