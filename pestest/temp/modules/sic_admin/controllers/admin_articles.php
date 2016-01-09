<?php
class Admin_articles_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
		$this->articles_model = new Articles_Model();  
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

    public function index()
    {             
    	$this->session->delete('sess_search');
		$this->showlist();        
    }
    
    public function search()
    {
		$txt_keyword = $this->input->post('txt_keyword');	// new key search
		//$sel_search = $this->input->post('sel_search');
		if($this->session->get('sess_search')){			
       	 	$this->search = $this->session->get('sess_search');
        }
    	if ($txt_keyword !== NULL)	// if new search key exist
		{
			 $this->search['keyword'] = $txt_keyword;	
			// $this->search['option'] = $sel_search;
		}		
		
      
		//Set
		$this->session->set_flash('sess_search',$this->search);	
		
			
		$this->showlist();
    }     
    
    public function display()
	{
		//Get
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');
		}
        //Display
		$display = $this->input->post('sel_display');		
		if(isset($display)){    		
			$this->search['display'] = $display;
		}
		//Set
		$this->session->set('sess_search',$this->search);	
		$this->showlist();
	}
    
    private function showlist()
    {
    	$this->template->content = new View('admin_articles/list');
		//Assign
        $this->template->content->set(array(
            'display' => $this->search['display']
        ));
		if(isset($this->search['keyword']))
			$this->template->content->keyword = $this->search['keyword'];
		if(isset($this->search['option']))
       		$this->template->content->option = $this->search['option'];
		//search
		//print_r($this->search);die();
		if (isset($this->search['keyword']) != "")
		{														
			$this->articles_model->search($this->search);			
		}
		// Display Account is Admin, Superadmin
		//$this->db->where('administrator_level<>',3);
		$total_items = count($this->articles_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_articles/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->articles_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		// Display Account is Admin, Superadmin
		if (isset($this->search['keyword']) != ""){														
			$this->articles_model->search($this->search);			
		}
		//$this->db->where('administrator_level<>',3);
		$mlist = $this->articles_model->get();
        $this->template->content->set(array(
            'mlist' => $mlist
		));
    }
    
    public function create()
    {		
		$this->template->content = new View('admin_articles/frm');    	
		$this->template->content->mr = $this->mr;				
    }
    
    public function edit($id)
    {	   	
		$this->template->content = new View('admin_articles/frm');
		$this->template->content->mr = $this->articles_model->get($id);
    }
    
    
    private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    	$form = $this->articles_model->get_frm();
		
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
				
                if($hd_id) url::redirect('admin_articles/edit/'.$hd_id);
                else url::redirect('admin_articles/create');
				die();
			}
        }
    }
    
    public function save()
    {   	    	
    	$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id']))
		{
			$articles = ORM::factory('articles_orm');
		
		} else {
			$articles = ORM::factory('articles_orm', $frm['hd_id']);
			
		}
		$articles->articles_name = $frm['txt_name']; // active
		$articles->articles_content = $frm['txt_content']; // admin
		$articles->articles_status = $frm['sel_status'];
		$articles->save();	
		
		if($articles->saved)
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
		
		if(isset($_POST['hd_save_add']) && empty($frm['hd_id']))
			url::redirect('admin_articles/create');		
		elseif(empty($frm['hd_id']))
			url::redirect('admin_articles');
		else
			url::redirect('admin_articles/edit/'.$frm['hd_id']);
		die(); 	
    }   
    
    public function delete($id)
    {				
        
    		$result_admin = $this->articles_model->delete($id);
        	$json['status'] = $result_admin?1:0;
			$json['mgs'] = $result_admin?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
    
    public function setstatus($id)
    {    	    	
       
    		$result = ORM::factory('articles_orm', $id);
        	$result->articles_status = abs($result->articles_status - 1);
        	$result->save();       	
        	$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
        	url::redirect(uri::segment(1));
            die();
        
    }
}
?>