<?php
class Banner_Model extends Model
{
	protected $table_name = 'banner';
	protected $primary_key = 'banner_id';	
		
	public function get_banner_layout($page_id, $positon, $id = '')
	{
		if ($id != '')
		{
			if (is_array($id))
				$this->db->in($this->primary_key,$id);
			else
				$this->db->where($this->primary_key,$id);
		}		
		
		$this->db->where('position', $positon);
		$this->db->where('bl.page_id', $page_id);
		$this->db->orderby('bl.order');
		
		$this->db->join('banner_layout as bl', array('banner.banner_id' => 'bl.banner_id'));
		
		return $this->db->get($this->table_name)->result_array(false);
	}
	
	public function update_banner_layout($page_id, $set, $where)
	{
		return $this->db->update('banner_layout', $set, $where);
	}
	
	public function insert_banner_layout($set)
	{
		$this->db->insert('banner_layout',$set);
	}
	
	public function delete_banner_layout($where)
	{
		$this->db->where($where);
		$this->db->delete('banner_layout');
	}
      
	public function delete($id)
	{
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);
		return count($this->db->delete($this->table_name));
    }	
}
?>