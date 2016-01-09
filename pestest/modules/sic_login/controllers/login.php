<?php
class Login_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');		
		$this->_get_session_template();
		$this->login_model = new Login_Model();
	}
	
	public function __call($method, $arguments)
	{		
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{			
			switch ($method)
			{
				case 'index' : $this->index(); break;
				
				case 'check_login' : $this->check_login(); break;
				
				case 'log_out' : $this->log_out(); break;
				
				default : $this->warning_msg('wrong_pid');	
			}
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
	
	public function check_login_js(){
		$login = $this->input->post('txt_email','',TRUE);	// security input data
		$pass  = md5($this->input->post('txt_password'));	// encrypt md5 input password
		$member_model = new Member_Model();
		$valid = $member_model->cus_exist($login, $pass);
		if ($valid !== FALSE)	// if login by customer account		
		{
			if(!$valid['status'])	// if cus_status = 0 
			{
				$arr_msg = array(
					'type'    => 'error',
					'message' => Kohana::lang('errormsg_lang.msg_inactive_error'),
				);
				echo json_encode($arr_msg);
				die();		
			}else{
				if(empty($valid['id_sess'])){
					$sess['type']     = 1; // user
					$sess['username'] = $valid['member_email'];					
					$sess['id']       = $valid['uid'];
					$sess['name']     = $valid['member_fname'].' '.$valid['member_lname'];
					$sess['email']    = $valid['member_email'];

					$this->db->where('uid',$valid['uid']);
					$this->db->update('member',array('id_sess'=>$this->session->id()));
				}else{
					$this->db->where('session_id',$valid['id_sess']);
					$result = $this->db->get('sessions')->result_array(false);
					if(empty($result)){
						$sess['type']     = 1; // user
						$sess['username'] = $valid['member_email'];					
						$sess['id']       = $valid['uid'];
						$sess['name']     = $valid['member_fname'].' '.$valid['member_lname'];
						$sess['email']    = $valid['member_email'];

						$this->db->where('uid',$valid['uid']);
						$this->db->update('member',array('id_sess'=>$this->session->id()));
					}else{
						$time_out =  time()-120;
						// echo date('m/d/Y h:i s',$time_out);
						// echo '<br>';
						// echo date('m/d/Y h:i s',$result[0]['last_activity']);
						$time = $result[0]['last_activity'];
						if($time < $time_out){
							$sess['type']     = 1; // user
							$sess['username'] = $valid['member_email'];					
							$sess['id']       = $valid['uid'];
							$sess['name']     = $valid['member_fname'].' '.$valid['member_lname'];
							$sess['email']    = $valid['member_email'];

							$this->db->where('session_id',$valid['id_sess']);
							$this->db->delete('sessions');

							$this->db->where('uid',$valid['uid']);
							$this->db->update('member',array('id_sess'=>$this->session->id()));
						}elseif($valid['id_sess'] !== $this->session->id()){
							$arr_msg = array(
								'type'    => 'multi_login',
								'message' => $valid['uid'],
							);
							echo json_encode($arr_msg);
							die();
						}else{
							$sess['type']     = 1; // user
							$sess['username'] = $valid['member_email'];					
							$sess['id']       = $valid['uid'];
							$sess['name']     = $valid['member_fname'].' '.$valid['member_lname'];
							$sess['email']    = $valid['member_email'];
						}
					}
					//echo '<pre>';
					//print_r($result);
					//die();
				}		
				
			}
		}else{
			$arr_msg = array(
				'type'    => 'error',
				'message' => Kohana::lang('errormsg_lang.error_login_pass'),
			);
			echo json_encode($arr_msg);
			die();
		}
				
		$this->login_model->set('customer',$sess);

		$arr_msg = array(
			'type'    => 'login',
			'message' => url::base().'courses',
		);
		echo json_encode($arr_msg);
		die();
	}

	public function multi_login(){
		$id_user = $this->input->post('txt_id_user_login');
		$this->db->where('uid',$id_user);
		$member = $this->db->get('member')->result_array(false);
		if(!empty($member)){
			$sess['type']     = 1; // user
			$sess['username'] = $member[0]['member_email'];					
			$sess['id']       = $member[0]['uid'];
			$sess['name']     = $member[0]['member_fname'].' '.$member[0]['member_lname'];
			$sess['email']    = $member[0]['member_email'];

			$this->db->where('session_id',$member[0]['id_sess']);
			$this->db->delete('sessions');

			$this->db->where('uid',$member[0]['uid']);
			$this->db->update('member',array('id_sess'=>$this->session->id()));

			$this->login_model->set('customer',$sess);		
			url::redirect(url::base().'courses');
			die();	
		}
		url::redirect(url::base().'home');
		die();
	}

	private function check_login()
	{	
		$login = $this->input->post('txt_email','',TRUE);	// security input data
		$pass  = md5($this->input->post('txt_password'));	// encrypt md5 input password
		$member_model = new Member_Model();
		$valid = $member_model->cus_exist($login, $pass);
		if ($valid !== FALSE)	// if login by customer account		
		{
			if(!$valid['status'])	// if cus_status = 0 
			{
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));							
				url::redirect(url::base().'home');
				die();				
			}else{					
				$sess['type']     = 1; // user
				$sess['username'] = $valid['member_email'];					
				$sess['id']       = $valid['uid'];
				$sess['name']     = $valid['member_fname'].' '.$valid['member_lname'];
				$sess['email']    = $valid['member_email'];
			}
		}else{					
			$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_login_pass'));		
			url::redirect(url::base().'home');
			die();
		}
				
		$this->login_model->set('customer',$sess);		
		url::redirect(url::base().'courses');
		die();	
	}
	
	public function set_user_active(){
		if($this->sess_cus === false){
			echo json_encode('stop');
			die();
		}else{
			$this->db->where('uid',$this->sess_cus['id']);
			$member = $this->db->get('member')->result_array(false);
			if(!empty($member)){
				$this->db->where('session_id',$member[0]['id_sess']);
				$this->db->update('sessions',array('last_activity' => time()));
				echo json_encode('start');
				die();
			}else{
				echo json_encode('stop');
				die();
			}
		}
	}

	public function auto_logout(){
		if($this->sess_cus === false){
			echo json_encode('logout');
			die();
		}else{
			$this->db->where('uid',$this->sess_cus['id']);
			$member = $this->db->get('member')->result_array(false);
			if(!empty($member)){
				if($member[0]['id_sess'] != $this->session->id()){
					echo json_encode('logout');
					die();
				}else{
					echo json_encode('login');
					die();
				}
			}else{
				echo json_encode('logout');
				die();
			}
		}
	}

	private function log_out()
	{	
		$this->login_model->log_out();
		$this->session->destroy();
		url::redirect(url::base().'home');
		die();
	}	
}
?>