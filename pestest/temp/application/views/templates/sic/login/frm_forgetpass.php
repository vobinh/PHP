<link href="<?php echo url::base()?>plugins/js/ui/ui.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url::base()?>plugins/js/ui/jquery.validate.js"></script>
<form name="frm" id="frm" action="<?php echo url::base()?>forgotpass/save" method="post">
<table name="forgotpass" width="100%" align="center" cellspacing="0" cellpadding="0" border="0" class="forgotpass">
<tr>
    <td class="forgotpass_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;"><?php echo Kohana::lang('login_lang.lbl_forgotpass') ?>&nbsp;</td>
</tr>
<tr>
	<td class="forgotpass_middle">
    	<table name="forgotpass_middle_Co" cellpadding="5" cellspacing="0">
        <tr>
        <td colspan="2" style="padding-left:11%">Not a problem! Just enter your email address and we'll send a new password.</td>
        </tr>
        <tr>
        	<td align="right" width="15%">Email<font color="#FF0000">*</font></td>
        	<td align="left">
            <input name="txt_email" onkeypress="return  keyPhone(event)" type="text" id="txt_email" class="text" size="30"/></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td align="left">
            	<button type="submit" name="btn_submit" class="btn_1"/><?php echo Kohana::lang('login_lang.btn_get_new_pass')?></button>
            	<button type="button" name="btn_back" class="btn_1" onclick="javascript:location.href='<?php echo url::base()?>'"/>
                	<?php echo Kohana::lang('client_lang.btn_back')?>
                </button>
            </td>
        </tr>
        </table>
	</td>
</tr>
<tr><td class="forgotpass_bottom">&nbsp;</td></tr>
</table>
</form>
<script type="text/javascript">
$('input#txt_email').focus();
$(document).ready(function() {
	$('#frm').validate({
		rules: {
			txt_email: {
		  		required: true,
		  		email: true,
				remote: {
					url: "<?php echo url::base()?>forgotpass/check_email",
					type: "post"
				}
			},
	    },
	    messages: {
			txt_email: { 
				required: "required",
				email: "invalid",
				remote: "This email does not exist or is not registered. Please try again."
	    	},
		}
	});
});
</script>
