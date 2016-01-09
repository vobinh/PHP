<?php
class Mypage_Controller extends Template_Controller 
{	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->testing_model            = new Testing_Model(); 
		$this->member_model             = new Member_Model();
		$this->test_model               = new Test_Model(); 
		$this->category_model           = new Category_Model();
		$this->testingdetail_model      = new Testingdetail_Model(); 
		$this->testing_category_model   = new Testingcategory_Model();
		$this->questionnaires_model     = new Questionnaires_Model(); 
		$this->answer_model             = new Answer_Model(); 
		$this->payment_model            = new Payment_Model();
		
		$this->courses_model            = new Courses_Model();
		$this->study_model              = new Study_Model();
		$this->lesson_model             = new Lesson_Model();
		$this->lesson_annotation_model  = new Lesson_annotation_Model();
		$this->member_certificate_model = new Member_certificate_Model();
		$this->certificate_model        = new Certificate_Model();
		$this->Login_Model              = new Login_Model();
		
		$this->template               = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/home');
		$this->_get_session_template();
	}
	
	private function _get_session_template()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
		if ($this->session->get('input_data'))
		{
			$indata = $this->session->get('input_data');
			$this->mr['member_fname'] = $indata['txt_first_name'];
			$this->mr['member_lname'] = $indata['txt_last_name'];
			$this->mr['member_email'] = $indata['txt_email'];
		}	
			
	}
	
	public function __call($method, $arguments)
	{
		
			switch ($method)
			{			
				case 'index' : $this->viewaccount(); break;
				
				case 'update_account' : $this->update_account(); break;
				
				case 'testing' : $this->testing(); break;
				
				case 'viewtesting' : $this->viewtesting(); break;
				
			}			
		//}		
	} 
	
	

    public function viewaccount(){ 	
    	
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/viewaccount');
      	//assign str random
		$data     = ORM::factory('member_orm',$this->sess_cus['id'])->as_array();
		$this->mr = array_merge($this->mr,$data);
      	$this->template->content->mr = $this->mr;

      	//echo '<pre>';
      	//print_r($this->mr);
    	
    }
	
	public function where_sql(){
		$arr_lesson     = $this->db->query('SELECT DISTINCT id_lesson FROM testing WHERE id_lesson IS NOT NULL AND member_uid = '.$this->sess_cus['id'])->result_array(false);
		
		$arr_courses    = '';
		$result_courses = array();
		foreach ($arr_lesson as $key => $value) {
			//echo $value['id_lesson'].'<br>';
			$item_lesson = $this->lesson_model->get($value['id_lesson']);
			if(!empty($item_lesson)){
				if(empty($arr_courses) || (!empty($arr_courses) && !in_array($item_lesson['id_courses'], $arr_courses)))
					$arr_courses[] = $item_lesson['id_courses'];
			}
		}
		if(!empty($arr_courses)){
			$result_courses = $this->courses_model->get($arr_courses);
			$this->db->where('id_courses',!empty($result_courses[0]['id'])?$result_courses[0]['id']:'empty');
			$this->db->orderby('id','asc');
			$result_lesson = $this->lesson_model->get();
		}

		//$this->show_arr($result_courses);
		if(!isset($this->search['courses'])){
			$this->search['courses'] = !empty($result_courses[0]['id'])?$result_courses[0]['id']:'empty';
			$this->search['lesson']  = !empty($result_lesson[0]['id'])?$result_lesson[0]['id']:'empty';
		}

		if(isset($this->search['lesson'])){
			$item_lesson = $this->lesson_model->get($this->search['lesson']);
			$this->db->where('test_uid',isset($item_lesson['id_test_pass'])?$item_lesson['id_test_pass']:'');
			$this->db->where('id_lesson',$this->search['lesson']);
		}
		else{
			$this->db->where('test_uid','empty');
			$this->db->where('id_lesson','empty');
		}
		//$this->db->orderby('uid','desc');
    }
	
	public function search(){
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
		$this->testing();
	}
	
	public function show_arr($arr){
		echo '<pre>';
			print_r($arr);
		echo '</pre>';
	}

	private function testing(){
		//$this->session->delete('sess_search');
    	$this->template->content =  new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/viewtesting');
		
		$uid =  $this->sess_cus['id'];
		$arraypayment = array();
		$test         = array();
		$courses      = array();
		$lesson       = array();
		
		$type_course  = '';
		$id_courses   = '';
		$id_lesson    = '';
		$type_slt     = '';
		$flag         = false;


		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();

		foreach($payment as $value){
			$arraypayment[] =  $value['courses_id'];
		}

		if(!empty($arraypayment)){
			$courses = $this->courses_model->get($arraypayment);
		}
		
		if(!empty($courses)){
			if(isset($this->search['courses'])){
				$flag        = false;
				$key_courses = array_search($this->search['courses'], array_column($courses, 'id'));
				$id_courses  = isset($this->search['courses'])?$this->search['courses']:'empty';
				$id_lesson   = isset($this->search['lesson'])?$this->search['lesson']:'empty';
				if($courses[$key_courses]['type'] == 0){
					$type_course = 0;
				}elseif($courses[$key_courses]['type'] == 1){
					$type_course = 1;
				}
			}else{
				$flag = true;
				if($courses[0]['type'] == 0){
					$type_course = 0;
					$id_courses  = $courses[0]['id'];
				}elseif($courses[0]['type'] == 1){
					$type_course = 1;
					$id_courses  = $courses[0]['id'];
				}
			}

			if($type_course == 0){
				$this->db->where('id_courses',$id_courses);
				$this->db->orderby('id','asc');
				$lesson = $this->lesson_model->get();
				//$this->show_arr($lesson);
				if(!empty($lesson) && $flag == true){
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
		$arrayhas  = array();
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
    		'base_url'       => 'mypage/testing/search/',
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
		
		$chartcategory = $this->testing_category_model->getbycol('member_uid',$this->sess_cus['id']);
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
				$arraycategory[(!empty($parent['category'])?$parent['category'].'-':'').$category['category']][$i] = $value['percentage'];
			}
		}
		$this->template->content->set(array(
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
		));
    }
	
	public function searchdetail()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$this->session->set_flash('sess_search',$this->search);
		$this->viewtesting();
	}
	
	public function viewtesting($idtesting,$id='')
    {
		if(isset($idtesting) && isset($id)){
			$view =  new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/viewtestingdetail');
			
			$testing = $this->testing_model->get($idtesting);
			$this->db->where('testing_code',$id);
			$this->db->where('id_lesson',$testing['id_lesson']);
			$this->db->where('member_uid',$this->sess_cus['id']);
			$total_items = count($this->testingdetail_model->get());
			
			if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else $per_page = $this->search['display']; 
			} else
				$per_page = $this->site['site_num_line2'];
		
			$this->pagination = new Pagination(array(
				'base_url'       => 'mypage/viewtesting/'.$idtesting.'/'.$id.'/searchdetail/',
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => 10,
				'style'          => 'digg',
			));
					
			$this->testingdetail_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
			$this->db->where('testing_code',$id);
			$this->db->where('id_lesson',$testing['id_lesson']);
			$this->db->where('member_uid',$this->sess_cus['id']);
			$mlist = $this->testingdetail_model->get();
			foreach($mlist as $key => $value){
				$question = $this->questionnaires_model->get($value['questionnaires_uid']);
				$this->db->where('questionnaires_uid',$mlist[$key]['questionnaires_uid']);
				$this->db->where('type',1);
				$mlist[$key]['answerright']= $this->answer_model->get();
				if(isset($question['category_uid'])){
					$category =  $this->category_model->get($question['category_uid']);
					if(!empty($category)){
						$parent   = $this->category_model->get($category['parent_id']);
					$mlist[$key]['category'] = (!empty($parent['category'])?$parent['category'].' - ':'').$category['category'];
					}else{
						$mlist[$key]['category']='';
					}
				}	
				if($value['selected_answer']!=0)
				$mlist[$key]['answer']   = $this->answer_model->get($value['selected_answer']);
				else $mlist[$key]['answer']="";
				$mlist[$key]['question']   = $this->questionnaires_model->get($value['questionnaires_uid']);
			}
			
			
			$testing['menber']   = $this->member_model->get($testing['member_uid']);
			$testing['test']     = $this->test_model->get($testing['test_uid']);
			
			
			$mlist[0]['testing'] = $testing;
			$view->mlist = $mlist;
			$view->render(true);
			die();
		}
    }
    
	public function _check_email($array,$field)
	{
		$email_exist = ORM::factory('member_orm')->where('member_email',$array[$field])->count_all();
		
		if ($email_exist)
		{
			$array->add_error($field,'_check_email');
		}	
	}
	
	public function _check_old_pass($array,$field)
	{
		$old_pass = ORM::factory('member_orm')->find($this->sess_cus['id'])->member_pw;
		
		if ($old_pass !== md5($array[$field]))
		{
			$array->add_error($field,'_check_old_pass');
		}
	}
    
    private function _get_myacc_valid()
    {
    	$old_pass = $this->input->post('txt_old_pass');
    	$new_pass = $this->input->post('txt_new_pass');
		$re_pass = $this->input->post('txt_cf_new_pass');
		
		$form = Array
    	(
    		'txt_first_name' => '',
    		'txt_last_name' => '',
    		'txt_email' => '',
    		'txt_company_name' => '',
    		'txt_contact_name' => '',
    		'txt_contact_email' => '',
			'txt_old_pass' => '',
			'txt_new_pass' => '',
			'txt_cf_new_pass'=>'',
			'chk_sendmail'=>''
			
		);
		
		$errors = $form;
		
		if ($_POST)
		{
			$post = new Validation($_POST);
			
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_email','required','email');		
			if ($this->sess_cus['email'] !== $this->input->post('txt_email'))
				$post->add_callbacks('txt_email',array($this,'_check_email'));
			
			
			
			if (!empty($old_pass) || !empty($new_pass) )
			{				
				$post->add_rules('txt_new_pass','length[6,30]');
				$post->add_rules('txt_cf_new_pass','matches[txt_new_pass]');
				$post->add_callbacks('txt_old_pass',array($this,'_check_old_pass'));
			}
			
			if ($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
					return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('register_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.='. '.$name;
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect(url::base().'mypage/viewaccount');
				die();
			}
		}
    }
    
    private function update_account()
    {
    	$old_pass = $this->input->post('txt_old_pass');
        $frm_myacc = $this->_get_myacc_valid();
		if($frm_myacc['chk_sendmail']=='on')
			$chk_sendmail =1;
		else
			$chk_sendmail =0;
		$sess_cus = $this->Login_Model->get('customer');
		if ($sess_cus !== FALSE)
		{
			$rec_up = array
			(			
				'member_fname' => $frm_myacc['txt_first_name'],
				'member_lname' =>  $frm_myacc['txt_last_name'],
				'member_email' => $frm_myacc['txt_email'],
				'company_name' => $frm_myacc['txt_company_name'],
				'company_contact_name' => $frm_myacc['txt_contact_name'],
				'company_contact_email' => $frm_myacc['txt_contact_email'],
				'send_mail' => $chk_sendmail,	
			);
			if (!empty($old_pass))
			{
				$rec_up['member_pw'] = md5($frm_myacc['txt_new_pass']);
				$this->session->set_flash('info_msg',Kohana::lang('errormsg_lang.msg_change_pass'));
			}
			
			$this->db->update('member',$rec_up,array('uid' => $sess_cus['id']));
			
			$this->session->set_flash('success_msg', 'The changes have been saved.');
			
			url::redirect(url::base().'mypage/viewaccount');
			die();
		}
				
    }
	

}
?>