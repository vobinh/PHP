<?php
class Testingcategory_Model extends Model {
	
	protected $table_name = 'testing_category';
	protected $primary_key = 'uid';
	
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
	
	public function getbycol($col,$id)
	{
        $this->db->select($this->table_name.'.*');
        
        if(isset($id))
        $this->db->where($this->table_name.'.'.$col, $id);
        
        $this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        return $result;
       
	}
	
	public function deletebycol($col, $id='') {
        $this->db->where($col, $id);
        return count($this->db->delete($this->table_name));
    }
}
?>