<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
function save(type)
{
	
	document.getElementById('hd_save_add').value = type;
	var company = document.getElementById('txt_company').value;
	var mail = document.getElementById('txt_email').value;
	var code = document.getElementById('txt_code').value;
		
	if( company.length < 1 || company.length > 255)
	{
		alert('<?php echo "Company name have to 1 to 255 charter"?>');
		return false; 
	}
	if( mail.length < 3 || mail.length > 255 )
	{
		alert('<?php echo "Email have to 3 to 255 charter"?>');
		return false; 
	}
	if(code.length != 12 )
	{
		alert('<?php echo "Code have to 12 charter"?>');
		return false; 
	}
	
	document.frm.submit();
}


</script>