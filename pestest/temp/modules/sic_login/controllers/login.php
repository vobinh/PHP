<?php
class Login_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');		
		$this->_get_session_template();
	}
	
	public function __call($method, $arguments)
	{		
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{			
			switch ($method)
			{
				case 'index' : $this->index(); break;
				
				case 'check_login' : $this->check_login(); break;
				
				case 'log_out' : $this->log_out(); break;
				
				default : $this->warning_msg('wrong_pid');	
			}
		}
	}
	
	private function _get_session_template()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
	}
	
	
	private function check_login()
	{	
		$login = $this->input->post('txt_email','',TRUE);	// security input data
		$pass = md5($this->input->post('txt_password'));			// encrypt md5 input password
		
		// query result
		/*$admin_model = new Administrator_Model();
		$valid = $admin_model->account_exist($login, $pass,1);
		if($valid !== FALSE)		// if login by admin account	
		{
			if (!$valid['administrator_status'])	// if cus_status = 0 
			{
				//echo 'sd';die();
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));		
				url::redirect($this->site['history']['current']);
				die();
			}
			
			else
			{								
				$sess_admin['id'] = $valid['administrator_id'];
				$sess_admin['level'] = $valid['administrator_level'];				
				$sess_admin['username'] = $valid['administrator_username'];			
				$sess_admin['name'] = $valid['administrator_fname'].' '.$valid['administrator_lname'];
				$sess_admin['email'] = $valid['administrator_email'];	
				$sess_admin['type'] = 0;			
				Login_Model::set('admin',$sess_admin);
				Login_Model::status_online($sess_admin['id'], 'online');
				url::redirect('admin_account');
				die();
			}				
		}
		else
		{
			$member_model = new Member_Model();
			$valid = $member_model->cus_exist($login, $pass);
			if ($valid !== FALSE)	// if login by customer account		
			{
				if(!$valid['status'])	// if cus_status = 0 
				{
					$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));							
					url::redirect('home');
					die();				
				}
				else
				{					
					$sess['type'] = 1; // user
					$sess['username'] = $valid['member_email'];					
					$sess['id'] = $valid['uid'];
					$sess['name'] = $valid['member_fname'].' '.$valid['member_lname'];
					$sess['email'] = $valid['member_email'];
				}
			}
			else
			{					
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_login_pass'));		
				url::redirect('home');
				die();
			}
		}*/
		$member_model = new Member_Model();
			$valid = $member_model->cus_exist($login, $pass);
			if ($valid !== FALSE)	// if login by customer account		
			{
				if(!$valid['status'])	// if cus_status = 0 
				{
					$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));							
					url::redirect('home');
					die();				
				}
				else
				{					
					$sess['type'] = 1; // user
					$sess['username'] = $valid['member_email'];					
					$sess['id'] = $valid['uid'];
					$sess['name'] = $valid['member_fname'].' '.$valid['member_lname'];
					$sess['email'] = $valid['member_email'];
				}
			}
			else
			{					
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_login_pass'));		
				url::redirect('home');
				die();
			}
				
		Login_Model::set('customer',$sess);		
		url::redirect('test');
		die();	
	}
		
	private function log_out()
	{	
		Login_Model::log_out();
		$this->session->destroy();
		url::redirect('home');
		die();
	}	
}
?>