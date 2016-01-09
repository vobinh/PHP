<?php
$model_lang = new Language_Model();
$list = $model_lang->get_with_active();
$lang = array();
for($i=0; $i<count($list); $i++)
{
	$lang['txt_name'.$list[$i]['languages_id']] = array
    (
        'required' => '['.$list[$i]['languages_name'].'] Tên được yêu cầu.',
        'alpha' => '['.$list[$i]['languages_name'].'] Chỉ những ký tự Alphabe được chấp nhận.',
        'length' => '['.$list[$i]['languages_name'].'] Tên phải từ 3-20 chữ.',
        'default' => '['.$list[$i]['languages_name'].'] Giá trị nhập không hợp lệ.',
    );
}
?>