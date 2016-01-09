<script type="text/javascript">
function save(value)
{
	var title          = document.getElementById('txt_title').value;
	var provider_name  = document.getElementById('txt_provider_name').value;	
	var course_manager = document.getElementById('txt_course_manager').value;		
	
	if( title.length < 3 ){
		$.msgbox('<?php echo Kohana::lang("test_frm_lang.val_title")?>', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}

	if( provider_name.length < 1 ){
		$.msgbox('Certificate of Completion provider name empty!', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}

	if( course_manager.length < 1 ){
		$.msgbox('Course manager empty!', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
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