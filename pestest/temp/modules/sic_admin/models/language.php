<?php
class Language_Model extends ORM {
	
	protected $table_name = 'languages';
	protected $primary_key = 'languages_id';
	protected $col_sort_order = 'categories_sort_order';
	protected $col_order = 'languages_sort_order';
	protected $col_status = 'languages_status';
	protected $sorting = array('languages_sort_order' => 'desc', 'languages_id' => 'desc');
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->col_order,'desc');
		$this->db->orderby($this->primary_key,'asc');
		$query = $this->db->get();
		if($query->count() > 0)
			return $query->result_array(false);
		else
			return false;
	}
	
	public function get_with_active()
	{
		$this->db->where($this->col_status,'1');
		return $this->get();
	}
}
?>