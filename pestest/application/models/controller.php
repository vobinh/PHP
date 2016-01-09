<?php
class Controller_Model extends Model
{
	public function set_limit($limit, $offet = '')
	{
		if ($offet == '')
			$this->db->limit($limit);
		else
			$this->db->limit($limit, $offet);		
	}	
}
?>