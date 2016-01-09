<?php
class Payment_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/home');
		// Init session 
		$this->authorizenet_model = new Authorizenet_config_Model();
		$this->promotion_model = new Promotion_Model();
		$this->category_model = new Category_Model();
		$this->test_model = new Test_Model(); 
		$this->member_model = new Member_Model();
		$this->payment_model = new Payment_Model();
		if($this->sess_cus =="")
		{
			url::redirect('');
			die();
		}
		$this->_get_session_template();
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
	
   	public function index($test,$promotion="")
	{
		$test = base64_decode($test);
		
		
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/payment');
		$datatest = $this->test_model->get($test);
		$mrauthorizenet =$this->authorizenet_model->get();
		$datatest['api_login']=$mrauthorizenet['api_login'];
		$datatest['transaction_key']=$mrauthorizenet['transaction_key'];
		$datatest['post_url']=$mrauthorizenet['post_url'];
		////////
		if(!empty($promotion))
		{
		  $promotion_id = base64_decode($promotion);
		  $mr_promotion = $this->promotion_model->get($promotion_id);
		  if(isset($mr_promotion['promotion_price']) && $mr_promotion['promotion_price']!=0)
		  {
			  $datatest['price'] = $datatest['price']-$mr_promotion['promotion_price'];
			  $datatest['promotion_price']= $mr_promotion['promotion_price'];
			  $datatest['promotion_code']= $mr_promotion['promotion_code'];
			  $datatest['promotion_id']= $mr_promotion['uid'];
		  }
		  else{
		  	$datatest['price'] = $datatest['price'];
			$datatest['promotion_price']= "";
			$datatest['promotion_code']= "";
			$datatest['promotion_id']="";
		  }
		}else{
		   $datatest['promotion_price']= "";
			$datatest['promotion_code']= "";
			$datatest['promotion_id']="";
		}
		//////
		//////
		date_default_timezone_get('America/Los_Angeles');
		$orderid = strtoupper(text::random('alnum',12));
		$transaction_order_number = rand(10000,99999);
		$time = number_format(((time()) * 10000000) + 621355968000000000 , 0, '.', '');
		$pg_ts_hash = hash_hmac("md5","".$mrauthorizenet['api_login']."|10|1.0|".$datatest['price']."|".$time."|".$transaction_order_number,"".$datatest['transaction_key']."");
		$datatest['orderid']=$orderid;
		$datatest['transaction_order_number']=$transaction_order_number; 
		$datatest['time']=$time;
		$datatest['hash']=$pg_ts_hash;
		
		////////
		 
		
		////
		$this->session->set_flash('sess_search',$datatest);
		////
		$this->template->content->test = $datatest;
	}
	
	public function checkcode($test){	
		$code = $this->input->post('txt_checkcode');
		if(isset($code) && isset($test)){
			$this->db->where('test_uid',$test);
			$this->db->where('promotion_code',$code);
			$this->db->where('status','Active');
			$promotion = $this->promotion_model->get();
			if(isset($promotion[0]['qty']) && isset($promotion[0]['usage_qty']) && isset($promotion[0]['start_date']) && isset($promotion[0]['end_date'])){
				if(($promotion[0]['qty'] > $promotion[0]['usage_qty']) || ($promotion[0]['qty']== 0)){
					
					if(($promotion[0]['start_date'] < strtotime('now') || ($promotion[0]['start_date'] == 0)) 
					&& ($promotion[0]['end_date'] > strtotime('now') || ($promotion[0]['start_date'] == 0))){
						$this->db->update('promotion',array('usage_qty' => $promotion[0]['usage_qty']+1),array('uid'=>$promotion[0]['uid']));
						if($_SERVER["HTTP_HOST"] != "localhost"){
							require_once('PHPMailer_v5.1/class.phpmailer.php');
							$id = $this->sess_cus['id'];
							$member = $this->member_model->get($id);
							$datatest = $this->test_model->get($promotion[0]['test_uid']);
							if(!$this->send_email_outlook($promotion[0],$member,$datatest)){
							   $this->send_email_outlook($promotion[0],$member,$test);
							}
						}
						if(isset($promotion[0]['promotion_price']) && $promotion[0]['promotion_price']==0)
						url::redirect($this->site['base_url'].'test/start/'. base64_encode($test.text::random('numeric',3)).'/'. base64_encode($promotion[0]['promotion_code'].text::random('numeric',3)));
						else{
							url::redirect('payment/index/'.base64_encode($test).'/'.base64_encode($promotion[0]['uid']));
							
						}
					}
					$this->session->set('error_code','Promotion code has expired.');
				}
				else
				$this->session->set('error_code','The usage limit for this code has been reached.');
			}else
			$this->session->set('error_code','Correction invalid code.');
			url::redirect('payment/index/'.base64_encode($test));
		}
	} 
	
	
	public function paycard($test=""){
		
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');
			require_once('PHPMailer_v5.1/class.phpmailer.php');
			$id = $this->sess_cus['id'];
			$member = $this->member_model->get($id);
			$datatest = $this->test_model->get($this->search['uid']);
			$ordercode = $this->search['orderid'];
			$promotionid = $this->search['promotion_id'];
			$uid = $this->search['uid'];
			if($this->session->get('sess_search'))
		  	$this->session->delete('sess_search');
			////////////
			if($_SERVER["HTTP_HOST"] != "localhost"){
				if(!$this->send_email_outlook_pay(($ordercode),$member,$datatest)){
							 $this->send_email_outlook_pay(($ordercode),$member,$datatest);
				}
			}
			
			///////
			if(isset($promotionid) && !empty($promotionid))
			url::redirect($this->site['base_url'].'test/start/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay/'.base64_encode($promotionid.text::random('numeric',3)));
			else
			url::redirect($this->site['base_url'].'test/start/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay');
			
			die();
		}else{
		   $this->session->set('error_code','Transaction Fail ! ');
			url::redirect('test/showlist');
			die();
		}
		
		
	} 
	
	private function send_email_outlook_pay($ordercode,$member,$test)
    {
		
  		if($test['price']>0)
		$html_content = Data_template_Model::get_value('EMAIL_PAYMENT');
		else 
		$html_content = Data_template_Model::get_value('EMAIL_SKIPPAYMENT');
		
	    $mailuser = $this->sess_cus['email'];
		$subject = 'Payment '.$this->site['site_name'];
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$test['test_title'],$html_content);
		$html_content = str_replace('#ordercode#',$ordercode,$html_content);			
		
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
			
			$arr_email = explode('@',$mailuser);
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
		    $mail->AddAddress($mailuser);
			if(isset($member['company_contact_email']) && $member['company_contact_email']!=''){
		   		$mail->AddAddress($member['company_contact_email']);
			}
			
		    $mail->Subject = $subject;
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
	
	
	private function send_email_pay($ordercode,$member,$test)
    {
    	$swift = email::connect();
		 
		$from = $this->site['site_email'];
		$mailuser = $this->sess_cus['email'];
		$subject = 'Payment '.$this->site['site_name'];
		
		
		if($test['price']>0)
		$html_content = Data_template_Model::get_value('EMAIL_PAYMENT');
		else 
		$html_content = Data_template_Model::get_value('EMAIL_SKIPPAYMENT');
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$test['test_title'],$html_content);
		$html_content = str_replace('#ordercode#',$ordercode,$html_content);			
			
		
		$recipients = new Swift_RecipientList;
		$recipients->addTo($this->site['site_email']);
		$recipients->addTo($mailuser);
		if(isset($member['company_contact_email']) && $member['company_contact_email']!=''){
		   		$recipients->addTo($member['company_contact_email']);
		}
		 
		$message = new Swift_Message($subject, $html_content, "text/html");

	
		if($swift->send($message, $recipients, $from)){
		
		} else {
			
		}	
		// Disconnect
		$swift->disconnect();
    }
	
	
	private function send_email_outlook($record,$member,$test)
    {
		
  		$html_content = Data_template_Model::get_value('EMAIL_CHECKCODE_USER');
		
	    $mailuser = $this->sess_cus['email'];
		$subject = 'Check Code '.$this->site['site_name'];
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$test['test_title'],$html_content);
		$html_content = str_replace('#description#',($record['description']!='')?'<p><strong>Description: '.$record['description'].'</strong></p>':'',$html_content);
		$html_content = str_replace('#period#',
		(isset($list['start_date']) && ($record['start_date'] != 0))?date('m/d/Y',$record['start_date']):'' .
        (isset($list['end_date']) && ($record['end_date'] != 0))? ' ~ '.date('m/d/Y',$record['end_date']):'No limit'
		,$html_content);
		$html_content = str_replace('#no#',isset($record['qty'])?$record['usage_qty'] + 1 .'/'.$record['qty']:'No limit',$html_content);			
		
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
			$arr_email = explode('@',$record['email']);
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
			
			
			if(isset($record['email']) && $record['email']!=''){
		   		$mail->AddAddress($record['email']);
			}
			
		    $mail->Subject = $subject;
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
	/*public function testsendmail()
    {
		
  		require_once('PHPMailer_v5.1/class.phpmailer.php');
		$html_content = Data_template_Model::get_value('EMAIL_CHECKCODE_USER');
		
	    $mailuser = $this->sess_cus['email'];
		$subject = 'Check Code '.$this->site['site_name'];
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#','Test send mail',$html_content);
		$html_content = str_replace('#description#','test',$html_content);
					
		
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
			$record['email']='sonata.techknowledge@gmail.com';
			$arr_email = explode('@',$record['email']);
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
			$mail->FromName="pestest.com";
			$mail->Sender="support@pestest.com"; 
			}
			
			
			if(isset($record['email']) && $record['email']!=''){
		   		$mail->AddAddress($record['email']);
			}
			
		    $mail->Subject = $subject;
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

    }*/
	
	
	private function send_email($record,$member,$test)
    {
    	$swift = email::connect();
		 
		$from = $this->site['site_email'];
		$mailuser = $this->sess_cus['email'];
		$subject = 'Check Code '.$this->site['site_name'];
		
		$html_content = Data_template_Model::get_value('EMAIL_CHECKCODE_USER');
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$test['test_title'],$html_content);
		$html_content = str_replace('#description#',($record['description']!='')?'<p><strong>Description: '.$record['description'].'</strong></p>':'',$html_content);
		$html_content = str_replace('#period#',
		(isset($list['start_date']) && ($record['start_date'] != 0))?date('m/d/Y',$record['start_date']):'' .
        (isset($list['end_date']) && ($record['end_date'] != 0))? ' ~ '.date('m/d/Y',$record['end_date']):'No limit'
		,$html_content);
		$html_content = str_replace('#no#',isset($record['qty'])?$record['usage_qty']+ 1 .'/'.$record['qty']:'No limit',$html_content);			
		
		$recipients = new Swift_RecipientList;
		$recipients->addTo($this->site['site_email']);
		$recipients->addTo($mailuser);
		if(isset($record['email']) && $record['email']!=''){
		   		$recipients->addTo($record['email']);
		}
		 
		$message = new Swift_Message($subject, $html_content, "text/html");

	
		if($swift->send($message, $recipients, $from)){
		
		} else {
			
		}	
		// Disconnect
		$swift->disconnect();
    }
	
}
?>