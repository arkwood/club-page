<?php
class DBObject 
{
	public $ID;
		
	function equals($obj)
	{
		$className = get_class($this);
		if ($obj instanceof $className)
		{
			if ($obj->ID == $this->ID)
			{
				return true;
			}
		}
		return false;
	}
}
?>
