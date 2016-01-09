<?php
class Menu_category_Model extends Model {
	
	protected $table_name = 'menu_categories';
	protected $primary_key = 'menu_categories_id';
	protected $col_pid = 'menu_categories_pid';
	protected $col_gid = 'menu_categories_gid';
	protected $col_level = 'menu_categories_level';
	protected $col_order = 'menu_categories_sort_order';
	protected $sorting = array('menu_categories_pid' => 'asc', 'menu_categories_gid' => 'desc'
        , 'menu_categories_level' => 'asc', 'menu_categories_sort_order' => 'desc'
        , 'menu_categories_id'=>'desc');	
	protected $left_column = 'menu_categories_left';
	protected $right_column = 'menu_categories_right';
	protected $level_column = 'menu_categories_level';
    
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
        $this->db->join('menu_categories_description',
            array('menu_categories_description.menu_categories_id' => 'menu_categories.menu_categories_id'));
		$this->db->orderby($this->col_gid,'desc');
		$this->db->orderby($this->col_level,'asc');
		$this->db->orderby($this->col_order,'desc');
		$this->db->orderby($this->table_name.'.'.$this->primary_key,'desc');
        $this->db->groupby($this->table_name.'.'.$this->primary_key,'desc');
		$query = $this->db->get();
		return $query->result_array(false);
	}
	
	public function is_first_child()
	{		
		return ($this->{$this->left_column} === $this->parent->{$this->left_column} + 1);
	}
	
	public function is_last_child()
	{	
		return ($this->{$this->right_column} === $this->parent->{$this->right_column} - 1);
	}
	
	public function next_sibling()
	{
		return self::factory($this->object_name)->where($this->left_column,$this->{$this->right_column} + 1)->find();
	}
	
	public function prev_sibling()
	{
		return self::factory($this->object_name)->where($this->right_column,$this->{$this->left_column} - 1)->find();
	}
}
?>