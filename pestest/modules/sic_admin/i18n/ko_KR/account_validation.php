<?php
$lang = array
(
	'txt_first_name' => Array
	(	
		'length' => '최초의 이름은 최대 50 문자가 포함되어 있어야합니다.'
	),
	'txt_last_name' => Array
	(	
		'length' => '마지막 이름은 최대 50 문자가 포함되어 있어야합니다.'
	),
	'txt_username' => Array
	(	
		'required' => '사용자 이름은 필수 항목입니다.'
	),
	'txt_email' => Array
	(
		'required' => '이메일이 필요합니다.',
		'email' => '이메일 유효한 이메일 주소를 포함해야합니다.',
		'_check_email' => '이 이메일 주소는 사용되었습니다',
		'_check_email_db' => '계정이 이메일 주소를 사용할 수 없다'
	),	
	'txt_pass' => Array
	(
		'required' => '비밀 번호가 필요합니다.',
		'length' => '비밀 번호는 6 ~ 50 문자가 포함되어 있어야합니다.'
	),
	'txt_old_pass' => Array
	(
		'_check_old_pass' => '이전 비밀 번호가 잘못되었습니다.'
	),
	'txt_new_pass' => Array
	(	
		'required' => '새 비밀 번호가 필요합니다.',
		'length' => '비밀 번호는 6 ~ 50 문자가 포함되어 있어야합니다.'
	),
	'txt_cf_new_pass' => Array
	(	 	
		'matches' => '비밀 번호가 일치하지 않습니다.'
	)
);