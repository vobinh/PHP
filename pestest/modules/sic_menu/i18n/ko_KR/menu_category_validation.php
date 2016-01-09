<?php
$model_lang = new Language_Model();
$list = $model_lang->get_with_active();
$lang = array();
for($i=0; $i<count($list); $i++)
{
	$lang['txt_name'.$list[$i]['languages_id']] = array
    (
        'required' => '['.$list[$i]['languages_name'].'] The Name field is required.',
        'alpha' => '['.$list[$i]['languages_name'].'] Only alphabetic characters are allowed.',
        'length' => '['.$list[$i]['languages_name'].'] The Name field must be between three and twenty letters.',
        'default' => '['.$list[$i]['languages_name'].'] Invalid Input.',
    );
}
?>