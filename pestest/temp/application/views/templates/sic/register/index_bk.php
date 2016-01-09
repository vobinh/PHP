<!--<script type="text/javascript" src="<?php echo url::base()?>js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery.validate/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.js"></script>-->
<link href="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="padding:50px;">    
    	<div class="frm_login" style="max-width:440px">
            <form class="sky-form" method="post" action="<?php echo url::base()?>register/submit"  name="frm" id="frm" > 
            <header style="margin-bottom:10px;">Registration form</header>         
            <table class="register" align="center" border="0" cellspacing="0" cellpadding="8" width="100%">
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_email')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input name="txt_email" tabindex="1" type="text" id="txt_email" value="<?php echo isset($mr['cus_email'])?$mr['cus_email']:(isset($indata['txt_email'])?$indata['txt_email']:'')?>" style="width:240px;"> 
                <font color="#FF0000">*</font></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_pass')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="2" name="txt_password" type="password" id="txt_password" style="width:240px;"> 
                <font color="#FF0000">*</font></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_cfpass')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="3" name="txt_cfpass" type="password" id="txt_cfpass" style="width:240px;"> 
                <font color="#FF0000">*</font></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('myaccount_lang.lbl_first_name')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="4" name="txt_fname" type="text" id="txt_fname" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>" style="width:240px;"> 
                </td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('myaccount_lang.lbl_last_name')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="5" name="txt_lname" type="text" id="txt_lname" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>" style="width:240px;"> 
                </td>
            </tr>
            
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_security')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left">
                <div style="border:#ccc 2px solid; padding:5px 10px; margin-bottom:10px; float:left; background:#f1f0ed">
                <strong><?php echo $mr['str_random']?></strong>
                </div>
                </td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_retype_security')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="6" name="txt_random" type="text" id="txt_random" size="20" maxlength="20" style="width:240px;" /> 
                <font color="#FF0000">*</font></td>
            </tr>
            </table>
            <footer style="padding-right:100px;">
            	
                <button type="reset" name="Submit2" class="button" tabindex="7"><span><?php echo Kohana::lang('client_lang.btn_reset')?></span></button>
                <button type="submit" name="Submit"  class="button" tabindex="8"><span><?php echo Kohana::lang('register_lang.btn_register')?></span></button>
            </footer>
            </form> 
          </div>
      </td>
   </tr>
</table>
<script type="text/javascript">
document.getElementById('txt_email').focus();

$(document).ready(function() {
	$('#frm').validate({
		rules: {
			txt_password: {
				required: true
			},
			txt_cfpass: {
				required: true
			},
			txt_random: {
				required: true
			},
			txt_email:{
				required: true,
				email: true	
			},
			
	    },
	    messages: {
	    	txt_password: {
	        	required: "Password is required"
			},
			txt_cfpass: {
	        	required: "Confirm Password is required"
			},
			txt_random: {
	        	required: "Security Code is required"
			},
			
			txt_email:{
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
			},
			
		},
		
		errorPlacement: function(error, element)
		{
			var elem = $(element),
				corners = ['right center', 'left center'],
				flipIt = elem.parents('span.right').length > 0;

			if(!error.is(':empty')) {
				elem.filter(':not(.valid)').qtip({
					overwrite: false,
					content: error,
					position: {
						my: corners[ flipIt ? 0 : 1 ],
						at: corners[ flipIt ? 1 : 0 ],
						viewport: $(window)
					},
					show: {
						event: false,
						ready: true
					},
					hide: false,
					style: {
						classes: 'ui-tooltip-plain'
					}
				})
				.qtip('option', 'content.text', error);
			}

			// If the error is empty, remove the qTip
			else { elem.qtip('destroy'); }
		},
		success: $.noop
	});
});
</script>










