<?php
class Admin_Test_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->test_model = new Test_Model(); 
		$this->payment_model =new Payment_Model();
		
		$this->category_model = new Category_Model();
		$this->testing_category_model= new Testingcategory_Model();
		$this->testingdetail_model = new Testingdetail_Model(); 
		
		$this->questionnaires_model = new Questionnaires_Model(); 
		$this->answer_model   = new Answer_Model(); 
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
    	$this->template->content = new View('admin_test/list');
		$this->where_sql();
		$total_items = count($this->test_model->get());
		
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
    		'base_url'       => 'admin_test/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->test_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		$this->db->orderby('test_title','ASC');
		$mlist = $this->test_model->get();
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
			$this->db->like('test_description',$this->search['keyword']);
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
					$this->db->delete('test', array('uid' => $arr_id[$i]));
				}
			} elseif($sel_action == 'active') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('test', array('status' => '1'), array('uid' => $arr_id[$i]));
				}				
			} 
			 elseif($sel_action == 'inactive') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('test', array('status' => '0'), array('uid' => $arr_id[$i]));
				}				
			} 
			
		} else {		
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if (count($status)>0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
		url::redirect('admin_test');
	}
	
	
	public function create()
    {		
		$this->template->content = new View('admin_test/frm');
		$this->template->content->mr = $this->mr;				
    }
	
	public function edit($id)
    {		
		$this->template->content = new View('admin_test/frm');
		$this->template->content->test = $this->test_model->get($id);	    			
    }
	
	private function _get_frm_valid()
    {
  		$form = $this->test_model->get_frm();
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
				
                if($hd_id) url::redirect('admin_test/edit/'.$hd_id);
                else url::redirect('admin_test/create');
				die();
			}
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
	
    public function delete($id)
    {			
    		///////////
			 $this->db->where('test_uid',$id);
			 $this->db->delete('category');
			 
			 $this->db->where('test_uid',$id);
			 $mlist = $this->questionnaires_model->get();
			 for($i=0;$i<count($mlist);$i++)
			 {
			     $this->db->where('questionnaires_uid',$mlist[$i]['uid']);
				 $this->db->delete('answer');
				 //////
				  $this->db->where('questionnaires_uid',$mlist[$i]['uid']);
				 $this->db->delete('testing_detail');
				 ////
				 $this->questionnaires_model->delete($mlist[$i]['uid']);
			 }
			 
			 $this->db->where('test',$id);
			 $this->db->delete('testing_category');
			 
			 
			//////////
			$result_test = $this->test_model->delete($id);
			
			$json['status'] = $result_test?1:0;
			$json['mgs'] = $result_test?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
	
	public function save()
    {   	    	
		$frm = $this->_get_frm_valid();
		
		if(empty($frm['hd_id']))
		{
			$test = ORM::factory('test_orm');
			
		} else {
			$test = ORM::factory('test_orm', $frm['hd_id']);	
		}	
		$test->test_title	 = $frm['txt_title']; // active
		$test->test_description =$frm['erea_description'];
		$test->qty_question = $frm['txt_question']; // admin
		$test->type_time  = $frm['sel_type_time'];
		$test->time_value	 = $frm['txt_time_value'];
		$test->date	 = $frm['txt_date'];	
		$test->pass_score	 = $frm['txt_pass_score'];	
		$test->price	 = $frm['txt_price'];	
		$test->status = $frm['sel_status'];
		$test->questionpage = $frm['txt_questionpage'];
		$test->displayexplanation = $frm['displatex'];
		$test->save();	
		
		if($test->saved)
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
			url::redirect('admin_test/create');		
		//elseif(empty($frm['hd_id']))
		else	
			url::redirect('admin_test');
		//else
		//	url::redirect('admin_test/edit/'.$frm['hd_id']);
		die(); 	
    }   
	
	public function setstatus($id)
    {    	    	
       
		$result = ORM::factory('test_orm', $id);
		$result->status = abs($result->status  - 1);
		$result->save();       	
		$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
		url::redirect(uri::segment(1));
		die();
       
    }
	public function search_member($uid)
    {			
		$this->member($uid);
    } 
	public function member($uid)
	{
		$view =new View('admin_test/listmember');
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

}
?>