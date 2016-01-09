<?php
class Mail_weekly_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/user');
		// Init session 
		$this->_get_session_template();	
		$this->questionnaires_model   = new Questionnaires_Model();
		$this->answer_model           = new Answer_Model();

		$this->member_model           = new Member_Model();
		$this->category_model         = new Category_Model();
		$this->test_model             = new Test_Model(); 
		$this->testing_model          = new Testing_Model(); 
		$this->payment_model          = new Payment_Model(); 
		$this->testingdetail_model    = new Testingdetail_Model(); 
		$this->testing_category_model = new Testingcategory_Model();
		$this->lesson_model           = new Lesson_Model();
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
	
	public function __call($method, $arguments){
	} 
    
   	public function index(){
   		//echo $time_default = date_default_timezone_get();
		require_once Kohana::find_file('views/mailgun','init');
		$arr_member = array();
		$date_old = strtotime(date('m/d/Y').' -7 day');
		$date_now = $this->format_str_date(date('m/d/Y'),$this->site['site_short_date'],'/',23,59,0);
		$this->db->where('testing_date >=', $date_old);
		$this->db->where('testing_date <=', $date_now);
		$result = $this->testing_model->get();
		if(!empty($result)){
			foreach ($result as $key => $value) {
				if(empty($arr_member)){
					$arr_temp              = array();
					$arr_temp['member_id'] = $value['member_uid'];
					$arr_temp['data'][]    = $value;
					$arr_member[]          = $arr_temp;
				}else{
					$vt = array_search($value['member_uid'], array_column($arr_member, 'member_id'));
					if($vt === false){
						$arr_temp              = array();
						$arr_temp['member_id'] = $value['member_uid'];
						$arr_temp['data'][]    = $value;
						$arr_member[]          = $arr_temp;
					}else{
						$arr_member[$vt]['data'][]  = $value;
					}
				}
			}
		}
		if(!empty($arr_member)){
			foreach ($arr_member as $sl_mb => $item_mb) {
				$str_send      = '<p>Please see below for your testing activities for the week of '.date('m/d/Y').'.</p>';
				$str_main_data = '';
				if(!empty($item_mb['data'])){
					foreach ($item_mb['data'] as $sl_data => $item_data) {
						$str_cate = '';
						$str_data = '';
						$m_score  = 0;
						if(!empty($item_data['id_lesson'])){
							$this->db->where('testing_code',$item_data['testing_code']);
							$this->db->where('id_lesson',$item_data['id_lesson']);
							$this->db->where('member_uid',$item_mb['member_id']);
							$this->db->where('test',$item_data['test_uid']);
							$cate = $this->testing_category_model->get();
						}elseif(!empty($item_data['id_course'])){
							$this->db->where('testing_code',$item_data['testing_code']);
							$this->db->where('id_course',$item_data['id_course']);
							$this->db->where('member_uid',$item_mb['member_id']);
							$this->db->where('test',$item_data['test_uid']);
							$cate = $this->testing_category_model->get();
						}
						if(!empty($cate)){
							foreach ($cate as $sl_cate => $item_cate) {
								$m_cate = $this->category_model->get($item_cate['category']);
								if(empty($str_cate))
									$str_cate .=  $m_cate['category'];
								else
									$str_cate .=  ', '.$m_cate['category'];
							}
						}
						if($item_data['testing_type'] == 1){
							$m_score = $item_data['parent_score'];
							if($m_score > 100)
								$m_score = 100;
						}elseif($item_data['testing_type'] == 2){
							$m_score = $item_data['testing_score'] + $item_data['parent_score'];
							if($m_score > 100)
								$m_score = 100;
						}
						$str_data .= '<p>'.$this->format_int_date($item_data['testing_date'],'m/d/Y h:iA').' - '.$item_data['test_title'].' '.$str_cate.' - Score '.$m_score.'<p>';
						$str_main_data .= $str_data;
					}
					
				}
				/* Get member*/
				$m_member   = $this->member_model->get($item_mb['member_id']);
				$str_send   .= $str_main_data;
				$str_send   .= '<p><b>User: '.$m_member['member_email'].'</b></p>';
				$email_to   = array();
				$email_to[] = $m_member['member_email'];
				if(!empty($m_member['company_contact_email']))
					$email_to[] = $m_member['company_contact_email'];
				/**
				 * send email to user
				 */
				$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
					array(
						'from'       => MAIL_FROM,
						'to'         => $email_to,
						//'h:Reply-To' => $email_send,
						'subject'    => 'Pestest - Weekly Progress',
						'html'       => $str_send
			        ));
				if($result_send->http_response_body->message == 'Queued. Thank you.'){
					echo '<p>Send success '.$m_member['member_email'].' - '.date('m/d/Y').'</p>';
				}else{
					echo '<p>Send fail '.$m_member['member_email'].' - '.date('m/d/Y').'</p>';
				}
				
			}
		}else{
			echo '<p>No data testing activities for the week of '.date('m/d/Y').'</p>';
		}
		die();	
	}
}
?>