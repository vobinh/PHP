<?php
class Category_ORM_Model extends ORM_MPTT
{
	protected $table_name = 'category';
	protected $primary_key = 'uid';	
	protected $col_pid = 'parent_id';
	protected $col_gid = 'parent_id';
	protected $col_level = 'level';
	protected $sorting = array('parent_id' => 'asc', 'level' => 'asc' );	
	protected $left_column = 'left';
	protected $right_column = 'right';
	protected $level_column = 'level';
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->left_column,'asc');
		$this->db->orderby($this->col_gid,'asc');
		$this->db->orderby($this->col_level,'asc');
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