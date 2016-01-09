<script type="text/javascript">
document.getElementById('txt_email').focus();
$().ready(function() {
	$('#float_table').floatBanner();
});
function save(value)
{
	var email = document.getElementById('txt_email').value;
	var pass = document.getElementById('txt_pass').value;	
	var flag_pass = <?php echo isset($mr['uid'])?$mr['uid']:0?>;
	if(email=="")
	{
		alert('<?php echo ('Email field required')?>');
		document.getElementById('txt_email').focus();
		return false; 
	}  else if(pass=="" && flag_pass==0)
	{
		alert('<?php echo Kohana::lang('register_validation.txt_pass.required')?>');
		document.getElementById('txt_pass').focus();
		return false;
	} else if(pass!="" && pass.length<6)
	{
		alert('<?php echo Kohana::lang('register_validation.txt_pass.length')?>');
		document.getElementById('txt_pass').focus();
		return false;
	} 
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	$.getJSON("<?php echo $this->site['base_url']?>admin_json_user/check_user_exists/"+ $('input#txt_username').val() + "/" + $('input#hd_id').val(),
	function(obj) {
		if(obj.status) {
			check_email_exists(value);
		} else {
			$.msgbox(obj.msg, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]}, 
			function(re) { if(re == 'Yes')	$('input#txt_username').focus(); }
			);
		}
		$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
	}
	);
}
function check_email_exists(value){
	$.getJSON("<?php echo $this->site['base_url']?>admin_json_user/check_emailmember_exists/"+ $('input#txt_email').val() + "/" + $('input#hd_id').val(),
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