<?php
class Admin_certificate_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct(){
		$this->certificate_model = new Certificate_Model(); 
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
	
	public function index()
    {             
		
		
		$this->search = array('keyword' => '');
        $this->showlist();
        $this->_get_submit();		
    }
	
	private function showlist()
    {
    	$this->template->content = new View('admin_certificate/list');
		$this->where_sql();
		$total_items = count($this->certificate_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	    
		if(isset($this->search['display']) && $this->search['display'])
		 $this->template->content->display =$this->search['display'];
		 else $this->template->content->display =$per_page;
		
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_certificate/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->certificate_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		//$this->db->orderby('test_title','ASC');
		$mlist = $this->certificate_model->get();
		$this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	

	
	public function search()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$this->session->set_flash('sess_search',$this->search);
		$this->showlist();
	}
	
	public function where_sql()
    {
		if($this->search['keyword'])
			$this->db->like('title',$this->search['keyword']);
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

	public function create()
    {		
		$this->template->content = new View('admin_certificate/frm');
		$this->template->content->mr = $this->mr;				
    }
	
	public function edit($id)
    {		
		$this->template->content = new View('admin_certificate/frm');
		$this->template->content->certificate = $this->certificate_model->get($id);	    			
    }


	public function deleteAnswer($id){
			$result_answer = $this->answer_model->delete($id);
			$json['status'] = $result_answer?1:0;
			$json['mgs'] = $result_answer?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
			die();
	}
	
    public function delete($id){	
			$result_test = $this->certificate_model->delete($id);
			$json['status'] = $result_test?1:0;
			$json['mgs'] = $result_test?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }


	public function save(){
		// echo '<pre>';
		// 	print_r($_POST);
		// echo '</pre>';
		// die(); 
		$hd_id = $this->input->post('hd_id');
		if(empty($hd_id))
		{
			$certificate = ORM::factory('certificate_orm');
			
		} else {
			$certificate = ORM::factory('certificate_orm', $hd_id);	
		}

		$certificate->title          = $this->input->post('txt_title');
		$certificate->provider_name  = $this->input->post('txt_provider_name');
		$certificate->course_manager = $this->input->post('txt_course_manager');
		$certificate->save();	
		
		if($certificate->saved)
		{
			if(empty($hd_id))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
			else
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));		
		} else {
			if(empty($hd_id))
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_add'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}			
		
		if($this->input->post('hd_save_add'))
			url::redirect('admin_certificate/create');		
		else	
			url::redirect('admin_certificate');
		die(); 	
    }   
	
	public function setstatus($id)
    {    	    	
       
		$result = ORM::factory('certificate_orm', $id);
		$result->status = abs($result->status  - 1);
		$result->save();       	
		$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
		url::redirect($this->uri->segment(1));
		die();
       
    }
}
?>