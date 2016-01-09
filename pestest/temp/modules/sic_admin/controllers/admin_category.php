<?php
class Admin_category_Controller extends Template_Controller
{
	public $search;
	public $template = 'admin/index';	
	
    public function __construct()
    {
        parent::__construct();
		$this->category_model = new Category_Model();  
		$this->test_model= new Test_Model();
		$this->test_question= new Questionnaires_Model();
		$this->model = new Category_orm_Model();
        //  $this->search = array('display' => '');
	  	$this->search = array('keyword' => '','page' => 0,'cur_page' => '','type' => '','sort' => '','per_page' => '20','option' => '','display' => '');
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
    
   
    private function showlist()
    {
    	$model = new Category_orm_Model();
		$this->template->content = new View('admin_category/list');
		//Assign
        $this->template->content->set(array(
            'display' => $this->search['display']
        ));
		if(isset($this->search['keyword']))
			$this->template->content->keyword = $this->search['keyword'];
		if(isset($this->search['option']))
       		$this->template->content->option = $this->search['option'];
		
		if (isset($this->search['keyword']) != "")
		{														
			$this->category_model->search($this->search);			
		}
		$this->db->orderby('test_uid','ASC');
		//$this->db->orderby('uid','desc');
		$mlist = $this->category_model->get();
		foreach($mlist as $key => $value){
			$mlist[$key]['test']     = $this->getTestById($value['test_uid']);
			if($value['level']==1 && count($this->category_model-> getCategoryById('parent_id', $value['uid']))>0){
				$mlist[$key]['hidenParent'] = 1;
			}
			else{
				$mlist[$key]['hidenParent'] = 0;
			}
		}
		
        $this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	
    public function create()
    {		
		$this->template->content = new View('admin_category/frm');    	
		$this->template->content->test = $this->getTest();	
		$this->template->content->list_category = $this->model->get();		
    }
	
	public function getTest(){
		$this->db->orderby('test_title','ASC');
		return $this->test_model->get();
	}
    
    public function edit($id)
    {	   	
		$this->template->content = new View('admin_category/frm');
		$this->template->content->mr = $this->category_model->get($id);
		$this->template->content->test = $this->getTest();	
		$this->template->content->list_category = $this->model->get();
    }
    
    
    private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    
    	$form = $this->category_model->get_frm();
		
		$errors = $form;		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);			
			$post->add_rules('txt_cate','required');			
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
				
                if($hd_id) url::redirect('admin_category/edit/'.$hd_id);
                else url::redirect('admin_category/create');
				die();
			}
        }
    }
    
    public function save()
    {   	    	
    	$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id'])){ // create new
			$result = ORM::factory('category_orm');
			$result->insert_as_first_child($frm['sel_parent_name']);
		} else { // edit
			$result = ORM::factory('category_orm',$frm['hd_id']);	// current menu categories id
			$old_parent = ORM::factory('category_orm',$frm['hd_id'])->__get('parent');		// old parent 	
			$new_parent = ORM::factory('category_orm',$frm['sel_parent_name']);	// new parent has choice
			// if new parent different old parent && not itself
			if ($frm['sel_parent_name'] != $old_parent->uid && $frm['sel_parent_name'] != $frm['hd_id'])
			{			
				$result->move_to_first_child($new_parent);
			}
		} 
		
		$result->parent_id = $frm['sel_parent_name'];
		$result->category = $frm['txt_cate'];
		$result->status = $frm['sel_status'];
		$result->test_uid = $frm['sel_test'];
		$result->category_percentage = $frm['sel_cate_per'];
		$result->save();
		
		if($result->saved)
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
		
		if($this->input->post('hd_save_add'))
			url::redirect('admin_category/create');		
		//elseif(empty($frm['hd_id']))
		else	
			url::redirect('admin_category');
		//else
		//	url::redirect('admin_category/edit/'.$frm['hd_id']);
		//die(); 	
    }   
    
    public function delete($id)
    {				
        
    		$result_admin = $this->category_model->delete($id);
        	$json['status'] = $result_admin?1:0;
			$json['mgs'] = $result_admin?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
    
    public function setstatus($id)
    {    	    	
        
    		$result = ORM::factory('category_orm', $id);
        	$result->status = abs($result->status - 1);
        	$result->save();       	
        	$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
        	url::redirect(uri::segment(1));
            die();
    }
	
	public function getTestById($id){
		return $this->test_model->get($id);
	}
	
	public function checkpercent($value='',$id='',$uid=''){
		$str_error = $this->checkValue($id,$value,$uid);
		print_r($str_error);
		die();
	}
	
	private function checkValue($id,$value,$uid){
		$val=explode('.',$value);
		$val=$val[0];
		$str_error='';
		//check input value to 0-100 and value is number
		if((int)$val > 100 || (!is_numeric($val)) || $val < 0 ){
					  $str_error.='- Pecentage input 0 to 100. <br/>';
		}
		//get list category by test_uid
		$list = $this->category_model->getCategoryById('test_uid',$id);
		//if list not empty 		
		if(!empty($list)){
				//get number qty_question of test by category_id as num
				$numquestion = $this->test_model->get($id);
				$num = $numquestion['qty_question'];
				
				//count recod question by category id 
				$count = count($this->test_question->getQuestionById('category_uid',$uid));
			
				//get % of category current as subpercent
				$subpercent = 0;
				if(isset($uid)){	
					$categoryrow = $this->category_model->getCategoryById('test_uid',$id,$uid);
				}
				if(!empty($categoryrow))
					$subpercent = (int)$categoryrow[0]['category_percentage'];
				
				//get % test of category current 
				$total=0;
				foreach($list as $value){
					 $total += $value['category_percentage'];
				}
				
				//check question missing 
				$check_percentage = ($num*$val)/100;
				if($check_percentage > $count)
					  $str_error.='- Category missing '.($check_percentage - $count).' questions. <br/>';
				
				  
				//check interger by percent
				if(!is_int($check_percentage))
					  $str_error.='- Qty questions of should be integers. <br/>';
				
				//check % input	  
				$sum = $val+$total-$subpercent;
				if($sum > 100)
					  $str_error .='- Pecentage error curent '.$sum.'% <br/>';
		}else{
				if($id=='')
					$str_error .='- Please choise test<br/>';
				
		}
		return $str_error;
	}
	
	public function getTestByParent($id){
		$list=$this->category_model->get($id);
		$list=$this->test_model->get($list['test_uid']);
		echo json_encode($list);
		die();
	}
	
	public function editPercent($val,$uid,$test_uid){
	
	$str_error = $this->checkValue($test_uid,$val,$uid);
	if($str_error != ''){
		print_r($str_error);
	}else{
			$val = explode('.',$val);
			$val=$val[0];
			$result = ORM::factory('category_orm',$uid);
			$result->category_percentage = $val;
			$result->save();
			if($result->saved)
				print_r('Edit success');
		}
		die();
	}
	
}
?>