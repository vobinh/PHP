<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="article panel panel-default box box_shadow">
            	<h4 style="font-weight: bold;"><?php echo Kohana::lang('login_lang.lbl_forgotpass') ?></h4>
				<hr class="hr">
            	<div>
						<p>Your new password has been sent to your e-mail successfully.  If you do not receive the e-mail within the next 30 minutes, please check your junk-email  folder just in case the email was routed to your spam folder. If you still do  not receive your new password, please contact us using the contact form on our  site.</p>
						<p>
						Thank you.<br />
						<?php echo $this->site['site_name']?>  
						</p>
						<br />
						<div align="center">
						  <button type="button" name="btn_back" class="btn btn-success" onclick="location.href='<?php echo $this->site['base_url']?>'"><span><?php echo Kohana::lang('login_lang.btn_homepage') ?></span></button>
						</div>
            	</div>
            </div>
        </div>
    </div>
</div>
