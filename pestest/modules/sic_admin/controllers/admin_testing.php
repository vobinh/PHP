<?php
class Admin_testing_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->testing_model          = new Testing_Model(); 
		$this->member_model           = new Member_Model();
		$this->test_model             = new Test_Model(); 
		$this->category_model         = new Category_Model();
		$this->testing_category_model = new Testingcategory_Model();
		$this->testingdetail_model    = new Testingdetail_Model(); 
		$this->questionnaires_model   = new Questionnaires_Model(); 
		$this->answer_model           = new Answer_Model();
		$this->lesson_model           = new Lesson_Model();
		$this->courses_model          = new Courses_Model();
		
		parent::__construct();
		
	}
	
	public function index(){             
		$this->showlist();
		$this->search = array('keyword' => '');	
    }
	
	private function showlist(){
    	$this->template->content = new View('admin_testing/list');
		$this->where_sql();
		$total_items = count($this->testing_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_testing/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->testing_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		$mlist = $this->testing_model->get();
		foreach($mlist as $key => $value){
			if(!empty($value['id_lesson'])){
				$m_lesson           = $this->lesson_model->get($value['id_lesson']);
				$mlist[$key]['lesson_title']  = $m_lesson['title'];
				$m_courese          = $this->courses_model->get($m_lesson['id_courses']);
				$mlist[$key]['courses_title'] = $m_courese['title'];
			}else{
				$mlist[$key]['lesson_title']  = '';
				$m_courese          = $this->courses_model->get($value['id_course']);
				$mlist[$key]['courses_title'] = $m_courese['title'];
			}
		}
		$this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	
	public function delete($id){				
		$code            = $this->testing_model->get($id);
		$result_question = $this->testing_model->delete($id);
		$this->testing_category_model->deletebycol('testing_code',$code['testing_code']);
		$this->testingdetail_model->deletebycol('testing_code',$code['testing_code']);
		$json['status']  = $result_question?1:0;
		$json['mgs']     = $result_question?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user']    = array('id' => $id);
		echo json_encode($json);
        die();
    }
	
	public function search()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$txt_keyword = $this->input->post('txt_keyword');
		$val1        = $this->input->post('txt_val1');
		$val2        = $this->input->post('txt_val1');
		$valdate1    = $this->input->post('txt_valdate1');
		$valdate2    = $this->input->post('txt_valdate2');
		if ($txt_keyword !== NULL || $val1 !== NULL || $valdate1 !== NULL)	{// if new search key exist
			$this->search['sel_option'] = $this->input->post('sel_option');
			$this->search['keyword']    =  $this->input->post('txt_keyword');
			$this->search['val1']       = $this->input->post('txt_val1');
			$this->search['val2']       = $this->input->post('txt_val2');
			$this->search['valdate1']   = $this->input->post('txt_valdate1');
			$this->search['valdate2']   = $this->input->post('txt_valdate2');
			$this->session->set_flash('sess_search',$this->search);
		}
		$this->showlist();
	}
	
	public function where_sql()
    {
		/*$col = $this->input->post('sel_option');
		$keyword = $this->input->post('txt_keyword');
		$col = $this->input->post('sel_option');
		$val1 = $this->input->post('txt_val1');
		$val2 = $this->input->post('txt_val2');
		$valdate1 = $this->input->post('txt_valdate1');
		$valdate2 = $this->input->post('txt_valdate2');
		
		if(isset($val1) && isset($val2)){
			$this->db->where(array($col.' >=' => $val1,$col.' <= ' => $val2));
		}
		if(isset($valdate1) && isset($valdate2)){
			$this->db->where(array($col.' >=' => strtotime($valdate1),$col.' <= ' => strtotime($valdate2)));
		}
		if(isset($col) && isset($keyword)){
			 $keyword = strtolower($keyword);
			$this->db->where("LCASE(".$col.") LIKE '%" .$keyword. "%'");
		//	$this->db->like($col ,$keyword);
			if($col=='member_fname')
			$this->db->orwhere("LCASE(member_lname) LIKE '%" .$keyword. "%'");
			//	$this->db->orlike('member_lname',$keyword);
		}*/
		if(isset($this->search['val1']) && isset($this->search['val2'])){
			$this->db->where(array($this->search['sel_option'].' >=' => $this->search['val1'],$this->search['sel_option'].' <= ' => $this->search['val2']));
		}

		if(isset($this->search['valdate1']) && isset($this->search['valdate2'])){
			if(!empty($this->search['valdate2']))
				$this->db->where(array($this->search['sel_option'].' >=' => strtotime($this->search['valdate1']),$this->search['sel_option'].' <= ' => strtotime($this->search['valdate2'])));
			else{
				$this->db->where(array($this->search['sel_option'].' >=' => strtotime($this->search['valdate1']),$this->search['sel_option'].' <= ' => strtotime($this->search['valdate1'])+86399));
			} 
		}

		if(isset($this->search['sel_option']) && isset($this->search['keyword'])){
			$this->search['keyword'] = strtolower($this->search['keyword']);
			$this->db->where("LCASE(".$this->search['sel_option'].") LIKE '%" .$this->search['keyword']. "%'");
			//$this->db->like($col ,$keyword);
			if($this->search['sel_option']=='member_fname')
				$this->db->orwhere("LCASE(member_lname) LIKE '%" .$this->search['keyword']. "%'");
			//$this->db->orlike('member_lname',$keyword);
		}
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
	
	public function showlistdetail($idtesting,$id='',$type,$id_lesson){
		// echo $id_lesson;
		// die();
		if(isset($idtesting) && isset($id) && isset($id_lesson)){
			$this->template->content = new View('admin_testing/listdetail');
	
			$this->db->where('testing_code',$id);
			if($type == 1)
				$this->db->where('id_lesson',$id_lesson);
			else
				$this->db->where('id_course',$id_lesson);

			$total_items = count($this->testingdetail_model->get());
			
			if(isset($this->search['display']) && $this->search['display']){
				if($this->search['display'] == 'all')
					$per_page = $total_items;
				else $per_page = $this->search['display']; 
			} else
				$per_page = $this->site['site_num_line2'];
		
			$this->pagination = new Pagination(array(
				'base_url'       => 'admin_testing/showlistdetail/'.$idtesting.'/'.$id.'/'.$type.'/'.$id_lesson.'/search/',
				'uri_segment'    => 'page',
				'total_items'    => $total_items,
				'items_per_page' => $per_page,
				'style'          => 'digg',
			));
					
			$this->testingdetail_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
			$this->db->where('testing_code',$id);

			if($type == 1)
				$this->db->where('id_lesson',$id_lesson);
			else
				$this->db->where('id_course',$id_lesson);

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
						$mlist[$key]['category'] = $parent['category'].' - '.$category['category'];	
					}else{
						$mlist[$key]['category']='';
					}	
				}
				$mlist[$key]['answer']   = $this->answer_model->get($value['selected_answer']);
				$mlist[$key]['question']   = $this->questionnaires_model->get($value['questionnaires_uid']);
			}
			
			$testing = $this->testing_model->get($idtesting);
			//echo '<pre>';
			//print_r($testing);
			//die();
			// if testing == 

			$testing['menber']   = $this->member_model->get($testing['member_uid']);
			$testing['test']     = $this->test_model->get($testing['test_uid']);
			if($type == 1){
				$m_lesson           = $this->lesson_model->get($testing['id_lesson']);
				$testing['lesson']  = $m_lesson;
				$m_courese          = $this->courses_model->get($m_lesson['id_courses']);
				$testing['courses'] = $m_courese;
			}else{
				$testing['lesson']  = '';
				$m_courese          = $this->courses_model->get($testing['id_course']);
				$testing['courses'] = $m_courese;
			}
			
			$mlist[0]['testing'] = $testing;
			
			$this->template->content->set(array(
				'mlist'     => $mlist,
				'idtesting' => $idtesting,
				'id_test'   => $id,
				'type'      => $type,
				'id_lesson' => $id_lesson,
			));
		}
    }
}
?>