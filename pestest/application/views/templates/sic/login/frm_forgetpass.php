<div class="row">
	<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 box box_shadow">
		<h4 style="font-weight: bold;"><?php echo Kohana::lang('login_lang.lbl_forgotpass') ?></h4>
		<hr class="hr">
		<div>
			<form name="frm" id="frm" action="<?php echo url::base()?>forgotpass/save" method="post">
				<div class="form-group text-center">
					<label for="txt_email">Not a problem! Just enter your e-mail address and we will send you a link to reset your password.</label>
				</div>
				<div class="form-group">
			    	<label for="txt_email">Email address <span style="color: red;">*</span></label>
			    	<input type="email" class="form-control" tabindex="1" name="txt_email" id="txt_email" onkeypress="return  keyPhone(event)" placeholder="Your Email Address">
			  	</div>
			  	<div class="text-center">
			  		<button type="submit" name="btn_submit" class="btn btn-success" style="margin-top: 2px;">
			  			<?php echo 'Send'; ?>
			  		</button>
			  		
			  		<button type="button" name="btn_back" class="btn btn-default" style="margin-top: 2px;" onclick="javascript:location.href='<?php echo url::base()?>'"/>
                		<?php echo Kohana::lang('client_lang.btn_back');?>
                	</button>
			  	</div>
			</form>
		</div>
	</div>
</div>

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
