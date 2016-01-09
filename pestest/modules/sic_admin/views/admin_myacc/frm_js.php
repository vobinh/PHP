<script type="text/javascript">
document.getElementById('txt_old_pass').focus();
function save(value)
{
	var old_pass = document.getElementById('txt_old_pass').value;
	var new_pass = document.getElementById('txt_new_pass').value;
	var cf_new_pass = document.getElementById('txt_cf_new_pass').value;
	if(old_pass!="" && old_pass.length<6)
	{
		alert('<?php echo Kohana::lang('account_validation.txt_pass.length')?>');
		document.getElementById('txt_old_pass').focus();
		return false;
	} else if(new_pass!="" && new_pass.length<6)
	{
		alert('<?php echo Kohana::lang('account_validation.txt_pass.length')?>');
		document.getElementById('txt_new_pass').focus();
		return false;
	} else if(cf_new_pass!="" && cf_new_pass.length<6)
	{
		alert('<?php echo Kohana::lang('account_validation.txt_pass.length')?>');
		document.getElementById('txt_cf_new_pass').focus();
		return false;
	} else if(old_pass!="" && (new_pass=="" || cf_new_pass==""))
	{
		alert('<?php echo Kohana::lang('account_validation.txt_new_pass.required')?>');
		if(new_pass=="")
		document.getElementById('txt_new_pass').focus();
		else if(cf_new_pass=="")
		document.getElementById('txt_cf_new_pass').focus();
		return false;
	} else if(new_pass!="" && cf_new_pass!="" && new_pass!=cf_new_pass)
	{
		alert('<?php echo Kohana::lang('account_validation.txt_cf_new_pass.matches')?>');
		document.getElementById('txt_new_pass').focus();
		return false;
	} else if(document.getElementById('txt_email').value=="")
	{
		alert('<?php echo Kohana::lang('account_validation.txt_email.required')?>');
		document.getElementById('txt_email').focus();
		return false;
	} else if(document.getElementById('txt_email').value!="")
	{
		var email = document.getElementById('txt_email').value;
		if(!valid_email(email)) 
		{
			alert('<?php echo Kohana::lang('account_validation.txt_email.email')?>');
			document.getElementById('txt_email').focus();
			document.getElementById('txt_email').value = "";
			return false;
		}
	}
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	$.getJSON("<?php echo $this->site['base_url']?>admin_json_user/check_oldpass/"+ $('input#hd_id').val() + "/" + $('input#txt_old_pass').val(),
	function(obj) {
		if(obj.status) {
			check_email_exists(value);
		} else {
			$.msgbox(obj.msg, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]}, 
			function(re) { if(re == 'Yes')	$('input#txt_oldpass').focus(); }
			);
		}
		$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
	}
	);
}
function check_email_exists(value){
	$.getJSON("<?php echo $this->site['base_url']?>admin_json_user/check_email_exists/"+ $('input#txt_email').val() + "/" + $('input#hd_id').val(),
	function(obj) {
		if(obj.status) {
			if(value=='add') $('input#hd_save_add').val(1);
			document.frm.submit();
		} else {
			$.msgbox(obj.msg, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]}, 
			function(re) { if(re == 'Yes')	$('input#txt_email').focus(); }
			);
		}				
	}
	);
}
</script>