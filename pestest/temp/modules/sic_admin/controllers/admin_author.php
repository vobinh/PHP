<?php
class Admin_author_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
		$this->author_model = new Author_Model(); 
		$this->administrator_model = new Administrator_Model();   
       // $this->search = array('display' => '');
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
		$sel_search = $this->input->post('sel_search');
		if($this->session->get('sess_search')){			
       	 	$this->search = $this->session->get('sess_search');
        }
    	if ($txt_keyword !== NULL)	// if new search key exist
		{
			 $this->search['keyword'] = $txt_keyword;	
			 $this->search['option'] = $sel_search;
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
    	$this->template->content = new View('admin_author/list');
		//Assign
        $this->template->content->set(array(
            'display' => $this->search['display']
        ));
		if(isset($this->search['keyword']))
			$this->template->content->keyword = $this->search['keyword'];
		if(isset($this->search['option']))
       		$this->template->content->option = $this->search['option'];
		// Display Account is Admin, Superadmin
		
		if (isset($this->search['keyword']) != ""){														
			$this->author_model->search($this->search);			
		}
		//$this->db->where('administrator_level<>',3);
		$total_items = count($this->author_model->get());
		
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_author/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->author_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		// Display Account is Admin, Superadmin
		//$this->db->where('administrator_level<>',3);
		if (isset($this->search['keyword']) != ""){														
			$this->author_model->search($this->search);			
		}
		$mlist = $this->author_model->get();
        $this->template->content->set(array(
            'mlist' => $mlist
		));
    }
    
    public function create()
    {		
		$this->template->content = new View('admin_author/frm');    	
		$this->template->content->mr = $this->mr;				
    }
    
    public function edit($id)
    {	   	
		$this->template->content = new View('admin_author/frm');
		$admin= $this->author_model->get($id);
		$this->template->content->mlist = $this->administrator_model->get_email($admin['email']);
		$this->template->content->mr = $this->author_model->get($id);
    }
    
    
    private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
		$hd_id_author = $this->input->post('hd_id_author');
    	$txt_pass = $this->input->post('txt_pass');
    	$form = $this->author_model->get_frm();
		
		$errors = $form;		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);			
			//$post->add_rules('txt_username','required','length[3,50]');			
			$post->add_rules('txt_email','email','required');
			
			if(empty($hd_id)) 
			{	
				//print_r('abc');die();			
				$post->add_rules('txt_pass','required','length[6,30]');
				//$post->add_callbacks('txt_username',array($this,'_check_username'));
				$post->add_callbacks('txt_email',array($this,'_check_email'));
			}
			elseif(!empty($txt_pass))
			{
				$post->add_rules('txt_pass','length[6,30]');
			}
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
				
                if($hd_id) url::redirect('admin_author/edit/'.$hd_id);
                else url::redirect('admin_author/create');
				die();
			}
        }
    }
    
    public function save()
    {   	    	
    	$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id_author']))
		{
			$administrator = ORM::factory('administrator_orm');
			$author= ORM::factory('author_orm');
			$administrator->administrator_pass = md5($frm['txt_pass']);
			$administrator->administrator_date_created = time();
		} else {
			$administrator = ORM::factory('administrator_orm', $frm['hd_id']);
			$author= ORM::factory('author_orm', $frm['hd_id_author']);
			if(!empty($frm['txt_pass'])) $administrator->administrator_pass = md5($frm['txt_pass']);	
		}
		//save table admin
		$administrator->administrator_fname = $frm['txt_fname']; // active
		$administrator->administrator_lname = $frm['txt_lname']; // admin
		$administrator->administrator_username = $frm['txt_username'];
		$administrator->administrator_email = $frm['txt_email'];	
		$administrator->administrator_username = $frm['txt_email'];
		$administrator->administrator_status = $frm['sel_status'];
		$administrator->administrator_level = 3;
		$administrator->save();	
		
		//save table author
		$author->fname = $frm['txt_fname']; 
		$author->lname = $frm['txt_lname'];
		$author->email = $frm['txt_email'];
		$author->status = $frm['sel_status']; 
		$author->uid = $administrator->administrator_id;	
		$author->save();
		
		
		if($administrator->saved)
		{
			if(empty($frm['hd_id_author']))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
			else
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));		
		} else {
			if(empty($frm['hd_id_author']))
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_add'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}			
		
		if(empty($frm['hd_id_author']))
			url::redirect('admin_author/edit/'.$administrator->administrator_id);
		else
			url::redirect('admin_author/edit/'.$frm['hd_id_author']);
		die(); 	
    }   
    
    public function delete($id)
    {				
        	//delete table admin
			$admin= $this->author_model->get($id);
			$result_del_admin =$this->administrator_model->delete_email($admin['email']);
			//delete table author
    		$result_admin = $this->author_model->delete($id);
			
        	$json['status'] = $result_admin?1:0;
			$json['mgs'] = $result_admin?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
    
    public function setstatus($id)
    {    	    	
    		$result = ORM::factory('author_orm', $id);
        	$result->status = abs($result->status - 1);
        	$result->save();
			///
			$administrator = ORM::factory('administrator_orm', $id);
        	$administrator->administrator_status = abs($administrator->administrator_status - 1);
        	$administrator->save();    
			///       	
        	$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
        	url::redirect(uri::segment(1));
            die();
    }
}
?>