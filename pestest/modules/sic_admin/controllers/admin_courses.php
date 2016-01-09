<?php
class Admin_courses_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->courses_model            = new Courses_Model();
		$this->lesson_model             = new Lesson_Model();
		$this->lesson_annotation_model  = new Lesson_annotation_Model();
		
		$this->certificate_model        = new Certificate_Model(); 
		
		$this->test_model               = new Test_Model(); 
		$this->payment_model            = new Payment_Model();
		
		$this->study_model              = new Study_Model();
		$this->member_certificate_model = new Member_certificate_Model();
		
		$this->category_model           = new Category_Model();
		$this->testing_category_model   = new Testingcategory_Model();
		$this->testingdetail_model      = new Testingdetail_Model(); 
		
		$this->questionnaires_model     = new Questionnaires_Model(); 
		$this->answer_model             = new Answer_Model(); 
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
	

	public function get_tags_courses(){
		$template = new View('admin_courses/frm_add_tags');
		$list_tags = $this->db->get('tags')->result_array(false);
		$template->set(array(
				'list_tags' => !empty($list_tags)?$list_tags:'',
			)
		);
		$template->render(true);
		die();
	}

	public function get_sponsor_tags_courses(){
		$template = new View('admin_courses/frm_add_sponsor_tags');
		$list_tags = $this->db->get('sponsor_tags')->result_array(false);
		$template->set(array(
				'list_tags' => !empty($list_tags)?$list_tags:'',
			)
		);
		$template->render(true);
		die();
	}

	public function get_tags(){
		$template = new View('admin_courses/list_tags');
		$list_tags = $this->db->get('tags')->result_array(false);
		//echo '<pre>';
		//print_r($list_tags);
		$template->set(array(
				'list_tags' => !empty($list_tags)?$list_tags:'',
			)
		);
		$template->render(true);
		die();
	}

	public function get_sponsor_tags(){
		$template = new View('admin_courses/list_sponsor_tags');
		$list_sponsor_tags = $this->db->get('sponsor_tags')->result_array(false);
		$template->set(array(
				'list_sponsor_tags' => !empty($list_sponsor_tags)?$list_sponsor_tags:'',
			)
		);
		$template->render(true);
		die();
	}

	public function get_sponsor_img(){
		$template = new View('admin_courses/list_sponsor_img');
		$list_img = $this->db->get('sponsor_img')->result_array(false);
		//echo '<pre>';
		//print_r($list_tags);
		$template->set(array(
			'list_img' => !empty($list_img)?$list_img:'',
		));
		$template->render(true);
		die();
	}

	public function save_tags(){
		$arr_name = $this->input->post('txt_tags_name');
		$arr_id   = $this->input->post('txt_tags_id');
		if(!empty($arr_id)){
			foreach ($arr_name as $key => $value) {
				$data = array('name' => $value);
				if(!empty($arr_id[$key])){
					$this->db->where('id',$arr_id[$key]);
					$this->db->update('tags',$data);
				}else{
					$this->db->insert('tags',$data);
				}
			}
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
			url::redirect('admin_courses');
		}
		url::redirect('admin_courses');
		die();
	}

	public function save_sponsor_tags(){
		$arr_name = $this->input->post('txt_sponsor_tags_name');
		$arr_id   = $this->input->post('txt_sponsor_tags_id');
		if(!empty($arr_id)){
			foreach ($arr_name as $key => $value) {
				$data = array('name' => $value);
				if(!empty($arr_id[$key])){
					$this->db->where('id',$arr_id[$key]);
					$this->db->update('sponsor_tags',$data);
				}else{
					$this->db->insert('sponsor_tags',$data);
				}
			}
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
			url::redirect('admin_courses');
		}
		url::redirect('admin_courses');
		die();
	}

	public function save_recommended(){
		if(isset($_POST)){
			$data = array(
					'location' => 'empty'
				);
			$this->db->where('location <> "empty"');
			$this->db->update('courses',$data);

			$arr_courses = $this->input->post('slt_id_course');
			if(!empty($arr_courses)){
				$arr_courses = array_unique($arr_courses);
				foreach ($arr_courses as $key => $value) {
					$item = array(
						'location' => ($key + 1)
					);
					$this->db->where('id',$value);
					$this->db->update('courses',$item);
				}
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
				url::redirect('admin_courses');
			}
			url::redirect('admin_courses');
			die();
		}
		url::redirect('admin_courses');
		die();
	}
	public function add_recommended($sl){
		$template = new View('admin_courses/item_recommended');
		$this->db->orderby('id','asc');
		$list_courses = $this->courses_model->get();
		$template->set(array(
				'list_courses' => !empty($list_courses)?$list_courses:'',
				'sl'           => $sl,
			)
		);
		$template->render(true);
		die();
	}

	public function get_recommended(){
		$template = new View('admin_courses/list_recommended');
		$this->db->orderby('id','asc');
		$list_courses = $this->courses_model->get();

		$this->db->where('location <> "empty"');
		$this->db->orderby('location', 'asc');
		$list_using = $this->courses_model->get();
		//$this->show_arr($list_using);
		$template->set(array(
				'list_courses' => !empty($list_courses)?$list_courses:'',
				'list_using'   => !empty($list_using)?$list_using:''
			)
		);
		$template->render(true);
		die();	
	}

	public function index()
    {             
		
		$this->search = array('keyword' => '');
        $this->showlist();
        $this->_get_submit();		
    }
	
	public function list_crop_company(){
		$template = new View('admin_courses/company_crop');	
		
		$template->set(array(
			'emergency'=>'hyjh'));
		$template->render(true);
		die();
	}

	public function frm_crop_sponsor(){
		$template = new View('admin_courses/sponsor_crop');	
		
		$template->set(array(
			'emergency'=>'hyjh'));
		$template->render(true);
		die();
	}

	public function crop_img(){
		$template = new View('admin_courses/crop_img');	
		
		$template->set(array(
			'emergency'=>'crop_img'));
		$template->render(true);
		die();
	}

	private function showlist(){
    	$this->template->content = new View('admin_courses/list');
		$this->where_sql();
		$total_items = count($this->courses_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else 
            	$per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	    
		if(isset($this->search['display']) && $this->search['display'])
			$this->template->content->display =$this->search['display'];
		else 
			$this->template->content->display =$per_page;
		
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_courses/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->courses_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();

		//$this->db->orderby('location','ASC');
		$this->db->orderby('id','DESC');
		$mlist = $this->courses_model->get();
		// echo $this->db->last_query();
		// echo '<pre>';
		// echo print_r($mlist);
		foreach($mlist as $id => $list){
			$this->db->where('id_courses',$list['id']);
			$mlist[$id]['lesson_count'] = count($this->lesson_model->get());
		}

		$this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	
    public function show_arr($arr){
    	echo'<pre>';
    		print_r($arr);
    	echo'</pre>';
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
			$this->db->like('description',$this->search['keyword']);
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
	
	public function saveall(){
		$arr_id = $this->input->post('chk_id');
		$status = array();
		if(is_array($arr_id)){			
			/**
			 * do with action select
			 */
			$sel_action = $this->input->post('sel_action');
			if($sel_action == 'delete'){
				foreach ($arr_id as $key => $value) {
					/**
					 * get list lesson
					 */
			    	$this->db->where('id_courses',$value);
			    	$arr_lesson = $this->lesson_model->get();
			    	if(!empty($arr_lesson)){
			    		foreach($arr_lesson as $stt_lesson => $item_lesson){
			    			/**
			    			 * delete annotation text
			    			 */
							$this->lesson_annotation_model->delete_annotaion_by_lesson_id($item_lesson['id']);
			    		}
			    		/**
			    		 * delete lesson
			    		 */
			    		$this->lesson_model->delete_lesson_by_courses_id($value);
			    	}
			    	/**
			    	 * delete courses
			    	 */
					$result_courses = $this->courses_model->delete($value);
				}
			}elseif($sel_action == 'active') {
				for($i=0;$i<count($arr_id);$i++){
					$status = $this->db->update('courses', array('status' => '1'), array('id' => $arr_id[$i]));
				}				
			}elseif($sel_action == 'inactive') {
				for($i=0;$i<count($arr_id);$i++){
					$status = $this->db->update('courses', array('status' => '0'), array('id' => $arr_id[$i]));
				}				
			} 
			
		}else{		
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if(count($status) > 0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
		url::redirect('admin_courses');
	}
	
	
	public function create()
    {		
		$this->template->content              = new View('admin_courses/frm');
		//list test
		$this->db->orderby('test_title','ASC');
		$arr_test = $this->test_model->get();

		$this->template->content->mr          = $this->mr;
		$this->template->content->arr_test    = $arr_test;
		$this->template->content->certificate = $this->certificate_model->get();				
    }
	
	public function create_lesson($id_courses=''){
		if(empty($id_courses)){
			$this->session->set_flash('error_msg','Courses not exist!');
			url::redirect('admin_courses/create');
			die();
		}
		$this->template->content             = new View('admin_courses/frm_lesson');
		//list test
		$this->db->orderby('test_title','ASC');
		$arr_test = $this->test_model->get();

		$arr_lesson = '';
		$this->db->where('id_courses',$id_courses);
		$this->db->orderby('id','asc');
		$arr_lesson = $this->lesson_model->get();

		$this->template->content->mr         = $this->mr;
		$this->template->content->id_courses = $id_courses;
		$this->template->content->arr_test   = $arr_test;
		$this->template->content->arr_lesson = $arr_lesson;
    }

	public function edit($id){		
		$this->template->content = new View('admin_courses/frm');
		$this->db->where('id_courses',$id);
		$this->db->orderby('id','asc');
		$list_lesson  = $this->lesson_model->get();

		$this->db->orderby('test_title','ASC');
		$arr_test = $this->test_model->get();

		$m_courses = $this->courses_model->get($id);
		$m_tags    = array();
		if(!empty($m_courses['tags_id'])){
			$arr_tags = array_filter(explode('|', $m_courses['tags_id']));
			$this->db->in('id',$arr_tags);
			$m_tags = $this->db->get('tags')->result_array(false);
		}

		$m_sponsor_tags    = array();
		if(!empty($m_courses['sponsor_tags_id'])){
			$arr_sponsor_tags = array_filter(explode('|', $m_courses['sponsor_tags_id']));
			$this->db->in('id',$arr_sponsor_tags);
			$m_sponsor_tags = $this->db->get('sponsor_tags')->result_array(false);
		}

		$this->template->content->arr_test     = $arr_test;
		$this->template->content->courses      = $m_courses;
		$this->template->content->tags         = $m_tags;
		$this->template->content->sponsor_tags = $m_sponsor_tags;
		$this->template->content->list_lesson  = $list_lesson;
		$this->template->content->certificate  = $this->certificate_model->get();
    }

    public function edit_lesson($id){		
		$this->template->content = new View('admin_courses/frm_lesson');

		$lesson = $this->lesson_model->get($id);
		//list annotation
		$this->db->where('id_lesson',$id);
		$this->db->orderby('id','asc');
		$annotation = $this->lesson_annotation_model->get();
		//list lesson by id_courese
		$arr_lesson     = '';
		$arr_categories = '';
		if(!empty($lesson)){
			$this->db->where('id_courses',$lesson['id_courses']);
			$this->db->where('id <> '.$id);
			$this->db->orderby('id','asc');
			$arr_lesson = $this->lesson_model->get();

			$arr_categories = $this->category_model->getCategoryById('test_uid',!empty($lesson['id_test_pass'])?$lesson['id_test_pass']:'empty');
		}
		//$this->show_arr($arr_categories);
		//list test
		$this->db->orderby('test_title','ASC');
		$arr_test = $this->test_model->get();
		//$this->show_arr($lesson);

		$this->template->content->lesson         = $lesson;
		$this->template->content->id_courses     = $lesson['id_courses'];
		$this->template->content->annotation     = $annotation;
		$this->template->content->arr_lesson     = $arr_lesson;
		$this->template->content->arr_categories = $arr_categories;
		$this->template->content->arr_test       = $arr_test;


    }
	
	public function deleteAnswer($id){
			$result_answer = $this->answer_model->delete($id);
			$json['status'] = $result_answer?1:0;
			$json['mgs'] = $result_answer?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
			die();
	}
	
	public function delete_img($id){
		$this->db->where('id',$id);
		$item = count($this->db->delete('sponsor_img'));
		$json['status'] = $item?1:0;
		$json['mgs']    = $item?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user']   = array('id' => $id);
		echo json_encode($json);
		die();
	}

    public function delete($id){			
    	// delete lesson
    	$this->db->where('id_courses',$id);
    	$arr_lesson = $this->lesson_model->get();
    	if(!empty($arr_lesson)){
    		foreach($arr_lesson as $stt_lesson => $item_lesson){
    			//delete annotation text
				$this->lesson_annotation_model->delete_annotaion_by_lesson_id($item_lesson['id']);
    		}
    		$this->lesson_model->delete_lesson_by_courses_id($id);
    	}

		/**
		 * [$result_courses description]
		 * @var [id]
		 */
		$result_courses = $this->courses_model->delete($id);

		$json['status'] = $result_courses?1:0;
		$json['mgs'] = $result_courses?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user'] = array('id' => $id);
		echo json_encode($json);
		die();
    }

    public function delete_lesson($id){			
    	//delete annotation text
		$this->lesson_annotation_model->delete_annotaion_by_lesson_id($id);


		/**
		 * [$result_courses description]
		 * @var [id]
		 */
		$result_lesson = $this->lesson_model->delete($id);

		$json['status'] = $result_lesson?1:0;
		$json['mgs'] = $result_lesson?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user'] = array('id' => $id);
		echo json_encode($json);
		die();
    }

    protected function _save_file_s3($file){
    	require_once Kohana::find_file('views/aws','init');
    	$name = $file['name'];
		$size = $file['size'];
		$type = $file['type'];
		$tmp  = $file['tmp_name'];
		$actual_image_name = date('mdYhms').'_'.$name;
		try {
		    // Upload data.
		    $result = $s3Client->putObject(array(
				'Bucket'      => $s3_bucket,
				'Key'         => 'NoteS/'.$actual_image_name,
				'SourceFile'  => $tmp,
				'ACL'         => 'public-read',
				'ContentType' => $type
		    ));
		    return $actual_image_name;
		}catch (S3Exception $e) {
			return false;
		}
		return false;
    }

	 protected function _save_img_s3($image,$hd_id=''){
    	require_once Kohana::find_file('views/aws','init');
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
          if($hd_id){
          	$this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
			url::redirect('admin_courses/edit/'.$hd_id);
			die();
          }else{
          	$this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
			url::redirect('admin_courses/create');
			die();
          }
        }else{
			$name = $image['name'];
			$size = $image['size'];
			$tmp  = $image['tmp_name'];
			$actual_image_name = md5($image['name'].time()).".".'gif';
			try {
			    // Upload data.
			    $result = $s3Client->putObject(array(
					'Bucket'      => $s3_bucket,
					'Key'         => 'courses_img/'.$actual_image_name,
					'SourceFile'  => s3_resize($tmp,750,750),
					'ACL'         => 'public-read',
					'ContentType' => 'image/gif'
			    ));
			    return $actual_image_name;
			}catch (S3Exception $e) {
				return FALSE;
			}
         return FALSE;
     	}
    }

    public function save_img($folder=""){
    	if(s3_using == 'on'){
    		require_once Kohana::find_file('views/aws','init');
			$s3Client->registerStreamWrapper();
    	}
    	$unusing_folder = false;
    	if(empty($folder)){
			$folder         = 'courses_img';
			$unusing_folder = true;
			$time           = 'courses_'.md5(time()).rand(1,1000);
    	}else{
    		$time    = $folder.'_'.md5(time()).rand(1,1000);
    	}
		$img     = $this->input->post('image');
		$img     = str_replace('data:image/png;base64,', '', $img);
		$img     = str_replace(' ', '+', $img);
		$data    = base64_decode($img);
		if(s3_using == 'on'){
			file_put_contents('s3://'.$s3_bucket.'/'.$folder.'/'.$time.'.png', $data);
		}else{
			file_put_contents(DOCROOT.'uploads/'.$folder.'/'.$time.'.png', $data);
		}

		if($unusing_folder){
			echo json_encode($time.'.png');
		}else{
			if($folder == 'sponsor_icon'){
				echo json_encode($time.'.png');
			}else{
				$data = array(
						'name_img' => $time.'.png',
					);
				$result = $this->db->insert('sponsor_img',$data);
				echo json_encode(array('id' => $result->insert_id(),'img' => $time.'.png'));	
			}
			
		}
		exit();
    }

    protected function _save_img_courses($image,$hd_id=''){
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
          $this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
          if($hd_id) url::redirect('admin_courses/edit/'.$hd_id);
                else url::redirect('admin_courses/create');
        }else{
	        $directory = DOCROOT.'uploads/courses_img/';
	        if ($file = upload::save($image, NULL, $directory)){
	            $filename = md5(rand(0, 999)).'.png';
	  
	            Image::factory($file)
	                ->resize(750, 750, Image::AUTO)
	                ->save($directory.$filename);
	            // Delete the temporary file
	            unlink($file);
	            return $filename;
	        }
	        return FALSE;
    	}
    }

    private function _get_frm_valid()
    {
  		$form = $this->courses_model->get_frm();
		if($_POST)
    	{
    		$post = new Validation($_POST);
			//$post->add_rules('erea_question','required','length[3,1000]');			
		
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
				
                if($hd_id) url::redirect('admin_courses/edit/'.$hd_id);
                else url::redirect('admin_courses/create');
				die();
			}
        }
    }

    public function save_ajax(){  
		$file_logo = $this->input->post('image_company');
		$courses = ORM::factory('courses_orm');

		$courses->id_test = $this->input->post('txt_test_id');

		$courses->type        = $this->input->post('txt_type');
		$courses->title       = $this->input->post('txt_title');
		$courses->description = $this->input->post('txt_description');
		$courses->price       = $this->input->post('txt_price');

		$courses->image          = $file_logo;	
		$courses->status         = $this->input->post('txt_status');
		$courses->day_valid      = $this->input->post('txt_days_valid');
		$courses->id_certificate = $this->input->post('txt_certificate');
		$courses->save();	
	
		if($courses->saved)
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

		url::redirect('admin_member/convert_data');		
		die(); 	
    } 

	public function save(){  
		echo '<pre>';
		print_r($_POST);
		echo $a = time();
		echo '<br>';
		echo date('m/d/Y',$a);
		//die();
		$file_logo = $this->input->post('image_company');
		//$logo      = @$_FILES['txt_courses_img'];
		$hd_id     = $this->input->post('hd_id');
		if(empty($hd_id)){
			$courses = ORM::factory('courses_orm');
			
		}else {
			$courses = ORM::factory('courses_orm', $hd_id);	
		}

		// if(isset($logo['error']) && $logo['error'] == 0){
		// 	if(s3_using == 'on')
		// 		$file_logo = $this->_save_img_s3($logo,!empty($hd_id)?$hd_id:'');
		// 	else
		// 		$file_logo = $this->_save_img_courses($logo,!empty($hd_id)?$hd_id:'');
		// }
		if(!empty($_POST['txt_type'] && $_POST['txt_type'] == 1)){
			$courses->id_test = $this->input->post('slt_id_test_pass');
		}

		if(isset($_POST['txt_tags_id']) && !empty($_POST['txt_tags_id'])){
			$m_tags = array_unique($_POST['txt_tags_id']);
			$str = '|';
			foreach ($m_tags as $key => $value) {
				$str .= $value.'|';
			}
			$courses->tags_id = $str;
		}

		if(isset($_POST['txt_sponsor_tags_id']) && !empty($_POST['txt_sponsor_tags_id'])){
			$m_sponsor_tags = array_unique($_POST['txt_sponsor_tags_id']);
			$str_sponsor = '|';
			foreach ($m_sponsor_tags as $key => $value) {
				$str_sponsor .= $value.'|';
			}
			$courses->sponsor_tags_id = $str_sponsor;
		}

		$courses->type        = $this->input->post('txt_type');
		$courses->title       = $this->input->post('txt_title');
		$courses->description = $this->input->post('txt_description');
		$courses->price       = $this->input->post('txt_price');
		// if(!empty($file_logo)){
		// 	$courses->image   = $file_logo;
		// }
		$courses->image          = $file_logo;	
		$courses->sponsor_icon   = $this->input->post('txt_sponsor_img');
		$courses->status         = $this->input->post('txt_status');
		$courses->day_valid      = $this->input->post('txt_days_valid');
		$courses->id_certificate = $this->input->post('txt_certificate');
		$courses->video_control  = $this->input->post('slt_control');

		$courses->authorized_day_using = time();
		$courses->authorized_day       = !empty($_POST['txt_day_using'])?$_POST['txt_day_using']:0;
		$courses->save();	
	
		if($courses->saved)
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
		
		if($this->input->post('hd_save_add') == 1)
			url::redirect('admin_courses/create');
		elseif($this->input->post('hd_save_add') == 2)
			url::redirect('admin_courses/create_lesson/'.$courses->id);	
		else	
			url::redirect('admin_courses');
		die(); 	
    }   
	
    public function save_lesson(){   
		//$frm = $this->_get_frm_valid();
		//$this->show_arr($_POST);
		//$this->show_arr($_FILES);
		//die();
		$hd_id = $this->input->post('hd_id');
		if(empty($hd_id)){
			$lesson = ORM::factory('lesson_orm');
			
		}else {
			$lesson = ORM::factory('lesson_orm', $hd_id);	
		}

		if(isset($_FILES['txt_attach_file']) && $_FILES['txt_attach_file']['error'] == 0){
			$attach_name = $this->_save_file_s3($_FILES['txt_attach_file']);
			if($attach_name == false){
				$this->session->set_flash('error_msg','Attach Note File False.');
			}else{
				$lesson->attach_file = $attach_name;
			}
		}elseif(isset($_FILES['txt_attach_file']) && $_FILES['txt_attach_file']['error'] == 1){
			$this->session->set_flash('error_msg','Attach Note File False.');
		}else{
			$lesson->attach_file = $this->input->post('txt_file_acttach');
		}
		$lesson->title               = $this->input->post('txt_title');
		//$lesson->id_lesson_pass    = $this->input->post('slt_id_lesson_pass');
		$lesson->percent_lesson_pass = $this->input->post('txt_percent_lesson_pass');
		$lesson->id_test_pass        = $this->input->post('slt_id_test_pass');
		$lesson->percent_test_pass   = $this->input->post('txt_percent_test_pass');
		$lesson->video_link          = $this->input->post('txt_video_link');
		$lesson->pass                = $this->input->post('txt_days_valid');
		$lesson->id_courses          = $this->input->post('id_courses');
		$lesson->id_categories       = $this->input->post('slt_id_categories');
		$lesson->hide_annotation     = isset($_POST['txt_hide_annotation'])?'Y':'N';
		$lesson->type                = '';
		$lesson->save();	
	
		if($lesson->saved)
		{
			//delete annotation text
			$this->lesson_annotation_model->delete_annotaion_by_lesson_id($lesson->id);

			//add annotation text
			$arr_annotation_time = $this->input->post('txt_time');
			$arr_annotation_text = $this->input->post('txt_text');
			if(!empty($arr_annotation_time)){
				foreach($arr_annotation_time as $stt => $item){
					$annotation            = ORM::factory('lesson_annotation_orm');
					$annotation->time      = $item;
					$annotation->text      = $arr_annotation_text[$stt];
					$annotation->id_lesson = $lesson->id;
					$annotation->save();
				}
			}
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
		
		if($this->input->post('hd_save_add') == 1)
			url::redirect('admin_courses/create_lesson/'.$this->input->post('id_courses'));	
		else	
			url::redirect('admin_courses/edit/'.$this->input->post('id_courses'));
		die(); 	
    }

	public function setstatus($id)
    {    	    	
       
		$result = ORM::factory('courses_orm', $id);
		$result->status = abs($result->status  - 1);
		$result->save();       	
		$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
		url::redirect($this->uri->segment(1));
		die();
       
    }
	public function search_member($uid)
    {			
		$this->member($uid);
    } 
	public function member($uid)
	{
		$view = new View('admin_courses/listmember');
		$this->db->orderby('payment_date','DESC');
		$this->db->select('member.member_fname','member.member_lname','member.member_email','member.company_name','member.company_contact_email');
		$this->db->where('courses_id',$uid);
		$this->db->join('member', 'member.uid', 'payment.member_uid');
		$mlist = $this->payment_model->get();
		$this->pagination = new Pagination(array(
				'base_url'       => 'admin_courses/search_member/'.$uid,
				'uri_segment'    => 'page',
				'total_items'    => count($mlist),
				'items_per_page' => $this->site['site_num_line2'],
				'style'          => 'digg',
			));		
		$this->payment_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		
		$this->db->orderby('payment_date','DESC');
		$this->db->select('member.member_fname','member.member_lname','member.member_email','member.company_name','member.company_contact_email');
		$this->db->where('courses_id',$uid);
		$this->db->join('member', 'member.uid', 'payment.member_uid');
		$mlist = $this->payment_model->get();

		$view->mr         = $this->courses_model->get($uid);
		$view->mlist      = $mlist;
		$view->id_courses = $uid;
		$view->render(true);
		die();
	}
	
	public function member2($uid)
	{
		$view =new View('admin_test/listmember2');
		$this->db->orderby('payment_date','DESC');
		$this->db->select('member.member_fname','member.member_lname','member.member_email','member.company_name','member.company_contact_email');
		$this->db->where('test_uid',$uid);
		$this->db->join('member', 'member.uid', 'payment.member_uid');
		$mlist = $this->payment_model->get();
		
		$this->pagination = new Pagination(array(
				'base_url'       => 'admin_test/search_member/'.$uid,
				'uri_segment'    => 'page',
				'total_items'    => count($mlist),
				'items_per_page' => $this->site['site_num_line2'],
				'style'          => 'digg',
			));		
		$this->payment_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->db->orderby('payment_date','DESC');
		$this->db->select('member.member_fname','member.member_lname','member.member_email','member.company_name','member.company_contact_email');
		$this->db->where('test_uid',$uid);
		$this->db->join('member', 'member.uid', 'payment.member_uid');
		$mlist = $this->payment_model->get();
		$view->mr = $this->test_model->get($uid);
		$view->mlist = $mlist;
		$view->render(true);
		die();
	}
	
	public function order_file($action,$id)
	{
		$this->db->orderby('test_order','asc');
		$list = $this->test_model->get();
		
		for($i=0;$i<count($list);$i++){
		$this->db->update('test',array('test_order' => $i+1),array('uid' =>$list[$i]['uid']));
		}
		$mr_arr = $this->test_model->get($id);
		
		if($action == 'up')
		{
			$this->db->where('test_order',$mr_arr['test_order']-1);
			$mr_temp = $this->test_model->get();
			
		
			$this->db->update('test',array('test_order'=>$mr_arr['test_order']),array('uid'=>$mr_temp[0]['uid']));
			$this->db->update('test',array('test_order'=>$mr_temp[0]['test_order']),array('uid'=>$id));
			
			
		}
		else{
			$this->db->where('test_order',$mr_arr['test_order']+1);
			$mr_temp = $this->test_model->get();
			$this->db->update('test',array('test_order'=>$mr_arr['test_order']),array('uid'=>$mr_temp[0]['uid']));
			$this->db->update('test',array('test_order'=>$mr_temp[0]['test_order']),array('uid'=>$id));
			
		}
		 url::redirect('admin_test');
		die();
	}

}
?>