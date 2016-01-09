<?php
class Authorizenet_config_Model extends Model {
	
	protected $table_name = 'authorizenet_config';
	protected $primary_key = 'api_login';
	
	public function get($id = '')
	{
		$this->db->select('authorizenet_config.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('authorizenet_config.api_login', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('authorizenet_config')->result_array(false);
         
		return @$result[0];
	}
}
?>