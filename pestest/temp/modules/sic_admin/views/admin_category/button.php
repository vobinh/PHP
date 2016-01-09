<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base() ?>admin_category'">
<span><?php echo Kohana::lang('global_lang.btn_back_list') ?></span>
</button>
<?php if ($this->sess_admin['level'] == 1) { ?>
<button type="button" name="btn_save" class="button save" onclick="
if($('#noticepercent').text() != 'Good value'){
	$.msgbox(' Error !<br/>'+ $('#noticepercent').text() +'<br/>Please input values again.?', {
		type : 'confirm',
		buttons : [
			{type: 'submit', value:'Ok'}]
		}, 
		function(re) {
        	if(re == 'Ok') 
            	  return false;
        }
    )
       return false;
};
javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>
<!--<button type="button" name="btn_save_add" class="button save" onclick="checkError();">
<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
</button>//-->
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
<?php } // end if level super admin ?>
<script>
function  checkError(){
	if($('#noticepercent').text() != 'Good value'){
	$.msgbox(' Error !<br/>'+ $('#noticepercent').text() +'<br/>Please input values again.?', {
		type : 'confirm',
		buttons : [
			{type: 'submit', value:'Ok'}]
		}, 
		function(re) {
        	if(re == 'Ok') 
            	  return false;
        }
    )
       return false;
};
javascript:save('add');
}
</script>