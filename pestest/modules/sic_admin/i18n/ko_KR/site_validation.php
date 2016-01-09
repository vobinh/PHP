<?php
$lang = array
(
	'txt_name' => Array


	(	'required' => 'Site Name is required.',
		'length' => 'Site Name must be between 5 and 50 letters.'
	),
	'txt_phone' => Array
	(

		'required' => 'Phone is required.',	
	),
	'txt_email' => Array
	(

		'required' => 'Email is required.',
		'email' => 'Email must contain a valid email address.'
	),
	'txt_title' => Array
	(


		'required' => 'Title is required.',
		'length' => 'Title must be contain maximum 200 letters.'
	),
	'txt_admin_num_line' => Array
	(

		'digit' => 'Line per Admin page must be a poscitive decimal'
	),
	'txt_client_num_line2' => Array
	(

		'digit' => 'Line per Client page must be a poscitive decimal'
	),
	'attach_logo' => Array
	(


		'type' => 'Only type PNG, GIF, JPEG, JPG allow.',
		'size' => 'Maximum of image size is 10 Mb.' 
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