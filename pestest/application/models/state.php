<?php
class State_Model extends Model {
	
	protected $table_name = 'state';
	protected $primary_key = 'state_id';
	protected $col_name = 'state_name';
	protected $sorting = array('state_id' => 'desc');
	
	public function get($id = '')
	{
		$this->db->select('state.*');
		
		if($id != '')
			$this->db->{is_array($id)?'in':'where'}('state.state_id', $id);
			
		$this->db->orderby($this->col_name,'asc');
		$result = $this->db->get('state')->result_array(false);
		
		if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}	
}
?>