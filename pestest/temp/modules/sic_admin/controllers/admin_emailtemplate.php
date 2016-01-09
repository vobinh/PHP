<?php
class Admin_emailtemplate_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
		$this->data_template_model = new Data_template_Model();  
        //$this->search = array('display' => '','keyword' => '','page' => 0,'cur_page' => '','option' => '');
		$this->search = array('keyword' => '','page' => 0,'cur_page' => '','type' => '','sort' => '','per_page' => '20','option' => '','display'=>'');
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

  
    
    public function edit($id)
    {	   	
		$this->template->content = new View('admin_emailtemplate/frm');
		$this->template->content->mr = $this->data_template_model->get($id);
    }
    
    
    private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    	$form = $this->data_template_model->get_frm();
		
		$errors = $form;		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);					
			$post->add_rules('txt_name', 'required', 'length[1,200]');
       		$post->add_rules('txt_content', 'required');
			
		
			if($post->validate()) 
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());				
				$errors = arr::overwrite($errors, $post->errors('account_validation'));
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
                if($hd_id) url::redirect('admin_emailtemplate/edit/'.$hd_id);
				die();
			}
        }
    }
    
    public function save()
    {   	    	
    	$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id']))
		{
			$data_template = ORM::factory('data_template_orm');
		
		} else {
			$data_template = ORM::factory('data_template_orm', $frm['hd_id']);
			
		}
		$data_template->configuration_title = $frm['txt_name']; // active
		$data_template->configuration_value = $frm['txt_content']; // admin
		$data_template->save();	
		
		if($data_template->saved)
		{
			if(empty($frm['hd_id']))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
			else
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));		
		} else {
			if(empty($frm['hd_id']))
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_add'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}			
		
	
			url::redirect('admin_emailtemplate/edit/'.$frm['hd_id']);
		die(); 	
    }   
    
   
}
?>