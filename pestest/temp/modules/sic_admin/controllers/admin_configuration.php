<?php
class Admin_configuration_Controller extends Template_Controller {

    public $mr = array();
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
        
        $this->search = array('keyword' => '','page' => 0);
        
        $this->_get_submit();
    }
    
	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;
	}
	
	function _get_submit()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
		if($this->session->get('frm')){			
			$this->template->content->mr = $this->session->get('frm');
		}
	}
	
	public function index()
	{
		$this->showlist();	
		
		$this->_get_submit();	
	}
	
	private function showlist()
	{
		$this->template->content = new View('admin_configuration/list');
		
		//Assign
		$this->template->content->keyword = $this->search['keyword'];
		
		$model = new Configuration_Model();
		$this->db->orderby('configuration_title','asc');
		$mlist = $model->get();
		
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_configuration/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => count($mlist),
		    'items_per_page' => count($mlist),
		    'style'          => 'digg',
		));	
		
		$this->db->limit($this->pagination->items_per_page,$this->pagination->sql_offset);
		
       $this->db->orderby('configuration_title','asc');
		$mlist = $model->get();
		$this->template->content->mlist = $mlist;	
		$this->_get_submit();		
	}
	public function search()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		//Keyword
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword)){    		
			$this->search['keyword'] = $keyword;
		}
		
		$this->session->set_flash('sess_search',$this->search);

		$this->showlist();
	}
	public function create()
	{
		$this->template->content = new View('admin_configuration/frm');
		
		$this->_get_submit();
	}
	
    private function _set_form($form)
    {	
		$record['sim_number'] = $form['txt_number'];
		$record['sim_account'] = $form['txt_account'];
		$record['sim_price'] = $form['txt_price'];
        $record['sim_provider'] = $form['txt_provider'];
        $record['sim_desc'] = $form['txt_desc'];
		return $record;
	}
    
	private function _get_record()
	{		
	    $form = array
	    (	
	    	'txt_number' => '',
	        'txt_account' => '',
	        'txt_price' => '',
            'txt_provider' => '',
            'txt_desc' => '',
	    );
	 
	    $errors = $form;
	 	
	    if ($_POST)
	    {
	        $post = new Validation($_POST);
	 		
	        $post->pre_filter('trim', TRUE);
	        $post->add_rules('txt_number','required');
			
			$form = arr::overwrite($form, $post->as_array());
			$form = $this->_set_form($form);
	        if($post->validate())
	        {
     			return $form;
	        } else {
	        	$this->session->set_flash('frm',$form);
	        	$errors = arr::overwrite($errors, $post->errors('sim_validation'));
	            $str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				$hd_id = $this->input->post('hd_id');
				if($hd_id)
					url::redirect('admin_sim/edit/'.$hd_id); 
				else
					url::redirect('admin_sim/create'); 
	        }
	    }
	}
	
	public function save()
	{
		$hd_id = $this->input->post('hd_id');
		$record = $this->_get_record();
		
		if($record)
		{
			if(!$hd_id){
				$query = $this->db->insert('configuration', $record);
				$hd_id = $query->insert_id();
			} else {
				$query = $this->db->update('configuration', $record, array('sim_id' => $hd_id));
			} 
		}
		
		if(!$this->input->post('hd_id'))
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
		else
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
		
        if(isset($_POST['btn_save_add']) && $hd_id)
            url::redirect('admin_sim/create'); 
        elseif($hd_id)
            url::redirect('admin_sim/edit/'.$hd_id);
        else
            url::redirect('admin_sim');   
    }
    
    public function saveall()
    {
    	$arr_id = $this->input->post('chk_id');
		
		if(is_array($arr_id)){
			$sel_action = $this->input->post('sel_action');
		
			if($sel_action == 'delete')	{
				$id = ORM::factory('configuration_orm')->delete_all($arr_id);
				if($id)
					$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_del'));
			} elseif($sel_action == 'block') {
				foreach($arr_id as $arr => $id){
					$result = ORM::factory('configuration_orm',$id);
			    	$result->sim_status = 0;
			    	$id = $result->save();
		    	}
		    	if($id)
					$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
			} elseif($sel_action == 'active')
			{
				foreach($arr_id as $arr => $id){
					$result = ORM::factory('configuration_orm',$id);
			    	$result->sim_status = 1;
			    	$id = $result->save();
		    	}
		    	if($id)
					$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
			}			
		} else {
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}		

		url::redirect('admin_configuration/search');		
		die();
    }
    
	public function save_list($id='')
	{
		$hd_id = $this->input->post('hd_id');
		$txt_key = $this->input->post('txt_key');
        $txt_title = $this->input->post('txt_title');
        $txt_value = $this->input->post('txt_value');
        $txt_desc = $this->input->post('txt_desc');
		
		if(!$id)
        {
			for($i=0; $i<count($hd_id); $i++)
            {
                $record = array(
                    'configuration_title' => $txt_title[$i],
                    'configuration_key' => $txt_key[$i],
                    'configuration_value' => $txt_value[$i],
                    'configuration_description' => $txt_desc[$i]
                );
                $result = $this->db->update('configuration', $record, array('configuration_id' => $hd_id[$i]));
			}
		} else {
            $i = $id;
            $record = array(
                    'configuration_title' => $txt_title[$i],
                    'configuration_key' => $txt_key[$i],
                    'configuration_value' => $txt_value[$i],
                    'configuration_description' => $txt_desc[$i]
                );
            $result = $this->db->update('configuration', $record, array('configuration_id' => $hd_id[$i]));
		}
		
		if(!$this->input->post('hd_id'))
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
		else
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
		
        url::redirect('admin_configuration');   
    }
    
	public function edit($id)
	{
		$this->template->content = new View('admin_configuration/frm');

		$this->_get_submit();
		
		$this->template->content->mr = ORM::factory('configuration_orm')->find($id)->as_array();		
	}
	
	public function delete($id)
	{
		$status = $this->db->delete('configuration',array('configuration_id' => $id));
		if(count($status)>0)
		{
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_del'));
		}
		url::redirect('admin_configuration'); 
		die();    
	}	
}
?>