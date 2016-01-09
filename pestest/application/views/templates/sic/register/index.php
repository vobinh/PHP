<script>
function showchoise(){
	$('.choisespone').show('slow');
}
function hidechoise(){
	$('.choisespone').hide('slow');
}
</script>
<style type="text/css" media="screen">
	.input_frm{
		display: inline;
    	width: 96%;
	}
</style>
<div class="row">
	<div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 box box_shadow">
		<h4 style="font-weight: bold;">Registration Form</h4>
		<hr class="hr">
		<div>
			<form class="form-horizontal" method="post" action="<?php echo url::base()?>register/submit"  name="frmdialog" id="frmdialog" novalidate="novalidate" >
				<div class="form-group">
			    	<label for="txt_email" class="col-sm-4 control-label"><?php echo Kohana::lang('register_lang.lbl_email')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" autocomplete="off" name="txt_email" tabindex="1" type="text" onkeypress="return  keyPhone(event); " id="txt_email_dialog" value="<?php echo isset($mr['cus_email'])?$mr['cus_email']:(isset($indata['txt_email'])?$indata['txt_email']:'')?>"> 
	              		<font color="#FF0000">*</font>
				    </div>
			  </div>

			  <div class="form-group">
			    	<label for="txt_password" class="col-sm-4 control-label"><?php echo Kohana::lang('register_lang.lbl_pass')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="2" name="txt_password" type="password" id="txt_password_dialog" placeholder="" > 
	              		<font color="#FF0000">*</font><br>
	              		<span style="font-style:italic;font-size:10px;" >6-character minimum, case sensitive</span>
				    </div>
			  </div>

			  <div class="form-group">
			    	<label for="txt_cfpass" class="col-sm-4 control-label"><?php echo Kohana::lang('register_lang.lbl_cfpass')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="3" name="txt_cfpass" type="password" id="txt_cfpass_dialog"> 
	              		<font color="#FF0000">*</font>
				    </div>
				    <input type="hidden" value="<?php echo $mr['str_random']?>" id="hid_cfpass_dialog"/>
			  </div>

			  <div class="form-group">
			    	<label for="txt_fname" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_first_name')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="4" name="txt_fname" type="text" id="txt_fname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>">
				    	<font color="#FF0000">*</font>
				    </div>
			  </div>

			  <div class="form-group">
			    	<label for="txt_lname" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_last_name')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="5" name="txt_lname" type="text" id="txt_lname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_name'])?$indata['txt_name']:'')?>">
				    	<font color="#FF0000">*</font>
				    </div>
			  </div>

			  	<div class="form-group">
			    	<label for="txt_lname" class="col-sm-4 control-label">Company Name</label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="6" name="txt_cpname" type="text" id="txt_cpname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_cpname'])?$indata['txt_cpname']:'')?>" />
			  		</div>
			  	</div>
			  	<div class="form-group">
				    <div class="col-sm-8 col-sm-offset-4">
				      	<p style="margin-bottom: 0px;"><span ><font size="3px">Do you have a sponsor? </font>&nbsp;&nbsp;</span>
		                  	<input type="radio" name="choisespone" tabindex="7" id="choisespone" value="1" style="width:15px" onchange="showchoise()"/>
		                	<b>Yes</b>&nbsp;
		                  	<input type="radio" name="choisespone" tabindex="8" id="choisespone" value="0" style="width:15px" checked="checked" onchange="hidechoise()"/>
		                	<b>No</b>
		                  	<br />
		                    <font size="2px" color="#666666">
		                    	Designate a sponsor to have them receive e-mail updates of your progress, as well as receipts for your purchases on the site.
		                  	</font>
		                 </p>
			  		</div>
			  	</div>

			  	<div class="form-group choisespone" style="display:none">
			    	<label for="txt_spname" class="col-sm-4 control-label">Sponsor Name</label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="9" name="txt_spname" type="text" id="txt_spname_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_spname'])?$indata['txt_spname']:'')?>"/>
			  		</div>
			  	</div>

			  	<div class="form-group choisespone" style="display:none">
			    	<label for="txt_spemail" class="col-sm-4 control-label">Sponsor E-mail</label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="10" name="txt_spemail" type="text" id="txt_spemail_dialog" value="<?php echo isset($mr['cus_name'])?$mr['cus_name']:(isset($indata['txt_spemail'])?$indata['txt_spemail']:'')?>"/>
			  		</div>
			  	</div>

			  	<div class="form-group">
			    	<label for="str_random" class="col-sm-4 control-label"><?php echo Kohana::lang('register_lang.lbl_security')?></label>
				    <div class="col-sm-8">
				      	<div style="border:#ccc 2px solid; padding:5px 10px; float:left; background:#f1f0ed">
                			<strong><?php echo $mr['str_random']?></strong>        
                		</div>
			  		</div>
			  	</div>

			  	<div class="form-group">
			    	<label for="txt_random" class="col-sm-4 control-label"><?php echo Kohana::lang('register_lang.lbl_retype_security')?></label>
				    <div class="col-sm-8">
				      	<input class="form-control input_frm" tabindex="11" name="txt_random" type="text" id="txt_random_dialog" size="20" maxlength="20"/>
			  		</div>
			  	</div>

			  	<div class="form-group">
				    <div class="col-sm-8 col-sm-offset-4">
				      	<input type="checkbox" name="chk_sendmail" id="chk_sendmail" tabindex="12" style="width:16px;vertical-align: middle; margin: auto;height: 16px;" />
              			<span>Disable e-mail notifications.</span>
			  		</div>
			  	</div>
			  	<div class="text-center">
			  		<button style="padding: 10px 30px;" type="submit" name="Submit" tabindex="13" class="btn btn-success" tabindex="8"><?php echo Kohana::lang('register_lang.btn_register')?></button>
			  	</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
  $('#frmdialog').validate({
 		rules: {
			txt_email:{
				required: true,
				email: true	,
				remote: {
					url: '<?php echo url::base()?>home/checkEmailVal/',
       				type: 'post',
				}
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
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>",
				remote: "Email is already registered!"
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
				corners = ['top left', 'bottom left'],
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