<?php 
	if(!empty($arr_generate)){
		foreach ($arr_generate as $key => $value) {?>
			<p><?php echo $value; ?> <input type="hidden" name="txt_code_item[]" value="<?php echo $value; ?>"></p>
	<?php }?>
<?php }?>