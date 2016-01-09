<?php
$lang = array
(
'txt_name' => Array
(	'required' => 'The Name field is required.',
 	'length' => 'The name must be between 5 and 50 letters.'
),
'txt_phone' => Array
(
	'required' => 'Phone field is required.',	
	'phone' => 'Phone Number Invalid'
),
'txt_fax' => Array
(	'required' => 'Fax field is required.',
 	'phone' => 'Fax Number Invalid'
),
'txt_email' => Array
(
 	'required' => 'The Email field is required.',
	'email' => 'The Email field must contain a valid email address.'
),
'txt_bankacc' => Array
(
 	'required' => 'Bank Account field is required.',
	'length' => 'Bank Account must be between 6 and 20 letters.',
	'alpha_dash' => 'Bank Account must contain alphabetical characters, numbers, underscores and dashes letters'
),
'txt_title' => Array
(
	'required' => 'Title field is required.',
	'length' => 'Title must be between 6 and 200 letters.'
),
'txt_footer' => Array
(
 	'required' => 'Footer field is required.',
	'length' => 'Footer must be between 10 and 500 letters.'
),
'txt_num_line' => Array
(
	'digit' => 'The line per page must be a poscitive decimal'
),
'txt_num_line2' => Array
(
	'digit' => 'The line per page 2 must be a poscitive decimal'
),
'attach_logo' => Array
(
	'type' => 'Only type PNG, GIF, JPEG, JPG allow.',
	'size' => 'Maximum of image size is 2 Mb.' 
),
'txt_width' => Array
(
	'required' => 'Width of Logo empty',
	'digit' => 'Width of Logo must be a Number'
),
'txt_height' => Array
(
 	'required' => 'Height of Logo empty',
	'digit' => 'Height of Logo must be a Number'
)
);