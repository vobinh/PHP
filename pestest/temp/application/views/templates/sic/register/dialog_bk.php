<script>
function showchoise(){
	$('.choisespone').show('slow');
}
function hidechoise(){
	$('.choisespone').hide('slow');
}

</script>
<link href="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="padding:0px 0;">    
    	<div class="frm_login" style="width:100%">
            <form class="sky-form" method="post" action="<?php echo url::base()?>register/submit"  name="frmdialog" id="frmdialog" novalidate="novalidate" > 
            <header style="margin-bottom:10px;">Registration form</header>         
            <table class="register" align="center" border="0" cellspacing="0" cellpadding="8" width="100%">
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_email')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input name="txt_email" tabindex="3" type="text" id="txt_email_dialog" value="<?php echo isset($mr['cus_email'])?$mr['cus_email']:(isset($indata['txt_email'])?$indata['txt_email']:'')?>" style="width:240px;height:30px;"> 
                <font color="#FF0000">*</font></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:0px;" align="right" ><?php echo Kohana::lang('register_lang.lbl_pass')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left" >
                <input tabindex="4" name="txt_password" type="password" id="txt_password_dialog" style="width:240px;height:30px;" placeholder="Password" >
                <font color="#FF0000">*</font>                </td>
            </tr>
             <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right" valign="top"></td>
                <td class="pad10" valign="top" align="left" style="padding-bottom:10px;font-style:italic;font-size:10px;" >6-character minimum, case sensitive</td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_cfpass')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="5" name="txt_cfpass" type="password" id="txt_cfpass_dialog" style="width:240px;height:30px;"> 
                <input type="hidden" value="<?php echo $mr['str_random']?>" id="hid_cfpass_dialog"/>
                <font color="#FF0000">*</font></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('myaccount_lang.lbl_first_name')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="6" name="txt_fname" type="text" id="txt_fname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>" style="width:240px;height:30px;"> <font color="#FF0000">*</font>                </td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('myaccount_lang.lbl_last_name')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="7" name="txt_lname" type="text" id="txt_lname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>" style="width:240px;height:30px;"> <font color="#FF0000">*</font>                </td>
            </tr>
            
            <tr>
              <td class="padbot10" style="padding-bottom:10px;" align="right"><span class="padbot10" style="padding-bottom:10px;">Company Name&nbsp;&nbsp;</span></td>
              <td class="pad10" align="left"><input tabindex="7" name="txt_cpname" type="text" id="txt_cpname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_cpname'])?$indata['txt_cpname']:'')?>" style="width:240px;height:30px;" /></td>
            </tr>
            <tr>
              <td class="padbot10" style="padding-bottom:10px;" align="right">&nbsp;</td>
              <td class="pad10" align="left"><p><span ><font size="3px">Do you have a sponsor? </font>&nbsp;&nbsp;</span>
                  <input type="radio" name="choisespone" id="choisespone" value="1" style="width:15px" onchange="showchoise()"/>
                <b>Yes</b>&nbsp;
                  <input type="radio" name="choisespone" id="choisespone" value="0" style="width:15px" checked="checked" onchange="hidechoise()"/>
                <b>No</b>
                  <br />
                    <font size="2px" color="#666666">
                  A sponsor is someone who is covering the expenses<br /> 
                  for your purchases on PesTesT. (e.g. Your employer) <br />
                  If you do not have one, please select "No." Your sponsor <br />
                  will receive e-mail notifications of your progress.<br /><br /> </font></p>
              </td>

            </tr>
            <tr class="choisespone" style="display:none">
              <td class="padbot10" style="padding-bottom:10px;" align="right">Sponsor Name&nbsp;&nbsp;</td>
              <td class="pad10" align="left"><input tabindex="7" name="txt_spname" type="text" id="txt_spname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_spname'])?$indata['txt_spname']:'')?>" style="width:240px;height:30px;" /></td>
            </tr>
            <tr class="choisespone" style="display:none">
              <td class="padbot10" style="padding-bottom:10px;" align="right">Sponsor E-mail&nbsp;&nbsp;</td>
              <td class="pad10" align="left"><input tabindex="7" name="txt_spemail" type="text" id="txt_spemail_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_spemail'])?$indata['txt_spemail']:'')?>" style="width:240px;height:30px;" /></td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_security')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left">
                <div style="border:#ccc 2px solid; padding:5px 10px; margin-bottom:10px; float:left; background:#f1f0ed">
                <strong><?php echo $mr['str_random']?></strong>                </div>                </td>
            </tr>
            <tr>
                <td class="padbot10" style="padding-bottom:10px;" align="right"><?php echo Kohana::lang('register_lang.lbl_retype_security')?>&nbsp;&nbsp;</td>
                <td class="pad10" align="left"><input tabindex="7" name="txt_random" type="text" id="txt_random_dialog" size="20" maxlength="20" style="width:240px;height:30px;" /> 
                 <font color="#FF0000">*</font></td>
            </tr>
            </table>
<footer style="padding-right:200px;">
            	
                <button type="reset" name="Submit2" class="button" tabindex="7"><span><?php echo Kohana::lang('client_lang.btn_reset')?></span></button>
                <button type="submit" name="Submit" onclick="checkInput();"  class="button" tabindex="8"><span><?php echo Kohana::lang('register_lang.btn_register')?></span></button>
            </footer>
            </form> 
          </div>
      </td>
   </tr>
</table>
<script type="text/javascript">
	function checkInput(){
		$.ajax({
				url: '<?php echo url::base()?>home/checkEmail/'+$('#txt_email_dialog').val(),
				type: "post",
				
				success: function (data) {
				if(data == 0){
					$('#frmdialog').submit();	
				}
				else
					$('#notice').html('Email had registered !.');
							
			}
		});
		 
	} 
</script>
<script type="text/javascript">
$(document).ready(function() {
//txt_password= $('#txt_password_dialog').val();
  $('#frmdialog').validate({
 		rules: {
			txt_email:{
				required: true,
				email: true	
			},
			txt_password: {
				required: true,
				minlength: 6,
				maxlength:25,
			},
			txt_fname: {
				required: true
				
			},
			txt_lname: {
				required: true
			},
			txt_cfpass: {
				equalTo: "#txt_password_dialog"
			},
			txt_spemail: {
				email: true	
			},
			txt_random: {
				equalTo: "#hid_cfpass_dialog"
			},
			
			
	    },
	    messages: {
	    	
			txt_email:{
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
			},
			txt_password: {
				required: "Password is required",
				minlength: "6-character minimum, case sensitive",
				maxlength: "Please input less 25 Characters"
			},
			txt_fname: {
				required: "First Name is required"
				
			},
			txt_lname: {
				required: "Last Name is required"
			},
			txt_cfpass: {
				equalTo: "These passwords don't match."
			},
			txt_spemail:{
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
			},
			txt_random: {
				equalTo: "The input didn't match the Security Code. Please try again."
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
	$(".ui-icon-closethick").on("click", function() {
		   $('#frmdialog').removeData('qtip');
		   $('.qtip :visible').remove();
		   $(".ui-tooltip").qtip('destroy')
   });
});
</script>









