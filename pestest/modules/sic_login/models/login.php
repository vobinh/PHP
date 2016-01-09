<?php
class Login_model extends Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->session = Session::instance();	// init property session use Session
	}
	
	public function set($obj = 'customer', $val)
	{	
		$this->session->set('sess_'.$obj,$val);		
	}    
	
	public function get($obj = 'customer')
	{		
		if ($this->session->get('sess_'.$obj)) 
			return $this->session->get('sess_'.$obj);			
		
		return FALSE;
	}
	
	public function log_out($obj = 'customer')
	{
		$this->session->delete('sess_'.$obj);	
	}
	
	public function save_active_last($id)
	{
		$admin = ORM::factory('administrator_orm')->find($id);
		
		$admin->administrator_active_last = time();
		$admin->save();
	}
	
	public function status_online($id, $status = 'online')
	{
		$admin = ORM::factory('administrator_orm')->find($id);
		
		$admin->administrator_status_online = ($status=='online')?1:0;
		$admin->administrator_log_sessid = $this->session->id();
		if ($status == 'online') $admin->administrator_login_last = time();
		$admin->save();
	}
}
?>