<?php
	require Kohana::find_file('vendor/mailgun1.7.2','autoload');
	use Mailgun\Mailgun;

	$data_mailgun = $this->db->get('mailgun_config')->result_array(false);
	if(!empty($data_mailgun[0])){
		define('MAILGUN_KEY', $data_mailgun[0]['mailgun_key']);
		define('MAILGUN_PUBKEY', $data_mailgun[0]['mailgun_pubkey']);
		define('MAIL_FROM',$data_mailgun[0]['mailgun_from']);
		define('MAILGUN_DOMAIN', $data_mailgun[0]['mailgun_domain']);
	}

	define('MAILGUN_LIST', 'customer@sandbox54bdc93fa0844c69a4c4eb16e9cefecf.mailgun.org');
	define('MAILGUN_SECRET', 'webtos.websolutions.vn');

	$mailgun         = new Mailgun(MAILGUN_KEY);
	$mailgunValidate = new Mailgun(MAILGUN_PUBKEY);

	$mailgunOptIn = $mailgun->OptInHandler();
?>