<?php
class Menu_category_description_Model extends Model
{
	protected $table_name = 'menu_categories_description';
	protected $primary_key = 'menu_categories_description_id'; 
	protected $col_categories_id = 'menu_categories_id'; 
	protected $col_language_id = 'languages_id'; 
	protected $sorting = array('menu_categories_id' => 'desc');
    
	public function get($category_id='',$language_id='')
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		if($category_id) $this->db->where($this->col_categories_id,$category_id);
		if($language_id) $this->db->where($this->col_language_id,$language_id);
		$this->db->orderby($this->primary_key,'desc');
		$query = $this->db->get();
		return $query->result_array(false);
	}
}