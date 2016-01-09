<?php
class Courses_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/home');
		// Init session 
		$this->_get_session_template();	
		$this->questionnaires_model     = new Questionnaires_Model();
		$this->answer_model             = new Answer_Model();
		$this->category_model           = new Category_Model();
		$this->test_model               = new Test_Model(); 
		$this->testing_model            = new Testing_Model(); 
		$this->payment_model            = new Payment_Model(); 
		$this->testingdetail_model      = new Testingdetail_Model(); 
		$this->testing_category_model   = new Testingcategory_Model();
		$this->promotion_model          = new Promotion_Model();
		
		$this->courses_model            = new Courses_Model();
		$this->study_model              = new Study_Model();
		$this->lesson_model             = new Lesson_Model();
		$this->lesson_annotation_model  = new Lesson_annotation_Model();
		$this->member_certificate_model = new Member_certificate_Model();
		$this->certificate_model        = new Certificate_Model();
		$this->data_template_model      = new Data_template_Model();
		//print_r($this->sess_cus);
		if($this->sess_cus =="")
		{
			url::redirect(url::base());
			die();
		}
		
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
	}
	
	public function __call($method, $arguments)
	{
		
	} 
	
	public function show_arr($arr){
		echo '<pre>';
			print_r($arr);
		echo '</pre>';
	}
	
	public function download_s3($name=""){
		require_once Kohana::find_file('views/aws','init');
		try {
			$obj = $s3Client->getObject(array(
				'Bucket' => $s3_bucket,
	    		'Key'    => 'NoteS/'.$name
			));
			header('Content-Type: application/octet-stream');
			header("Content-Disposition: attachment; filename=".$name );
			header('Content-type: ' .$obj['ContentType']);
			header("Expires: 0");
			header("Pragma: no-cache");
			echo $obj['Body'];
			die();
		} catch (Exception $e) {
			die();
		}
		
	}

	public function showlist($sort='recommended'){
		$arr_sort = array('recommended', 'newest', 'popular');
		if(!in_array($sort, $arr_sort))
			$sort = 'recommended';
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/newlist');
		
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		$arraypayment = array();
		foreach($payment as $value){
			$test = $this->test_model->get($value['courses_id']);
			
			if((isset($value['daytest'] ) && strtotime("-". $value['daytest'] ." day" ) <= $value['payment_date'])
				||(isset($value['daytest']) && $value['daytest'] ==0))
			{
				if(!in_array($value['courses_id'],$arraypayment))
					$arraypayment[] =  $value['courses_id'];
			}
		}
		$using_cookie = false;
		if(isset($_COOKIE['arr_courses'])) {
	   		if(!empty($_COOKIE['arr_courses'])){
	    		$list_item = array_map('trim',explode(',', $_COOKIE['arr_courses']));
	    		if(!empty($list_item)){
	    			$using_cookie = true;
	    		}else{
	    			$cookie_name  = "arr_courses";
	    			setcookie($cookie_name, '', time() - (86400 * 1), "/");
	    			setcookie('sponsor_img', '', time() - (86400 * 1), "/");
	    		}
	    	}else{
	    		$cookie_name  = "arr_courses";
	    		setcookie($cookie_name, '', time() - (86400 * 1), "/"); // 86400 = 1 day
	    		setcookie('sponsor_img', '', time() - (86400 * 1), "/");
	    	}
		}
		$this->member_model = new Member_Model(); 
		$m_member           = $this->member_model->get($this->sess_cus['id']);
		$slt_tags           = 0;
		$slt_type_search    = 0;	
		if(isset($_POST) && !empty($_POST)){
			$data_tags = array('tag_id' => @$_POST['slt_tags']);
			$this->db->where('uid',$this->sess_cus['id']);
			$this->db->update('member',$data_tags);

			if(isset($_POST['slt_tags']) && $_POST['slt_tags'] != 0){
				$this->db->where('tags_id like "%|'.$_POST['slt_tags'].'|%"');
				$slt_tags = $_POST['slt_tags'];
			}
			if(isset($_POST['slt_type_search']) && $_POST['slt_type_search'] != 0){
				$slt_type_search = $_POST['slt_type_search'];
				if($_POST['slt_type_search'] == 1)
					$this->db->where('type <> 1');
				else
					$this->db->where('type = 1');
			}
		}else{
			$slt_tags = isset($m_member['tag_id'])?$m_member['tag_id']:0;
			if(!empty($slt_tags))
				$this->db->where('tags_id like "%|'.$slt_tags.'|%"');
		}

		$this->db->where('status',1);
		if($sort == 'recommended'){
			//if($using_cookie == false)
			$this->db->orderby('location','asc');
			$this->db->orderby('id','desc');
		}elseif($sort == 'newest'){
			$using_cookie = false;
			$this->db->orderby('id','desc');
		}elseif($sort == 'popular'){
			$using_cookie = false;
			$this->db->orderby('payment_count','desc');
		}
		$mr['mlist'] = $this->courses_model->get();
		$flag = false;
		foreach($mr['mlist'] as $key => $value){
			$this->db->where('courses_id',$value['id']);
			$this->db->where('member_uid',$this->sess_cus['id']);
			$this->db->limit(1);
			$payment = $this->payment_model->get();
			if(isset($payment[0]['payment_date']) && isset($payment[0]['daytest'])){
				$mr['mlist'][$key]['payment_date']  = $payment[0]['payment_date'];
				$mr['mlist'][$key]['daytest']       = $payment[0]['daytest'];
				$mr['mlist'][$key]['payment_price'] = $payment[0]['payment_price'];
			}
			if($using_cookie){
				$key_sort =  array_search($value['id'],$list_item);
				if($key_sort === false){
					$mr['mlist'][$key]['recommended'] = 'empty';
				}else{
					// check date using
					if(!empty($mr['mlist'][$key]['authorized_day_using']) && !empty($mr['mlist'][$key]['authorized_day']) && strtotime("-". $mr['mlist'][$key]['authorized_day'] ." day" ) <= $mr['mlist'][$key]['authorized_day_using']){
			          	$txt_int_day = round(abs(strtotime(date('m/d/Y',$mr['mlist'][$key]['authorized_day_using']). ' + '.$mr['mlist'][$key]['authorized_day'].' day') - strtotime(date('m/d/Y')))/(60*60*24));
			        }else{
			          	$txt_int_day = 0;
			        }
			        if($txt_int_day > 0){
						$mr['mlist'][$key]['recommended'] = ($key_sort + 1);
						$flag = true;
			        }else{
			        	$mr['mlist'][$key]['recommended'] = 'empty';
			        }
				}
			}
		}
		
		if($using_cookie && $flag){
			$arr_id          = array();
			$arr_recommended = array();
			$arr_location    = array();
			foreach ($mr['mlist'] as $sl_mr => $item_mr) {
				$arr_id[$sl_mr]          = $item_mr['id'];
				$arr_recommended[$sl_mr] = $item_mr['recommended'];
				$arr_location[$sl_mr]    = $item_mr['location'];
			}
			array_multisort($arr_recommended, SORT_ASC, SORT_STRING, $arr_location, SORT_ASC, SORT_STRING, $arr_id, SORT_DESC, $mr['mlist']);
		}elseif($using_cookie && !$flag){
			setcookie('arr_courses', '', time() - (86400 * 1), "/");
	    	setcookie('sponsor_img', '', time() - (86400 * 1), "/");
			$arr_id       = array();
			$arr_location = array();
			foreach ($mr['mlist'] as $sl_mr => $item_mr) {
				$arr_id[$sl_mr]       = $item_mr['id'];
				$arr_location[$sl_mr] = $item_mr['location'];
			}
			array_multisort($arr_location, SORT_ASC, SORT_STRING, $arr_id, SORT_DESC, $mr['mlist']);
		}

		//$this->show_arr($mr);
		$list_tags = $this->db->get('tags')->result_array(false);
		$this->template->content->mr              = $mr;
		$this->template->content->list_tags       = $list_tags;
		$this->template->content->m_sort          = $sort;
		$this->template->content->arraypayment    = $arraypayment;
		$this->template->content->slt_tags        = $slt_tags;
		$this->template->content->slt_type_search = $slt_type_search;
	}
	
	
	public function mytest(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/mylist');
		$mr = array();
		$arraypayment = array();
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		foreach($payment as $value){
			$test = $this->test_model->get($value['test_uid']);
			if((isset($value['daytest']) && strtotime("-". $value['daytest'] ." day" ) <= $value['payment_date'])
									|| (isset($value['daytest']) && $value['daytest']==0))
			{		
				$arraypayment[] =  $value['test_uid'];
			}
		}
		
		if(!empty($arraypayment)){
			$this->db->in('uid', $arraypayment);
			$this->db->where('status',1);
			$total_items = count($this->test_model->get());
			if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else $per_page = $this->search['display']; 
			}else
				$per_page = $this->site['site_num_line2'];
			
			$this->pagination = new Pagination(array(
				'base_url'       => 'test/showlist/search/',
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));		
			$this->where_sql();
			$this->db->in('uid', $arraypayment);
			
			$this->db->where('status',1);
			$mr['mlist'] = $this->test_model->get();
			foreach($mr['mlist'] as $key=> $value){
				
				$this->db->where('test_uid',$value['uid']);
				$this->db->where('member_uid',$this->sess_cus['id']);
				$this->db->limit(1);
				$payment = $this->payment_model->get();
				$mr['mlist'][$key]['payment_date']= $payment[0]['payment_date'];
				$mr['mlist'][$key]['daytest']= $payment[0]['daytest'];
			}
			foreach($mr['mlist'] as $key => $value){
				
				$this->db->where('test_uid',$mr['mlist'][$key]['uid']);
				$category = $this->category_model->get();
				$mr['mlist'][$key]['category'] = $category;
				
				$this->db->limit(1);
				$this->db->where('member_uid',$this->sess_cus['id']);
				$this->db->where('test_uid',$value['uid']);
				$mr['mlist'][$key]['list'] = $this->testing_model->get();
				if(!empty($mr['mlist'][$key]['list'][0]['parent_code'])){
					$this->db->where('testing_code',$mr['mlist'][$key]['list'][0]['parent_code']);
					$testparent = $this->testing_model->get();
					$mr['mlist'][$key]['scoreparent'] = $testparent[0]['testing_score'];
				}
			}
			
		}
		$this->show_arr($mr);
		$this->template->content->mr = $mr;		
	}
	
	public function dialogmytest(){
		$view = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/historylist');
		$this->db->where('member_uid = '.$this->sess_cus['id'].' AND (test_uid = 0 OR test_uid = "" OR test_uid = "NULL")');
		$payment = $this->payment_model->get();;
		foreach($payment as $key => $value){
			$courses = $this->courses_model->get($value['courses_id']);
			$payment[$key]['courses'] = isset($courses)?$courses:'';		
		}
		$total_items = count($payment );
		if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		$this->pagination = new Pagination(array(
    		'base_url'       => 'courses/showlist/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		
		$mr['mlist'] = $payment;
		
		$view->mr = $mr;
		$view->render(true);	
		die();	
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
		$this->showtest();
	}
	
	public function free_courses($id_courses='',$code="",$type="promotion",$promotionid="",$item_id=""){
		$id_courses = substr(base64_decode($id_courses),0,strlen(base64_decode($id_courses)) - 3);
		if(!empty($code))
			$code = substr(base64_decode($code),0,strlen(base64_decode($code)) - 3);
		$courses = $this->courses_model->get($id_courses);
		$this->db->where('member_uid',$this->sess_cus['id']);
		$this->db->where('courses_id',$id_courses);
		$payment = $this->payment_model->get();
		$insert = true;

		foreach($payment as $value){
			if(((isset($value['daytest'])?strtotime("-". $value['daytest'] ." day"):strtotime('now')) <= $value['payment_date']) || $value['daytest'] == 0){
				$insert = false;
			}
		}

		if($insert && !empty($code)){
			if(isset($type) && $type == 'promotion'){
				$inserts =  $this->db->insert('payment', array(
					'member_uid'     => $this->sess_cus['id'],
					'courses_id'     => $id_courses,
					'payment_date'   => strtotime(date('m/d/Y')),
					'valid_until'    => 1,
					'promotion_code' => $code,
					'payment_price'  => 0,
					'daytest'        => isset($courses['day_valid'])?$courses['day_valid']:'0'
				));
			}else{
				if(!empty($promotionid))
					$promotionid = substr(base64_decode($promotionid),0,strlen(base64_decode($promotionid)) - 3);
				else
					$promotionid = "";

				if(!empty($promotionid)){
					$mr_promotion = $this->promotion_model->get($promotionid); 
					if(isset($mr_promotion['promotion_price']))
						$courses['price'] = $courses['price']-$mr_promotion['promotion_price'];
					if(!empty($item_id)){
						$this->db->where('idpromotion_item',$item_id);
						$m_item_code = $this->db->get('promotion_item')->result_array(false);
						if(isset($m_item_code[0]['code']))
							$promotion_code = $m_item_code[0]['code'];
						else
							$promotion_code = "";
					}else{
						if(isset($mr_promotion['promotion_code']))
							$promotion_code = $mr_promotion['promotion_code'];
						else
							$promotion_code = "";
					}
					
				}else{
					$promotion_code = "";
				} 
			  
				$inserts =  $this->db->insert('payment', array(
					'member_uid'       => $this->sess_cus['id'],
					'courses_id'       => $id_courses,
					'payment_date'     => strtotime(date('m/d/Y')),
					'valid_until'      => 1,
					'transaction_code' => isset($courses['price'])&&$courses['price']>0?$code:'',
					'payment_price'    => isset($courses['price'])?$courses['price']:'0',
					'daytest'          => isset($courses['day_valid'])?$courses['day_valid']:'0',
					'promotion_code'   => isset($promotion_code)?$promotion_code:''
				));
			}
			if(!empty($courses)){
				/*cap nhat so luong mua*/
				$m_course = ORM::factory('courses_orm',$courses['id']);
				$m_course->payment_count = ($m_course->payment_count + 1);
				$m_course->save();
			}
			if(!empty($inserts)){
				//$this->template->content->insert = 'You have paid successfully'; 
				$this->session->set_flash('success_msg','You have paid successfully');
			}
		}
		url::redirect(url::base().'courses');
	}

	public function start($id_test="",$id_lesson="",$id_courses=""){
		$id_test    = substr(base64_decode($id_test),0,strlen(base64_decode($id_test)) - 3);
		$id_lesson  = substr(base64_decode($id_lesson),0,strlen(base64_decode($id_lesson)) - 3);
		$id_courses = substr(base64_decode($id_courses),0,strlen(base64_decode($id_courses)) - 3);
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/start');
		/**
		 * get data test
		 */
		$mr = $this->test_model->get($id_test);

		/**
		 * Get data 
		 * 	+lesson
		 * 	+courses
		 */
		$lesson  = $this->lesson_model->get($id_lesson);
		if(!empty($id_courses))
			$courses = $this->courses_model->get($id_courses);
		else
			$courses = $this->courses_model->get($lesson['id_courses']);

		if(!empty($courses) && $courses['type'] == 1){
			$this->db->where('id_member',$this->sess_cus['id']);
			$this->db->where('id_course',$courses['id']);
			$study = $this->study_model->get();
			//$this->show_arr($study);
			if(empty($study[0])){
				$m_study              = ORM::factory('study_orm');
				$m_study->id_member   = $this->sess_cus['id'];
				$m_study->id_course   = $courses['id'];
				$m_study->video_pass  = 'N';
				$m_study->lesson_pass = 'N';
				$m_study->course_pass = 'N';
				$m_study->save();
			}
		}
		
		/**
		 * get data category
		 */

		if(!empty($lesson['id_categories'])){
			$this->db->where('category.uid',$lesson['id_categories']);

		}
		$this->db->where('test_uid',$id_test);
		$this->db->where('category_percentage >=',0);
		$mlist_cate = $this->category_model->get();
		$total_questions = 0;

		if(!empty($mlist_cate)){
			foreach ($mlist_cate as $key => $value) {
				$total_questions += floor($mr['qty_question'] * $value['category_percentage'] / 100);
			}
			$mr['total_questions'] = $total_questions;
		}

		if(!empty($lesson['id_categories']) && !empty($mlist_cate[0]) && $mlist_cate[0]['category_percentage'] > 0){
			$m_total =  floor($mr['qty_question'] * $mlist_cate[0]['category_percentage'] / 100);
			$m_time  =  floor($mr['time_value'] * $mlist_cate[0]['category_percentage'] / 100);
			$mr['m_total'] = $m_total;
			$mr['m_time']  = $m_time;
			
		}
		/**
		 * data to view
		 */
		$this->template->content->mr         = $mr;
		$this->template->content->mlist_cate = $mlist_cate;
		$this->template->content->lesson     = $lesson;
		$this->template->content->courses    = $courses;
	}
    
   	public function index($sort='0'){   		
   		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/mylist');
		$mr = array();
		$arraypayment   = array();
		$arr_id_payment = array();
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		foreach($payment as $value){
			$courses = $this->courses_model->get($value['courses_id']);
			if((isset($value['daytest']) && strtotime("-". $value['daytest'] ." day" ) <= $value['payment_date'])
									|| (isset($value['daytest']) && $value['daytest']==0))
			{		
				$arraypayment[]   =  $value['courses_id'];
				$arr_id_payment[] = $value['uid'];
			}
		}
		if(!empty($arraypayment)){
			$this->db->in('id', $arraypayment);
			$this->db->where('status',1);
			$total_items = count($this->courses_model->get());
				if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else $per_page = $this->search['display']; 
			} else
				$per_page = $this->site['site_num_line2'];
			$this->pagination = new Pagination(array(
				'base_url'       => 'courses/showlist/search/',
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));
			$this->db->join('payment', array('courses.id' => 'payment.courses_id'),'LEFT');	
			$this->where_sql();
			$this->db->in('id', $arraypayment);
			$this->db->in('payment.uid', $arr_id_payment);
			$this->db->where('status',1);
			if($sort == 1)
				$this->db->orderby('payment_count','desc');
			elseif($sort == 0){
				$this->db->orderby('payment.uid','desc');
			}
			$this->db->select('courses.*','payment.payment_date','payment.daytest');
			$mr['mlist'] = $this->courses_model->get();
			foreach($mr['mlist'] as $key=> $value){
				$m_date      = strtotime(date('m/d/Y',$mr['mlist'][$key]['payment_date']). ' + '.$mr['mlist'][$key]['daytest'].' day');
				$date1       = date_create(date('Y-m-d', $m_date));
				$date2       = date_create(date('Y-m-d'));
				$diff        = date_diff($date1,$date2);
				
				$mr['mlist'][$key]['days_left'] = (int)$diff->format("%a");
				//$mr['mlist'][$key]['days_left']  = floor(abs(strtotime(date('m/d/Y',$mr['mlist'][$key]['payment_date']). ' + '.$mr['mlist'][$key]['daytest'].' day') - strtotime(date('m/d/Y')))/(60*60*24));
			}
		} 
		$this->template->content->mr     = $mr;
		$this->template->content->m_sort = $sort;
	}
	
	public function lesson($id_lesson){
		$id_lesson = base64_decode($id_lesson);
		if(empty($id_lesson)){
			url::redirect(url::base().'courses');
			die();
		}
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/lesson');
		$lesson = $this->lesson_model->get($id_lesson);
		if(!empty($lesson)){
			//check study lesson
			$this->db->where('id_member',$this->sess_cus['id']);
			$this->db->where('id_lesson',$id_lesson);
			$study = $this->study_model->get();
			//$this->show_arr($study);
			if(empty($study[0])){
				$m_study              = ORM::factory('study_orm');
				$m_study->id_member   = $this->sess_cus['id'];
				$m_study->id_lesson   = $id_lesson;
				$m_study->video_pass  = 'N';
				$m_study->lesson_pass = 'N';
				$m_study->save();
			}

			$lesson['video_pass']  = !empty($study[0]['video_pass'])?$study[0]['video_pass']:'N';
			$lesson['lesson_pass'] = !empty($study[0]['lesson_pass'])?$study[0]['lesson_pass']:'N';

			$using_download = false;
			if(!empty($lesson['attach_file'])){
				require Kohana::find_file('views/aws','init');
				$check_file = $s3Client->doesObjectExist($s3_bucket, "NoteS/".(!empty($lesson['attach_file'])?$lesson['attach_file']:'0'));
				if($check_file == 1)
					$using_download = true;
			}
			//Get courses
			$courses = $this->courses_model->get($lesson['id_courses']);

			//Get Annotation text
			$this->db->where('id_lesson',$lesson['id']);
			$this->db->orderby('id','asc');
			$lis_annotation = $this->lesson_annotation_model->get();

			$this->template->content->courses        = $courses;
			$this->template->content->lesson         = $lesson;
			$this->template->content->using_download = $using_download;
			$this->template->content->lis_annotation = $lis_annotation;

		}else{
			url::redirect(url::base().'courses');
			die();
		}
	}

	public function video_pass(){
		$id_lesson = $this->input->post('id_lesson');
		$this->db->where('id_member',$this->sess_cus['id']);
		$this->db->where('id_lesson',$id_lesson);
		$study = $this->study_model->get();
		if(!empty($study[0])){
			$arr_data = array(
				'video_pass' => 'Y',
			);
			$m_study = ORM::factory('study_orm',$study[0]['id']);
			$m_study->video_pass = 'Y';
			$m_study->save();
			if($m_study->saved)
				echo json_encode('1');
			else
				echo json_encode('0');
			die();
		}
		echo json_encode('0');
		die();
	}

	public function study($id_courses=''){
		$id_courses = base64_decode($id_courses);
		if(empty($id_courses)){
			url::redirect(url::base().'courses');
			die();
		}
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/study');
		$courses = $this->courses_model->get($id_courses);
		if(!empty($courses)){
			//Get lesson
			$this->db->where('lesson.id_courses',$id_courses);
			$this->db->orderby('id','asc');
			$list_lesson = $this->lesson_model->get();

			if(!empty($list_lesson)){
				foreach ($list_lesson as $index => $item_lesson) {
					$this->db->where('id_member',$this->sess_cus['id']);
					$this->db->where('id_lesson',$item_lesson['id']);
					$study = $this->study_model->get();
					$list_lesson[$index]['video_pass']  = !empty($study[0]['video_pass'])?$study[0]['video_pass']:'N';
					$list_lesson[$index]['lesson_pass'] = !empty($study[0]['lesson_pass'])?$study[0]['lesson_pass']:'N';
				}
			}
			if($this->check_finish_courses($id_courses)){
				$this->db->where('member_uid',$this->sess_cus['id']);
				$this->db->where('id_courses',$id_courses);
				$list_certificate = $this->member_certificate_model->get();
				$this->template->content->list_certificate = $list_certificate;
				$this->template->content->courses_unlock   = !empty($list_certificate)?'yes':'no';

			}else{
				$this->template->content->courses_unlock     = 'no';
			}
			$this->template->content->courses     = $courses;
			$this->template->content->list_lesson = $list_lesson;
		}else{
			url::redirect(url::base().'courses');
			die();
		}
	}

	public function check_finish_courses($id_courses){
		$courses = $this->courses_model->get($id_courses);
		if(!empty($courses)){
			//Get lesson
			$this->db->where('lesson.id_courses',$id_courses);
			$this->db->orderby('id','asc');
			$list_lesson = $this->lesson_model->get();

			if(!empty($list_lesson)){
				foreach ($list_lesson as $index => $item_lesson) {
					$this->db->where('id_member',$this->sess_cus['id']);
					$this->db->where('id_lesson',$item_lesson['id']);
					$study = $this->study_model->get();
					if(empty($study[0]['lesson_pass']) || $study[0]['lesson_pass'] == 'N'){
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}

	public function certificate(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/certificate');
		$this->db->where('member_uid',$this->sess_cus['id']);
		$list_certificate = $this->member_certificate_model->get();
		if(!empty($list_certificate)){
			foreach ($list_certificate as $key => $value) {
				$item_certificate = $this->certificate_model->get($value['certificate_id']);
				$list_certificate[$key]['item'] = $item_certificate;
			}
		}
		//$this->show_arr($list_certificate);
		$this->template->content->list_certificate = $list_certificate;
	}

	public function insert_font(){
		require_once Kohana::find_file('vendor/html2pdf/fpdf','fpdf');
		$pdf = new FPDF();
		$pdf->AddFont('english_mt','','english_mt.php');
		$pdf->AddPage();
		$pdf->SetFont('english_mt','',35);
		$pdf->Write(10,'Changez de police avec FPDF !');
		$pdf->Output();
		die();
	}

	public function print_certificate($id){
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/print_certificate');
		
		$id = base64_decode($id);
		$certificate = $this->member_certificate_model->get($id);
		if(!empty($certificate)){
			$item_certificate = $this->certificate_model->get($certificate['certificate_id']);
			$certificate['item'] = $item_certificate;
		}
		
		$this->template->set(array( 
			'certificate' => $certificate,
		));

		require Kohana::find_file('vendor/html2pdfv','html2pdf');
		$html2pdf = new HTML2PDF('L','A5','en',array(0, 0, 0, 0));
		$html2pdf->WriteHTML($this->template,false);
		echo $html2pdf->Output('certificate.pdf');
		die();
	}

	public function testing(){
		$this->session->delete('sess_save');
		if(isset($_POST['txt_finish']) && $_POST['txt_finish'] == 1){
			$id_courses = $this->input->post('txt_id_courses');
			$courses = $this->courses_model->get($this->input->post('txt_id_courses'));
			if(!empty($courses['type']) && $courses['type'] == 1){
				url::redirect(url::base().'courses');
		  		die();
			}else{
				if($this->check_finish_courses($id_courses)){
					/**
					 * add certificate member
					 */
					$courses = $this->courses_model->get($this->input->post('txt_id_courses'));
					if(!empty($courses)){
						// Check certificate
						$this->db->where('member_uid',$this->sess_cus['id']);
						$this->db->where('certificate_id',$courses['id_certificate']);
						$this->db->orderby('id','desc');
						$certificate = $this->member_certificate_model->get();

						if(!empty($certificate[0]) && $certificate[0]['new'] == 1){
							/**
							 * add certificate
							 */
							$m_certificate  = ORM::factory('member_certificate_orm',$certificate[0]['id']);
							$m_certificate->new = 2;
							$m_certificate->save();
							url::redirect(url::base().'courses/study/'.base64_encode($id_courses));
							die();
						}else{
							url::redirect(url::base().'courses/study/'.base64_encode($id_courses));
							die();
						}
					}else{
						url::redirect(url::base().'courses/study/'.base64_encode($id_courses));
						die();
					}
				}else{
					 url::redirect(url::base().'courses/study/'.base64_encode($id_courses));
				}
			}
			die();
		}
		if(!isset($_POST['sel_test']) ){
		  url::redirect(url::base().'courses');
		  die();
		}
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/test');
		$id_test    = $this->input->post('sel_test');
		$id_courses = $this->input->post('txt_id_courses');
		$id_lesson  = $this->input->post('txt_id_lesson');

		$mr      = $this->test_model->get($id_test);
		$lesson  = $this->lesson_model->get($id_lesson);
		$courses = $this->courses_model->get($id_courses);
		/////
		if(!empty($lesson['id_categories'])){
			$this->db->where('category.uid',$lesson['id_categories']);
		}
		$this->db->orderby('uid','DESC');
		$this->db->where('test_uid',$_POST['sel_test']);
		$this->db->where('category_percentage >=',0);
		$mist_cate = $this->category_model->get();
		if(!empty($mist_cate)){
			for($j=0; $j<count($mist_cate); $j++)
			{ 
				$this->db->limit(($mist_cate[$j]['category_percentage'] * $mr['qty_question'] / 100));
				$this->db->where('category_uid',$mist_cate[$j]['uid']);
				$mlist = $this->questionnaires_model->randdom();
				$mist_cate[$j]['questionnaires'] = $mlist;
				for($i=0;$i<count($mist_cate[$j]['questionnaires']);$i++)
				{
					$rand = $this->answer_model->get_questionnaires($mlist[$i]['uid']);

					if(!empty($rand)){

						$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
						$mist_cate[$j]['questionnaires'][$i]['answer'] = array_merge($rand,$rand_0);

					}else{
						$mist_cate[$j]['questionnaires'][$i]['answer'] = $this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
					}
					
				}
	
			}
		}else{
				$mist_cate = array();
				$this->db->limit($mr['qty_question']);
				$this->db->where('test_uid',$_POST['sel_test']);
				$mlist = $this->questionnaires_model->randdom();
				$mist_cate[0]['questionnaires'] = $mlist;
				for($i=0;$i<count($mist_cate[0]['questionnaires']);$i++)
				{
					$rand=$this->answer_model->get_questionnaires($mlist[$i]['uid']);
					if(!empty($rand)){
						
						$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
						$mist_cate[0]['questionnaires'][$i]['answer']=array_merge($rand,$rand_0);

					}else{

						$mist_cate[0]['questionnaires'][$i]['answer']=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);

					}
				}
		}
		
		if(!empty($lesson['id_categories']) && !empty($mist_cate[0]) && $mist_cate[0]['category_percentage'] > 0){
			$m_total =  floor($mr['qty_question'] * $mist_cate[0]['category_percentage'] / 100);
			$m_time  =  floor($mr['time_value'] * $mist_cate[0]['category_percentage'] / 100);
			/* Cap nhat lai so cau hoi vao thoi gian */
			//$mr['m_total'] = $m_total;
			//$mr['m_time']  = $m_time;
			$mr['qty_question'] = $m_total;
			$mr['time_value']   = $m_time;
		}else{
			$total_questions = 0;
			if(!empty($mist_cate)){
				foreach ($mist_cate as $key => $value) {
					$total_questions += floor($mr['qty_question'] * $value['category_percentage'] / 100);
				}
				$mr['qty_question'] = $total_questions;
			}
		}



		$mr['typetest'] = 1;	
		$this->db->where('status',1);
		$mtest = $this->test_model->get();

		$this->template->content->mr      = $mr;
		$this->template->content->mlist   = $mist_cate;
		$this->template->content->mtest   = $mtest ;
		$this->template->content->courses = $courses ;
		$this->template->content->lesson  = $lesson ;
	}
	
	public function getTestCategory($testing_code , $test_uid){
			$view = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/dialog');
			
		    $this->db->where('test_uid',$test_uid);
			$this->db->where('category_percentage >',0);
			$this->db->orderby('parent_id','ASC');
			$this->db->orderby('category','ASC');
			$listcategory = $this->category_model->get();
			$mr = array();  
			foreach($listcategory as $value){
					$parent = $this->category_model->get($value['parent_id']);
					$this->db->where('category_uid',$value['uid']);
					$qtyques = $this->questionnaires_model->get();
					if(!empty($qtyques)){
						if(isset($parent['category']) && $parent['category'] !='')	
							$olist[$value['uid']] = $parent['category'].'-'.$value['category'];
						else
							$olist[$value['uid']] = $value['category'];
					}
			}
			$mr['testing_code'] = $testing_code;
			$mr['test_uid'] = $test_uid;
			if(isset($mr['testing_code'])){
				$this->db->where('testing_code',$mr['testing_code']);
				$testparent = $this->testing_model->get();
				$mr['parent_code']  = $testparent[0]['parent_code'];
			}
			$mr['olist'] = $olist;
			$view->mr = $mr;
			
			$view->render(true);
			
			die();
			
	}
	
	public function testingcategory()
	{
			
		if(!isset($_POST['hd_test'])){
			url::redirect(url::base().'test');
		    die();
		}
		$this->session->delete('sess_save');
		$mr = $this->test_model->get($_POST['hd_test']);
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/test');
		$mist_cate = array();
		if(!empty($_POST['ocategory'])){
			foreach($_POST['ocategory'] as $value){
				$mist_cate[] = $this->category_model->get($value);
			}
		}else{
			$mr['test_title']='Please check full !!';
		}

		$qty_question = 0;
		for($j=0;$j<count($mist_cate);$j++)
		{ 
		    $this->db->limit(($mist_cate[$j]['category_percentage']*$mr['qty_question'])/100);
			$this->db->where('category_uid',$mist_cate[$j]['uid']);
			$mlist = $this->questionnaires_model->randdom();
			$mist_cate[$j]['questionnaires'] = $mlist;
			for($i=0;$i<count($mist_cate[$j]['questionnaires']);$i++)
		    {
		   		// $mist_cate[$j]['questionnaires'][$i]['answer'] 
		   		 $rand=$this->answer_model->get_questionnaires($mlist[$i]['uid']);
					if(!empty($rand)){

						$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
						$mist_cate[$j]['questionnaires'][$i]['answer']=array_merge($rand,$rand_0);

					}else{

						$mist_cate[$j]['questionnaires'][$i]['answer']=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);

					}
				$qty_question++;
		    }
		}
		
		$mr['typetest'] = 3;
		$mr['qty_question'] = $qty_question;
		$mr['parent_id'] = $_POST['parent_id'];
		
		$this->db->where('status',1);
		$mtest = $this->test_model->get();
		$this->template->content->mr = $mr;
		$this->template->content->mlist = $mist_cate;
		$this->template->content->mtest =$mtest ;
	}
	
	public function getChartByCategory($cateuid,$member_uid,$id_lesson='',$id_courses='')
	{
		$courses = $this->courses_model->get($id_courses);

		$this->db->where('category',$cateuid);
		$this->db->where('member_uid',$member_uid);

		if(!empty($courses) && $courses['type'] == 0){
			$this->db->where('id_lesson',$id_lesson);
		}elseif(!empty($courses) && $courses['type'] == 1){
			$this->db->where('id_course',$courses['id']);
		}

		$list = $this->testing_category_model->get();
		//$this->show_arr($list);
		foreach($list as $key=> $value){
			$name = $this->category_model->get($value['category']);
			$list[$key]['name']     = $name['category'];
			$list[$key]['str_date'] = $this->format_int_date($list[$key]['testing_date'],'m/d/Y H:i');
		}
		
		echo( json_encode($list));
		die();
	}
	public function getChartCategory($testing_code,$member_uid)
	{
		$this->db->where('testing_code',$testing_code);
		$this->db->where('member_uid',$member_uid);
		$list = $this->testing_category_model->get();
		foreach($list as $key=> $value){
			$category = $this->category_model->get($value['category']);
			if(!empty($category)){	
				$parent = $this->category_model->get($category['parent_id']);
				$list[$key]['name'] =  $parent['category'].'-'.$category['category'];
			}
		}
		
		echo( json_encode($list));
		die();
	}
	
	public function getChartTest($test_uid,$member_uid,$id_lesson='',$courses='')
	{

		$this->db->where('test_uid',$test_uid);
		if(!empty($courses) && $courses['type'] == 0){
			$this->db->where('id_lesson',$id_lesson);
		}elseif(!empty($courses) && $courses['type'] == 1){
			$this->db->where('id_course',$courses['id']);
		}
		$chartlist = $this->testing_model->getTestingByChart('member_uid',$member_uid);
		//echo $this->db->last_query();
		//die();
		foreach($chartlist as $key => $value){
			$chartlist[$key]['test']  = $this->test_model->get($value['test_uid']);
		}
		return $chartlist;
	}
	
	public function testingwrong(){
		// $this->show_arr($_POST);
		// die();
		if(!isset($_POST['hd_test'])){
			url::redirect(url::base().'courses');
		    die();
		}

		$this->session->delete('sess_save');
		$mr = $this->test_model->get($_POST['hd_test']);
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/test');
		
		$id_courses = $this->input->post('txt_id_courses');
		$id_lesson  = $this->input->post('txt_id_lesson');
		
		$lesson     = $this->lesson_model->get($id_lesson);
		$courses    = $this->courses_model->get($id_courses);
		
		$mist_cate = array();
		$this->db->where('result',0);
		$this->db->where('member_uid',$this->sess_cus['id']);

		if(!empty($courses) && $courses['type'] == 0){
			$this->db->where('id_lesson',$id_lesson);
		}elseif(!empty($courses) && $courses['type'] == 1){
			$this->db->where('id_course',$courses['id']);
		}
		$this->db->where('testing_code',$_POST['testing_code']);
		$arrayquestion = $this->testingdetail_model->get();
		
		//$this->show_arr($arrayquestion);

		$arraycategory = array();
		
		foreach($arrayquestion as $value){
			$questionnaires = $this->questionnaires_model->get($value['questionnaires_uid']);
			if(isset($questionnaires['category_uid']) && !in_array($questionnaires['category_uid'],$arraycategory)){
					$mist_cate[] = $this->category_model->get($questionnaires['category_uid']);
					$arraycategory[] =  $questionnaires['category_uid'];
			}
			
		}
		
		$mr['typetest'] = 2;

		$question =array();
		foreach($arrayquestion as $value){
			$question[] = $this->questionnaires_model->get($value['questionnaires_uid']);
		}
		
		$qty_question = 0;
		foreach($mist_cate as $key => $val)
		{ 	
				
				foreach($question as $value){
				
					if(isset($val['uid']) && $val['uid'] == $value['category_uid']){
						$mist_cate[$key]['questionnaires'][] = $value;
						for($i=0;$i<count($mist_cate[$key]['questionnaires']);$i++)
						{
							// $mist_cate[$key]['questionnaires'][$i]['answer'] =
							$rand= $this->answer_model->get_questionnaires($mist_cate[$key]['questionnaires'][$i]['uid']);
							if(!empty($rand)){

								$rand_0=$this->answer_model->get_questionnaires_zero($mist_cate[$key]['questionnaires'][$i]['uid']);
								$mist_cate[$key]['questionnaires'][$i]['answer']=array_merge($rand,$rand_0);

							}else{

								$mist_cate[$key]['questionnaires'][$i]['answer']=$this->answer_model->get_questionnaires_zero($mist_cate[$key]['questionnaires'][$i]['uid']);

							}
						}
						$qty_question++;
						
					}
				
					
				}
				
				if($val== ''){
					foreach($question as $value){
				        if(!empty($value)){
							$mist_cate[0]['questionnaires'][] = $value;
							
								for($i=0;$i<count($mist_cate[0]['questionnaires']);$i++)
								{
									// $mist_cate[0]['questionnaires'][$i]['answer'] = 
									$rand= $this->answer_model->get_questionnaires($mist_cate[0]['questionnaires'][$i]['uid']);
									if(!empty($rand)){

										$rand_0=$this->answer_model->get_questionnaires_zero($mist_cate[0]['questionnaires'][$i]['uid']);
										$mist_cate[$key]['questionnaires'][$i]['answer']=array_merge($rand,$rand_0);

									}else{

										$mist_cate[$key]['questionnaires'][$i]['answer']=$this->answer_model->get_questionnaires_zero($mist_cate[0]['questionnaires'][$i]['uid']);

									}
								}
							$qty_question++; 
							}
					}
					break;
				}
				
		}

		if(!empty($lesson['id_categories'])){
			$m_cate = $this->category_model->get($lesson['id_categories']);
			$mr['time_value'] =  floor($mr['time_value'] * $m_cate['category_percentage'] / 100);
		}

		$mr['qty_question'] = $qty_question;
		$mr['parent_id'] = $_POST['parent_id'];
		
		$this->db->where('status',1);
		$mtest = $this->test_model->get();

		$this->template->content->mr      = $mr;
		$this->template->content->mlist   = $mist_cate;
		$this->template->content->mtest   = $mtest;
		
		$this->template->content->lesson  = $lesson;
		$this->template->content->courses = $courses;

	}
	
	public function resulttest(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/resulttesting');
		if($_POST && !($this->session->get('sess_save'))){
			$id_courses      = $this->input->post('txt_id_courses');
			$id_lesson       = $this->input->post('txt_id_lesson');

			$lesson  = $this->lesson_model->get($id_lesson);
			$courses = $this->courses_model->get($id_courses);

			$pass            = 0;
			$fail            = 0;
			$arraycategory   = array();
			$arraycategory[] = null ;
			$categorylimit   = $_POST['category'];

			for($i = 0; $i < count($_POST['hd_question']); $i++){
			    if(isset($_POST['radio'.$_POST['hd_question'][$i]]) && $_POST['radio'.$_POST['hd_question'][$i]] == 1){
					$arr_uid = explode('|',$_POST['radio'.$_POST['hd_question'][$i]]);
					if(isset($arr_uid[0]) && $arr_uid[0] == 1){
						if(!isset($arraycategory[$arr_uid[2]]))
							$arraycategory[$arr_uid[2]] = 1;
						else
							$arraycategory[$arr_uid[2]] += 1;
						$pass++;
					}else{
						$fail++;
						$arraycategory[] = 0;
					}
				} 
				else{
					$fail++;
				}
			}

			if($_POST['hd_type']==0)
				$mr['timeduration'] = $_POST['hd_timeduration'];
			else{
				$time = $_POST['hd_duration'];
				if($_POST['hd_timeduration'] == '00:00:00')
					$mr['timeduration'] = gmdate("H:i:s",  $time*60);
				else{
			    	$time_redmain = $this->seconds($_POST['hd_timeduration']);
					$mr['timeduration'] = gmdate("H:i:s",($time*60)-$time_redmain);
				}
			}

			$percentcategory = array();
			foreach($categorylimit as $value){
				$temp = explode('|',$value);
				foreach($arraycategory as $key => $value){
					if($temp[0] == $key){
						$percentcategory[$temp[0]] = array($value,$temp[1]);
						break;
					}else 
						$percentcategory[$temp[0]] = array(0,$temp[1]);
				} 
			}
			
			$questionnaires = array();
			foreach($_POST['hd_question'] as $value){
				$questionnaires = $this->questionnaires_model->get($_POST['hd_question']);
			}

			$mlist     = array();
			$chartlist = array();
			$olist     = array();

			foreach($percentcategory as $key => $value){
			   	if($value[1] != 0){
					$category = $this->category_model->get($key);
					if(isset($category['parent_id'])){
						$parent = $this->category_model->get($category['parent_id']);
						$value[] = $category['uid'];
						foreach($questionnaires as $key => $val){
							$this->db->where('questionnaires_uid',$val['uid']);
							$this->db->where('type',1);
							$answer = $this->answer_model->get();
							
							$questionnaires[$key]['answer'] = isset($answer[0]['answer'])?$answer[0]['answer']:'';
							$questionnaires[$key]['images'] = isset($answer[0]['images'])?$answer[0]['images']:'';
						 	if($val['category_uid'] == $category['uid']){
								$value['answer'][$key] = $questionnaires[$key];
								$dhpost = isset($_POST['radio'.$questionnaires[$key]['uid']])?$_POST['radio'.$questionnaires[$key]['uid']]:'';
								if(!empty($dhpost)){
									$temp = explode('|',$dhpost);
									$hasanswer = $this->answer_model->get($temp[1]);
									$value['answer'][$key]['has'] = isset($hasanswer['answer'])?$hasanswer['answer']:'';
									$value['answer'][$key]['hasimages'] = isset($hasanswer['images'])?$hasanswer['images']:'';
								}else{
									$value['answer'][$key]['has']  = '';
									$value['answer'][$key]['hasimages'] = '';
								}
							}
						}
						$mlist[(!empty($parent['category'])?$parent['category'].'-':'').$category['category']] = $value;
						$chartlist[$category['uid']] = ($value[0]*100)/$value[1];
					}
				}
			}
			$this->db->where('member_uid',$this->sess_cus['id']);
			$this->db->where('test_uid',$_POST['hd_test']);
			if(!empty($courses) && $courses['type'] == 0){
				$this->db->where('id_lesson',$id_lesson);
			}elseif(!empty($courses) && $courses['type'] == 1){
				$this->db->where('id_course',$courses['id']);
			}

			$stt_no = $this->testing_model->get_code($this->format_str_date(date('m/d/Y'),$this->site['site_short_date']));
			$testing_code = date('ymd').'-'.$stt_no;
			
			//get number question of test
			$test_main = $this->test_model->get($_POST['hd_test']);
			if(!empty($lesson['id_categories'])){
				$m_cate = $this->category_model->get($lesson['id_categories']);
				$number_question =  floor($test_main['qty_question'] * $m_cate['category_percentage'] / 100);
			}else{
				$this->db->where('test_uid',$test_main['uid']);
				$this->db->where('category_percentage >=',0);
				$mlist_cate = $this->category_model->get();
				$total_questions = 0;

				if(!empty($mlist_cate)){
					foreach ($mlist_cate as $key => $value) {
						$total_questions += floor($test_main['qty_question'] * $value['category_percentage'] / 100);
					}
				}

				$number_question = !empty($total_questions)?$total_questions:count($_POST['hd_question']);
			}
			
			//echo $number_question;

			$mr['fail']      = round(($fail*100)/$number_question,1);
			// '<br>';
			$mr['pass']      = round(($pass*100)/$number_question,1);

			if(isset($_POST['parent_id']) && $_POST['parent_id']!='')
				$mr['parent_id'] = $_POST['parent_id'];
			else
			  	$mr['parent_id'] = $testing_code;
			
			/**
			  * Save data test
			  */ 
			$this->save($mr['timeduration'],$mr['pass'],$chartlist,$testing_code,$mr['parent_id'],$_POST['typetest']);
			
			$this->db->limit(1);
			$this->db->where('testing_code',$testing_code);
			$this->db->where('member_uid',$this->sess_cus['id']);
			if(!empty($courses) && $courses['type'] == 0){
				$this->db->where('id_lesson',$id_lesson);
			}elseif(!empty($courses) && $courses['type'] == 1){
				$this->db->where('id_course',$courses['id']);
			}

			$mr['last_test'] = $this->testing_model->get(); 
			$scoreparent = 0;
			if(isset($mr['parent_id'])){
				$this->db->where('parent_code',$mr['parent_id']);
				$this->db->orderby('uid','desc');
				$testing_parent = $this->testing_model->get();
				if(isset($testing_parent[0]['parent_score'])){
					if($testing_parent[0]['testing_type'] == 2){
						$scoreparent = ($testing_parent[0]['parent_score'] + $testing_parent[0]['testing_score']);
					}
					else{
						$scoreparent = $testing_parent[0]['parent_score'];
					}
				}
			}
			if($scoreparent >= 99.9)
				$scoreparent = 100;
			/**
			 * cap nhat neu pass lesson
			 * get lesson kiem tra
			 */
			if(!empty($courses) && $courses['type'] == 0){
				if(!empty($lesson)){
					if($scoreparent >= $lesson['percent_test_pass']){
						$this->db->where('id_member',$this->sess_cus['id']);
						$this->db->where('id_lesson',$id_lesson);
						$study = $this->study_model->get();
						if(!empty($study[0])){
							$m_study              = ORM::factory('study_orm',$study[0]['id']);
							$m_study->lesson_pass = 'Y';
							$m_study->save();
						}
					}
				}
			}elseif (!empty($courses) && $courses['type'] == 1) {
				/**
				 * get data test
				 */
				$data_test = $this->test_model->get($_POST['hd_test']);
				// $this->show_arr($data_test);
				// die();
				if(!empty($data_test)){
					$m_scores_pass = !empty($data_test['pass_score'])?$data_test['pass_score']:0;
					if($scoreparent >= $m_scores_pass){
						$this->db->where('id_member',$this->sess_cus['id']);
						$this->db->where('id_course',$id_courses);
						$study = $this->study_model->get();
						if(!empty($study[0])){
							$m_study              = ORM::factory('study_orm',$study[0]['id']);
							$m_study->course_pass = 'Y';
							$m_study->save();
						}
					}
				}
				
			}
			
			//}

			$mr['last_test'][0]['test'] = $this->test_model->get($mr['last_test'][0]['test_uid']);
			
			$this->session->set('sess_save',$testing_code);
			 	  
		    $this->db->where('test_uid',$mr['last_test'][0]['test_uid']);
		    $this->db->where('category_percentage >',0);
		    $this->db->orderby('parent_id','ASC');
		    $this->db->orderby('category','ASC');
						
			$listcategory = $this->category_model->get();
			  
			foreach($listcategory as $value){
				//$category = $this->category_model->get($value['uid']);
				$parent = $this->category_model->get($value['parent_id']);
				$this->db->where('category_uid',$value['uid']);
				$qtyques = $this->questionnaires_model->get();
				if(!empty($qtyques)){
					if(isset($parent['category']) && $parent['category'] !='')	
						$olist[$value['uid']] = $parent['category'].'-'.$value['category'];
					else
						$olist[$value['uid']] = $value['category'];
				}
			}
			 
			$mr['idtest']       = $_POST['hd_test'];
			$mr['mlist']        = $mlist;
			$mr['olist']        = $olist;
			$mr['testing_code'] = $testing_code;
			
			/*  
			$this->db->where('result',0);
			$this->db->where('testing_code',$testing_code);
			$this->db->where('member_uid',$this->sess_cus['id']);
			if(!empty($courses) && $courses['type'] == 0){
				$this->db->where('id_lesson',$id_lesson);
			}elseif(!empty($courses) && $courses['type'] == 1){
				$this->db->where('id_course',$courses['id']);
			}

			$arrayquestion = $this->testingdetail_model->get();
			foreach($arrayquestion as $key => $value){
				$this->db->where('questionnaires_uid',$value['questionnaires_uid']);
				$this->db->where('type',1);
				$answer                                 = $this->answer_model->get();
				$mr['arrayquestion'][$key]              = $this->questionnaires_model->get($value['questionnaires_uid']);
				$mr['arrayquestion'][$key]['answer']    = isset($answer[0]['answer'])?$answer[0]['answer']:'';
				$mr['arrayquestion'][$key]['images']    = isset($answer[0]['images'])?$answer[0]['images']:'';
				$hasanswer                              = $this->answer_model->get($value['selected_answer']);
				$mr['arrayquestion'][$key]['hasanswer'] = isset($hasanswer['answer'])?$hasanswer['answer']:'';
				$mr['arrayquestion'][$key]['hasimages'] = isset($hasanswer['images'])?$hasanswer['images']:'';
			}
			*/
			/**
			 * kiem tra ket thuc courses
			 */
			if (!empty($courses) && $courses['type'] == 0 && !empty($courses['id_certificate'])) {
				if($this->check_finish_courses($id_courses)){
					/**
					 * add certificate member
					 */
					if(!empty($courses)){
						// Check certificate
						$this->db->where('member_uid',$this->sess_cus['id']);
						$this->db->where('certificate_id',$courses['id_certificate']);
						$this->db->orderby('id','desc');
						$certificate = $this->member_certificate_model->get();
						// check payment
						$arraypayment = array();
						$this->db->where('member_uid',$this->sess_cus['id']);
						$this->db->where('courses_id',$id_courses);
						$payment = $this->payment_model->get();
						foreach($payment as $value){
							if((isset($value['daytest']) && strtotime("-". $value['daytest'] ." day" ) <= $value['payment_date']) || (isset($value['daytest']) && $value['daytest']==0)){		
								$arraypayment[] =  $value['uid'];
							}
						}
						
						if(empty($certificate[0])){
							/**
							 * add certificate
							 */
							$m_certificate                 = ORM::factory('member_certificate_orm');
							$m_certificate->member_uid     = $this->sess_cus['id'];
							$m_certificate->id_courses     = $id_courses;
							$m_certificate->certificate_id = $courses['id_certificate'];
							$m_certificate->date           = $this->format_str_date(date('m/d/Y'),$this->site['site_short_date'],'/',date('H'),date('i'),date('s'));
							$m_certificate->id_payment     = !empty($arraypayment[0])?$arraypayment[0]:'';
							$m_certificate->save();
						}else{
							if($certificate[0]['id_payment'] != $arraypayment[0]){
								/**
								 * add certificate
								*/
								$m_certificate                 = ORM::factory('member_certificate_orm');
								$m_certificate->member_uid     = $this->sess_cus['id'];
								$m_certificate->id_courses     = $id_courses;
								$m_certificate->certificate_id = $courses['id_certificate'];
								$m_certificate->date           = $this->format_str_date(date('m/d/Y'),$this->site['site_short_date'],'/',date('H'),date('i'),date('s'));
								$m_certificate->id_payment     = !empty($arraypayment[0])?$arraypayment[0]:'';
								$m_certificate->save();
							}	
						}
					}
				}
			}

			//check study lesson
			$this->db->where('id_member',$this->sess_cus['id']);
			if(!empty($courses) && $courses['type'] == 0){
				$this->db->where('id_lesson',$id_lesson);
			}elseif(!empty($courses) && $courses['type'] == 1){
				$this->db->where('id_course',$courses['id']);
			}
			$study = $this->study_model->get();

			$chartlist = $this->getChartTest($mr['idtest'],$this->sess_cus['id'],$id_lesson,$courses);

			$this->template->content->chartlist   = $chartlist;
			$this->template->content->mr          = $mr;
			$this->template->content->scoreparent = $scoreparent;
			
			$this->template->content->lesson      = $lesson;
			$this->template->content->courses     = $courses;
			$this->template->content->study       = $study;
		}
		else
		  url::redirect(url::base());
	}
	
	public function seconds($time){
		$time = explode(':', $time);
		return ($time[0]*3600) + ($time[1]*60) + $time[2];
	}
	
	public function test_cron(){
		echo 'test_cron';
		die();
	}

	public function save($duration,$score,$category,$testing_code,$parent_code,$type='1'){
		$parent_score = $score;
		$id_courses   = $this->input->post('txt_id_courses');
		$id_lesson    = $this->input->post('txt_id_lesson');
		$courses      = $this->courses_model->get($id_courses);
		// $this->show_arr($courses);
		// echo $id_courses.'<br>';
		// echo $id_lesson.'<br>';
		// die();
		if($type == 2){
			$this->db->where('parent_code',$parent_code);
			$this->db->orderby('uid','desc');
			$testing_parent = $this->testing_model->get();
			if(isset($testing_parent[0]['parent_score'])){
				if($testing_parent[0]['testing_type'] == 2)
					$parent_score = $testing_parent[0]['parent_score'] + $testing_parent[0]['testing_score'];
				else
					$parent_score = $testing_parent[0]['parent_score'];
			}
			$m_d = (double)$score + (double)$parent_score;
			$m_b = (double)99.9;
			$a   = trim((string)$m_d);
			if((double)$a >= (double)$m_b){
				$m_d = 100 - $m_d;
				if($m_d >= 0)
					$score = ($score + abs($m_d));
				else
					$score = ($score - abs($m_d));
			}
		}else{
			$score = 0;
		}
		//die();
		if(!empty($courses) && $courses['type'] == 0){
			$testing_date = $this->format_str_date(date('m/d/Y'),$this->site['site_short_date'],'/',date('H'),date('i'),0);
			$record = array(
				'parent_code'   => $parent_code,
				'testing_code'  => $testing_code,	        	
				'test_uid'      => $_POST['hd_test'],
				'member_uid'    => $this->sess_cus['id'],
				'id_lesson'     => $id_lesson,
				'testing_date'  => $testing_date,
				'testing_type'  => $_POST['typetest'],
				'testing_score' => $score,
				'parent_score'  => $parent_score,
				'duration'      => $this->seconds($duration), 
		    );
		    $this->db->insert('testing',$record);

		    /**
			 * save testing detail
			 */
			$this->savedetail($testing_code,$id_lesson,$courses['id'],0);
			/**
			 * save testing category 
			 */
			$this->saveCategory($testing_code,$testing_date,$category,$_POST['hd_test'],$id_lesson,$courses['id'],0);
		}elseif(!empty($courses) && $courses['type'] == 1){
			$testing_date = $this->format_str_date(date('m/d/Y'),$this->site['site_short_date'],'/',date('H'),date('i'),0);
			$record = array(
				'parent_code'   => $parent_code,
				'testing_code'  => $testing_code,	        	
				'test_uid'      => $_POST['hd_test'],
				'member_uid'    => $this->sess_cus['id'],
				'id_course'     => $courses['id'],
				'testing_date'  => $testing_date,
				'testing_type'  => $_POST['typetest'],
				'testing_score' => $score,
				'parent_score'  => $parent_score,
				'duration'      => $this->seconds($duration), 
		    );
		    $this->db->insert('testing',$record);
		    /**
			 * save testing detail
			 */
			$this->savedetail($testing_code,$id_lesson,$courses['id'],1);
			/**
			 * save testing category 
			 */
			$this->saveCategory($testing_code,$testing_date,$category,$_POST['hd_test'],$id_lesson,$courses['id'],1);
		}
		
		/*
		* Phan send mail

		if($_SERVER["HTTP_HOST"] != "localhost"){
			require_once('PHPMailer_v5.1/class.phpmailer.php');
			$this->member_model = new Member_Model(); 
			$member =  $this->member_model->get($this->sess_cus['id']);
			$member_email = explode('@',$this->sess_cus['email']);
			if($member['send_mail']==0){
				if(isset($member_email[1]) && ($member_email[1]=='hotmail.com' || $member_email[1]=='live.com' || $member_email[1]=='outlook.com')) 
				{
					$send = $this->send_email_testing($record);
					if(!$send)
						$this->send_email_testing($record);
				}
				else{
				   $this->send_email_testing($record);
				}
				if(isset($member['company_contact_email']) && !empty($member['company_contact_email']) 
														   && strlen(trim($member['company_contact_email'])) > 0)
				{
					 $company_email = explode('@',$member['company_contact_email']);
					 if(isset($company_email[1]) && ($company_email[1]=='hotmail.com' 
													|| $company_email[1]=='live.com' || 
													$company_email[1]=='outlook.com')) 
					{
					  $sendcompany = $this->send_email_company_testing($record);
					  if(!$sendcompany)
					   $this->send_email_company_testing($record);
					}
					else{
					   $this->send_email_company_testing($record);
					}
				}
			}
			
		}
		
		* Phan send mail
		*/
	}

	private function saveCategory($testing_code,$testing_date,$category,$test,$id_lesson,$id_course,$type=0){
		foreach($category as $key=>$value){
			if($type == 0){
				$this->db->insert(
					'testing_category',
					array(
						'category'     => $key,
						'test'         => $test,
						'percentage'   => $value,
						'testing_code' => $testing_code,
						'testing_date' => $testing_date,
						'member_uid'   => $this->sess_cus['id'],
						'id_lesson'    => $id_lesson
					)
				);
			}elseif($type == 1){
				$this->db->insert(
					'testing_category',
					array(
						'category'     => $key,
						'test'         => $test,
						'percentage'   => $value,
						'testing_code' => $testing_code,
						'testing_date' => $testing_date,
						'member_uid'   => $this->sess_cus['id'],
						'id_course'    => $id_course
					)
				);
			}
		}
	}
	
	public function savedetail($testing_code,$id_lesson,$id_course,$type=0){
		for($i=0;$i<count($_POST['hd_question']);$i++){
			if(isset($_POST['radio'.$_POST['hd_question'][$i]]) && !empty($_POST['radio'.$_POST['hd_question'][$i]]))
			 {
				$arr_uid = explode('|',$_POST['radio'.$_POST['hd_question'][$i]]);
				if(isset($arr_uid[0]) && $arr_uid[0]==1)
				$result=1;
				else $result=0;
			 	
			}else{
				$result     = 0;
				$arr_uid[1] = 0;
			}
			
			if($type == 0){
				$this->db->insert(
					'testing_detail',
					array(
						'testing_code'       => $testing_code,
						'member_uid'         => $this->sess_cus['id'],
						'id_lesson'          => $id_lesson,
						'questionnaires_uid' => $_POST['hd_question'][$i],
						'selected_answer'    => isset($arr_uid[1])?$arr_uid[1]:"",
						'result'             => $result
				    )
				);
			}elseif($type == 1) {
				$this->db->insert(
					'testing_detail',
					array(
						'testing_code'       => $testing_code,
						'member_uid'         => $this->sess_cus['id'],
						'id_course'          => $id_course,
						'questionnaires_uid' => $_POST['hd_question'][$i],
						'selected_answer'    => isset($arr_uid[1])?$arr_uid[1]:"",
						'result'             => $result
				    )
				);
			}  
			
		}
	}

	private function send_email_company_testing($record){
		require_once Kohana::find_file('views/mailgun','init');

		$html_content = $this->data_template_model->get_value('EMAIL_TESTING_COMPANY');
		if(isset($this->sess_cus['id'])){
			 $this->member_model = new Member_Model(); 
			 $member =  $this->member_model->get($this->sess_cus['id']);
			 if(!empty($member) && strlen(trim($member['company_contact_email'])) > 0){
						
				 $mailcompany = $member['company_contact_email'];	
				 
				 
				 if(strlen(trim($member['company_contact_name']))>0){
				 		$company = $member['company_contact_name'];	
				 }
				 elseif(strlen(trim($member['company_name'])) > 0){
							$company = $member['company_name'];	
					 }
					 else{
							$company = $mailcompany;	
					 }	
				 	
				 if(strlen(trim($member['member_fname']))>0){
				 		$name = $member['member_fname'].' '.$member['member_lname'];
				 }else {
						$name = $member['member_email'];
				 }
				 
				$html_content = str_replace('#company#',$company,$html_content);	
				
				$score = 0;
				if($record['testing_type'] == 1){
					$score = $record['parent_score'];
				}else{
					$score = $record['testing_score'];
				}

				//if(isset($record['testing_score']) && !empty($record['testing_score']))	
				$test =  $this->test_model->get($record['test_uid']);
					$html_content = str_replace('#test#',isset($test['test_title'])?$test['test_title']:'',$html_content);
				$html_content = str_replace('#date#',  $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
				$html_content = str_replace('#name#',$name,$html_content);
				$html_content = str_replace('#email#',$member['member_email'],$html_content);
				$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
				$html_content = str_replace('#score#',$score,$html_content);
			    $html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
				$html_content = str_replace('#site#',$this->site['site_name'],$html_content);

				$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
					array(
						'from'       => MAIL_FROM,
						'to'         => $mailcompany,
						//'h:Reply-To' => $email_send,
						'subject'    => $subject,
						'html'       => $html_content
			        ));
				if($result_send->http_response_body->message == 'Queued. Thank you.'){
					return true;
				}else{
					return false;
				}
			}
		}
	}

	private function send_mail_company_outlook($record){
		
	    $html_content = $this->data_template_model->get_value('EMAIL_TESTING_COMPANY');
		if(isset($this->sess_cus['id'])){
			 $this->member_model = new Member_Model(); 
			 $member =  $this->member_model->get($this->sess_cus['id']);
			 if(!empty($member) && strlen(trim($member['company_contact_email'])) > 0){
						
				 $mailcompany = $member['company_contact_email'];	
				 
				 
				 if(strlen(trim($member['company_contact_name']))>0){
				 		$company = $member['company_contact_name'];	
				 }
				 elseif(strlen(trim($member['company_name'])) > 0){
							$company = $member['company_name'];	
					 }
					 else{
							$company = $mailcompany;	
					 }	
				 	
				 if(strlen(trim($member['member_fname']))>0){
				 		$name = $member['member_fname'].' '.$member['member_lname'];
				 }else {
						$name = $member['member_email'];
				 }
				 
				$html_content = str_replace('#company#',$company,$html_content);	
				
				//if(isset($record['testing_score']) && !empty($record['testing_score']))	
				$test =  $this->test_model->get($record['test_uid']);
					$html_content = str_replace('#test#',isset($test['test_title'])?$test['test_title']:'',$html_content);
				$html_content = str_replace('#date#',  $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
				$html_content = str_replace('#name#',$name,$html_content);
				$html_content = str_replace('#email#',$member['member_email'],$html_content);
				$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
				$html_content = str_replace('#score#',$record['testing_score'],$html_content);
			    $html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
				$html_content = str_replace('#site#',$this->site['site_name'],$html_content);
				
				$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
				$mail->IsSendmail(); // telling the class to use SendMail transport
				$mail->IsHTML(true);
			  
				$mail->IsSMTP();
				$mail->CharSet="windows-1251";
				$mail->CharSet="utf-8";
				try {
					//$mail->Host = 'smtp.gmail.com';
					$mail->Host = 'pestest.com';
					$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
					$mail->SMTPAuth = true;
					$mail->Port = 465; 
					$mail->SMTPDebug  = 0; 
					$arr_email = explode('@',$mailcompany);
					if(isset($arr_email[1]) && $arr_email[1]=='gmail.com')
					{
					$mail->Host = 'smtp.gmail.com';
					$gmail = array('pestest01@gmail.com','pestest02@gmail.com','pestest03@gmail.com');
					$mail->Username = $gmail[array_rand($gmail)];
					$mail->Password = '#this&is4u#'; 
					$mail->From="support@pestest.com";
					$mail->FromName="PesTest.com";
					$mail->Sender="support@pestest.com";
					}
					else{
					$mail->Host = 'pestest.com';
					$mail->Username = 'info@pestest.com';
					$mail->Password = '#pestest2014#'; 
					$mail->From="support@pestest.com";
					$mail->FromName="PesTest.com";
					$mail->Sender="support@pestest.com";
					}
					$mail->SetFrom($this->site['site_email'],'Pestest.com');
					$mail->AddAddress($mailcompany);
					
					$mail->Subject = 'Testing '.$this->site['site_name'];
					$mail->Body = $html_content;
			   		if($mail->Send()){
		    			return true;
					}else{
						return false;
					}
			
				} catch (phpmailerException $e) {
					//echo $e->errorMessage(); //Pretty error messages from PHPMailer
				} catch (Exception $e) {
					//echo $e->getMessage(); //Boring error messages from anything else!
				}		

			}
		}		 
	}
	
	private function send_email_testing($record){
		require_once Kohana::find_file('views/mailgun','init');

		$html_content = $this->data_template_model->get_value('EMAIL_TESTING');
		     if(isset($this->sess_cus['name']) && !empty($this->sess_cus['name']))	
			 $name = $this->sess_cus['name'];
			 else $name = $this->sess_cus['email'];
			 
		$html_content = str_replace('#name#',$name,$html_content);
		$score = 0;
		if($record['testing_type'] == 1){
			$score = $record['parent_score'];
		}else{
			$score = $record['testing_score'];
		}

		$test =  $this->test_model->get($record['test_uid']);
		$html_content = str_replace('#test#',isset($test['test_title'])?$test['test_title']:'',$html_content);
		$html_content = str_replace('#date#', $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
		$html_content = str_replace('#score#',$score,$html_content);
		$html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
		$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
		$html_content = str_replace('#site#',$this->site['site_name'],$html_content);

		$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
			array(
				'from'       => MAIL_FROM,
				'to'         => $this->sess_cus['email'],
				//'h:Reply-To' => $email_send,
				'subject'    => 'Testing '.$this->site['site_name'],
				'html'       => $html_content
	        ));
		if($result_send->http_response_body->message == 'Queued. Thank you.'){
			return true;
		}else{
			return false;
		}
	}
	private function send_email_outlook($record)
    {
		
  		$html_content = $this->data_template_model->get_value('EMAIL_TESTING');
		     if(isset($this->sess_cus['name']) && !empty($this->sess_cus['name']))	
			 $name = $this->sess_cus['name'];
			 else $name = $this->sess_cus['email'];
			 
		$html_content = str_replace('#name#',$name,$html_content);	
	
		//if(isset($record['testing_score']) && !empty($record['testing_score']))	
		$test =  $this->test_model->get($record['test_uid']);
			$html_content = str_replace('#test#',isset($test['test_title'])?$test['test_title']:'',$html_content);
		$html_content = str_replace('#date#', $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
		$html_content = str_replace('#score#',$record['testing_score'],$html_content);
		$html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
		$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
		$html_content = str_replace('#site#',$this->site['site_name'],$html_content);
		
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	    $mail->IsSendmail(); // telling the class to use SendMail transport
	    $mail->IsHTML(true);
	  
	    $mail->IsSMTP();
	    $mail->CharSet="windows-1251";
	    $mail->CharSet="utf-8";
	    try {
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->SMTPAuth = true;
			$mail->Port = 465; 
			$mail->SMTPDebug  = 0; 
			$arr_email = explode('@',$this->sess_cus['email']);
			if(isset($arr_email[1]) && $arr_email[1]=='gmail.com')
			{
				$mail->Host = 'smtp.gmail.com';
				$gmail = array('pestest01@gmail.com','pestest02@gmail.com','pestest03@gmail.com');
				$mail->Username = $gmail[array_rand($gmail)];
				$mail->Password = '#this&is4u#'; 
				$mail->From="support@pestest.com";
			   $mail->FromName="PesTest.com";
			   $mail->Sender="support@pestest.com";
			}
			else{
				$mail->Host = 'pestest.com';
				$mail->Username = 'info@pestest.com';
				$mail->Password = '#pestest2014#'; 
				$mail->From="support@pestest.com";
		    	$mail->FromName="PesTest.com";
			    $mail->Sender="support@pestest.com";
			}
			$mail->SetFrom($this->site['site_email'],'Pestest.com');
		    $mail->AddAddress($this->sess_cus['email']);
		   	// $mail->AddAddress($this->site['site_email']);
			
		    $mail->Subject = 'Testing '.$this->site['site_name'];
		    $mail->Body = $html_content;
	  		if($mail->Send()){
		    	return true;
			}else{
	    		return false;
			}
	    } catch (phpmailerException $e) {
	  	    //echo $e->errorMessage(); //Pretty error messages from PHPMailer
	    } catch (Exception $e) {
	  	    //echo $e->getMessage(); //Boring error messages from anything else!
	    }		

    }
	
	public function send_mail_company($record){
		
		$swift = email::connect();
		 
		//From, subject
		$from = $this->site['site_email'];
		$subject = 'Testing '.$this->site['site_name'];
		
		//HTML message
		$html_content = $this->data_template_model->get_value('EMAIL_TESTING_COMPANY');
	    //Replate content
		     if(isset($this->sess_cus['name']) && !empty($this->sess_cus['name']))	
			 $name = $this->sess_cus['name'];
			 else $name = $this->sess_cus['email'];
			 
		$html_content = str_replace('#name#',$name,$html_content);	
		
		if(isset($this->sess_cus['id'])){
			 $this->member_model = new Member_Model(); 
			 $member =  $this->member_model->get($this->sess_cus['id']);
			 if(!empty($member) && strlen(trim($member['company_contact_email'])) > 0){
						
				 $mailcompany = $member['company_contact_email'];	
				 
				 
				 if(strlen(trim($member['company_contact_name']))>0){
				 		$company = $member['company_contact_name'];	
				 }
				 elseif(strlen(trim($member['company_name'])) > 0){
							$company = $member['company_name'];	
					 }
					 else{
							$company = $mailcompany;	
					 }	
				 	
				 if(strlen(trim($member['member_fname']))>0){
				 		$name = $member['member_fname'].' '.$member['member_lname'];
				 }else {
						$name = $member['member_email'];
				 }
				 
				$html_content = str_replace('#company#',$company,$html_content);	
				
				//if(isset($record['testing_score']) && !empty($record['testing_score']))	
				$test =  $this->test_model->get(4);
					$html_content = str_replace('#test#',isset($test['test_title'])?$test['test_title']:'',$html_content);
				$html_content = str_replace('#date#',  $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
				$html_content = str_replace('#name#',$name,$html_content);
				$html_content = str_replace('#email#',$member['member_email'],$html_content);
				$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
				$html_content = str_replace('#score#',$record['testing_score'],$html_content);
			    $html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
				$html_content = str_replace('#site#',$this->site['site_name'],$html_content);
					
				//Build recipient lists
				$recipients = new Swift_RecipientList;
				$recipients->addTo($mailcompany);
				 
				//Build the HTML message
				$message = new Swift_Message($subject, $html_content, "text/html");
		
				
				if($swift->send($message, $recipients, $from)){
				
				} else {
					
				}	
				// Disconnect
				$swift->disconnect();	
		
			}
		}
	}
	
	private function send_email($record)
    {
    	//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		$from = $this->site['site_email'];
		$subject = 'Testing '.$this->site['site_name'];
		
		//HTML message
		$html_content = $this->data_template_model->get_value('EMAIL_TESTING');
		//Replate content
		     if(isset($this->sess_cus['name']) && !empty($this->sess_cus['name']))	
			 $name = $this->sess_cus['name'];
			 else $name = $this->sess_cus['email'];
			 
		$html_content = str_replace('#name#',$name,$html_content);	
		
		$test =  $this->test_model->get($record['test_uid']);
			$html_content = str_replace('#test#',$test['test_title'],$html_content);
		$html_content = str_replace('#date#', $this->format_int_date($record['testing_date'],$this->site['site_short_date']),$html_content);
		$html_content = str_replace('#score#',$record['testing_score'],$html_content);
		$html_content = str_replace('#duration#',gmdate("H:i:s",$record['duration']),$html_content);
		$html_content = str_replace('#code#',$record['testing_code'],$html_content);			
		$html_content = str_replace('#site#',$this->site['site_name'],$html_content);		

		//Build recipient lists
		$recipients = new Swift_RecipientList;
		$recipients->addTo($this->sess_cus['email']);
		//$recipients->addTo($this->site['site_email']);
		 
		//Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");

	
		if($swift->send($message, $recipients, $from)){
		
		} else {
			
		}	
		// Disconnect
		$swift->disconnect();
    }
	
}
?>