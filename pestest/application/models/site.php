<?php
class Site_Model extends Model
{	
	protected $table_name = 'site';
	protected $primary_key = 'site_id';
	
	/** Get Client Languages in Database
	 * 	 
	 *	@return int (languages_id in languages TABLE)
	 **/
	public function get_client_lang()
	{			
		$result = $this->db->get('site')->result_array(false);
		
		return $result[0]['site_lang_client'];
	}
	
	public function get_admin_lang()
	{			
		$result = $this->db->get('site')->result_array(false);
		
		return $result[0]['site_lang_admin'];
	}
	
	public function get()
	{		
		$result = $this->db->get('site')->result_array(false);
		
		return $result[0];
	}
	
	public function update($set)
	{
		$site = Site_Model::get();
		
		$this->db->where('site_id', $site['site_id']);
		
		$result = $this->db->update('site', $set);
		
		return (count($result) > 0 ? TRUE : FALSE);
	}
}
?>