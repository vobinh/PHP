<?php
class Admin_Test_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->test_model             = new Test_Model(); 
		$this->payment_model          = new Payment_Model();
		
		$this->category_model         = new Category_Model();
		$this->testing_category_model = new Testingcategory_Model();
		$this->testingdetail_model    = new Testingdetail_Model(); 
		
		$this->questionnaires_model   = new Questionnaires_Model(); 
		$this->answer_model           = new Answer_Model(); 
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
		//$this->db->orderby('test_title','ASC');
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
	
	public function import(){
		if(isset($_FILES)){
            $xml = @simplexml_load_file($_FILES['txt_import']['tmp_name']);
            if($xml === false) { 
            	$this->session->set_flash('warning_msg','Load file XML errors, Check structure!');	
				url::redirect('admin_test');
				die();
            }
            if(!isset($xml->testinfo)){
            	$this->session->set_flash('warning_msg','Structure file XML errors!');	
				url::redirect('admin_test');
				die();
            }else{
            	$this->db->where('parent_id',0);
            	$this->db->where('level',0);
            	$root_parent_id = $this->category_model->get();
            	$root_parent_id = !empty($root_parent_id[0])?$root_parent_id[0]['uid']:0;
            	//die();
				$test                     = ORM::factory('test_orm');		
				$test->test_title         = (string)$xml->testinfo->test_title; // active
				$test->test_description   = (string)$xml->testinfo->test_description;
				$test->qty_question       = $xml->testinfo->qty_question; // admin
				$test->type_time          = $xml->testinfo->type_time;
				$test->time_value         = $xml->testinfo->time_value;
				$test->date               = $xml->testinfo->date;	
				$test->pass_score         = $xml->testinfo->pass_score;	
				$test->price              = $xml->testinfo->price;	
				$test->status             = $xml->testinfo->status;
				$test->questionpage       = $xml->testinfo->questionpage;
				$test->displayexplanation = (string)$xml->testinfo->displayexplanation;
				$test->save();

				if($test->saved){
            		$arr_id_cate = array();
					if(isset($xml->categorys->category)){
						foreach ($xml->categorys->category as $category) {
							if(empty($arr_id_cate)){
								$result_category = ORM::factory('category_orm');
								$result_category->insert_as_first_child($root_parent_id);

								$result_category->parent_id           = $root_parent_id;
								$result_category->path                = (string)$category->path;
								$result_category->test_uid            = $test->uid;
								$result_category->state               = (string)$category->state;
								$result_category->industry            = (string)$category->industry;
								$result_category->branch              = (string)$category->branch;
								$result_category->sub_branch          = (string)$category->sub_branch;
								$result_category->category            = (string)$category->category;
								$result_category->sub_category        = (string)$category->sub_category;
								$result_category->category_percentage = (float)$category->category_percentage;
								$result_category->status              = (string)$category->status;
								$result_category->save();

								if($result_category->saved){
									$arr_id_cate["$category->uid"] = $result_category->uid;
								}
							}else{

								$result_category = ORM::factory('category_orm');
								if(!empty($arr_id_cate["$category->parent_id"])){
									$result_category->insert_as_first_child($arr_id_cate["$category->parent_id"]);
									$result_category->parent_id  = $arr_id_cate["$category->parent_id"];
								}else{
									$result_category->insert_as_first_child($root_parent_id);
									$result_category->parent_id  = $root_parent_id;
								}
								$result_category->path                = (string)$category->path;
								$result_category->test_uid            = $test->uid;
								$result_category->state               = (string)$category->state;
								$result_category->industry            = (string)$category->industry;
								$result_category->branch              = (string)$category->branch;
								$result_category->sub_branch          = (string)$category->sub_branch;
								$result_category->category            = (string)$category->category;
								$result_category->sub_category        = (string)$category->sub_category;
								$result_category->category_percentage = (float)$category->category_percentage;
								$result_category->status              = (string)$category->status;
								$result_category->save();

								if($result_category->saved){
									$arr_id_cate["$category->uid"] = $result_category->uid;
								}
							}
						}
					}

					if(isset($xml->questions->question)){
						if(!empty($arr_id_cate)){
							$arr_id_question = array();
							foreach ($xml->questions->question as $question) {
								$m_question                     = ORM::factory('questionnaires_orm');
								$m_question->test_uid           = $test->uid;
								$m_question->category_uid       = $arr_id_cate["$question->category_uid"];
								$m_question->question           = (string)$question->question;
								$m_question->created_by         = (int)$question->created_by;
								$m_question->input_date         = strtotime(date('d/m/Y h:i:s'));
								$m_question->answer_description = (string)$question->answer_description;
								$m_question->note               = (string)$question->note;	
								$m_question->note_ext           = (string)$question->note_ext;
								$m_question->status             = (string)$question->status;
								$m_question->save();
								if($m_question->saved){
									$arr_id_question["$question->uid"] = $m_question->uid;
								}
							}
						}
					}
					if(isset($xml->answers->answer)){
						if(!empty($arr_id_question)){
							foreach ($xml->answers->answer as $answer) {
								$m_answer                     = ORM::factory('answer_orm');
								$m_answer->questionnaires_uid = $arr_id_question["$answer->questionnaires_uid"];
								$m_answer->answer             = (string)$answer->answer;
								$m_answer->type               = (int)$answer->type;
								$m_answer->images             = (string)$answer->images;
								$m_answer->save();
							}
						}
					}
				}else{
					$this->session->set_flash('warning_msg','Structure file XML errors!');	
					url::redirect('admin_test');
					die();
				}
				$this->session->set_flash('success_msg','Import data succsess!');	
				url::redirect('admin_test');
				die();
            }
		}
		die();
	}

	public function export($id){
		header("Content-Type: plain/xml");
		header("Content-Disposition: Attachment; filename=test_".$id."_".date('Ymd').'_'.date('Hi').".xml");
		header("Pragma: no-cache");
		header("Expires: 0");
		$xmldoc = new DOMDocument(); 
		$xmldoc->formatOutput = true;   
		$root = $xmldoc->createElement("test");
		$xmldoc->appendChild( $root );

		$result = $this->test_model->get($id);
		if(!empty($result)){
			
			$xml_answers = $xmldoc->createElement("answers");
			$xml_question = $xmldoc->createElement("questions");
			$xml_category = $xmldoc->createElement("categorys");
			$xml_test_info = $xmldoc->createElement("testinfo");

			/* write tag test info */
			$xml_item_test_title = $xmldoc->createElement("test_title");
			$xml_item_test_title->appendChild(
               $xmldoc->createTextNode($result['test_title'])
            );

            $xml_item_test_uid = $xmldoc->createElement("uid");
			$xml_item_test_uid->appendChild(
               $xmldoc->createTextNode($result['uid'])
            );

			$xml_item_test_description = $xmldoc->createElement("test_description");
			$xml_item_test_description->appendChild(
               $xmldoc->createTextNode($result['test_description'])
            );

            $xml_item_test_qty_question = $xmldoc->createElement("qty_question");
			$xml_item_test_qty_question->appendChild(
               $xmldoc->createTextNode($result['qty_question'])
            );

            $xml_item_test_date = $xmldoc->createElement("date");
			$xml_item_test_date->appendChild(
               $xmldoc->createTextNode($result['date'])
            );

            $xml_item_test_type_time = $xmldoc->createElement("type_time");
			$xml_item_test_type_time->appendChild(
               $xmldoc->createTextNode($result['type_time'])
            );

            $xml_item_test_time_value = $xmldoc->createElement("time_value");
			$xml_item_test_time_value->appendChild(
               $xmldoc->createTextNode($result['time_value'])
            );

            $xml_item_test_status = $xmldoc->createElement("status");
			$xml_item_test_status->appendChild(
               $xmldoc->createTextNode($result['status'])
            );

            $xml_item_test_pass_score = $xmldoc->createElement("pass_score");
			$xml_item_test_pass_score->appendChild(
               $xmldoc->createTextNode($result['pass_score'])
            );

			$xml_item_test_price = $xmldoc->createElement("price");
			$xml_item_test_price->appendChild(
               $xmldoc->createTextNode($result['price'])
            );

            $xml_item_test_questionpage = $xmldoc->createElement("questionpage");
			$xml_item_test_questionpage->appendChild(
               $xmldoc->createTextNode($result['questionpage'])
            );

            $xml_item_test_displayexplanation = $xmldoc->createElement("displayexplanation");
			$xml_item_test_displayexplanation->appendChild(
               $xmldoc->createTextNode($result['displayexplanation'])
            );

            $xml_item_test_test_order = $xmldoc->createElement("test_order");
			$xml_item_test_test_order->appendChild(
               $xmldoc->createTextNode($result['test_order'])
            );

			$xml_test_info->appendChild($xml_item_test_uid);
			$xml_test_info->appendChild($xml_item_test_title);
			$xml_test_info->appendChild($xml_item_test_description);
			$xml_test_info->appendChild($xml_item_test_qty_question);
			$xml_test_info->appendChild($xml_item_test_date);
			$xml_test_info->appendChild($xml_item_test_type_time);
			$xml_test_info->appendChild($xml_item_test_time_value);
			$xml_test_info->appendChild($xml_item_test_status);
			$xml_test_info->appendChild($xml_item_test_pass_score);
			$xml_test_info->appendChild($xml_item_test_price);
			$xml_test_info->appendChild($xml_item_test_questionpage);
			$xml_test_info->appendChild($xml_item_test_displayexplanation);
			$xml_test_info->appendChild($xml_item_test_test_order);
			/* end write tag test info */

			$this->db->where('test_uid',$result['uid']);
			$m_category = $this->category_model->get();
			if(!empty($m_category)){
				foreach ($m_category as $key => $value_category) {
					/* write tag category */
					$xml_item_category = $xmldoc->createElement("category");

					$xml_item_category_uid = $xmldoc->createElement("uid");
					$xml_item_category_uid->appendChild(
		               $xmldoc->createTextNode($value_category['uid'])
		            );

		            $xml_item_category_parent_id = $xmldoc->createElement("parent_id");
					$xml_item_category_parent_id->appendChild(
		               $xmldoc->createTextNode($value_category['parent_id'])
		            );

		            $xml_item_category_level = $xmldoc->createElement("level");
					$xml_item_category_level->appendChild(
		               $xmldoc->createTextNode($value_category['level'])
		            );

		            $xml_item_category_path = $xmldoc->createElement("path");
					$xml_item_category_path->appendChild(
		               $xmldoc->createTextNode($value_category['path'])
		            );

		            $xml_item_category_left = $xmldoc->createElement("left");
					$xml_item_category_left->appendChild(
		               $xmldoc->createTextNode($value_category['left'])
		            );

		            $xml_item_category_right = $xmldoc->createElement("right");
					$xml_item_category_right->appendChild(
		               $xmldoc->createTextNode($value_category['right'])
		            );

		            $xml_item_category_test_uid = $xmldoc->createElement("test_uid");
					$xml_item_category_test_uid->appendChild(
		               $xmldoc->createTextNode($value_category['test_uid'])
		            );

		            $xml_item_category_state = $xmldoc->createElement("state");
					$xml_item_category_state->appendChild(
		               $xmldoc->createTextNode($value_category['state'])
		            );

		            $xml_item_category_industry = $xmldoc->createElement("industry");
					$xml_item_category_industry->appendChild(
		               $xmldoc->createTextNode($value_category['industry'])
		            );

		            $xml_item_category_branch = $xmldoc->createElement("branch");
					$xml_item_category_branch->appendChild(
		               $xmldoc->createTextNode($value_category['branch'])
		            );

		            $xml_item_category_sub_branch = $xmldoc->createElement("sub_branch");
					$xml_item_category_sub_branch->appendChild(
		               $xmldoc->createTextNode($value_category['sub_branch'])
		            );

		            $xml_item_category_category = $xmldoc->createElement("category");
					$xml_item_category_category->appendChild(
		               $xmldoc->createTextNode($value_category['category'])
		            );

		            $xml_item_category_sub_category = $xmldoc->createElement("sub_category");
					$xml_item_category_sub_category->appendChild(
		               $xmldoc->createTextNode($value_category['sub_category'])
		            );

		            $xml_item_category_category_percentage = $xmldoc->createElement("category_percentage");
					$xml_item_category_category_percentage->appendChild(
		               $xmldoc->createTextNode($value_category['category_percentage'])
		            );

		            $xml_item_category_status = $xmldoc->createElement("status");
					$xml_item_category_status->appendChild(
		               $xmldoc->createTextNode($value_category['status'])
		            );

		            $xml_item_category->appendChild($xml_item_category_uid);
		            $xml_item_category->appendChild($xml_item_category_parent_id);
		            $xml_item_category->appendChild($xml_item_category_level);
		            $xml_item_category->appendChild($xml_item_category_path);
		            $xml_item_category->appendChild($xml_item_category_left);
		            $xml_item_category->appendChild($xml_item_category_right);
		            $xml_item_category->appendChild($xml_item_category_test_uid);
		            $xml_item_category->appendChild($xml_item_category_state);
		            $xml_item_category->appendChild($xml_item_category_industry);
		            $xml_item_category->appendChild($xml_item_category_branch);
		            $xml_item_category->appendChild($xml_item_category_sub_branch);
		            $xml_item_category->appendChild($xml_item_category_category);
		            $xml_item_category->appendChild($xml_item_category_sub_category);
		            $xml_item_category->appendChild($xml_item_category_category_percentage);
		            $xml_item_category->appendChild($xml_item_category_status);
					/* end write tag category */

					//Get question
					$this->db->where('test_uid',$result['uid']);
					$this->db->where('category_uid',$value_category['uid']);
					$m_question = $this->questionnaires_model->get();
					if(!empty($m_question)){
						foreach ($m_question as $key => $value_question) {
							/* write tag question */
							$xml_item_question = $xmldoc->createElement("question");

							$xml_item_question_uid = $xmldoc->createElement("uid");
							$xml_item_question_uid->appendChild(
				               $xmldoc->createTextNode($value_question['uid'])
				            );

				            $xml_item_question_test_uid = $xmldoc->createElement("test_uid");
							$xml_item_question_test_uid->appendChild(
				               $xmldoc->createTextNode($value_question['test_uid'])
				            );

				            $xml_item_question_category_uid = $xmldoc->createElement("category_uid");
							$xml_item_question_category_uid->appendChild(
				               $xmldoc->createTextNode($value_question['category_uid'])
				            );

				            $xml_item_question_question = $xmldoc->createElement("question");
							$xml_item_question_question->appendChild(
				               $xmldoc->createTextNode($value_question['question'])
				            );

				            $xml_item_question_created_by = $xmldoc->createElement("created_by");
							$xml_item_question_created_by->appendChild(
				               $xmldoc->createTextNode($value_question['created_by'])
				            );

				            $xml_item_question_input_date = $xmldoc->createElement("input_date");
							$xml_item_question_input_date->appendChild(
				               $xmldoc->createTextNode($value_question['input_date'])
				            );

				            $xml_item_question_answer_description = $xmldoc->createElement("answer_description");
							$xml_item_question_answer_description->appendChild(
				               $xmldoc->createTextNode($value_question['answer_description'])
				            );

				            $xml_item_question_note = $xmldoc->createElement("note");
							$xml_item_question_note->appendChild(
				               $xmldoc->createTextNode($value_question['note'])
				            );

				            $xml_item_question_note_ext = $xmldoc->createElement("note_ext");
							$xml_item_question_note_ext->appendChild(
				               $xmldoc->createTextNode($value_question['note_ext'])
				            );

				            $xml_item_question_status = $xmldoc->createElement("status");
							$xml_item_question_status->appendChild(
				               $xmldoc->createTextNode($value_question['status'])
				            );

							$xml_item_question->appendChild($xml_item_question_uid);
							$xml_item_question->appendChild($xml_item_question_test_uid);
							$xml_item_question->appendChild($xml_item_question_category_uid);
							$xml_item_question->appendChild($xml_item_question_question);
							$xml_item_question->appendChild($xml_item_question_created_by);
							$xml_item_question->appendChild($xml_item_question_input_date);
							$xml_item_question->appendChild($xml_item_question_answer_description);
							$xml_item_question->appendChild($xml_item_question_note);
							$xml_item_question->appendChild($xml_item_question_note_ext);
							$xml_item_question->appendChild($xml_item_question_status);
							/* end write tag question */



							$this->db->where('questionnaires_uid',$value_question['uid']);
							$m_answer = $this->answer_model->get();
							if(!empty($m_answer)){
								foreach ($m_answer as $key => $value_answer) {
									$xml_item_answer = $xmldoc->createElement("answer");

									$xml_item_answer_uid = $xmldoc->createElement("uid");
									$xml_item_answer_uid->appendChild(
						               $xmldoc->createTextNode($value_answer['uid'])
						            );

						            $xml_item_answer_questionnaires = $xmldoc->createElement("questionnaires_uid");
									$xml_item_answer_questionnaires->appendChild(
						               $xmldoc->createTextNode($value_answer['questionnaires_uid'])
						            );

						            $xml_item_answer_answer = $xmldoc->createElement("answer");
									$xml_item_answer_answer->appendChild(
						               $xmldoc->createTextNode($value_answer['answer'])
						            );

						            $xml_item_answer_images = $xmldoc->createElement("images");
									$xml_item_answer_images->appendChild(
						               $xmldoc->createTextNode(!empty($value_answer['images'])?url::base().'pestest/uploads/answer/'.$value_answer['images']:'')
						            );

						            $xml_item_answer_type = $xmldoc->createElement("type");
									$xml_item_answer_type->appendChild(
						               $xmldoc->createTextNode($value_answer['type'])
						            );

						            $xml_item_answer_random = $xmldoc->createElement("random");
									$xml_item_answer_random->appendChild(
						               $xmldoc->createTextNode($value_answer['random'])
						            );

									$xml_item_answer->appendChild($xml_item_answer_uid);
									$xml_item_answer->appendChild($xml_item_answer_questionnaires);
									$xml_item_answer->appendChild($xml_item_answer_answer);
									$xml_item_answer->appendChild($xml_item_answer_images);
									$xml_item_answer->appendChild($xml_item_answer_type);
									$xml_item_answer->appendChild($xml_item_answer_random);

									$xml_answers->appendChild($xml_item_answer);
								}
							}
							$xml_question->appendChild($xml_item_question);
						}
					}
					$xml_category->appendChild($xml_item_category);
				}
			}
			$root->appendChild($xml_test_info);
			$root->appendChild($xml_category);
			$root->appendChild($xml_question);
			$root->appendChild($xml_answers);
		}
		echo $xmldoc->saveXML();
		die();
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
	 protected function _save_img_s3($image,$hd_id=''){
    	require_once Kohana::find_file('views/aws','init');
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
          if($hd_id){
          	$this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
			url::redirect('admin_test/edit/'.$hd_id);
			die();
          }else{
          	$this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
			url::redirect('admin_test/create');
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
					'SourceFile'  => s3_resize($tmp,200,200),
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

    protected function _save_img_courses($image,$hd_id=''){
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
          $this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
          if($hd_id) url::redirect('admin_test/edit/'.$hd_id);
                else url::redirect('admin_test/create');
        }else{
	        $directory = DOCROOT.'uploads/courses_img/';
	        if ($file = upload::save($image, NULL, $directory)){
	            $filename = md5(rand(0, 999)).'.png';
	  
	            Image::factory($file)
	                ->resize(200, 200, Image::AUTO)
	                ->save($directory.$filename);
	            // Delete the temporary file
	            unlink($file);
	            return $filename;
	        }
	        return FALSE;
    	}
    }

	public function save()
    {   	    	
		$frm = $this->_get_frm_valid();
		$file_logo  = '';
		$logo       = @$_FILES['txt_courses_img'];
		if(empty($frm['hd_id']))
		{
			$test = ORM::factory('test_orm');
			
		} else {
			$test = ORM::factory('test_orm', $frm['hd_id']);	
		}

		if(isset($logo['error']) && $logo['error'] == 0){
			if(s3_using == 'on')
				$file_logo = $this->_save_img_s3($logo,!empty($frm['hd_id'])?$frm['hd_id']:'');
			else
				$file_logo = $this->_save_img_courses($logo,!empty($frm['hd_id'])?$frm['hd_id']:'');
		
		}	
		$test->test_title         = $frm['txt_title']; // active
		$test->test_description   = $frm['erea_description'];
		$test->qty_question       = $frm['txt_question']; // admin
		$test->type_time          = $frm['sel_type_time'];
		$test->time_value         = $frm['txt_time_value'];
		$test->date               = $frm['txt_date'];	
		$test->pass_score         = $frm['txt_pass_score'];	
		$test->price              = $frm['txt_price'];	
		$test->img                = $file_logo;	
		$test->status             = $frm['sel_status'];
		$test->questionpage       = $frm['txt_questionpage'];
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
		url::redirect($this->uri->segment(1));
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