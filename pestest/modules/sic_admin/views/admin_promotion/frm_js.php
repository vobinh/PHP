<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
$('#btn_generate').click(function(event) {
	/* Act on the event */
	var number = $('#txt_count_code').val();
	if($.isNumeric(number) == false || number <= 0){
		$.msgbox('Number is integer, value positive', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		$('#txt_qty').val('');
		return false;
	}
	$('.loading_img').show();
	$.ajax({
		url: '<?php echo url::base()?>admin_promotion/generate_code',
		type: 'POST',
		dataType: 'html',
		data: {txt_sl: number},
	})
	.done(function(data) {
		$('#div_code').html(data);
		$('.loading_img').hide();
		$('#txt_qty').val(number);
	})
	.fail(function() {
		$('.loading_img').hide();
		$.msgbox('Error, try again.', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		$('#txt_qty').val('');
		return false;
	});
	
});
function save(type){
	var m_type = 1;
	<?php if(!empty($m_type) && $m_type =='multiple'){?>
		m_type = 2;
	<?php }?>
	
	var m_action = 'add';
	<?php if(isset($pro) && !empty($pro)){?>
		m_action = 'edit';
	<?php }?>

	document.getElementById('hd_save_add').value = type;
	var company = document.getElementById('txt_company').value;
	var mail = document.getElementById('txt_email').value;
	
	if($('#sel_test').val() == 0){
		$.msgbox('Please selected courses.', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false;
	}	
	if( company.length < 1 || company.length > 255)
	{
		$.msgbox('Company name have to 1 to 255 charter', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}
	if( mail.length < 3 || mail.length > 255 )
	{
		$.msgbox('Email have to 3 to 255 charter', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}
	if(m_type == 1){
		var code = document.getElementById('txt_code').value;
		if(code.length != 12 ){
			$.msgbox('Code have to 12 charter', {
				type : 'error',
				buttons : [
					{type: 'submit', value:'Cancel'},
				]
			});
			return false; 
		}
	}else{
		if(m_action == 'add'){
			if($( "input[name='txt_code_item[]']" ).length <= 0){
				$.msgbox('Please generate code.', {
					type : 'error',
					buttons : [
						{type: 'submit', value:'Cancel'},
					]
				});
				return false;
			}
		}
		
	}
	document.frm.submit();
}
</script>