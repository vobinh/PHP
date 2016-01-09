<?php
$lang = array
(
	'txt_width' => Array
	(
		'required' => 'Width of Banner empty',
		'digit' => 'Width of Banner must be a Number'
	),
	'txt_height' => Array
	(
		'required' => 'Height of Banner empty',
		'digit' => 'Height of of Banner must be a Number'
	),
	'attach_image' => Array
	( 	
		'type' => 'Just allow '.ORM::factory('file_type_orm')->where('file_type_detail','image')->find()->file_type_ext.' extension',
		'size' => 'Image size maximum is 10 Mb'
	),
	'attach_flash' => Array
	( 	
		'type' => 'Just allow '.ORM::factory('file_type_orm')->where('file_type_detail','flash')->find()->file_type_ext.' extension',
		'size' => 'Flash size maximum is 10 Mb'
	)
);
?>