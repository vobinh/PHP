<?php
class Comment_Model extends Model
{
	public $table_name = 'comment';
	public $primary_key = 'comment_id';	
	
	public function get_com($bbs_id)
	{		
		$this->db->where('bbs_id', $bbs_id);
		$this->db->orderby('comment_time','desc');
		
		return $this->db->get($this->table_name)->result_array(false);	
	}
}
?>