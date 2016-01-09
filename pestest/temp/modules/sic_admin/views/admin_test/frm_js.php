<script type="text/javascript">
function save(value)
{
	var title = document.getElementById('txt_title').value;
	var description = document.getElementById('erea_description').value;	
	var num_question = document.getElementById('txt_question').value;	
	var time_value = document.getElementById('txt_time_value').value;	
	
	if( title.length < 3 )
	{
		alert('<?php echo Kohana::lang('test_frm_lang.val_title')?>');
		return false; 
	}
	
	if( num_question.length < 1 )
	{
		alert('<?php echo Kohana::lang('test_frm_lang.val_question')?>');
		return false; 
	}
	
	if( time_value.length < 1 )
	{
		alert('<?php echo Kohana::lang('test_frm_lang.val_time_value')?>');
		return false; 
	}
	if(value=='add') $('input#hd_save_add').val(1);
	document.frm.submit();
}

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}
function checkSelect(){
		 if($('#sel_type_time').val() == '1' && ($('#txt_time_value').val() == '' || $('#txt_time_value').val() == '0')){
			$('#txt_time_value').focus();
			alert('Please input again. If "Type time" is Down input value > 0');
		 }
}
</script>