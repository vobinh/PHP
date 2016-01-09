<?php
class Member_ORM_Model extends ORM {
	
	protected $table_name = 'member';
	protected $primary_key = 'uid';
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->primary_key,'desc');
		return $this->db->get();
	}
}
?>