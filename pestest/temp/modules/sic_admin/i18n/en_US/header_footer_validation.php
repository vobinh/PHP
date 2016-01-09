<?php defined('SYSPATH') or die('No direct access allowed.');
$lang = array
(
	'attach_image' => Array
	(
		'type' => 'Just allow '.Model::factory('file_type')->get_fext('image').' extension',
		'size' => 'Image Size maximum is 10 Mb'
	),
	'attach_flash' => Array
	(
		'type' => 'Just allow '.Model::factory('file_type')->get_fext('flash').' extension',
		'size' => 'Image Size maximum is 10 Mb'
	)
);