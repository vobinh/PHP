<?php
class Admin_payment_method_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		parent::__construct();
		$this->_get_session_msg();
		
	}
	
	public function __call($method, $arguments)
	{
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
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
	
	public function _get_submit()
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
		
		$this->template->content = new View('admin_payment_method/frm_authorizenet_config');
		//Authorize.net
		$mr_aut = ORM::factory('payment_method_orm')->where('payments_method_code', 'AUT')->find()->as_array();	
    	$model = new Authorizenet_config_Model();
    	$mr_temp = $model->get(); 
    	$mr_aut = array_merge($mr_temp,$mr_aut);
    	$this->template->content->mr_aut = $mr_aut;
       
	    $this->_get_submit();		
    }
	
	 private function _get_record_aut_config()
    {
    	$form = array
	    (
	        'txt_aut_api_login' => '',
	        'txt_aut_transaction_key' => '',
	        'sel_aut_post_url' => '',	        
	    );
		
		$errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_aut_api_login','trim','required');
			$post->add_rules('txt_aut_transaction_key','trim','required');
			$post->add_rules('sel_aut_post_url','trim','required');
			
			$form = arr::overwrite($form, $post->as_array());
			$form = $this->_set_form_aut_config($form);
			if($post->validate())
 			{
 				return $form; 				
			} else {
				$this->session->set_flash('frm_aut',$form);
				$errors = arr::overwrite($errors, $post->errors('authorizenet_config_validation'));
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.='<br>'.$name;
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect('admin_payment_method');
				die();
			}
        }
    }
	private function _set_form_aut_config($form)
    {	
		$record['api_login'] = $form['txt_aut_api_login'];
		$record['transaction_key'] = $form['txt_aut_transaction_key'];
		$record['post_url'] = $form['sel_aut_post_url'];				
		return $record;
	}
	
	public function save()
	{
		$record = $this->_get_record_aut_config();
		if($record)
		{
		    $result = $this->db->update('authorizenet_config', $record, array('api_login' => $this->input->post('hd_aut_api_login')));  
			if($result)
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));		
			else $this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}		
		url::redirect('admin_payment_method');
		die();
	}
	
	
	
	
}
?>