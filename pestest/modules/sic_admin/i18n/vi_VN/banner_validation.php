<?php
$lang = array
(
	'txt_width' => Array
	(
		'required' => 'Chiều rộng banner là rỗng',
		'digit' => 'Chiều rộng banner phải là số'
	),
	'txt_height' => Array
	(
		'required' => 'Chiều cao banner là rỗng',
		'digit' => 'Chiều cao banner phải là số '
	),
	'attach_image' => Array
	( 	
		//'valid' => 'No Image Format',
		'type' => 'Chỉ cho phép các phần mở rộng '.ORM::factory('file_type_orm')->where('file_type_detail','image')->find()->file_type_ext,
		'size' => 'Kích thước tối đa của hình là 1 Mb'		
	),
	'attach_flash' => Array
	( 	
		//'valid' => 'No Flash Format',
		'type' => 'Chỉ cho phép phần mở rộng là '.ORM::factory('file_type_orm')->where('file_type_detail','flash')->find()->file_type_ext,
		'size' => 'Kích thước tối đa của flash 1 Mb'
	)
);
?>