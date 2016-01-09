<?php
$lang = array
(
	'txt_first_name' => Array
	(	
		'length' => 'First name must be contain maximum 50 characters.'
	),
	'txt_last_name' => Array
	(	
		'length' => 'Last name must be contain maximum 50 characters.'
	),
	'txt_username' => Array
	(	
		'required' => 'Username is required.',
		'_check_username' => 'The Username has been used',
	),
	'txt_email' => Array
	(
		'required' => 'Email is required.',
		'email' => 'Email address invalid',
		'_check_email' => 'This email address has been used',
		'_check_email_db' => 'There is not account use this email address'
	),	
	'txt_pass' => Array
	(
		'required' => 'Password is required.',
		'length' => 'Password must be contain 6 ~ 50 characters.'
	),
	'txt_old_pass' => Array
	(
		'_check_old_pass' => 'Old Password invalid.'
	),
	'txt_new_pass' => Array
	(	
		'required' => 'New Password is required.',
		'length' => 'Password must be contain 6 ~ 50 characters.'
	),
	'txt_cf_new_pass' => Array
	(	 	
		'matches' => 'Password is not matched.'
	),
	'txt_content' => Array
	(
		'required' => 'Content is required.',
		
	),
);
