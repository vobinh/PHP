<?php
class Register_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/user');
		$this->_get_session_template();
		$this->data_template_model  = new Data_template_Model();
		$this->login_model = new Login_Model();
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
	public function index()
	{		
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{
			$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/register/index');		
			$this->template->div_bottom = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/bottom_login');
			if ($this->session->get('input_data'))
				$this->template->content->indata = $this->session->get('input_data');
	
	        //assign random str
			$this->mr['str_random'] = text::random('numeric',6);
	        $this->session->set_flash('sess_random',$this->mr['str_random']);
			$this->template->content->mr = $this->mr;
		}		
	}
	
	public function loadregister($email)
	{		
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{
			$view = new View('templates/'.$this->site['config']['TEMPLATE'].'/register/dialog');		
			
			if ($this->session->get('input_data'))
				$this->template->content->indata = $this->session->get('input_data');
	
	        //assign random str
			$this->mr['str_random'] = text::random('numeric',6);
			$this->mr['cus_email'] = $email;
	        $this->session->set_flash('sess_random',$this->mr['str_random']);
			$view->mr = $this->mr;
			$view->render(TRUE);
		}	
		die();	
	}
	
	
	
	
	public function _check_security_code($array,$field)
	{
		if ($this->session->get('sess_random'))
			$str_random = $this->session->get('sess_random');
			
		if ($array[$field] !== $str_random)		// if security code invalid
		{
			$array->add_error($field,'_check_security_code');
		}
	}
	
	public function _check_email($array,$field)
	{
		$email_exist = ORM::factory('member_orm')->where('member_email',$array[$field])->count_all();
		
		if ($email_exist)
		{
			$array->add_error($field,'_check_email');
		}	
	}
	
	private function _get_register_valid()
	{
		$form = array 
		(
			'txt_email' => '',
			'txt_password' => '',
			'txt_cfpass' => '',	
			'txt_email' => '',	
			'txt_random' => '',	
			'txt_fname' => '',	
			'txt_lname' => '',	
			'txt_cpname' => '',	
			'txt_spname' => '',	
			'txt_spemail' => '',
			'chk_sendmail' => '',
				
		);
		$errors = $form;
		if ($_POST)
		{
			$post = new Validation($_POST);
			
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_password','required','length[1,50]');
			$post->add_rules('txt_cfpass','required','matches[txt_password]');
			$post->add_rules('txt_email','required','email');
			$post->add_rules('txt_random','required');
			$post->add_callbacks('txt_email',array($this,'_check_email'));
			//$post->add_callbacks('txt_random',array($this,'_check_security_code'));
			
			if ($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
 				return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('register_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect(url::base().'register');
				die();
			}
		}
	}
	public function submit()
	{
		$frm_reg = $this->_get_register_valid();
		if($frm_reg['chk_sendmail']=='on')
			$chk_sendmail = 1;
		else
			$chk_sendmail = 0;
		$rec_up = array(	
			'member_email'          => $frm_reg['txt_email'],
			'member_pw'             => md5($frm_reg['txt_password']),			
			'member_fname'          => $frm_reg['txt_fname'],
			'member_lname'          => $frm_reg['txt_lname'],
			'company_name'          => $frm_reg['txt_cpname'],
			'company_contact_name'  => $frm_reg['txt_spname'],
			'company_contact_email' => $frm_reg['txt_spemail'],
			'register_date'         => time(),
			'status'                => 1,
			'send_mail'             => $chk_sendmail,
		);
		$this->db->insert('member',$rec_up);
		$this->session->set_flash('success_msg', 'Congratulations. You have successfully created an account. Please log-in using your new account.');
		
		$member_email = explode('@',$rec_up['member_email']);
		if($chk_sendmail == 0){	
			
			if(isset($member_email[1]) && ($member_email[1]=='hotmail.com' 
											|| $member_email[1]=='live.com' || 
											$member_email[1]=='outlook.com')) 
			{
			$send = $this->send_mailgun_register($rec_up,$frm_reg['txt_password']);
				if(!$send)
				$this->send_mailgun_register($rec_up,$frm_reg['txt_password']);
			}else{
			  $this->send_mailgun_register($rec_up,$frm_reg['txt_password']);
			}	
			
		}
		
		/**
		 * login
		 */
		$login = $frm_reg['txt_email'];
		$pass  = md5($frm_reg['txt_password']);
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

				$this->db->where('uid',$valid['uid']);
				$this->db->update('member',array('id_sess'=>$this->session->id()));
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

	private function send_mailgun_register($record,$pass){
		require_once Kohana::find_file('views/mailgun','init');
		$html_content = $this->data_template_model->get_value('EMAIL_REGISTER');
	    $html_content = str_replace('#name#',$record['member_fname'].' '.$record['member_lname'],$html_content);	
	    $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);
	    $html_content = str_replace('#username#',$record['member_email'],$html_content);
	    $html_content = str_replace('#password#',$pass,$html_content);
	    $subject = 'Register '.$this->site['site_name'];
	    $result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
			array(
				'from'       => MAIL_FROM,
				'to'         => $record['member_email'],
				//'h:Reply-To' => $email_send,
				'subject'    => $subject,
				'html'       => $html_content
	        ));
		if($result_send->http_response_body->message == 'Queued. Thank you.'){
			return true;
		}else{
			return false;
		}
	}

	private function send_mail_outlook($record,$pass){
	
	   require_once('PHPMailer_v5.1/class.phpmailer.php');
	   
	   $html_content = $this->data_template_model->get_value('EMAIL_REGISTER');
	   $html_content = str_replace('#name#',$record['member_fname'].' '.$record['member_lname'],$html_content);	
	   $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);
	   $html_content = str_replace('#username#',$record['member_email'],$html_content);
	   $html_content = str_replace('#password#',$pass,$html_content);
		
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
		   
		  $arr_email = explode('@',$record['member_email']);
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
		
		  
				
		   $mail->SetFrom($this->site['site_email'],'Register '.$this->site['site_name']);
		   $mail->AddAddress($record['member_email']);
		   
		   $mail->Subject = 'Register '.$this->site['site_name'];
		   $mail->Body = $html_content;
		   
		   if($mail->Send()){
		    	return true;
		   }
		   else{
		   		return false;
		   }
	   }
	   catch (phpmailerException $e) {
	 		  // echo $e->errorMessage(); //Pretty error messages from PHPMailer
	   }
	   catch (Exception $e) {
	 		 // echo $e->getMessage(); //Boring error messages from anything else!
	   }
    }
	private function send_email($record,$pass)
    {
    	//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		$from = $this->site['site_email'];
		$subject = 'Register '.$this->site['site_name'];
		
		//HTML message
			 
	    $html_content = $this->data_template_model->get_value('EMAIL_REGISTER');
	    $html_content = str_replace('#name#',$record['member_fname'].' '.$record['member_lname'],$html_content);	
	    $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);
	    $html_content = str_replace('#username#',$record['member_email'],$html_content);
	    $html_content = str_replace('#password#',$pass,$html_content);
			

		//Build recipient lists
		$recipients = new Swift_RecipientList;
		$recipients->addTo($record['member_email']);
		 
		//Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");

	
		if($swift->send($message, $recipients, $from)){
		
		} else {
			
		}	
		// Disconnect
		$swift->disconnect();
    }
	
	private function thanks()
	{		
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/register/thanks');
		$this->template->content->site = $this->site;
		$this->template->content->mr = $this->mr;
	}
	
}
?>