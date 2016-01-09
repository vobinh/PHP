<div class="row">
	<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 box box_shadow">
		<h4 style="font-weight: bold;">New password</h4>
		<hr class="hr">
		<div>
			<form name="frm" id="frm_save_new" action="<?php echo url::base()?>forgotpass/save_new" method="post">
				<div class="form-group text-center">
					<label for="txt_email">Please enter your new password:</label>
				</div>
				<div class="form-group">
			    	<label for="txt_email"><?php echo !empty($email)?$email:''; ?></label>
			    	<input type="password" tabindex="1" class="form-control" name="txt_new_pass" id="txt_new_pass" value="" placeholder="New password">
			  		<input type="hidden" name="txt_email" value="<?php echo !empty($email)?$email:''; ?>">
			  	</div>
			  	<div class="text-center">
			  		<button type="button" name="btn_submit" onclick="fn_new_save()" class="btn btn-success" style="margin-top: 2px;">
			  			<?php echo 'Confirm'; ?>
			  		</button>
			  		
			  		<button type="button" name="btn_back" class="btn btn-default" style="margin-top: 2px;" onclick="javascript:location.href='<?php echo url::base()?>'"/>
                		<?php echo 'Cancel';?>
                	</button>
			  	</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	function fn_new_save(){
		if($('#txt_new_pass').val().length < 6){
			$.growl.error({ message: "Password must be at least 6 characters" });
			return false;
		}
		$('#frm_save_new').submit();
	}
</script>