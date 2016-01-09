
<?php
	require_once 'init.php';
	
	$email = 'tuanvukimhoanh9192@gmail.com';
	$validate = $mailgunValidate->get('address/validate',array('address' => $email))->http_response_body; // kiem tra mail: is_valid = 1 dung la email
	echo '<br>';
	echo MAIL_FROM;
	echo '<br>';
	echo MAILGUN_DOMAIN;
	if($validate->is_valid){
		// lay hash cua email
		$hash = $mailgunOptIn->generateHash(MAILGUN_LIST, MAILGUN_SECRET,$email);
		/*
			/*send email
			$result_send = $mailgun->sendMessage(MAILGUN_DOMAIN,
		                  array('from'    => 'Mailgun Sandbox <postmaster@sandbox54bdc93fa0844c69a4c4eb16e9cefecf.mailgun.org>',
		                        'to'      => 'wthanhbinh@gmail.com',
		                        'subject' => 'Hello torin',
		                        'html'    => "
									Hello {$email},<br>
									test mail mailgun.<br>
									http://webtos.websolutions.vn/mailgun?hash={$hash}
		                        "
		                        ));
		*/
		// $mailgun->sendMessage(MAILGUN_DOMAIN,
		//                   	array(
		// 					    'from'    => MAIL_FROM,
		// 					    'to'      => 'wthanhbinh@gmail.com',
		// 					    'subject' => 'Hello',
		// 					    'text'    => 'Testing some Mailgun awesomness!',
		// 					    'html'    => '<html>HTML version of the body</html>'
		// 					), 
		//                   	array("attachment" => array('filePath'   => '@uploads/import/1418001835_Book1.xlsx')));
		
		// $mailgun->sendMessage(MAILGUN_DOMAIN,
		//                   	array(
		// 					    'from'    => MAIL_FROM,
		// 					    'to'      => 'tuanvukimhoanh9192@gmail.com',
		// 					    'subject' => 'Hello',
		// 					    'text'    => 'Testing some Mailgun awesomness!',
		// 					    'html'    => '<html>HTML version of the body</html>'
		// 					), 
		//                   	array("attachment" => array('filePath'   => '@uploads/import/1418001835_Book1.xlsx')));

		/*
			// them email nay vao list cua mailgun vs trang thai chua kich hoat
			$result = $mailgun->post("lists/".MAILGUN_LIST."/members", array(
			    'address'     => $email,
			    'name'        => 'wthanhbinh',
			    'description' => 'customer',
			    'subscribed'  => 'no',
			));
		*/

		/*
			// cap nhat trang thai cua email trong list
			
		

		//GET
		if(isset($_GET['hash'])){
			$hash = $mailgunOptIn->validateHash(MAILGUN_SECRET,$hash);//$_GET['hash']
			if($hash){
				$list = $hash['mailingList'];
				$email_get = $hash['recipientAddress'];

				$mailgun->put("lists/".MAILGUN_LIST."/members/".$email_get, array(
				    'subscribed'  => 'yes',
				));
			}
		}
		*/
	}


	    
?>