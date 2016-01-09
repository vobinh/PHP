<?php
class Test_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		// Init session 
		$this->_get_session_template();	
		$this->questionnaires_model = new Questionnaires_Model();
		$this->answer_model = new Answer_Model();
		$this->category_model = new Category_Model();
		$this->test_model = new Test_Model(); 
		$this->testing_model = new Testing_Model(); 
		$this->payment_model = new Payment_Model(); 
		$this->testingdetail_model = new Testingdetail_Model(); 
		$this->testing_category_model= new Testingcategory_Model();
		$this->promotion_model = new Promotion_Model();
		//print_r($this->sess_cus);
		if($this->sess_cus =="")
		{
			url::redirect('');
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
	
	
	public function showlist(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/newlist');
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		$arraypayment = array();
		foreach($payment as $value){
			$test = $this->test_model->get($value['test_uid']);
			
			if((isset($value['daytest'] ) && strtotime("-". $value['daytest'] ." day" ) <= $value['payment_date'])
				||(isset($value['daytest']) && $value['daytest'] ==0))
			{
				if(!in_array($value['test_uid'],$arraypayment))
					$arraypayment[] =  $value['test_uid'];
			}
		}
		//if(!empty($arraypayment))
			//$this->db->notin('uid', $arraypayment);
		$this->db->where('status',1);
		$total_items = count($this->test_model->get());
		    if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		$this->pagination = new Pagination(array(
    		'base_url'       => 'test/showlist/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		//if(!empty($arraypayment))
			//$this->db->notin('uid', $arraypayment);
		$this->db->where('status',1);		
		$mr['mlist'] = $this->test_model->get();
		foreach($mr['mlist'] as $key => $value){
				$this->db->where('test_uid',$value['uid']);
				$this->db->where('member_uid',$this->sess_cus['id']);
				$this->db->limit(1);
				$payment = $this->payment_model->get();
				if(isset($payment[0]['payment_date']) && isset($payment[0]['daytest'])){
					$mr['mlist'][$key]['payment_date'] = $payment[0]['payment_date'];
					$mr['mlist'][$key]['daytest']= $payment[0]['daytest'];
					$mr['mlist'][$key]['payment_price']= $payment[0]['payment_price'];
					}
		}
		$this->template->content->mr = $mr;		
		$this->template->content->arraypayment = $arraypayment;		
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
			} else
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
		/* echo('<pre>');
		print_r($mr);
		die(); */
		$this->template->content->mr = $mr;		
	}
	
	public function dialogmytest(){
		$view = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/historylist');
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		foreach($payment as $key => $value){
			$test = $this->test_model->get($value['test_uid']);
			$payment[$key]['test'] = isset($test)?$test:'';		
		}
		$total_items = count($payment );
		if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
		$this->pagination = new Pagination(array(
    		'base_url'       => 'test/showlist/search/',
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
	
	public function start($idtest="",$code="",$type="promotion",$promotionid=""){
		 $idtest = substr(base64_decode($idtest),0,strlen(base64_decode($idtest)) - 3);
		 if(!empty($code))
		 $code = substr(base64_decode($code),0,strlen(base64_decode($code)) - 3);
		 
		 $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/start');
		
		 $test = $this->test_model->get($idtest);
		 $this->db->where('member_uid',$this->sess_cus['id']);
		 $this->db->where('test_uid',$idtest);
		 $payment = $this->payment_model->get();
		 $insert = true;
		 foreach($payment as $value){
				 if(((isset($value['daytest'])?strtotime("-". $value['daytest'] ." day"):strtotime('now')) <= $value['payment_date']) 
				 || $value['daytest'] == 0){
						$insert = false;
				 }
		 }
		 if($insert && !empty($code)){
			 if(isset($type )&& $type=='promotion')
			 {
				 $inserts =  $this->db->insert('payment', array('member_uid'=>$this->sess_cus['id'],'test_uid' => $idtest,'payment_date' => strtotime(date('m/d/Y')),'valid_until'=>1,'promotion_code'=>$code,'payment_price'=>0,'daytest'=>isset($test['date'])?$test['date']:'0'));
			 }else{
				  if(!empty($promotionid))
		          $promotionid = substr(base64_decode($promotionid),0,strlen(base64_decode($promotionid)) - 3);
				  else  $promotionid="";
				  if(!empty($promotionid)){
					 $mr_promotion = $this->promotion_model->get($promotionid); 
					 if(isset($mr_promotion['promotion_price']))
				     $test['price']= $test['price']-$mr_promotion['promotion_price'];
					 if(isset($mr_promotion['promotion_code']))
					 $promotion_code=$mr_promotion['promotion_code'];
					 else  $promotion_code="";
				  }else{
				    $promotion_code="";
				 } 
				  
			  	 $inserts =  $this->db->insert('payment', array('member_uid'=>$this->sess_cus['id'],'test_uid' => $idtest,'payment_date' => strtotime(date('m/d/Y')),'valid_until'=>1,'transaction_code'=>isset($test['price'])&&$test['price']>0?$code:'','payment_price'=>isset($test['price'])?$test['price']:'0','daytest'=>isset($test['date'])?$test['date']:'0','promotion_code'=>isset($promotion_code)?$promotion_code:''));
			 }
			 if(!empty($inserts))
			 {
				$this->template->content->insert = 'You have paid successfully'; 
			 }
		 }
		 
		 $mr = $this->test_model->get($idtest);
		 $this->template->content->mr = $mr;
		 //////
		 $this->db->where('test_uid',$idtest);
		 $this->db->where('category_percentage >=',0);
		 $mlist_cate = $this->category_model->get();
		
		 $this->template->content->mlist_cate = $mlist_cate;
		 ////
	}
    
   	public function index()
	{
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/index');
	
		$mr = array();
		$this->db->limit(1);
		$this->db->where('member_uid',$this->sess_cus['id']);
		//$this->db->where('testing_type',1);	
		$mr['last_test'] = $this->testing_model->get();
		
		$scoreparent = 0;
		if(!empty($mr['last_test'][0]['parent_code'])){
			$this->db->where('testing_code',$mr['last_test'][0]['parent_code']);
			$testparent = $this->testing_model->get();
			$scoreparent = $testparent[0]['testing_score'];
		}
		if (!empty($mr['last_test'])){
			$mr['last_test'][0]['test'] = $this->test_model->get($mr['last_test'][0]['test_uid']);
			
			$this->db->limit(1);
			$this->db->where('testing_code',$mr['last_test'][0]['testing_code']);
			$category = $this->testing_category_model->get();
			if(!empty($category)){
				$mr['last_test'][0]['categoryid'] = $category[0]['category']; 
			}
			$this->db->limit(1);
			$this->db->where('member_uid',$this->sess_cus['id']);
			$this->db->where('testing_code',$mr['last_test'][0]['testing_code']);
		
			
			$chartcategory = $this->testing_category_model->get();
				
	
			$mr['chartcategory'] = 	$chartcategory;
			$mr['mlist'] = $this->test_model->get();
			 
			$this->db->where('test_uid',$mr['last_test'][0]['test_uid']);
			$this->db->where('category_percentage >',0);
		    $this->db->orderby('parent_id','ASC');
		    $this->db->orderby('category','ASC');
			
			
			$listcategory = $this->category_model->get();
			  
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
			
			$this->template->content->chartlist = $this->getChartTest($mr['last_test'][0]['test_uid'],$this->sess_cus['id']);
			$mr['olist'] = isset($olist)?$olist:array();
		}
		
		$arraypayment = array();
		$this->db->where('member_uid',$this->sess_cus['id']);
		$payment = $this->payment_model->get();
		$this->template->content->payment = $payment;
		$this->template->content->mr = $mr;	
		$this->template->content->scoreparent = $scoreparent;
		
	}

	public function testing()
	{
		$this->session->delete('sess_save');
		if(!isset($_POST['sel_test']) ){
		  url::redirect('test');
		  die();
		}
		
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/test');
		
		/////////
		
		
		$mr = $this->test_model->get($_POST['sel_test']);
		/////
		$this->db->orderby('uid','DESC');
		$this->db->where('test_uid',$_POST['sel_test']);
		$this->db->where('category_percentage >=',0);
		
		$mist_cate = $this->category_model->get();
		
		if(!empty($mist_cate)){
			for($j=0;$j<count($mist_cate);$j++)
			{ 
				$this->db->limit(($mist_cate[$j]['category_percentage']*$mr['qty_question'])/100);
				$this->db->where('category_uid',$mist_cate[$j]['uid']);
				$mlist = $this->questionnaires_model->randdom();
				$mist_cate[$j]['questionnaires'] = $mlist;
				for($i=0;$i<count($mist_cate[$j]['questionnaires']);$i++)
				{
					$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
					$rand=$this->answer_model->get_questionnaires($mlist[$i]['uid']);
					$get_answers_full=$this->answer_model->get_answers_full($mlist[$i]['uid']);
					$get_answers_1=$this->answer_model->get_answers_1($mlist[$i]['uid']);

					if(!empty($rand)){
						$key_1='';

						foreach ($rand as $k=>$value) {

								$key_1=array_search($value, $get_answers_full);
								unset($get_answers_full[$key_1]);
								$get_answers_full[$key_1]=$get_answers_1[$k];
			
						}
						
						$mist_cate[$j]['questionnaires'][$i]['answer']=$get_answers_full; 
					}else{
						$mist_cate[$j]['questionnaires'][$i]['answer']=$rand_0;
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
					$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
					$rand=$this->answer_model->get_questionnaires($mlist[$i]['uid']);
					$get_answers_full=$this->answer_model->get_answers_full($mlist[$i]['uid']);
					$get_answers_1=$this->answer_model->get_answers_1($mlist[$i]['uid']);

					if(!empty($rand)){
						$key_1='';

						foreach ($rand as $k=>$value) {

								$key_1=array_search($value, $get_answers_full);
								unset($get_answers_full[$key_1]);
								$get_answers_full[$key_1]=$get_answers_1[$k];
			
						}
						
						$mist_cate[0]['questionnaires'][$i]['answer']=$get_answers_full;

					}else{

						$mist_cate[0]['questionnaires'][$i]['answer']=$rand_0;

					}
				}
		}
		
		$mr['typetest'] = 1;	
		$this->db->where('status',1);
		$mtest = $this->test_model->get();
		$this->template->content->mr = $mr;
		$this->template->content->mlist = $mist_cate;
		$this->template->content->mtest =$mtest ;
		
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
			url::redirect('test');
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
		   		$rand_0=$this->answer_model->get_questionnaires_zero($mlist[$i]['uid']);
				$rand=$this->answer_model->get_questionnaires($mlist[$i]['uid']);
				$get_answers_full=$this->answer_model->get_answers_full($mlist[$i]['uid']);
				$get_answers_1=$this->answer_model->get_answers_1($mlist[$i]['uid']);

				if(!empty($rand)){
					$key_1='';

					foreach ($rand as $k=>$value) {

							$key_1=array_search($value, $get_answers_full);
							unset($get_answers_full[$key_1]);
							$get_answers_full[$key_1]=$get_answers_1[$k];
		
					}
					
						$mist_cate[$j]['questionnaires'][$i]['answer']=$get_answers_full;

				}else{

						$mist_cate[$j]['questionnaires'][$i]['answer']=$rand_0;

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
	
	public function getChartByCategory($cateuid,$member_uid)
	{
		$this->db->where('category',$cateuid);
		$this->db->where('member_uid',$member_uid);
		$list = $this->testing_category_model->get();
		foreach($list as $key=> $value){
			$name = $this->category_model->get($value['category']);
			$list[$key]['name'] = $name['category'];
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
	
	public function getChartTest($test_uid,$member_uid)
	{
		$this->db->where('test_uid',$test_uid);
		$chartlist = $this->testing_model->getTestingByChart('member_uid',$member_uid);
		//echo $this->db->last_query();
		//die();
		foreach($chartlist as $key => $value){
			$chartlist[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		return  $chartlist;
	}
	
	public function testingwrong()
	{
		
		
		if(!isset($_POST['hd_test'])){
			url::redirect('test');
		    die();
		}
		
		$this->session->delete('sess_save');
		$mr = $this->test_model->get($_POST['hd_test']);
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/test');
		
		
		$mist_cate = array();
		$this->db->where('result',0);
		$this->db->where('testing_code',$_POST['parent_id']);
		$arrayquestion = $this->testingdetail_model->get();
			
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
							$rand_0=$this->answer_model->get_questionnaires_zero($mist_cate[$key]['questionnaires'][$i]['uid']);
							$rand=$this->answer_model->get_questionnaires($mist_cate[$key]['questionnaires'][$i]['uid']);
							$get_answers_full=$this->answer_model->get_answers_full($mist_cate[$key]['questionnaires'][$i]['uid']);
							$get_answers_1=$this->answer_model->get_answers_1($mist_cate[$key]['questionnaires'][$i]['uid']);

							if(!empty($rand)){
								$key_1='';

								foreach ($rand as $k=>$value) {

								$key_1=array_search($value, $get_answers_full);
								unset($get_answers_full[$key_1]);
								$get_answers_full[$key_1]=$get_answers_1[$k];
		
							}
					
								$mist_cate[$key]['questionnaires'][$i]['answer']=$get_answers_full;

							}else{

								$mist_cate[$key]['questionnaires'][$i]['answer']=$rand_0;

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
									$rand_0=$this->answer_model->get_questionnaires_zero($mist_cate[0]['questionnaires'][$i]['uid']);
									$rand=$this->answer_model->get_questionnaires($mist_cate[0]['questionnaires'][$i]['uid']);
									$get_answers_full=$this->answer_model->get_answers_full($mist_cate[0]['questionnaires'][$i]['uid']);
									$get_answers_1=$this->answer_model->get_answers_1($mist_cate[0]['questionnaires'][$i]['uid']);

									if(!empty($rand)){
										$key_1='';

										foreach ($rand as $k=>$value) {

										$key_1=array_search($value, $get_answers_full);
										unset($get_answers_full[$key_1]);
										$get_answers_full[$key_1]=$get_answers_1[$k];
		
									}
					
										$mist_cate[0]['questionnaires'][$i]['answer']=$get_answers_full;

									}else{

										$mist_cate[0]['questionnaires'][$i]['answer']=$rand_0;

									}
								}
							$qty_question++; 
							}
					}
					break;
				}
				
		}
		$mr['qty_question'] = $qty_question;
		$mr['parent_id'] = $_POST['parent_id'];
			
		$this->db->where('status',1);
		$mtest = $this->test_model->get();
		$this->template->content->mr = $mr;
		$this->template->content->mlist = $mist_cate;
		$this->template->content->mtest =$mtest ;

	}
	
	public function resulttest()
	{   
	  $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/resulttesting');
	 
	  if($_POST &&  !($this->session->get('sess_save')) )
	  {
	  	 $pass =0;
		 $fail=0;
		 $arraycategory = array();
		 $arraycategory[] = null ;
		 $categorylimit = $_POST['category'];
			 for($i=0;$i<count($_POST['hd_question']);$i++)
		 {
		   if(isset($_POST['radio'.$_POST['hd_question'][$i]]) && $_POST['radio'.$_POST['hd_question'][$i]] == 1)
			{
				$arr_uid = explode('|',$_POST['radio'.$_POST['hd_question'][$i]]);
				if(isset($arr_uid[0]) && $arr_uid[0]==1){
					if(!isset($arraycategory[$arr_uid[2]]))
						$arraycategory[$arr_uid[2]] = 1;
					else
						$arraycategory[$arr_uid[2]] += 1;
					$pass++;
				}
				else  
				{
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
		 else
		 {
		   $time = $_POST['hd_duration'];
		   if($_POST['hd_timeduration']=='00:00:00') $mr['timeduration']=gmdate("H:i:s",  $time*60);
		   else{
		      $time_redmain = $this->seconds($_POST['hd_timeduration']);
			  $mr['timeduration']=gmdate("H:i:s",($time*60)-$time_redmain);
		   }
		  } 
		  $percentcategory = array();
		  foreach($categorylimit as $value){
		  	$temp = explode('|',$value);
			 
			foreach($arraycategory as $key => $value){
				if($temp[0] == $key)
				{
					$percentcategory[$temp[0]] = array($value,$temp[1]);
					break;
				} else 
				$percentcategory[$temp[0]] = array(0,$temp[1]);
			 
			}
			 
		  }
		  $questionnaires= array();
		  foreach($_POST['hd_question'] as $value){
		  		$questionnaires = $this->questionnaires_model->get($_POST['hd_question']);
		  }
		 
		  
		    
		  $mlist = array();
		  $chartlist = array();
		  $olist = array();
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
						
					 	if($val['category_uid'] == $category['uid']){
							$value['answer'][$key] = $questionnaires[$key];
							$dhpost = isset($_POST['radio'.$questionnaires[$key]['uid']])?$_POST['radio'.$questionnaires[$key]['uid']]:'';
							if(!empty($dhpost)){
								$temp = explode('|',$dhpost);
								$hasanswer = $this->answer_model->get($temp[1]);
								$value['answer'][$key]['has'] = $hasanswer['answer'];
							}else{
								$value['answer'][$key]['has']  = '';
							}
						}
						
					 }
					
					
					 $mlist[$parent['category'].'-'.$category['category']] = $value;
					 
						$chartlist[$category['uid']] = ($value[0]*100)/$value[1];
				
				 }
			 }
		  }
		  
		 
		  $stt_no = $this->testing_model->get_code($this->format_str_date(date('m/d/Y'),$this->site['site_short_date']));
		  $testing_code = date('ymd').'-'.$stt_no;
		  
		  $mr['fail'] = round(($fail*100)/(count($_POST['hd_question'])),2);
		  $mr['pass'] = round(($pass*100)/(count($_POST['hd_question'])),2);
		 
		  if(isset($_POST['parent_id']) && $_POST['parent_id']!='')
		  	$mr['parent_id'] = $_POST['parent_id'];
		  else
		  	$mr['parent_id'] = $testing_code;
		 
		  
		  $this->save($mr['timeduration'],$mr['pass'],$chartlist,$testing_code,$mr['parent_id']);
		  
		  $this->db->limit(1);
		  $this->db->where('testing_code',$testing_code);
		  $mr['last_test'] = $this->testing_model->get(); 
		  $scoreparent = 0;
		  if(isset($mr['parent_id'])){
			   $this->db->where('testing_code',$mr['last_test'][0]['parent_code']);
			   $testparent = $this->testing_model->get();
			   $scoreparent = $testparent[0]['testing_score'];
		  }	
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
		 
		  $mr['idtest'] = $_POST['hd_test'];
		  $mr['mlist'] = $mlist;
		  $mr['olist'] = $olist;
		  $mr['testing_code'] = $testing_code;
		  
		  $this->db->where('result',0);
		  $this->db->where('testing_code',$testing_code);
		  $arrayquestion = $this->testingdetail_model->get();
		  foreach($arrayquestion as $key => $value){
		  	 $this->db->where('questionnaires_uid',$value['questionnaires_uid']);
			 $this->db->where('type',1);
			 $answer = $this->answer_model->get();
			 $mr['arrayquestion'][$key] = $this->questionnaires_model->get($value['questionnaires_uid']);
			 $mr['arrayquestion'][$key]['answer']= isset($answer[0]['answer'])?$answer[0]['answer']:'';
			 $mr['arrayquestion'][$key]['images']= isset($answer[0]['images'])?$answer[0]['images']:'';
			 $hasanswer = $this->answer_model->get($value['selected_answer']);
			 $mr['arrayquestion'][$key]['hasanswer']= isset($hasanswer['answer'])?$hasanswer['answer']:'';
			 $mr['arrayquestion'][$key]['hasimages']= isset($hasanswer['images'])?$hasanswer['images']:'';
		  }
		 /*  echo("<pre>");
		  print_r($arrayquestion);
		  print_r($mr['arrayquestion']);
		  die(); */
		
		  $this->template->content->chartlist = $this->getChartTest($mr['idtest'],$this->sess_cus['id']);
		  $this->template->content->mr = $mr;
		  $this->template->content->scoreparent = $scoreparent; 
	  }
	  else
	  url::redirect('');
	
	}
	
	public function seconds($time){
	$time = explode(':', $time);
	return ($time[0]*3600) + ($time[1]*60) + $time[2];
	}
	
	public function save($duration,$score,$category,$testing_code,$parent_code){
		$record=array(
			'parent_code' => $parent_code,
			'testing_code' => $testing_code,	        	
	        'test_uid' => $_POST['hd_test'],
	        'member_uid' => $this->sess_cus['id'],
            'testing_date' => $this->format_str_date(date('m/d/Y'),$this->site['site_short_date']),
			'testing_type' => $_POST['typetest'],
			'testing_score' => $score,
			'duration' => $this->seconds($duration), 
	    );
		$this->db->insert('testing',$record);
		$this->savedetail($testing_code);
		$this->saveCategory($testing_code,$category,$_POST['hd_test']);
		if($_SERVER["HTTP_HOST"] != "localhost"){
			require_once('PHPMailer_v5.1/class.phpmailer.php');
			$this->member_model = new Member_Model(); 
			$member =  $this->member_model->get($this->sess_cus['id']);
			$member_email = explode('@',$this->sess_cus['email']);
			if($member['send_mail']==0){
				if(isset($member_email[1]) && ($member_email[1]=='hotmail.com' || $member_email[1]=='live.com' || $member_email[1]=='outlook.com')) 
				{
					$send = $this->send_email_outlook($record);
					if(!$send)
					 $this->send_email_outlook($record);
				}
				else{
				   $this->send_email_outlook($record);
				}
				if(isset($member['company_contact_email']) && !empty($member['company_contact_email']) 
														   && strlen(trim($member['company_contact_email'])) > 0)
				{
					 $company_email = explode('@',$member['company_contact_email']);
					 if(isset($company_email[1]) && ($company_email[1]=='hotmail.com' 
													|| $company_email[1]=='live.com' || 
													$company_email[1]=='outlook.com')) 
					{
					  $sendcompany = $this->send_mail_company_outlook($record);
					  if(!$sendcompany)
					   $this->send_mail_company_outlook($record);
					}
					else{
					   $this->send_mail_company_outlook($record);
					}
				}
			}
			
		}
	}
	private function saveCategory($testing_code,$category,$test){
		foreach($category as $key=>$value){
				$this->db->insert('testing_category',array('category'=>$key,'test' => $test,'percentage'=>$value,'testing_code'=>$testing_code,'member_uid' => $this->sess_cus['id']));  
		}
	}
	
	public function savedetail($testing_code){
	 
	  for($i=0;$i<count($_POST['hd_question']);$i++)
		 {
		    
			if(isset($_POST['radio'.$_POST['hd_question'][$i]]) && !empty($_POST['radio'.$_POST['hd_question'][$i]]))
			 {
				$arr_uid = explode('|',$_POST['radio'.$_POST['hd_question'][$i]]);
				if(isset($arr_uid[0]) && $arr_uid[0]==1)
				$result=1;
				else $result=0;
			 	
			 }
			else
			  {
				  $result=0;
				  $arr_uid[1]=0;
			
			}
			  
			$this->db->insert('testing_detail',array('testing_code'=>$testing_code,
			                                         'questionnaires_uid'=>$_POST['hd_question'][$i],
													 'selected_answer'=>isset($arr_uid[1])?$arr_uid[1]:"",'result'=>$result));  
		 }
		 
	}
	private function send_mail_company_outlook($record){
		
	    $html_content = Data_template_Model::get_value('EMAIL_TESTING_COMPANY');
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
	
	private function send_email_outlook($record)
    {
		
  		$html_content = Data_template_Model::get_value('EMAIL_TESTING');
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
		$html_content = Data_template_Model::get_value('EMAIL_TESTING_COMPANY');
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
		$html_content = Data_template_Model::get_value('EMAIL_TESTING');
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