<?php 
$lang = array
(
'txt_username' => Array
(
	'required' => 'The User name field is required.',
	'length' => 'The name must be between 5 and 50 letters.',
	'_check_user' => 'The User Name has been used.'
),
'txt_name' => Array
(	
 	'required' => 'The Name field is required.',
 	'length' => 'The name must be between 5 and 50 letters.'
),
'txt_pass' => Array
(	
 	'required' => 'The Password field is required.',
 	'length' => 'The password must be longer 5 letters than.'
),
'txt_cfpass' => Array
(	
 	'required' => 'The Confirm Password field is required.',
 	'matches' => 'The Confirm Password Invalid.'
),
'txt_email' => Array
(
 	'required' => 'The Email field is required.',
	'email' => 'The Email field must contain a valid email address.',
	'_check_email' => 'The Email has been used.'
),
'txt_address' => Array
(
	'required' => 'The Address field is required.',
),
'txt_city' => Array
(
	'required' => 'The City field is required.',
),
'txt_state' => Array
(
	'required' => 'The State field is required.',
),
'txt_zipcode' => Array
(
	'required' => 'The Zipcode field is required.',
),
'txt_phone' => Array
(	
	'phone' => 'Phone Number Invalid'
),
'txt_random' => Array
(
	'required' => 'The Security Code field is required.',
	'_check_security_code' => 'The Security Code invalid.'
),
'txt_old_pass' => Array
(
	'_check_old_pass' => 'Old Password invalid.'
),
'txt_new_pass' => Array
(	
 	'required' => 'The New Password field is required.',
 	'length' => 'The password must be longer 5 letters than.'
),
'txt_cf_new_pass' => Array
(	 	
 	'matches' => 'The Confirm Password Invalid.'
)
);