<?php
class Payment_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/home');
		// Init session 
		$this->authorizenet_model      = new Authorizenet_config_Model();
		$this->promotion_model         = new Promotion_Model();
		$this->category_model          = new Category_Model();
		$this->test_model              = new Test_Model(); 
		$this->member_model            = new Member_Model();
		$this->payment_model           = new Payment_Model();
		
		$this->courses_model           = new Courses_Model();
		$this->study_model             = new Study_Model();
		$this->lesson_model            = new Lesson_Model();
		$this->lesson_annotation_model = new Lesson_annotation_Model();
		$this->data_template_model     = new Data_template_Model();

		if($this->sess_cus =="")
		{
			url::redirect(url::base());
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
	public function show_arr($arr){
		echo '<pre>';
			print_r($arr);
		echo '</pre>';
	}
   	public function index($courses,$promotion="",$item_id=""){
		$courses = base64_decode($courses);
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/courses/payment');
		$data_courses                    = $this->courses_model->get($courses);
		$mrauthorizenet                  = $this->authorizenet_model->get();
		$data_courses['api_login']       = $mrauthorizenet['api_login'];
		$data_courses['transaction_key'] = $mrauthorizenet['transaction_key'];
		$data_courses['post_url']        = $mrauthorizenet['post_url'];
		////////
		if(!empty($promotion)){
		  	$promotion_id = base64_decode($promotion);
		  	$mr_promotion = $this->promotion_model->get($promotion_id);
		  	// Check only code
		  	if(!empty($mr_promotion)){
		  		if($mr_promotion['type'] == '2'){
		  			// Get id item
		  			$id_item = base64_decode($item_id);
		  			$this->db->where('idpromotion_item',$id_item);
					$item_promotion = $this->db->get('promotion_item')->result_array(false);
					if(isset($mr_promotion['promotion_price']) && $mr_promotion['promotion_price']!=0){
						$data_courses['price']           = $data_courses['price']-$mr_promotion['promotion_price'];
						$data_courses['promotion_price'] = $mr_promotion['promotion_price'];
						$data_courses['promotion_code']  = $item_promotion[0]['code'];
						$data_courses['promotion_id']    = $mr_promotion['uid'];
						$data_courses['promotion_type']  = 2;
				  	}else{
						$data_courses['price']           = $data_courses['price'];
						$data_courses['promotion_price'] = "";
						$data_courses['promotion_code']  = "";
						$data_courses['promotion_id']    = "";
						$data_courses['promotion_type']  = "";
				 	}
		  		}else{
			  		if(isset($mr_promotion['promotion_price']) && $mr_promotion['promotion_price']!=0){
						$data_courses['price']           = $data_courses['price']-$mr_promotion['promotion_price'];
						$data_courses['promotion_price'] = $mr_promotion['promotion_price'];
						$data_courses['promotion_code']  = $mr_promotion['promotion_code'];
						$data_courses['promotion_id']    = $mr_promotion['uid'];
						$data_courses['promotion_type']  = 1;
				  	}else{
						$data_courses['price']           = $data_courses['price'];
						$data_courses['promotion_price'] = "";
						$data_courses['promotion_code']  = "";
						$data_courses['promotion_id']    = "";
						$data_courses['promotion_type']  = "";
				 	}
				 }
			}else{
				$data_courses['price']           = $data_courses['price'];
				$data_courses['promotion_price'] = "";
				$data_courses['promotion_code']  = "";
				$data_courses['promotion_id']    = "";
				$data_courses['promotion_type']  = "";
			}
		}else{
			$data_courses['promotion_price'] = "";
			$data_courses['promotion_code']  = "";
			$data_courses['promotion_id']    = "";
			$data_courses['promotion_type']  = "";
		}

		$orderid                                  = strtoupper(text::random('alnum',12));
		$transaction_order_number                 = rand(10000,99999);
		$data_courses['orderid']                  = $orderid;
		$data_courses['transaction_order_number'] = $transaction_order_number; 
		$data_courses['time']                     = '';
		$data_courses['hash']                     = '';
		
		$stripe_config = $this->db->get('stripe')->result_array(false);
		////
		$this->session->set_flash('sess_search',$data_courses);
		////
		$this->template->content->courses       = $data_courses;
		$this->template->content->stripe_config = $stripe_config;
	}
	
	public function checkcode($courses,$type=''){	
		$code = $this->input->post('txt_checkcode');
		if(isset($code) && isset($courses)){
			$this->db->where('courses_id',$courses);
			$this->db->where('promotion_code',$code);
			$this->db->where('status','Active');
			$promotion = $this->promotion_model->get();
			if(!empty($promotion)){
				/**
				 * check only code
				 */
				if(isset($promotion[0]['qty']) && isset($promotion[0]['start_date']) && isset($promotion[0]['end_date'])){
					if(((int)$promotion[0]['qty'] > (int)$promotion[0]['usage_qty']) || ($promotion[0]['qty']== 0)){
						
						if(($promotion[0]['start_date'] < strtotime('now') || ($promotion[0]['start_date'] == 0)) 
						&& ($promotion[0]['end_date'] > strtotime('now') || ($promotion[0]['start_date'] == 0))){
							if(isset($promotion[0]['promotion_price']) && $promotion[0]['promotion_price']==0){
								$this->db->update('promotion',array('usage_qty' => $promotion[0]['usage_qty']+1),array('uid'=>$promotion[0]['uid']));
								/**
							 	* send mail using promotion
							 	*/
								$id           = $this->sess_cus['id'];
								$member       = $this->member_model->get($id);
								$data_courses = $this->courses_model->get($promotion[0]['courses_id']);
								
								$this->send_mailgun($promotion[0],$member,$data_courses);
							
								if($type == 'json'){
									$array = array(
										'type'     => 'free',
										'messages' => url::base().'courses/free_courses/'. base64_encode($courses.text::random('numeric',3)).'/'. base64_encode($promotion[0]['promotion_code'].text::random('numeric',3)),
									);
									echo json_encode($array);
									die();
								}
								url::redirect(url::base().'courses/free_courses/'. base64_encode($courses.text::random('numeric',3)).'/'. base64_encode($promotion[0]['promotion_code'].text::random('numeric',3)));
							}
							else{
								if($type == 'json'){
									$data_payment = $this->get_info_check(base64_encode($courses),base64_encode($promotion[0]['uid']));
									$array = array(
										'type'     => 'payment',
										'messages' => $data_payment,
									);
									echo json_encode($array);
									die();
								}
								url::redirect(url::base().'payment/index/'.base64_encode($courses).'/'.base64_encode($promotion[0]['uid']));
								
							}
						}
						/**
						 * het han
						 */
						if($type == 'json'){
							$data_payment = $this->get_info_check(base64_encode($courses),base64_encode($promotion[0]['uid']));
							$array = array(
								'type'     => 'error',
								'messages' => 'Promotion code has expired.',
							);
							echo json_encode($array);
							die();
						}
						$this->session->set_flash('error_msg','Promotion code has expired.');
					}else{
						if($type == 'json'){
							$array = array(
								'type'     => 'error',
								'messages' => 'The usage limit for this code has been reached.',
							);
							echo json_encode($array);
							die();
						}else{
							if($type == 'json'){
								$array = array(
									'type'     => 'error',
									'messages' => 'The usage limit for this code has been reached.',
								);
								echo json_encode($array);
								die();
							}else{
								$this->session->set_flash('error_msg','The usage limit for this code has been reached.');
							}
						}
					}
				}else{
					if($type == 'json'){
						$array = array(
							'type'     => 'error',
							'messages' => 'Correction invalid code.',
						);
						echo json_encode($array);
						die();
					}else{
						$this->session->set_flash('error_msg','Correction invalid code.');
					}
				}
			}else{
				$this->db->where('code',$code);
				$this->db->where('status','1');
				$item_promotion = $this->db->get('promotion_item')->result_array(false);
				if(!empty($item_promotion)){
					$this->db->where('courses_id',$courses);
					$this->db->where('uid',$item_promotion[0]['promotion_id']);
					$this->db->where('status','Active');
					$promotion = $this->promotion_model->get();
					if(!empty($promotion)){
						/**
						 * check multi code
						 */
						if(isset($promotion[0]['qty']) && isset($promotion[0]['start_date']) && isset($promotion[0]['end_date'])){
							if(((int)$promotion[0]['qty'] > (int)$item_promotion[0]['usage_qty']) || ($promotion[0]['qty']== 0)){
								if(($promotion[0]['start_date'] < strtotime('now') || ($promotion[0]['start_date'] == 0)) 
								&& ($promotion[0]['end_date'] > strtotime('now') || ($promotion[0]['start_date'] == 0))){
									
									if(isset($promotion[0]['promotion_price']) && $promotion[0]['promotion_price']==0){
										
										$this->db->update('promotion',array('usage_qty' => $promotion[0]['usage_qty']+1),array('uid'=>$promotion[0]['uid']));
										// update item
						  				$data_item = array('usage_qty' => (int)$item_promotion[0]['usage_qty']+1);
						  				$this->db->where('idpromotion_item',$item_promotion[0]['idpromotion_item']);
						  				$this->db->update('promotion_item',$data_item);
						  				$item_code_id = $item_promotion[0]['idpromotion_item'];
										/**
									 	* send mail using promotion
									 	*/
									 	$promotion[0]['promotion_code'] = $item_promotion[0]['code'];
										$id           = $this->sess_cus['id'];
										$member       = $this->member_model->get($id);
										$data_courses = $this->courses_model->get($promotion[0]['courses_id']);
										$this->send_mailgun($promotion[0],$member,$data_courses);
									
										if($type == 'json'){
											$array = array(
												'type'     => 'free',
												'messages' => url::base().'courses/free_courses/'. base64_encode($courses.text::random('numeric',3)).'/'. base64_encode($promotion[0]['promotion_code'].text::random('numeric',3)),
											);
											echo json_encode($array);
											die();
										}
										url::redirect(url::base().'courses/free_courses/'. base64_encode($courses.text::random('numeric',3)).'/'. base64_encode($promotion[0]['promotion_code'].text::random('numeric',3)));
									}
									else{
										if($type == 'json'){
											$data_payment = $this->get_info_check(base64_encode($courses),base64_encode($promotion[0]['uid']),base64_encode($item_promotion[0]['idpromotion_item']));
											$array = array(
												'type'     => 'payment',
												'messages' => $data_payment,
											);
											echo json_encode($array);
											die();
										}
										url::redirect(url::base().'payment/index/'.base64_encode($courses).'/'.base64_encode($promotion[0]['uid']).'/'.base64_encode($item_promotion[0]['idpromotion_item']));
										
									}
								}
								/**
								 * het han
								 */
								if($type == 'json'){
									$data_payment = $this->get_info_check(base64_encode($courses),base64_encode($promotion[0]['uid']),base64_encode($item_promotion[0]['idpromotion_item']));
									$array = array(
										'type'     => 'error',
										'messages' => 'Promotion code has expired.',
									);
									echo json_encode($array);
									die();
								}
								$this->session->set_flash('error_msg','Promotion code has expired.');
							}else{
								if($type == 'json'){
									$array = array(
										'type'     => 'error',
										'messages' => 'The usage limit for this code has been reached.',
									);
									echo json_encode($array);
									die();
								}else{
									if($type == 'json'){
										$array = array(
											'type'     => 'error',
											'messages' => 'The usage limit for this code has been reached.',
										);
										echo json_encode($array);
										die();
									}else{
										$this->session->set_flash('error_msg','The usage limit for this code has been reached.');
									}
								}
								
							}
						}
					}else{
						if($type == 'json'){
							$array = array(
								'type'     => 'error',
								'messages' => 'Correction invalid code.',
							);
							echo json_encode($array);
							die();
						}else{
							$this->session->set_flash('error_msg','Correction invalid code.');
						}
					}
				}else{
					if($type == 'json'){
						$array = array(
							'type'     => 'error',
							'messages' => 'Correction invalid code.',
						);
						echo json_encode($array);
						die();
					}else{
						$this->session->set_flash('error_msg','Correction invalid code.');
					}
				}
			}
			url::redirect(url::base().'payment/index/'.base64_encode($courses));
		}
	} 
	
	public function get_info_check($courses,$promotion="",$item_id="")
	{
		$courses = base64_decode($courses);
		$data_courses                    = $this->courses_model->get($courses);
		$mrauthorizenet                  = $this->authorizenet_model->get();
		$data_courses['api_login']       = $mrauthorizenet['api_login'];
		$data_courses['transaction_key'] = $mrauthorizenet['transaction_key'];
		$data_courses['post_url']        = $mrauthorizenet['post_url'];

		if(!empty($promotion)){
		  	$promotion_id = base64_decode($promotion);
		  	$mr_promotion = $this->promotion_model->get($promotion_id);
		  	// Check only code
		  	if(!empty($mr_promotion)){
		  		if($mr_promotion['type'] == '2'){
		  			// Get id item
		  			$id_item = base64_decode($item_id);
		  			$this->db->where('idpromotion_item',$id_item);
					$item_promotion = $this->db->get('promotion_item')->result_array(false);
					if(isset($mr_promotion['promotion_price']) && $mr_promotion['promotion_price']!=0){
						$data_courses['price']           = $data_courses['price']-$mr_promotion['promotion_price'];
						$data_courses['promotion_price'] = $mr_promotion['promotion_price'];
						$data_courses['promotion_code']  = $item_promotion[0]['code'];
						$data_courses['promotion_id']    = $mr_promotion['uid'];
						$data_courses['promotion_type']  = 2;
				  	}else{
						$data_courses['price']           = $data_courses['price'];
						$data_courses['promotion_price'] = "";
						$data_courses['promotion_code']  = "";
						$data_courses['promotion_id']    = "";
						$data_courses['promotion_type']  = "";
				 	}
		  		}else{
			  		if(isset($mr_promotion['promotion_price']) && $mr_promotion['promotion_price']!=0){
						$data_courses['price']           = $data_courses['price']-$mr_promotion['promotion_price'];
						$data_courses['promotion_price'] = $mr_promotion['promotion_price'];
						$data_courses['promotion_code']  = $mr_promotion['promotion_code'];
						$data_courses['promotion_id']    = $mr_promotion['uid'];
						$data_courses['promotion_type']  = 1;
				  	}else{
						$data_courses['price']           = $data_courses['price'];
						$data_courses['promotion_price'] = "";
						$data_courses['promotion_code']  = "";
						$data_courses['promotion_id']    = "";
						$data_courses['promotion_type']  = "";
				 	}
				 }
			}else{
				$data_courses['price']           = $data_courses['price'];
				$data_courses['promotion_price'] = "";
				$data_courses['promotion_code']  = "";
				$data_courses['promotion_id']    = "";
				$data_courses['promotion_type']  = "";
			}
		}else{
			$data_courses['promotion_price'] = "";
			$data_courses['promotion_code']  = "";
			$data_courses['promotion_id']    = "";
			$data_courses['promotion_type']  = "";
		}

		$orderid                                  = strtoupper(text::random('alnum',12));
		$transaction_order_number                 = rand(10000,99999);
		$data_courses['orderid']                  = $orderid;
		$data_courses['transaction_order_number'] = $transaction_order_number; 
		$data_courses['time']                     = '';
		$data_courses['hash']                     = '';
		$this->session->set_flash('sess_search',$data_courses);
		return $data_courses;
	}
	
	public function test_payment(){
		phpinfo();
		die();
		require_once(APPPATH.'vendor/stripe_3.4.0/init.php');
		\Stripe\Stripe::setApiKey("sk_test_KoZDHh3YJHEtGnAX974ORvkf");
		try {
			$charge = \Stripe\Charge::create(array(
				"amount"      => 15000, // amount in cents, again
				"currency"    => "usd",
				"source"      => 'tok_1744MXH5rJOCkodH4mWKJWHJ',
				"description" => "",
			));
		} catch(\Stripe\Error\Card $e) {
		  // The card has been declined
		}
		die();
	}

	public function paycard($courses=""){
		if(isset($_POST)){
			$stripe_config = $this->db->get('stripe')->result_array(false);
			if(!empty($stripe_config)){
				if($stripe_config[0]['type'] == 1){
					$test_secret_key = $stripe_config[0]['live_secret_key'];
				}else{
					$test_secret_key = $stripe_config[0]['test_secret_key'];
				}
				
				require_once(APPPATH.'vendor/stripe_3.4.0/init.php');
				\Stripe\Stripe::setApiKey($test_secret_key);
				$data_stripe = $this->input->post('data_stripe');
				$m_total     = $this->input->post('m_total');
				$m_code      = $this->input->post('m_code');
				$m_type_code = $this->input->post('m_type_code');
				$item_code_id = '';
				$token = $data_stripe['id'];
				require_once Kohana::find_file('views/mailgun','init');
				try {
					$charge = \Stripe\Charge::create(array(
						"amount"      => $m_total, // amount in cents, again
						"currency"    => "usd",
						"source"      => $token,
						"description" => "Payment Courses"
					));
					if($this->session->get('sess_search')){			
						$this->search = $this->session->get('sess_search');
						$id           = $this->sess_cus['id'];
						$member       = $this->member_model->get($id);
						$data_courses = $this->courses_model->get($this->search['id']);
						$ordercode    = $this->search['orderid'];
						$promotionid  = $this->search['promotion_id'];
						$uid          = $this->search['id'];

						if($this->session->get('sess_search'))
					  		$this->session->delete('sess_search');
					  	/* update promotin */
					  	if(!empty($m_code) && !empty($m_type_code)){
					  		if($m_type_code == 2){
					  			$mr_promotion = $this->promotion_model->get($promotionid);
					  			$this->db->where('code',$m_code);
					  			$this->db->where('promotion_id',$promotionid);
					  			$m_item_code = $this->db->get('promotion_item')->result_array(false);
					  			if(!empty($m_item_code)){
					  				$mr_promotion['promotion_code'] = $m_item_code[0]['code'];
					  				$this->db->update('promotion',array('usage_qty' => $mr_promotion['usage_qty']+1),array('uid'=>$mr_promotion['uid']));

					  				// update item
					  				$data_item = array('usage_qty' => (int)$m_item_code[0]['usage_qty']+1);
					  				$this->db->where('idpromotion_item',$m_item_code[0]['idpromotion_item']);
					  				$this->db->update('promotion_item',$data_item);
					  				$item_code_id = $m_item_code[0]['idpromotion_item'];
					  				/**
									 * send mail using promotion
									 */
					  				$this->send_mailgun($mr_promotion,$member,$data_courses,1,$mailgun);
					  			}
					  		}elseif($m_type_code == 1){
					  			$mr_promotion = $this->promotion_model->get($promotionid);
					  			if(!empty($mr_promotion)){
					  				$this->db->update('promotion',array('usage_qty' => $mr_promotion['usage_qty']+1),array('uid'=>$mr_promotion['uid']));
					  				/**
									 * send mail using promotion
									 */
					  				$this->send_mailgun($mr_promotion,$member,$data_courses,1,$mailgun);
					  			}
					  		}
					  	}
						/**
						 * send mail vs mail gun
						 */
						$this->send_mailgun_pay(($ordercode),$member,$data_courses,1,$mailgun);
						if(isset($promotionid) && !empty($promotionid)){
							//url::redirect(url::base().'courses/free_courses/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay/'.base64_encode($promotionid.text::random('numeric',3)));
							$array  = array(
								'type'     => 'payment',
								'messages' => url::base().'courses/free_courses/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay/'.base64_encode($promotionid.text::random('numeric',3)).'/'.$item_code_id, 
							);
							echo json_encode($array);
						}else{
							//url::redirect(url::base().'courses/free_courses/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay');
							$array  = array(
								'type'     => 'payment',
								'messages' => url::base().'courses/free_courses/'. base64_encode($uid.text::random('numeric',3)).'/'. base64_encode($ordercode.text::random('numeric',3)).'/pay', 
							);
							echo json_encode($array);
						}
						die();
					}else{
						$array  = array(
							'type'     => 'session',
							'messages' => 'Transaction Fail!', 
						);
						echo json_encode($array);
						die();
					}
				} catch(\Stripe\Error\Card $e) {
				  // The card has been declined
					$array  = array(
						'type'     => 'stripe_error',
						'messages' => $e, 
					);
					echo json_encode($array);
				}
				die();
			}
		}
	} 
	
	private function send_mailgun_pay($ordercode,$member,$courses,$create="",$mailgun=''){
		if(empty($create))
			require_once Kohana::find_file('views/mailgun','init');

		if($courses['price']>0)
			$html_content = $this->data_template_model->get_value('EMAIL_PAYMENT');
		else 
			$html_content = $this->data_template_model->get_value('EMAIL_SKIPPAYMENT');

		$mailuser = $this->sess_cus['email'];
		//$subject = 'Payment '.$this->site['site_name'];
		$subject = 'Pestest - Your receipt';

		/*
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$courses['title'],$html_content);
		$html_content = str_replace('#ordercode#',$ordercode,$html_content);
		*/
		$html_content = '<p>You have made the following purchase at '.date('h:iA').' on '.date('m/d/Y').'.</p>';
		$html_content .= '<p>'.$courses['title'].' ($'.(!empty($courses['price'])?$courses['price']:0).')</p>';
		$html_content .= '<p>Click on "My Courses" tab after logging in to access the e-courses and tests you have purchased.</p>';
		$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
			array(
				'from'       => MAIL_FROM,
				'to'         => $mailuser,
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

	private function send_email_outlook_pay($ordercode,$member,$courses)
    {
		
  		if($courses['price']>0)
			$html_content = $this->data_template_model->get_value('EMAIL_PAYMENT');
		else 
			$html_content = $this->data_template_model->get_value('EMAIL_SKIPPAYMENT');
		
	    $mailuser = $this->sess_cus['email'];
		$subject = 'Payment '.$this->site['site_name'];
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$courses['title'],$html_content);
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
		$html_content = $this->data_template_model->get_value('EMAIL_PAYMENT');
		else 
		$html_content = $this->data_template_model->get_value('EMAIL_SKIPPAYMENT');
		
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
	
	private function send_mailgun($record,$member,$courses,$create="",$mailgun=''){
		if(empty($create))
			require_once Kohana::find_file('views/mailgun','init');

		$mailuser = $this->sess_cus['email'];
		$subject = 'Check Code '.$this->site['site_name'];

		$html_content = $this->data_template_model->get_value('EMAIL_CHECKCODE_USER');

		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$courses['title'],$html_content);
		$html_content = str_replace('#description#',($record['description']!='')?'<p><strong>Description: '.$record['description'].'</strong></p>':'',$html_content);
		$html_content = str_replace('#period#',
		(isset($list['start_date']) && ($record['start_date'] != 0))?date('m/d/Y',$record['start_date']):'' .
        (isset($list['end_date']) && ($record['end_date'] != 0))? ' ~ '.date('m/d/Y',$record['end_date']):'No limit'
		,$html_content);
		$html_content = str_replace('#no#',isset($record['qty'])?$record['usage_qty'] + 1 .'/'.$record['qty']:'No limit',$html_content);
		
		$html_content = str_replace('Test:', 'Courses:', $html_content);

		$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
			array(
				'from'       => MAIL_FROM,
				'to'         => $mailuser,
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

	private function send_email_outlook($record,$member,$courses)
    {
		
  		$html_content = $this->data_template_model->get_value('EMAIL_CHECKCODE_USER');
		
	    $mailuser = $this->sess_cus['email'];
		$subject = 'Check Code '.$this->site['site_name'];
		
		$html_content = str_replace('#date#',date('m/y/Y',strtotime('now')),$html_content);	
		$html_content = str_replace('#username#',$this->sess_cus['name'],$html_content);
		$html_content = str_replace('#test#',$courses['title'],$html_content);
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
		$html_content = $this->data_template_model->get_value('EMAIL_CHECKCODE_USER');
		
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
		
		$html_content = $this->data_template_model->get_value('EMAIL_CHECKCODE_USER');
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