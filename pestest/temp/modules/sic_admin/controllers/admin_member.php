<?php
class Admin_member_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
		$this->testing_model = new Testing_Model(); 
		$this->testingdetail_model = new Testingdetail_Model(); 
		$this->member_model = new Member_Model();  
		$this->payment_model =new Payment_Model();
		$this->test_model = new Test_Model();
		$this->category_model =new Category_Model();
		$this->testing_category_model= new Testingcategory_Model();
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
    	$this->template->content = new View('admin_member/list');
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
			$this->member_model->search($this->search);			
		}
		// Display Account is Admin, Superadmin
		//$this->db->where('administrator_level<>',3);
		$total_items = count($this->member_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_member/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->member_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		// Display Account is Admin, Superadmin
		if (isset($this->search['keyword']) != ""){														
			$this->member_model->search($this->search);			
		}
		//$this->db->where('administrator_level<>',3);
		$mlist = $this->member_model->get();
        $this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	
	public function search_mytest($uid)
    {			
		$this->mytest($uid);
    }     
	public function mytest($member_uid){
		$view =new View('admin_member/mylist');
		$mr = array();
		$arraypayment = array();
		$arraypay = array();
		$this->db->where('member_uid',$member_uid);
		$payment = $this->payment_model->get();
		foreach($payment as $value){
			$test = $this->test_model->get($value['test_uid']);
			if(isset($test['date'])){		
				$arraypayment[] =  $value['test_uid'];
			}
			if(isset($test['date']) && strtotime("-". $test['date'] ." day" ) <= $value['payment_date']){
				$arraypay[] = $value['test_uid'];
			}
		}
		if(!empty($arraypayment)){
			$this->db->in('uid', $arraypayment);
			$list = $this->test_model->get();
			
			
			$total_items = count($list);
			
				if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else $per_page = $this->search['display']; 
			} else
				$per_page = $this->site['site_num_line2'];
			$this->pagination = new Pagination(array(
				'base_url'       => 'admin_member/search_mytest/'.$member_uid,
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));		
			$this->test_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
			$this->where_sql();
			$this->db->in('uid', $arraypayment);
			$list = $this->test_model->get();
			foreach($list as $key => $value){
				$this->db->where('test_uid',$value['uid']);
				$this->db->where('member_uid',$member_uid);
				$this->db->limit(1);
				$payment = $this->payment_model->get();
				$list[$key]['payment_date']= $payment[0]['payment_date'];
				$list[$key]['daytest']= $payment[0]['daytest'];
			}
			foreach($list as $key => $value){
				if(in_array($value['uid'],$arraypay)){
					$list[$key]['payment'] = 1;
				}	
				else{
					$list[$key]['payment'] = 0;
				}
			}
			$mr['mlist'] = $list;
		}
		$view->mr = $mr;
		$view->render(true);
		die();
	}
	
	public function where_sql_testing()
    {
		// if($this->search['keyword'])
		//	$this->db->like('question',$this->search['keyword']);
		if(isset($this->search['test']))
			$this->db->where('test_uid',$this->search['test']);
		//else
		//	$this->db->where('test_uid','empty');
    }
	
	public function search_testing($uid)
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$test = $this->input->post('sel_test');	
		if(isset($test))
		{
			$this->search['test'] = $this->input->post('sel_test');
		}
		
		$this->session->set_flash('sess_search',$this->search);
		$this->testing($uid);
	}
	
	public function testing($uid , $test_uid='')
    {
		if(isset($test_uid) && $test_uid != '' )
			$this->db->where('test_uid',$test_uid);
		else	
			$this->db->where('test_uid','empty');
		$chartlist = $this->testing_model->getTestingByChart('member_uid',$uid);
		foreach($chartlist as $key => $value){
			$chartlist[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		
		$arraytest = array();
	    $arrayhas = array();
		if(!empty($chartlist) && $chartlist!=false){
			
			for($i = 0 ; $i<count($chartlist);$i++){
				$temp = $chartlist[$i]['test']['test_title'];
				if(!in_array($temp ,$arrayhas))
				{
					$arraytest[] = $chartlist[$i]['test']['test_title'];
				}
				$arrayhas[$i] = $chartlist[$i]['test']['test_title'];
			}
  		}
		
		
		$view =  new View('admin_member/viewtesting');
		
		if(isset($test_uid) && $test_uid != '' )
			$this->db->where('test_uid',$test_uid);
		else	
			$this->db->where('test_uid','empty');
		$total_items = count($this->testing_model->getTestingById('member_uid',$uid));
		  if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_member/search_testing/'.$uid,
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->testing_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		
		if(isset($test_uid) && $test_uid != '' )
			$this->db->where('test_uid',$test_uid);
		else	
			$this->db->where('test_uid','empty');
			
		$mlist = $this->testing_model->getTestingById('member_uid',$uid);
		
		foreach($mlist as $key => $value){
			$mlist[$key]['menber']   = $this->member_model->get($value['member_uid']);
			$mlist[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		
		if(isset($test_uid))
			$this->db->where('test',$test_uid);
		else
			$this->db->where('test','empty');
		$chartcategory = $this->testing_category_model->getbycol('member_uid',$uid);
		
		$arraycategory = array();
		$arraycode = array();
		$temp = '';
		$i=-1;
		foreach($chartcategory as $value){
			$temp =  $value['testing_code'];
			if(!in_array($temp,$arraycode)){
				$arraycode[] = $value['testing_code'];
				$i++;
			}
			
			$category = $this->category_model->get($value['category']);
			if(!empty($category )){
					$parent = $this->category_model->get($category['parent_id']);
				$arraycategory[$parent['category'].'-'.$category['category']][$i] = $value['percentage'];
			}
		}
		$arraypayment = array();
		$test = array();
		$this->db->where('member_uid',$uid);
		$payment = $this->payment_model->get();
		foreach($payment as $value){
			$arraypayment[] =  $value['test_uid'];
		}
		if(!empty($arraypayment)){
			$this->db->in('uid', $arraypayment);
			$test = $this->test_model->get();
		}
		
		$view->set(array(
            'mlist' => $mlist,
			'chartlist' =>$chartlist,
			'arraytest'=>$arraytest,
			'test' => $test,
			'arraycategory'=>$arraycategory,
			'arraycode'=>$arraycode,
			'member_uid' => $uid,
			'test_uid'=> $test_uid
		));
		$view->render(true);
		die();
    }
    
    public function create()
    {		
		$this->template->content = new View('admin_member/frm');    	
		$this->template->content->mr = $this->mr;				
    }
    
    public function edit($id)
    {	   	
		$this->template->content = new View('admin_member/frm');
		//
		$this->db->where('member_uid',$id);
		$payment = $this->payment_model->get();
		//
		$this->template->content->mr = $this->member_model->get($id);
		$this->template->content->payment = $payment;
		$this->db->where('member_uid',$id);
		$this->template->content->testing = $this->testing_model->get();
    }
    
    
    private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    	$txt_pass = $this->input->post('txt_pass');
    	$form = $this->member_model->get_frm();
		
		$errors = $form;		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);					
			$post->add_rules('txt_email','email','required');
			$post->add_rules('txt_comcontact_email','email');
			
			if(empty($hd_id)) 
			{				
				$post->add_rules('txt_pass','required','length[6,30]');
				//$post->add_callbacks('txt_email',array($this,'_check_email'));
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
				
                if($hd_id) url::redirect('admin_member/edit/'.$hd_id);
                else url::redirect('admin_member/create');
				die();
			}
        }
    }
    
    public function save()
    {   	    	
    	$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id']))
		{
			$member = ORM::factory('member_orm');
			$member->member_pw = md5($frm['txt_pass']);
			$member->register_date = time();
		} else {
			$member = ORM::factory('member_orm', $frm['hd_id']);
			if(!empty($frm['txt_pass'])) $member->member_pw = md5($frm['txt_pass']);	
		}
		$member->member_fname = $frm['txt_fname']; // active
		$member->member_lname = $frm['txt_lname']; // admin
		$member->member_email = $frm['txt_email'];
		$member->company_name = $frm['txt_comname'];	
		$member->company_contact_name = $frm['txt_comcontact_name'];
		$member->company_contact_email = $frm['txt_comcontact_email'];
		$member->status = $frm['sel_status'];
		$member->save();	
		
		if($member->saved)
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
		
		if(empty($frm['hd_id']))
			url::redirect('admin_member');
		else
			url::redirect('admin_member/edit/'.$frm['hd_id']);
		die(); 	
    }   
    
    public function delete($id)
    {				
        
    		$result_admin = $this->member_model->delete($id);
        	$json['status'] = $result_admin?1:0;
			$json['mgs'] = $result_admin?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
    
    public function setstatus($id)
    {    	    	
       
    		$result = ORM::factory('member_orm', $id);
        	$result->status = abs($result->status - 1);
        	$result->save();       	
        	$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
        	url::redirect(uri::segment(1));
            die();
        
    }
}
?>