<?php
class Admin_myaccount_Controller extends Template_Controller
{	
	public $template = 'admin/index';
	
    public function __construct()
    {
        parent::__construct();
        
		$this->administrator_model = new Administrator_Model(); 

        $this->_get_session_msg();
    }
    
	public function __call($method, $arguments)
	{
	
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
		
		$this->index();
	}
	
    private function _get_session_msg()
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
	
	public function index()
	{
        $this->template->content = new View('admin_myacc/frm');        
        
        $this->mr = array_merge($this->mr, $this->administrator_model->get($this->sess_admin['id']));       
        
		//print_r($this->mr);

        $this->template->content->mr = $this->mr;		
	}
	
	public function _check_email($array,$field)
	{
		$old_email = ORM::factory('user_orm')->find($this->sess_admin['id'])->user_email;
		$email_exist = Model::factory('user')->field_value_exist('user_email', $array[$field]);
		
		if($old_email !== $array[$field] && $email_exist)
		{
			$array->add_error($field,'_check_email');
		}
	}
	
	public function _check_old_pass($array,$field)
	{
		$old_pass = ORM::factory('user_orm')->find($this->sess_admin['id'])->user_pass;
		
		if($old_pass !== md5($array[$field]))
		{
			$array->add_error($field,'_check_old_pass');
		}
	}
	
	private function _get_valid_accinfo($old_pass)
	{	
		$form = array(
			'txt_old_pass' => '',
			'txt_new_pass' => '',
			'txt_cf_new_pass' => '',			
			'txt_email' => ''
		);
		
		$errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			
			if(!empty($old_pass))
			{				
				$post->add_rules('txt_new_pass','required','length[6,50]');
				$post->add_rules('txt_cf_new_pass','matches[txt_new_pass]');
				$post->add_callbacks('txt_old_pass',array($this,'_check_old_pass'));
			}
						
			$post->add_rules('txt_email','required','email');
			$post->add_callbacks('txt_email',array($this,'_check_email'));
			
			if($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
 				return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());
				$this->session->set_flash('input_data',$form);
				
				$errors = arr::overwrite($errors, $post->errors('account_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect(uri::segment(1));
				die();
			}
		}
	}
		
	public function save()
	{
        $old_pass = $this->input->post('txt_old_pass');
		$frm = $this->_get_valid_accinfo($old_pass);
		
		$user = ORM::factory('user_orm')->find($this->sess_admin['id']);
		$admin = ORM::factory('admin_orm')->find($this->sess_admin['id']);
		
		if(!empty($old_pass))
		{
			$user->user_pass = md5($frm['txt_new_pass']);
			$this->session->set_flash('info_msg',Kohana::lang('errormsg_lang.msg_change_pass'));
		}
		$user->user_email = $frm['txt_email'];
		$admin->save();
		$user->save();
		
		$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_update'));
		
		url::redirect(uri::segment(1));
		die();
	}
}
?>