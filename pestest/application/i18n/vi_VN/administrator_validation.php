<?php
$lang = array
(
'txt_name' => Array
(	
 	'required' => 'Tên không được để trống',
 	'length' => 'Độ dài của tên phải từ 5 đến 50 kí tự.'
),
'txt_username' => Array
(
	'required' => 'Tên tài khoản không được để trống',
	'length' => 'Độ dài của tên tài khoản phải từ 5 đến 50 kí tự.',
	'_check_username' => 'Tên tài khoản này đã được sử dụng'
),
'txt_pass' => Array
(	
	'required' => 'Mật khẩu không được để trống',
 	'length' => 'Mật khẩu phải có 5 kí tự trở lên'
),
'txt_email' => Array
(
 	'required' => 'Thư điện tử không được để trống',
	'email' => 'Địa chỉ Thư điện tử không hợp lệ',
	'_check_email' => 'Địa chỉ Thư điện tử này đã được sử dụng'
)
);