<script type="text/javascript">
document.getElementById('txt_username').focus();
$().ready(function() {
	$('#float_table').floatBanner();
});
function save(value)
{
	var cate = document.getElementById('txt_cate').value;
	var test = document.getElementById('sel_test').value;	
	var flag_pass = <?php echo isset($mr['uid'])?$mr['uid']:0?>;
	if(test=="")
	{
		alert('<?php echo ("Please choise test ")?>');
		document.getElementById('sel_test').focus();
		return false; 
	} 
	if(cate=="")
	{
		alert('<?php echo ("The Category field required")?>');
		document.getElementById('txt_cate').focus();
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