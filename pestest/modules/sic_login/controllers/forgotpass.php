<?php
class Forgotpass_Controller extends Template_Controller 
{
	public $template;
	
    public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/user');
        $this->_get_session_template();
        $this->Data_template_Model = new Data_template_Model();
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
			switch ($method)
			{			
				case 'index' : $this->index(); break;
				
				case 'save' : $this->save(); break;
				
				default : $this->index();	
			}			
	}
    
    private function index()
    {	
       $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/frm_forgetpass');		
    }
    
	private function _valid_forgotPass()
	{
		$email = $this->input->post('txt_email');	
		  $result = ORM::factory('member_orm')->where('member_email',$email)->find();
		 
		  if(isset($result->member_email)&& !empty($result->member_email))
			return $result->member_email;
		  else		
			return false;	
	}
	
	private function send_email($result)
    {
    	// Create new password for customer
    	$new_pass = text::random('numeric',8);
		if(isset($result->member_email) && !empty($result->member_email))
    	$result->member_pw = md5($new_pass);
		
    	$result->save();
    	
		//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		//$from = array($this->site['site_email'], 'Yesnotebook get password');
		
		$from = $this->site['site_email'];
		$subject = 'Your Temporary Password for '.$this->site['site_name'];
		//HTML message
		//print_r($html_content);die();
		//Replate content	
		$html_content = $this->Data_template_Model->get_value('EMAIL_FORGOTPASS');
		$name = $result->member_fname.' '. $result->member_lname;;
		
			
		$html_content = str_replace('#name#',$name,$html_content);	
		if(isset($result->member_email) && !empty($result->member_email))	
		$html_content = str_replace('#username#',$result->member_email,$html_content);		
		
		$html_content = str_replace('#site#',substr(url::base(),0,-1),$html_content);
		$html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);		
		$html_content = str_replace('#password#',$new_pass,$html_content);
		$html_content = str_replace('#EmailAddress#',$this->site['site_email'],$html_content);
		//fwrite($fi, $html_content);

		//Build recipient lists
		$recipients = new Swift_RecipientList;
		if(isset($result->member_email) && !empty($result->member_email))	
		$recipients->addTo($result->member_email);
		 
		 //Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");
		 
		if($swift->send($message, $recipients, $from)){
			
			//$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.info_mail_change_pass'));
			if(isset($result->member_email) && !empty($result->member_email))
			url::redirect(url::base().'forgotpass/thanks/' . $result->uid.'/customer');
			die();
		} else {
			
		}		 
		// Disconnect
		$swift->disconnect();
    }
	
    public function change_pass($base_email='',$time=''){
		$month_time = base64_decode($time);
		$endTime    = date('m/d/Y H:i',strtotime($month_time.' +30 minutes'));
		$now        = date('m/d/Y H:i');
		if(strtotime($endTime) >= strtotime($now)){
			url::redirect(url::base().'forgotpass/new_pass/'.$base_email);
			exit();
		}else{
			url::redirect(url::base().'forgotpass/expire_email');
			exit();
		}
	}

	public function expire_email(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/expire_email');
	}

	public function save_new(){
		
		$email = $this->input->post('txt_email');	
		$result = ORM::factory('member_orm')->where('member_email',$email)->find();

		if(isset($result->member_email)&& !empty($result->member_email)){
			$pass = md5($this->input->post('txt_new_pass'));
			$result->member_pw = $pass;
			$result->save();
			url::redirect(url::base().'forgotpass/change_success');
  			exit();
		}
		url::redirect(url::base().'forgotpass');
		die();
	}

	public function change_success(){
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/change_success');
	}

	public function new_pass($email=''){
		$this->template->content        = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/frm_new_pass');
		$base_decode_email              = base64_decode($email);
		$this->template->content->email = $base_decode_email;
	}

	private function send_mail_forgotpass($result){
		require_once Kohana::find_file('views/mailgun','init');
		$new_pass = text::random('numeric',8);
		if(isset($result->member_email) && !empty($result->member_email))
       		//$result->member_pw = md5($new_pass);
      	//$result->save();
		$code_email = base64_encode($result->member_email);
		$time_date  = date('m/d/Y H:i');
		$base_time  = base64_encode($time_date);

	   	$subject = 'Pestest - Password Reset Request';
	   	$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
			array(
				'from'       => MAIL_FROM,
				'to'         => $result->member_email,
				//'h:Reply-To' => $email_send,
				'subject'    => $subject,
				'html'       => "
								<p>Please click on the link below to reset your password. This link will become invalid in 30 minutes.</p>
								<a href='".url::base()."forgotpass/change_pass/".$code_email."/".$base_time."'>".url::base()."create/change_pass/".$code_email."/".$base_time."</a>"
	        ));
		if($result_send->http_response_body->message == 'Queued. Thank you.'){
			if(isset($result->member_email) && !empty($result->member_email)){
				url::redirect(url::base().'forgotpass/thanks/' . $result->uid.'/customer');
			}
			return true;
		}else{
			return false;
		}

	}

	private function send_mail_outlook($result){
	   $new_pass = text::random('numeric',8);
	   if(isset($result->member_email) && !empty($result->member_email))
       $result->member_pw = md5($new_pass);
		
       $result->save();
	   
	   
	   $subject = 'Your Temporary Password for '.$this->site['site_name'];
	   require_once('PHPMailer_v5.1/class.phpmailer.php');
	   
	   $html_content = $this->Data_template_Model->get_value('EMAIL_FORGOTPASS');
	   $name = $result->member_fname.' '. $result->member_lname;
		
			
	   $html_content = str_replace('#name#',$name,$html_content);	
	   if(isset($result->member_email) && !empty($result->member_email))	
	   $html_content = str_replace('#username#',$result->member_email,$html_content);		
		
	   $html_content = str_replace('#site#',substr(url::base(),0,-1),$html_content);
	   $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);		
	   $html_content = str_replace('#password#',$new_pass,$html_content);
	   $html_content = str_replace('#EmailAddress#',$this->site['site_email'],$html_content);
		
	   $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	   $mail->IsSendmail(); // telling the class to use SendMail transport
	   $mail->IsHTML(true);
	  
	   $mail->IsSMTP();
	   $mail->CharSet="windows-1251";
	   $mail->CharSet="utf-8";
	   try {
	   
		  // $mail->Host = 'pestest.com';
		  $arr_email = explode('@',$result->member_email);
		  if(isset($arr_email[1]) && $arr_email[1]=='gmail.com')
		  {
				
				$mail->Host = 'smtp.gmail.com';
				$gmail = array('pestest01@gmail.com','pestest02@gmail.com','pestest03@gmail.com');
				$mail->Username = $gmail[array_rand($gmail)];
				$mail->Password = '#this&is4u#'; 
				$from =  $gmail[array_rand($gmail)];
				$mail->From="support@pestest.com";
			    $mail->FromName="PesTest.com";
			    $mail->Sender="support@pestest.com";
		  }
		  else{
			$from = $this->site['site_email'];  
			$mail->Host = 'pestest.com';
			$mail->Username = 'info@pestest.com';//'result1.pestest@gmail.com'; // SMTP username
			$mail->Password = '#pestest2014#'; // SMTP password    
		    $mail->From="support@pestest.com";
			$mail->FromName="PesTest.com";
			$mail->Sender="support@pestest.com";
		 }
		   
		   $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		   $mail->SMTPAuth = true;
		   $mail->Port = 465; 
		   $mail->SMTPDebug  = 0; 
		 
		 
				
		   $mail->SetFrom($from,$subject);
		   $mail->AddAddress($result->member_email);
		   
		   $mail->Subject = 'Your Temporary Password for '.$this->site['site_name'];
		   $mail->Body = $html_content;
		   
		   if($mail->Send()){
		   		if(isset($result->member_email) && !empty($result->member_email)){
					url::redirect(url::base().'forgotpass/thanks/' . $result->uid.'/customer');
				}
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
    
	private function _get_record()
    {
    	$form = array
	    (
	       'txt_email' => ''	        
	    );
		
		$errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_email','required','email');
			if($post->validate())
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			} else {
				$errors = arr::overwrite($errors, $post->errors('login_lang'));
				$str_error = '';
				foreach($errors as $id => $name) if($name) $str_error.='<br>'.$name;
				$this->session->set_flash('error_msg',$str_error);
				url::redirect(url::base().'forgotpass');
				die();
			}
        }
    }
	
	private function save()
    {
		$record = $this->_get_record();
		$valid = $this->_valid_forgotPass();
		
		if ($valid)	// if login access
		{
			$result = ORM::factory('member_orm')->where('member_email',$valid)->find();//ORM::factory('customer_orm',$valid);
		  if(isset($result->member_email)&& !empty($result->member_email))
		  {
			$member_email = explode('@',$result->member_email);

			$this->send_mail_forgotpass($result);
				//  if(isset($member_email[1]) && ($member_email[1]=='hotmail.com' 
				//                                 || $member_email[1]=='live.com' || 
				// 								$member_email[1]=='outlook.com')) 
				// {
				// 	$send = $this->send_mail_outlook($result);
			 //        if(!$send)
				//     $this->send_mail_outlook($result);
				// }else{
				//    $this->send_mail_outlook($result);
				// }	 
			 
		 }	  
		} 
		else 
		{
			$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_email_not_exist'));	
					
		}
		
		url::redirect(url::base().'forgotpass');
		die();	
	}
	public function check_email() {
		
        $txt_email = $_POST['txt_email'];
		$result_member = ORM::factory('member_orm')->where('member_email',$txt_email)->find();
		 if(isset($result_member->member_email) && !empty($result_member->member_email)) 
		{
			echo 'true';
		}
		 else
            echo 'false';
        die();
    }
	
	public function thanks($id='',$type='customer') {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/login/thanks_forgetpass');
        if ($id && $type=='customer') {
             $mr = ORM::factory('member_orm',$id);
        }
        $this->template->content->mr = $mr;
    }
	
}
?>