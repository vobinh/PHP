<?php
$lang = array
(
'txt_name' => Array
(	
 	'required' => 'The Name field is required.',
 	'length' => 'The name must be between 5 and 50 letters.'
),
'txt_username' => Array
(
	'required' => 'The User name field is required.',
	'length' => 'The name must be between 5 and 50 letters.',
	'_check_username' => 'This username has been exist'
),
'txt_pass' => Array
(	
	'required' => 'The Password field is required.',
 	'length' => 'The password must be longer 5 letters than.'
),
'txt_email' => Array
(
 	'required' => 'The Email field is required.',
	'email' => 'The Email field must contain a valid email address.',
	'_check_email' => 'This email address has been exist'
)
);