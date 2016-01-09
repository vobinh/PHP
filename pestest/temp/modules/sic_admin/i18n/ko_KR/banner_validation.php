<?php
$lang = array
(
	'txt_width' => Array
	(
		'required' => '넓이가 지정되지 않았습니다.',
		'digit' => '넓이는 숫자만 허용됩니다.'
	),
	'txt_height' => Array
	(
		'required' => '높이가 지정되지 않았습니다.',
		'digit' => '높이는 숫자만 허용됩니다.'
	),
	'attach_image' => Array
	(
		'required' => '파일이 선택되지 않았습니다.',
		'valid' => '이미지 파일이 아닙니다.',
		'type' => ORM::factory('file_type_orm')->where('file_type_detail','image')->find()->file_type_ext.' 파일만 업로드 가능합니다.',
		'size' => '이미지 파일 사이즈는 10MB이 최대입니다.'
	),
	'attach_flash' => Array
	(
		'required' => '파일이 선택되지 않았습니다.',
		'valid' => '플래쉬 파일이 아닙니다.',
		'type' => ORM::factory('file_type_orm')->where('file_type_detail','flash')->find()->file_type_ext.' 파일만 업로드 가능합니다.',
		'size' => '이미지 파일 사이즈는 10MB이 최대입니다.'
	)
);