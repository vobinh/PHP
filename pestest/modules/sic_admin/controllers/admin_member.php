<?php
class Admin_member_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();

		$this->courses_model            = new Courses_Model();
		$this->lesson_model             = new Lesson_Model();
		$this->lesson_annotation_model  = new Lesson_annotation_Model();
		$this->certificate_model        = new Certificate_Model(); 
		
		$this->study_model              = new Study_Model();
		$this->member_certificate_model = new Member_certificate_Model();
		$this->testing_model            = new Testing_Model(); 
		$this->testingdetail_model      = new Testingdetail_Model(); 
		$this->member_model             = new Member_Model();  
		$this->payment_model            = new Payment_Model();
		$this->test_model               = new Test_Model();
		$this->category_model           = new Category_Model();
		$this->testing_category_model   = new Testingcategory_Model();
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
	
	public function create_courses($id_test){
		$template = new View('admin_member/frm_courses');
		$template->certificate = $this->certificate_model->get();
		$template->test        = $this->test_model->get($id_test);
		$template->render(true);
		die();
	}

	public function transfer_data($id_payment, $id_test, $id_course){
		$result = ORM::factory('payment_orm', $id_payment);
		if(!empty($result->uid)){
			$result->test_uid   = 0;
			$result->courses_id = $id_course;
			$result->save();

		}
		url::redirect('admin_member/convert_data');
		die();

	}

	public function convert_data(){
		$this->template->content = new View('admin_member/convert_data');
		$mlist = $this->member_model->get();
		$arr_member_using = array();
		if(!empty($mlist)){
			foreach ($mlist as $sl_member => $member) {
				$arraytest = array();
				$arraypay  = array();
				$this->db->where('member_uid',$member['uid']);
				$payment = $this->payment_model->get();

				
				foreach($payment as $value){
					$test = $this->test_model->get($value['test_uid']);
					if(isset($test['date']) && strtotime("-". $test['date'] ." day" ) <= $value['payment_date']){
						$arraypay[] = $value['uid'];
						$arraytest[] = $value['test_uid'];
					}
				}
				if(!empty($arraypay)){
					$mlist[$sl_member]['id_payment'] = $arraypay;
					$mlist[$sl_member]['id_test']    = $arraytest;
					$arr_member_using[] = $mlist[$sl_member];
				}
				
			}
		}
		//$this->show_arr($mlist);
        $this->template->content->set(array(
            'mlist' => $arr_member_using
		));
	}

	public function search_mytest($uid)
    {			
		$this->mytest($uid);
    }     
	public function mytest($member_uid){
		$view = new View('admin_member/mylist');
		$mr = array();
		$arraypayment = array();
		$arraypay = array();
		$this->db->where('member_uid',$member_uid);
		$payment = $this->payment_model->get();

		// echo '<pre>';
		// 	print_r($payment);
		// echo '</pre>';
		foreach($payment as $value){
			//$test = $this->test_model->get($value['test_uid']);
			$test = $this->courses_model->get($value['courses_id']);
			if(isset($test['day_valid'])){		
				$arraypayment[] =  $value['courses_id'];
			}
			if(isset($test['day_valid']) && strtotime("-". $test['day_valid'] ." day" ) <= $value['payment_date']){
				$arraypay[] = $value['courses_id'];
			}
		}
		if(!empty($arraypayment)){
			$this->db->in('id', $arraypayment);
			//$list = $this->test_model->get();
			$list = $this->courses_model->get();
			
			
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
			$this->db->in('id', $arraypayment);
			$list = $this->courses_model->get();
			//$list = $this->test_model->get();
			foreach($list as $key => $value){
				//$this->db->where('test_uid',$value['uid']);
				$this->db->where('courses_id',$value['id']);
				$this->db->where('member_uid',$member_uid);
				$this->db->limit(1);
				$payment = $this->payment_model->get();
				$list[$key]['payment_date']= $payment[0]['payment_date'];
				$list[$key]['daytest']= $payment[0]['daytest'];

				$this->db->where('id_courses',$value['id']);
				$list[$key]['lesson_count'] = count($this->lesson_model->get());
			}
			foreach($list as $key => $value){
				if(in_array($value['id'],$arraypay)){
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
	
	public function search_testing($uid){
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$courses = $this->input->post('sel_courses');
		$lesson  = $this->input->post('sel_lesson');
		$type    = $this->input->post('txt_hd_type_change');

		$this->search['courses'] = !empty($courses)?$courses:'empty';
		$this->search['lesson']  = !empty($lesson)?$lesson:'empty';
		$this->search['type']    = !empty($type)?$type:'';

		$this->session->set_flash('sess_search',$this->search);
		$this->testing($uid);
	}
	public function show_arr($arr){
		echo '<pre>';
			print_r($arr);
		echo '</pre>';
	}
	public function testing($uid , $test_uid='', $id_lesson=''){
		//$this->session->delete('sess_search');
		$view =  new View('admin_member/viewtesting');

    	$arraypayment = array();
		$test         = array();
		$courses      = array();
		$lesson       = array();
		
		$type_course  = '';
		$id_courses   = '';
		$id_lesson    = '';
		$type_slt     = '';
		$falg         = false;

		$this->db->where('member_uid',$uid);
		$payment = $this->payment_model->get();
		foreach($payment as $value){
			$arraypayment[] =  $value['courses_id'];
		}
		if(!empty($arraypayment)){
			$courses = $this->courses_model->get($arraypayment);
		}
		//$this->show_arr($this->search);
		// $this->show_arr($courses);
		// die();
		if(!empty($courses)){
			if(!empty($test_uid)){
				$key_courses = array_search($test_uid, array_column($courses, 'id'));
				$id_courses  = $test_uid;
				$falg = true;
				if($courses[$key_courses]['type'] == 0){
					$type_course = 0;
				}elseif($courses[$key_courses]['type'] == 1){
					$type_course = 1;
				}
			}else{
				if(isset($this->search['courses'])){
					$falg        = false;
					$key_courses = array_search($this->search['courses'], array_column($courses, 'id'));
					$id_courses  = isset($this->search['courses'])?$this->search['courses']:'empty';
					$id_lesson   = isset($this->search['lesson'])?$this->search['lesson']:'empty';
					if($courses[$key_courses]['type'] == 0){
						$type_course = 0;
					}elseif($courses[$key_courses]['type'] == 1){
						$type_course = 1;
					}
				}else{
					$falg = true;
					if($courses[0]['type'] == 0){
						$type_course = 0;
						$id_courses  = $courses[0]['id'];
					}elseif($courses[0]['type'] == 1){
						$type_course = 1;
						$id_courses  = $courses[0]['id'];
					}
				}
			}


			if($type_course == 0){
				$this->db->where('id_courses',$id_courses);
				$this->db->orderby('id','asc');
				$lesson = $this->lesson_model->get();
				//$this->show_arr($lesson);
				//die();
				if(!empty($lesson) && $falg == true){
					$id_lesson = $lesson[0]['id'];
				}elseif(!empty($lesson) && isset($this->search['type']) && $this->search['type'] == 1){
					$id_lesson = $lesson[0]['id'];
				}elseif(!empty($lesson) && isset($this->search['type']) && $this->search['type'] == 2){
					$id_lesson = isset($this->search['lesson'])?$this->search['lesson']:'empty';
				}
			}
		}

		if($type_course == 0)
			$this->db->where('id_lesson',$id_lesson);
		elseif($type_course == 1)
			$this->db->where('id_course',$id_courses);
		$chartlist = $this->testing_model->getTestingByChart('member_uid',$uid);


		foreach($chartlist as $key => $value){
			$chartlist[$key]['test'] = $this->test_model->get($value['test_uid']);
		}
		
		$arraytest = array();
	    $arrayhas = array();
		if(!empty($chartlist) && $chartlist!=false){
			
			for($i = 0 ; $i<count($chartlist);$i++){
				$temp = isset($chartlist[$i]['test']['test_title'])?$chartlist[$i]['test']['test_title']:'';
				if(!in_array($temp ,$arrayhas))
				{
					$arraytest[] = isset($chartlist[$i]['test']['test_title'])?$chartlist[$i]['test']['test_title']:'';
				}
				$arrayhas[$i] = isset($chartlist[$i]['test']['test_title'])?$chartlist[$i]['test']['test_title']:'';
			}
  		}
		
		if($type_course == 0)
			$this->db->where('id_lesson',$id_lesson);
		elseif($type_course == 1)
			$this->db->where('id_course',$id_courses);
		$total_items = count($this->testing_model->getTestingById('member_uid',$uid));

		if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else
            	$per_page = $this->search['display']; 
		}else
            $per_page = $this->site['site_num_line2'];
	
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_member/search_testing/'.$uid,
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->testing_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		
		if($type_course == 0)
			$this->db->where('id_lesson',$id_lesson);
		elseif($type_course == 1)
			$this->db->where('id_course',$id_courses);
		$mlist = $this->testing_model->getTestingById('member_uid',$uid);
		// echo $this->db->last_query();
		// $this->show_arr($mlist);
		// die();
		foreach($mlist as $key => $value){
			$mlist[$key]['menber'] = $this->member_model->get($value['member_uid']);
			$mlist[$key]['test']   = $this->test_model->get($value['test_uid']);
		}
		
		if($type_course == 0){
			$item_lesson = $this->lesson_model->get($id_lesson);
			
			$this->db->where('id_lesson',$id_lesson);
			$this->db->where('test',isset($item_lesson['id_test_pass'])?$item_lesson['id_test_pass']:'');
		}
		elseif($type_course == 1){
			$key1 = array_search($id_courses, array_column($courses, 'id'));
			$this->db->where('id_course',$id_courses);
			$this->db->where('test',isset($courses[$key1]['id_test'])?$courses[$key1]['id_test']:'');
		}
		
		$chartcategory = $this->testing_category_model->getbycol('member_uid',$uid);
		// echo $this->db->last_query();
		// $this->show_arr($chartcategory);
		// die();
		$arraycategory = array();
		$arraycode     = array();
		$arraydate     = array();
		$temp          = '';
		$i             = -1;
		foreach($chartcategory as $value){
			$temp =  $value['testing_code'];
			if(!in_array($temp,$arraycode)){
				$arraycode[] = $value['testing_code'];
				$arraydate[] = $this->format_int_date($value['testing_date'],'m/d/Y H:i');
				$i++;
			}
			
			$category = $this->category_model->get($value['category']);
			if(!empty($category )){
					$parent = $this->category_model->get($category['parent_id']);
				$arraycategory[$parent['category'].'-'.$category['category']][$i] = $value['percentage'];
			}
		}

		$view->set(array(
			'mlist'          => $mlist,
			'chartlist'      => $chartlist,
			'arraytest'      => $arraytest,
			'test'           => $test,
			'courses'        => $courses,
			'lesson'         => $lesson,
			'arraycategory'  => $arraycategory,
			'arraycode'      => $arraycode,
			'arraydate'      => $arraydate,
			'm_id_courses'   => $id_courses,
			'm_id_lesson'    => $id_lesson,
			'm_type_courses' => $type_course,
			'member_uid'     => $uid,
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
        	url::redirect($this->uri->segment(1));
            die();
        
    }
}
?>