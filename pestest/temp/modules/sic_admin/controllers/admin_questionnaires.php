<?php
class Admin_questionnaires_Controller extends Template_Controller
{
	
	public $template = 'admin/index';
	
    public function __construct()
    {
		$session=Session::instance();
		$this->questionnaires_model = new Questionnaires_Model(); 
		$this->answer_model   = new Answer_Model(); 
		$this->author_model   = new Author_Model(); 
		$this->category_model = new Category_Model();
		$this->test_model     = new Test_Model();
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
		$this->showlist();
		
		$this->search = array('keyword' => '');
        
        $this->_get_submit();		
    }
	
	private function getAnswar($uid){
		return $this->answer_model->getAnswerByQuestionId($uid);
	}
	
	private function getAuthor($uid){
		return $this->author_model->getAuthorByQuestionId($uid);
	}
	
	private function showlist()
    {
    	$this->template->content = new View('admin_questionnaires/list');
		$this->where_sql();
		if($this->sess_admin['level']==3)
		$this->db->where('created_by',$this->sess_admin['id']);
		$total_items = count( $this->questionnaires_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
		$page = $this->uri->segment('page');
		if(isset($page))
		{
			$this->session->set('page', $page);;
		}
		if(isset($this->search['display']) && $this->search['display'])
		 $this->template->content->display =$this->search['display'];
		 else $this->template->content->display =$per_page; 
		//echo($page);
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_questionnaires/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));
		$test = $this->input->post('sel_test');	
				
		$this->questionnaires_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		if($this->sess_admin['level']==3)
		$this->db->where('created_by',$this->sess_admin['id']);
		$mlist = $this->questionnaires_model->get();
		foreach($mlist as $key => $value){
			$mlist[$key]['answer']   = $this->getAnswar($value['uid']);
			$mlist[$key]['author']   = $this->getAuthor($value['created_by']);
			$mlist[$key]['category'] = $this->getCategoryById($value['category_uid']);
			$mlist[$key]['test']     = $this->getTestById($value['test_uid']);
		}
		$this->template->content->set(array(
            'mlist' => $mlist
		));
		$this->db->orderby('test_title','ASC');
		$mtest = $this->test_model->get() ;
		$this->template->content->set(array(
            'test' => $mtest
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
		
		$test = $this->input->post('sel_test');	
		if(isset($test))
		{
			$this->search['test'] = $this->input->post('sel_test');
		}
		
		$category = $this->input->post('sel_category');	
		if(isset($category)	)
		{
			$this->search['category'] = $this->input->post('sel_category');
		}else{
			//unset($this->search['category']);
		}
		
		$this->session->set_flash('sess_search',$this->search);
		$this->showlist();
	}
	
	public function export() {
	
		if($this->uri->segment(3) != 'null'){
			$this->search['test'] = $this->uri->segment(3) ;
		}
		else 
			$this->search['test'] = '';
			
		if($this->uri->segment(4) != 'null') {
			$this->search['category'] =  $this->uri->segment(4) ;
		}
		else 
			$this->search['category'] = '';
			
		if($this->uri->segment(5) != 'null') {
			$this->search['keyword'] =  $this->uri->segment(4) ;
		}
		else 
			$this->search['keyword'] = '';	
			
        /////".!empty($this->search['test'])?$this->search['test']:date('Y-m-d',time())."
		if(!empty($this->search['test']))$file_name = date('Y-m-d',time());//$this->search['test'];
		else $file_name = date('Y-m-d',time());
		header('Content-type: application/x-msdownload; charset=utf-16');
		header("Content-Disposition: attachment; filename=Questionnaires_".$file_name.".xls" );
		header("Expires: 0");
		header("Pragma: no-cache");
		///
		$this->template = new View('admin_questionnaires/excel'); 
	    $this->where_sql();
		if($this->sess_admin['level']==3)
		$this->db->where('created_by',$this->sess_admin['id']);
		$mlist = $this->questionnaires_model->get();
		//echo $this->db->last_query();
		$max_answers=0;
		foreach($mlist as $key => $value){
			$this->db->orderby('type','DESC');
			$mlist[$key]['answer']   = $this->getAnswar($value['uid']);
			$mlist[$key]['author']   = $this->getAuthor($value['created_by']);
			$mlist[$key]['category'] = $this->getCategoryById($value['category_uid']);
			$mlist[$key]['test']     = $this->getTestById($value['test_uid']);
			
			
			if(count($mlist[$key]['answer']) > $max_answers)	
			$max_answers = count($mlist[$key]['answer']);
		}
		$mr['max_answers'] = $max_answers;
		$this->template->set(array(
            'mlist' => $mlist,
			'mr'=>$mr
		));
		$this->template->render(true);
		die();
	
		//url::redirect($this->site['history']['current']);
	}
	
	public function where_sql()
    {
		if(isset($this->search['test']) && $this->search['test']!='Status' && $this->search['test']!='')
		{
			if(isset($this->search['keyword']) && $this->search['keyword'])
				$this->db->like('question',$this->search['keyword']);
			if(isset($this->search['test']))
				$this->db->where('test_uid',$this->search['test']);
			if(isset($this->search['category']) && !empty($this->search['category']))
				$this->db->where('category_uid',$this->search['category']);
		
		}else
			if(isset($this->search['test']) && $this->search['test']!=''){
		   		if(isset($this->search['keyword']) && $this->search['keyword']) 
		    		$this->db->where('LCASE(status) LIKE'.$this->db->escape('%'.$this->search['keyword'].'%'));
			}
			else{
				if(isset($this->search['keyword']) && $this->search['keyword'])
					$this->db->like('question',$this->search['keyword']);
			}
		
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
	
	public function setstatus($id , $status)
    {    
			    	
        $this->showlist();
    }
	 
	public function saveall()
	{
		
		$arr_id = $this->input->post('chk_id');
		$status = array();
		if(is_array($arr_id))
		{			
			//do with action select
			$sel_action = $this->input->post('sel_action');
		
			if($sel_action == 'delete')
			{
				for($i=0;$i<count($arr_id);$i++)
				{
					$this->db->delete('questionnaires', array('uid' => $arr_id[$i]));
					$this->db->delete('answer', array('questionnaires_uid' => $arr_id[$i]));
				}
			} elseif($sel_action == 'update') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('questionnaires', array('status' => 'Pending'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'pending') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('questionnaires', array('status' => 'Pending'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'active') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('questionnaires', array('status' => 'Active'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'deative') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('questionnaires', array('status' => 'Deative'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'obsoleted') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('questionnaires', array('status' => 'Obsoleted'), array('uid' => $arr_id[$i]));
				}
			}
		} else {		
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if (count($status)>0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
		url::redirect('admin_questionnaires');
	}
	
	private function getCategory($id){
		return $this->category_model->getCategoryById('test_uid',$id);
	}
	
	public function create()
    {		
		$this->template->content = new View('admin_questionnaires/frm');
		$this->template->content->set(array(
           'category' => $this->category_model->get()
        )); 
		$this->db->orderby('test_title','ASC');
		$this->template->content->test = $this->test_model->get();   	
		$this->template->content->mr = $this->mr;				
    }
	
	
	public function edit($id)
    {		
		$this->template->content = new View('admin_questionnaires/frm');
		$question = $this->questionnaires_model->get($id);
		$this->db->orderby('test_title','ASC');
		$mtest = $this->test_model->get();
		$this->template->content->set(array(
            'category' => $this->getCategory($question['test_uid']),
			'que'=> $question,
			'test'=>$mtest,
			'ans'=>$this->answer_model->getByColumn('questionnaires_uid',$id)	
        ));		
    }
	
	private function _get_frm_valid()
    {
  		$form = $this->questionnaires_model->get_frm();
		if($_POST)
    	{
    		$post = new Validation($_POST);
			$post->add_rules('erea_question','required','length[1,1000]');			
		
			if($post->validate()) 
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			}
			else
			{
				
				$form = arr::overwrite($form,$post->as_array());
								
				$errors =  $post->errors();
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
                if(isset($hd_id)) 
					url::redirect('admin_questionnaires/edit/'.$hd_id);
                else 
					url::redirect('admin_questionnaires/create');
				die();
			}
        }
    }
	public function import()
    {		
		$this->template->content = new View('admin_questionnaires/frm_import');
		$this->db->orderby('test_title','ASC');
		$this->template->content->test = $this->test_model->get();   	
    }
	public function sm()
	 {
		$this->template->content = new View('admin_questionnaires/frm_import');
		$this->db->orderby('test_title','ASC');
		$this->template->content->test = $this->test_model->get();   	
		
		 $form['txt_import'] = '';
		  $form['sel_test"'] = '';
		$errors = $form;	
		$sel_test = $this->input->post('sel_test');
		if(empty($sel_test))
		{
		 $this->session->set_flash('error_msg','Test is required');
		  url::redirect('admin_questionnaires/import');
		die();
		}
		$post = new Validation(($_FILES));
		$post->pre_filter('trim', TRUE);
		$post->add_rules('txt_import', 'required');
		$post->add_rules('txt_import','upload::size[10M]');
		$extension = end(explode('.', $_FILES['txt_import']['name']));
		if($extension != 'xls' &&  $extension != 'xlsx')
		{
		  $this->session->set_flash('error_msg','File just allow type xls, xlsx.');
		  url::redirect('admin_questionnaires/import');
		  die();
		}
		if ($post->validate())
		{
			if($_FILES['txt_import']['error']==0)
			{
				Kohana::config_set('upload.directory', DOCROOT.'uploads/import');
				$name = 'txt_import';
				$filename = upload::save($name,$_FILES[$name]['name']);//('txt_import');
				//echo $filename;die();	
				if($filename && !empty($filename))
				{
				 $this->read_excel($filename);
				 $this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
				  url::redirect('admin_questionnaires');
				}
				else{
				  $this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
				}		
			}
			 else {
			   $this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
			   url::redirect('admin_questionnaires/import');
			   die();
		   }
		}
		else
		{
			$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
			$this->session->set_flash('input_data',$form);		// Set input data in session			
						
			$errors = arr::overwrite($errors, $post->errors('questionnaires_import_validation'));
			$str_error = '';
			
			foreach($errors as $id => $name) if($name) $str_error.='<br>'.$name;
			$this->session->set_flash('error_msg',$str_error);
			url::redirect('admin_questionnaires/import');
		}
		
	
	}
	
	public function read_excel($filename='')
    {
	    $sel_test = $this->input->post('sel_test');
		require Kohana::find_file('vendor/PHPExcleReader/PHPExcel','IOFactory');
		$inputFileName =$filename;// DOCROOT.'uploads/import/2112-801-A.xls';
		
		try {
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
		
		} catch(Exception $e) {
			$this->session->set_flash('error_msg',$e->getMessage());
			url::redirect('admin_questionnaires/import');
			die();
		}
		 
		
		$row = 0;
		$lastColumn = $objPHPExcel->getActiveSheet(0)->getHighestColumn();
		$maxCell = $objPHPExcel->getActiveSheet()->getHighestRowAndColumn();
		//$sheetData = $objPHPExcel->getActiveSheet()->toArray('A0:' . $maxCell['column'] . 1000,true,true,true);
		$sheetData = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . 500);
       // $data = array_map('array_filter', $data);
       //$sheetData = array_filter($data);
	  
		//$lastColumn++;
		
		   $i=0;
		   $j=0;
		   
			foreach($sheetData as $rec)
			{
			   if($i==0)
			   {
				   $m=0;
			     $answer = array();
				 for ($column = 'A'; $column != $lastColumn; $column++) {
					//$cell = $objPHPExcel->getActiveSheet()->getCell($column.$row);
					//echo trim($rec[$m]);
					if(strpos(trim($rec[$m]),'nswers'))
					{
					  $answer[$j] = $m;
					  $j++;
					}
					$m++;
				}
				//echo $m;
				//die();
			   }	
				
			  if($i>0)
			  {
				
				$category = str_replace(array(' ','&nbsp;'),' ',trim($rec[0]));
				$question = str_replace(array(' ','&nbsp;'),' ',trim($rec[1]));
				$good_answer  = trim($rec[2]);
				$answer1 = trim($rec[3]);
				$answer2 = trim($rec[4]);
				$answer_description  =  trim($rec[$m]);
				//print_r($question);
				//print_r($m);
				//echo count($answer);die();
				///////GET Category
				$category_uid='';
				if(!empty($question))
				{
					if(!empty($category))
					{
						$category = strtolower($category);
						$this->db->where("LCASE(category) LIKE '%" .$category. "%'");
						$this->db->where('test_uid',$sel_test);
						$mr_cate = $this->category_model->get();
					    if(isset($mr_cate[0]['uid'])) $category_uid =$mr_cate[0]['uid'];
					}
					
					//////
					$record_question = array('category_uid'=>$category_uid,
					'status'=>'Active',
					'question'=>$question,
					'answer_description'=>$answer_description,
					'created_by'=>$this->sess_admin['id'],
					'input_date'=>strtotime(date('d/m/Y h:i:s')),
					'test_uid'=>$sel_test);   
					////
					$result = $this->db->insert('questionnaires',$record_question);
					$questionnaires_id =$result->insert_id();
						for($k=0;$k<count($answer);$k++)
						{
							$check_answer = trim($rec[$answer[$k]]);
							if(!empty($check_answer))
							{
							 if($k==0)
							 $this->db->insert('answer',array('questionnaires_uid'=>$questionnaires_id,'answer'=>trim($rec[$answer[$k]]),'type'=>1));
							 else
							 $this->db->insert('answer',array('questionnaires_uid'=>$questionnaires_id,'answer'=>trim($rec[$answer[$k]]),'type'=>0));
							}
						
						}
					
				 }
				
					
				}
				 $i++;
				
			}
      
	}
	
	public function deleteAnswer($id){
			$result_answer = $this->answer_model->delete($id);
			$json['status'] = $result_answer?1:0;
			$json['mgs'] = $result_answer?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
			die();
	}
	 public function del_images($id,$uid) {
        $msg = array('error' => '', 'success' => '');
        $path_file = DOCROOT . 'uploads/answer/' . $this->site['site_logo'];

        if (is_file($path_file) && file_exists($path_file)) {
            if (@unlink($path_file))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del');
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_del_file');
        }
        else
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist');

        if ($this->db->update('answer',array('images'=>""),array('uid'=>$id)))
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $msg['error'] .= Kohana::lang('errormsg_lang.error_data_update');

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect('admin_questionnaires/edit/'.$uid);
        die();
    }
	
    public function delete($id)
    {				
        
    		$result_question = $this->questionnaires_model->delete($id);
			$this->answer_model->deleteByColumn('questionnaires_uid',$id);
        	$json['status'] = $result_question?1:0;
			$json['mgs'] = $result_question?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
	
	public function ImageCreateFromBmp($filename)
	{
		/*** create a temp file ***/
		$tmp_name = tempnam("/tmp", "GD");
		/*** convert to gd ***/
		if($this->bmp2gd($filename, $tmp_name))
		{
			/*** create new image ***/
			$img = imagecreatefromgd($tmp_name);
			/*** remove temp file ***/
			unlink($tmp_name);
			/*** return the image ***/
			return $img;
		}
	 return false;
	}
	public function upload_file($name='',$file='')
    {
        
        $remove_arr = array(" ","/",'(',')');
        if(!empty($file['name'])) {
			$path_dir = DOCROOT.'uploads/answer/';                       
			if (($file["type"] == "image/gif")
				|| ($file["type"] == "image/jpeg")
				|| ($file["type"] == "image/pjpeg")
				|| ($file["type"] == "image/png"))
			{
				$path_file = upload::save($file,$name.'_'.time().'.'.substr($file['name'],-3),$path_dir);   
				$file_name= $name.'_'.time().'.'.substr($file['name'],-3);
				list($width, $height, $type, $attr) = getimagesize($path_file);
				
				if($width >593)
				{
				Image::factory($path_file)
				->resize(593,NULL,Image::AUTO)
				->save(DOCROOT.'uploads/answer/'.$file_name);
				
				}
				else
				{
				Image::factory($path_file)
				//->resize(593,NULL,Image::AUTO)
				->save(DOCROOT.'uploads/answer/'.$file_name);
				}
			}
			else{
				if(substr($file['name'],-4) =='xlsx')
				$exfile = substr($file['name'],-4);
				else if(substr($file['name'],-4) =='docx')
				$exfile = substr($file['name'],-4);
				else $exfile = substr($file['name'],-3);
				$path_file = upload::save($file,$name.'_'.time().'.'.$exfile,$path_dir);
				$file_name = $name.'_'.time().'.'.$exfile;
			}       
		 }
        else {
            $file_name='';
        }
        return $file_name;
    }
	public function save()
    {   
		$this->session->set('se_category_uid', $this->input->post('sel_category'));
		$this->session->set('se_test_uid', $this->input->post('sel_test'));
		$this->session->set('se_status', $this->input->post('sel_liststatus'));
			    	
		$frm = $this->_get_frm_valid();
		$array_answer = $this->input->post('answer_ans');
		$array_type = $this->input->post('type_ans');
		$array_id = $this->input->post('id_ans');
		//$array_images = $this->input->post('answer_file');
		
		if(empty($frm['hd_id']))
		{
			$question = ORM::factory('questionnaires_orm');
			
		} else {
			$question = ORM::factory('questionnaires_orm', $frm['hd_id']);	
		}	
		$question->category_uid	 = $frm['sel_category']; // active
		$question->status = $frm['sel_liststatus'];
		$question->question = $frm['erea_question']; // admin
		$question->answer_description = $frm['erea_description'];
		$question->note	 = $frm['erea_note'];	
		$question->note_ext = $frm['erea_note_extend'];	
		$question->test_uid = $frm['sel_test'];	
		if(empty($frm['hd_id'])){
			$question->created_by = $this->sess_admin['id'];
		}
		$question->input_date = strtotime(date('d/m/Y h:i:s'));
		$question->save();	
		$i = 0;
		$k=1;
		$r=1;
		$this->answer_model->deleteByColumn('questionnaires_uid',$array_id);
		
		foreach($array_answer as $key=>$value){

			$array_random=$this->input->post('random'.$r);

		 if(!empty($value) 
		 		|| (isset($_FILES['answer_file'.$k]['name']) && !empty($_FILES['answer_file'.$k]['name']))
				|| (isset($array_id[$i])))
		 {
			
			
			if(!isset($array_id[$i]))
			{
				
				$answer   = ORM::factory('answer_orm');	
				$answer->questionnaires_uid = $question->uid;
				$answer->answer = $value;
				$answer->type = (@$array_type[$i++] == 'on')? 1 : 0;
				$answer->random =(isset($array_random) == 'on')? 1 : 0;
				if(isset($_FILES['answer_file'.$i]) && !empty($_FILES['answer_file'.$i]['name'])){
					
					$file = $_FILES['answer_file'.$i];
					
					if($_FILES['answer_file'.$i]['type'] == 'image/bmp')
					{
						Kohana::config_set('upload.directory', DOCROOT.'uploads/answer');
						$filename = upload::save($name,$_FILES['answer_file'.$i]['name']);
						$img = $this->ImageCreateFromBmp($filename);
						/*** write the new jpeg image ***/
						$file_images = time().md5(basename($filename));
						imagegif($img, DOCROOT.'uploads/answer/'.$file_images.'.gif');
					}
					else $file_images = $this->upload_file($file['name'],$file);
				$answer->images = $file_images ;
				
				}
				
				$answer->save();
			}
			else
			{		
				$answer   = ORM::factory('answer_orm',$array_id[$i]);	
				$answer->questionnaires_uid = $question->uid;
				$answer->answer = $value;
				
				$answer->type = (@$array_type[$i++] == 'on')? 1 : 0;
				$answer->random = (isset($array_random) == 'on')? 1 : 0;
				if(isset($_FILES['answer_file'.$i]) && !empty($_FILES['answer_file'.$i]['name'])){
					
					$file = $_FILES['answer_file'.$i];
					
					if($_FILES['answer_file'.$i]['type'] == 'image/bmp')
					{
						Kohana::config_set('upload.directory', DOCROOT.'uploads/answer');
						$filename = upload::save($name,$_FILES['answer_file'.$i]['name']);
						$img = $this->ImageCreateFromBmp($filename);
						/*** write the new jpeg image ***/
						$file_images = time().md5(basename($filename));
						imagegif($img, DOCROOT.'uploads/answer/'.$file_images.'.gif');
					}
					else $file_images = $this->upload_file($file['name'],$file);
				$answer->images = $file_images ;
				
				}
				$answer->save();
				
			}
		  
		  }
			$k++;$r++;
		}
		$this->session->set('se_numasnwer', $k);
		if($question->saved)
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
		if($this->input->post('hd_save_add')=='add')
			url::redirect('admin_questionnaires/create');		
		//elseif(empty($frm['hd_id']))
			else
			url::redirect('admin_questionnaires/edit/'.$question->uid);
		//else
			//url::redirect('admin_questionnaires/edit/'.$frm['hd_id']);
		die(); 	
    }   
	public function setStatusQuestion($id , $value){
		$question = ORM::factory('questionnaires_orm', $id);	
		$question->status = ucfirst($value);
		$question->save();
		echo ' Edit udi = "'.$id.'" with value = "'.$value.'" success ';
		die();
	}
	
	public function getCateggory($id){
		$list = $this->category_model->getCategoryById('test_uid',$id);
		$str='';
		
		foreach($list as $value){
			$expand = '';
				 for ($k=1;$k<$value['level'];$k++)
				 $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				 $expand .= '|----';
				 if($value['level'] == 1 || $value['category']=='')
				 	$expand = '';
			$str .= "<option value=". $value['uid'] .">" . $expand.$value['category'] . "</option>";	
		}
		echo $str;
		die();
	}
	
	public function getCategoryById($id){
		return $this->category_model->get($id);
	}
	
	public function getCategoryByIdJson($id){
		$re = $this->category_model->getCategoryById('test_uid',$id);
		echo json_encode($re);
		die();
	}
	
	public function getTestById($id){
		return $this->test_model->get($id);
	}
}
?>