<?php
class Payment_method_Model extends Model {
	
	protected $table_name = 'payments_method';
	protected $primary_key = 'payments_method_id';
	protected $col_name = 'payments_method_name';
    protected $col_code = 'payments_method_code';
    protected $col_status = 'payments_method_status';
	protected $col_sort_order = 'payments_method_sort_order';
	
	public function get($id = '')
	{
		$this->db->select('payments_method.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('payments_method.payments_method_id', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('payments_method')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
    
	public function get_active($id = '')
	{
		$this->db->where($this->col_status,'1');
		return $this->get($id);
	}
    
    public function get_by_code($code = '')
	{
		$this->db->where('payments_method_code',$code);
		$result = $this->get();
        return isset($result[0])?$result[0]:'';
	}	
}
?>