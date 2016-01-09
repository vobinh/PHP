<?php
class Admin_promotion_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->promotion_model         = new Promotion_Model();
		$this->test_model              = new Test_Model();
		$this->payment_model           = new Payment_Model();
		$this->member_model            = new Member_Model();
		$this->test_model              = new Test_Model();
		
		$this->courses_model           = new Courses_Model();
		$this->study_model             = new Study_Model();
		$this->lesson_model            = new Lesson_Model();
		$this->lesson_annotation_model = new Lesson_annotation_Model();
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
		$this->_get_submit();	
		
		$this->search['keyword'] ="";
		$this->search['test']="";
		if($this->session->get('sess_search'))	
        $this->session->delete('sess_search'); 
		$this->showlist();
        	
    }
	
	
	private function showlist()
    {
    	$this->template->content = new View('admin_promotion/list');
		$this->where_sql();
		$total_items = count($this->promotion_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_promotion/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->promotion_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		
		$this->where_sql();
		$mlist = $this->promotion_model->get();
		foreach($mlist as $key => $value){
			$mlist[$key]['courses']     = $this->getTestById($value['courses_id']);
		}
		$this->template->content->set(array(
            'mlist' => $mlist
		));
		$this->template->content->set(array(
            'courses' => $this->courses_model->get() 
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
		if(isset($test)){
			$this->search['test'] = $this->input->post('sel_test');
		}
		
		$this->session->set_flash('sess_search',$this->search);
		$this->showlist();
	}
	
	public function where_sql()
    {
		if(isset($this->search['test']) && $this->search['test']!='Status' && $this->search['test']!=''){
			if($this->search['keyword'])
				$this->db->like('company',$this->search['keyword']);
			if(isset($this->search['test']))
				$this->db->where('courses_id',$this->search['test']);
		
		}elseif(isset($this->search['test']) && $this->search['test']!=''){
		   	if($this->search['keyword']) 
		    	$this->db->where('LCASE(status) LIKE'.$this->db->escape('%'.$this->search['keyword'].'%'));
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
	 
	public function generate_code(){
		$number = $this->input->post('txt_sl');
		$arr_generate = array();
		if($number > 0){
			for ($i=0; $i < $number ; $i++) {
				$code = strtoupper(text::random('alnum',12));
				$this->db->where('promotion_code',$code);
				$this->db->where('(type <> "2" OR isnull(type))');
				$result = $this->db->get('promotion')->result_array(false);
				if(!empty($result)){
					$code = $this->type_1();
					$this->db->where('code',$code);
					$result_2 = $this->db->get('promotion_item')->result_array(false);
					if(!empty($result_2)){
						$code = $this->type_2();
						if(in_array($code, $arr_generate)){
							$code = $this->check_generate_code($arr_generate);
						}
					}else{
						if(in_array($code, $arr_generate)){
							$code = $this->check_generate_code($arr_generate);
						}
					}
				}else{
					$this->db->where('code',$code);
					$result_2 = $this->db->get('promotion_item')->result_array(false);
					if(!empty($result_2)){
						$code = $this->type_2();
						if(in_array($code, $arr_generate)){
							$code = $this->check_generate_code($arr_generate);
						}
					}else{
						if(in_array($code, $arr_generate)){
							$code = $this->check_generate_code($arr_generate);
						}
					}
				}
				/**
				 * add arr
				 */
				$arr_generate[] = $code;
			}
		}
		$view = new view('admin_promotion/list_generate_code');
		$view->arr_generate = $arr_generate;
		$view->render(true);
		die();
	}

	public function check_generate_code($arr){
		$code = strtoupper(text::random('alnum',12));
		if(in_array($code, $arr)){
			$this->check_generate_code($arr);
		}else{
			return $code;
		}
	}

	public function type_1(){
		$code = strtoupper(text::random('alnum',12));
		$this->db->where('promotion_code',$code);
		$this->db->where('(type <> "2" OR isnull(type))');
		$result = $this->db->get('promotion')->result_array(false);
		if(!empty($result)){
			$this->type_1();
		}else{
			return $code;
		}
	}

	public function type_2(){
		$code = strtoupper(text::random('alnum',12));
		$this->db->where('code',$code);
		$result = $this->db->get('promotion_item')->result_array(false);
		if(!empty($result)){
			$this->type_2();
		}else{
			return $code;
		}
	}

	public function create($multiple='')
    {		
		$this->template->content = new View('admin_promotion/frm');
		//$this->template->content->test = $this->test_model->get();   	
		$this->template->content->courses = $this->courses_model->get();   	
		$this->template->content->mr      = $this->mr;
		$this->template->content->m_type  = !empty($multiple)?$multiple:'';
    }
	
	public function edit($id)
    {		
		$this->template->content = new View('admin_promotion/frm');
		$promotion = $this->promotion_model->get($id);
		$m_type        = 'singer';
		$arr_item_code = '';
		if(!empty($promotion)){
			if($promotion['type'] == 2){
				$m_type = 'multiple';
				$this->db->where('promotion_id',$promotion['uid']);
				$arr_item_code = $this->db->get('promotion_item')->result_array(false);
			}
		}
		$this->template->content->set(array(
			'pro'           => $promotion,
			'courses'       => $this->courses_model->get(),
			'arr_item_code' => $arr_item_code,
			'm_type'        => $m_type
        ));		
    }
	
	private function _get_frm_valid(){
		$form   = $this->promotion_model->get_frm();
		$hd_id  = $this->input->post('hd_id');
		$m_type = $this->input->post('txt_m_type');
		$errors = $form;
		if($_POST){
    		$post = new Validation($_POST);
			$post->add_rules('txt_company','required');	
			$post->add_rules('txt_email','required');
			if($m_type != 'multiple'){
				$post->add_rules('txt_code','required');		
			    if (empty($hd_id))	// create account
	                $post->add_callbacks('txt_code',array($this,'_check_code'));
				else	
					$post->add_callbacks('txt_code',array($this,'_check_code_exist'));
			}

			if($post->validate()){
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			}else{
				$form      = arr::overwrite($form,$post->as_array());				
				$errors    = arr::overwrite($errors, $post->errors('promotion_validation'));
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
                if($hd_id)
                	url::redirect('admin_promotion/edit/'.$hd_id);
                else {
                	if($m_type == 'multiple')
                		url::redirect('admin_promotion/create/'.$m_type);
                	else
                		url::redirect('admin_promotion/create');
                }
				die();
			}
        }
    }
	
	public function _check_code_exist($array,$field){
		$hd_id = $this->input->post('hd_id');
		$this->db->where('uid<>',$hd_id);
		$this->db->where('promotion_code',$array[$field]);
		$code_exist = $this->promotion_model->get();
		
		if(isset($code_exist[0]['promotion_code'])){
			$array->add_error($field,'_check_code');
		}else{
			$this->db->where('code',$array[$field]);
			$m_item = $this->db->get('promotion_item')->result_array(false);
			if(!empty($m_item)){
				$array->add_error($field,'_check_code');
			}
		}
	}
	public function _check_code($array,$field){
		$code_exist = ORM::factory('promotion_orm')->where('promotion_code',$array[$field])->count_all();
		if($code_exist){
			$array->add_error($field,'_check_code');
		}else{
			$this->db->where('code',$array[$field]);
			$m_item = $this->db->get('promotion_item')->result_array(false);
			if(!empty($m_item)){
				$array->add_error($field,'_check_code');
			}
		}
	}
	
	public function saveall(){
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
					$promotion = ORM::factory('promotion_orm', $arr_id[$i]);
			    	if($promotion->type == '2'){
			    		$this->db->where('promotion_id', $arr_id[$i]);
			    		$this->db->delete('promotion_item');
			    	}
					$this->db->delete('promotion', array('uid' => $arr_id[$i]));
				}
			} elseif($sel_action == 'Active') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('promotion', array('status' => 'Active'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'Inactive') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('promotion', array('status' => 'Inactive'), array('uid' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'Expired') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('promotion', array('status' => 'Expired'), array('uid' => $arr_id[$i]));
				}				
			}
		} else {		
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if (count($status)>0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
		url::redirect('admin_promotion');
	}	
	
    public function delete($id){
    	$promotion = ORM::factory('promotion_orm', $id);
    	if($promotion->type == '2'){
    		$this->db->where('promotion_id', $id);
    		$this->db->delete('promotion_item');
    	}
		$result_question = $this->promotion_model->delete($id);
		$json['status']  = $result_question?1:0;
		$json['mgs']     = $result_question?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user']    = array('id' => $id);
		echo json_encode($json);
        die();
    }
	
	
	public function save(){
		$frm    = $this->_get_frm_valid();
		$m_type = $this->input->post('txt_m_type');

		if(empty($frm['hd_id'])){
			$promotion = ORM::factory('promotion_orm');
		}else{
			$promotion = ORM::factory('promotion_orm', $frm['hd_id']);	
		}	
			
		$promotion->date           = strtotime($frm['txt_date']);
		$promotion->description    = $frm['erea_description'];
		$promotion->company        = $frm['txt_company'];
		$promotion->email          = $frm['txt_email'];	
		$promotion->qty            = $frm['txt_qty'];	
		//$promotion->test_uid     = $frm['sel_test'];
		$promotion->courses_id     = $frm['sel_test'];
		$promotion->start_date     = strtotime($frm['txt_start']);	
		$promotion->end_date       = strtotime($frm['txt_end']);	
		$promotion->status         = $frm['sel_status'];
		if($m_type == 'multiple')
			$promotion->type = 2;
		else{
			$promotion->type = 1;
			$promotion->promotion_code = $frm['txt_code']; 
		}

		if(isset($frm['chbdiscount']) && $frm['chbdiscount']==1)
			$promotion->promotion_price = 0;
		else
			$promotion->promotion_price = $frm['txt_promotion_price'];
		
		$promotion->save();	
		
		if($promotion->saved){
			if(empty($frm['hd_id'])){
				/**
				 * add
				 */
				if($m_type == 'multiple'){
					/**
					 * add promotion item
					 */
					if(isset($_POST['txt_code_item']) && !empty($_POST['txt_code_item'])){
						foreach ($_POST['txt_code_item'] as $key => $item_code) {
							$promotion_item = ORM::factory('promotion_item_orm');
							$promotion_item->code         = $item_code;
							$promotion_item->status       = '1';
							$promotion_item->promotion_id = $promotion->uid;
							$promotion_item->save();
						}
					}
				}
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
			}
			else{
				/**
				 * edit
				 */
				if(isset($_POST['txt_h_id_code'])){
					foreach ($_POST['txt_h_id_code'] as $key => $item_code) {
						if(isset($_POST['chk_status_code_'.$item_code]) && !empty($_POST['chk_status_code_'.$item_code])){
							$promotion_item = ORM::factory('promotion_item_orm', $item_code);
							if($promotion_item->status == '1'){
								$promotion_item->date_change = time();
								$promotion_item->user_change = $this->sess_admin['username'];
								$promotion_item->status      = '2';
								$promotion_item->save();
							}
							
						}else{
							$promotion_item = ORM::factory('promotion_item_orm', $item_code);
							$promotion_item->date_change = time();
							$promotion_item->user_change = $this->sess_admin['username'];
							$promotion_item->status      = '1';
							$promotion_item->save();
						}
					}
				}
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));		
			}
		}else {
			if(empty($frm['hd_id']))
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_add'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}			
		if($this->input->post('hd_save_add') == 'add'){
			if($m_type == 'multiple')
				url::redirect('admin_promotion/create/'.$m_type);
			else
				url::redirect('admin_promotion/create');	
		}
		else
			url::redirect('admin_promotion');
		die(); 	
    }   

	public function setStatusPromotion($id , $value){
		$question = ORM::factory('promotion_orm', $id);
		if($question->type == '2'){
			$msg_arr = array(
				'type'  => 2, 
				'id'    => $id,
				'value' => $value,
			);
			if($value == 'Batch'){
				$data = array(
					'status'      => '1',
					'date_change' => time(),
					'user_change' => $this->sess_admin['username'],
				);
				$this->db->where('promotion_id',$id);
				$this->db->update('promotion_item',$data);
				$value = 'Active';
			}

		}else{
			$msg_arr = array(
				'type' => 1, 
				'id'   => $id, 
			);
		}

		$question->status = ucfirst($value);
		$question->save();
		echo json_encode($msg_arr);
		die();
	}
	
	public function getTestById($id){
		//return $this->test_model->get($id);
		return $this->courses_model->get($id);
	}
	public function search_transaction($code)
    {			
		$this->mytest($code);
    }  
	/////////
	public function transaction($code="",$type=""){
		$view        = new View('admin_promotion/transaction');
		if(empty($type)){
			$this->db->where('promotion_code',$code);
			$list        = $this->payment_model->getpayment();
			$total_items = count($list);
				
			if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else
					$per_page = $this->search['display']; 
			}else
				$per_page = $this->site['site_num_line2'];

			$this->pagination = new Pagination(array(
				'base_url'       => 'admin_promotion/search_transaction/'.$code,
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));	
			$this->payment_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
			$this->db->where('promotion_code', $code);
			$mlist = $this->payment_model->getpayment();
			foreach($mlist as $key => $value){
				$mlist[$key]['member']   = $this->member_model->get($value['member_uid']);
				$mlist[$key]['test']     = $this->courses_model->get($value['courses_id']);
			}
			$view->mlist = $mlist;
		}else{
			$arr_item = array();
			$id_promotion = $code;
			$this->db->where('promotion_id',$id_promotion);
			$list_item = $this->db->get('promotion_item')->result_array(false);
			if(!empty($list_item)){
				foreach ($list_item as $key => $value) {
					$arr_item[] = $value['code'];
				}
			}
			$this->db->in('promotion_code',$arr_item);
			$list        = $this->payment_model->getpayment();
			$total_items = count($list);
				
			if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else
					$per_page = $this->search['display']; 
			}else
				$per_page = $this->site['site_num_line2'];

			$this->pagination = new Pagination(array(
				'base_url'       => 'admin_promotion/search_transaction/'.$code,
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));	
			$this->payment_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
			$this->db->in('promotion_code',$arr_item);
			$mlist = $this->payment_model->getpayment();
			foreach($mlist as $key => $value){
				$mlist[$key]['member']   = $this->member_model->get($value['member_uid']);
				$mlist[$key]['test']     = $this->courses_model->get($value['courses_id']);
			}
			$view->mlist = $mlist;
		}
		$view->render(true);
		die();
	}

	public function checkprice($value='',$id=''){
		$str_error = $this->checkValue($id,$value);
		print_r($str_error);
		die();
	}
	
	private function checkValue($id,$value){
		$val=explode('.',$value);
		$str_error='';
		
		$test = $this->test_model->get($id);
		//if list not empty 		
		if(!empty($test['price'])){
				
				if($test['price'] < $value)
					  $str_error .='Discount dollar value must be lower than the cost of the test!<br/>';
		}else{
				if($id=='')
					$str_error .='- Please choise test<br/>';
				
		}
		return $str_error;
	}
	
	////
}
?>