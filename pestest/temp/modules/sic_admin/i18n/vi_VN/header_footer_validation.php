<?php
$lang = array
(
	'attach_image' => Array
	(
		'type' => 'Chỉ cho phép các định dạng '.Model::factory('file_type')->get_fext('image'),
		'size' => 'Kích thước tối đa của tập tin hình ảnh là 10 Mb'
	),
	'attach_flash' => Array
	(
		'type' => 'Chỉ cho phép các định dạng '.Model::factory('file_type')->get_fext('flash'),
		'size' => 'Kích thước tối đa của tập tin hình ảnh là 10 Mb'
	)
);