<?php
class Promotion_item_Model extends Model {
	
	protected $table_name = 'promotion_item';
	protected $primary_key = 'idpromotion_item';
	
	public function get($id = '')
	{
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
}
?>