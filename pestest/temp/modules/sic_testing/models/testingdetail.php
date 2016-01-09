<?php
class Testingdetail_Model extends Model {
	
	protected $table_name = 'testing_detail';
	protected $primary_key = 'uid';
	protected $col_name = 'testing_code';
	
	public function get($id = '')
	{
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
	
	public function deletebycol($col, $id='') {
        $this->db->where($col, $id);
        return count($this->db->delete($this->table_name));
    }
}
?>