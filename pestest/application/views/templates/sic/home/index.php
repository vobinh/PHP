<link rel="stylesheet" href="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<!-- using recommended -->
<?php
  	require Kohana::find_file('views/aws','init');
  	$using_img = false;
	if(isset($using_cookie) && $using_cookie){ ?>
		<?php 
			// check sponsor_img
			if(!empty($id_sponsor_img)){
				$this->db->where('id',$id_sponsor_img);
				$m_sponsor_img = $this->db->get('sponsor_img')->result_array(false);
				if(!empty($m_sponsor_img[0]['name_img'])){
					$check_img = $s3Client->doesObjectExist($s3_bucket, "sponsor_img/".$m_sponsor_img[0]['name_img']);
		            if($check_img == '1'){
		            	$using_img = true;
		            }
				}
			}
		?>
<?php }?>
	<?php if($using_img) {?>
		<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1" style="margin-top: 45px;padding-top: 15px; overflow: hidden;">
			<?php if($using_img) {?>
			<div class="col-sm-5 div_img_recommended hidden-xs" style="margin-bottom: 15px;padding:0px;">
				<table style="height: 100%;width: 100%;">
					<tbody>
						<tr>
							<td>
								<img class="img-responsive box_shadow" style="background-color: rgb(255, 255, 255);" src="<?php echo linkS3 ?>sponsor_img/<?php echo $m_sponsor_img[0]['name_img'] ?>" alt="">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php }else{ ?>
				<div class="col-sm-5 box box_shadow div_img_recommended hidden-xs" style="margin-bottom: 15px;padding:0px;">
				</div>
			<?php }?>
			<div class="col-sm-7 div_login" style="margin-bottom: 15px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="130">
		            <tr>
		                <td class="header_logo text-center">
		                <?php
		                    if (!empty($this->site['site_logo'])) { 
		                        $check_img = $s3Client->doesObjectExist($s3_bucket, "site/".$this->site['site_logo']);
		                        if($check_img == '1'){
		                    ?>
		                        <a href="<?php echo url::base()?>">
		                            <img border="0" style="margin: auto;" class="img-responsive" src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>">
		                        </a>
		                <?php }} ?>
		                </td>
		            </tr>
		            <tr>
		                <td class="cls_logo_text text-center">
		                    <?php 
		                        echo !empty($this->site['site_sub_title'])?$this->site['site_sub_title']:'';
		                    ?>
		                </td>
		            </tr>
		        </table>
				<div class="col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 box box_shadow">
					<form  name="frm" id="frm" action="<?php echo $this->site['base_url']?>login/check_login" method="post"  novalidate="novalidate" style="margin-top:0">
					  	<div class="form-group">
					    	<label for="txt_email">Email address</label>
					    	<input type="email" class="form-control" tabindex="1" name="txt_email" id="txt_email" onkeypress="return  keyPhone(event)" placeholder="Your Email Address">
					  	</div>
					    <div class="form-group">
					    	<label for="txt_password">Password</label>
					    	<input class="form-control" tabindex="2"  id="txt_password" type="password" placeholder="Password" name="txt_password">
					    </div>
					  	<div class="form-group text-center">
					    	<button style="padding: 10px 30px;" tabindex="3" type="submit" id="btn_login" class="btn btn-success">Login</button><br>
					    	<a tabindex="4" href="<?php echo $this->site['base_url']?>forgotpass">Forgot password?</a>
					  	</div>
					</form>
				</div>
				<div class="col-sm-12 text-center" style="padding:0px;padding-top: 10px;padding-bottom: 5px;">
					<button style="padding: 10px 30px;" tabindex="3" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success">See what's on PestSchool</button>
				</div>
			</div>
		</div>
	<?php }else{ ?>
		<div class="row">
		<?php if(isset($using_cookie) && $using_cookie){ ?>
			<div class="col-sm-12">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="130">
		            <tr>
		                <td class="header_logo text-center" style="padding-top: 50px;">
		                <?php
		                    if (!empty($this->site['site_logo'])) { 
		                        $check_img = $s3Client->doesObjectExist($s3_bucket, "site/".$this->site['site_logo']);
		                        if($check_img == '1'){
		                    ?>
		                        <a href="<?php echo url::base()?>">
		                            <img border="0" style="margin: auto;" class="img-responsive" src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>">
		                        </a>
		                <?php }} ?>
		                </td>
		            </tr>
		            <tr>
		                <td class="cls_logo_text text-center">
		                    <?php 
		                        echo !empty($this->site['site_sub_title'])?$this->site['site_sub_title']:'';
		                    ?>
		                </td>
		            </tr>
		        </table>
			</div>
		<?php }?>
			<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 box box_shadow">
				<!-- <h4 style="font-weight: bold;">Login Form</h4> -->
				<!-- <hr class="hr"> -->
				<form  name="frm" id="frm" action="<?php echo $this->site['base_url']?>login/check_login" method="post"  novalidate="novalidate" style="margin-top:0">
				  	<div class="form-group">
				    	<label for="txt_email">Email address</label>
				    	<input type="email" class="form-control" tabindex="1" name="txt_email" id="txt_email" onkeypress="return  keyPhone(event)" placeholder="Your Email Address">
				  	</div>
				    <div class="form-group">
				    	<label for="txt_password">Password</label>
				    	<input class="form-control" tabindex="2"  id="txt_password" type="password" placeholder="Password" name="txt_password">
				    </div>
				  	<div class="form-group text-center">
				    	<button style="padding: 10px 30px;" tabindex="3" type="submit" id="btn_login" class="btn btn-success">Login</button><br>
				    	<a tabindex="4" href="<?php echo $this->site['base_url']?>forgotpass">Forgot password?</a>
				  	</div>
				</form>
			</div>
			<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 text-center" style="padding-top: 10px;padding-bottom: 5px;">
				<button style="padding: 10px 30px;" tabindex="3" type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success">See what's on PestSchool</button>
			</div>
		</div>
 <?php }?>

<?php /*?>
<!-- modal ajax goi o footer user -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4 class="modal-title">Modal header</h4>
			</div>
			<div class="modal-body">
				<?php 
					echo !empty($this->site['site_about'])?$this->site['site_about']:'';
				?>
			</div>
		</div>
	</div>
</div>
<?php */?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="/*width: 800px;*/">
    <div class="modal-content">
      <div class="modal-body" style="max-height: 500px;overflow: auto;">
        <?php 
			echo !empty($this->site['site_about'])?$this->site['site_about']:'';
		?>
      </div>
    </div>
  </div>
</div>
<input style="display:none;" type="button" name="btn" value="Submit" id="submitBtn" data-toggle="modal" data-target="#confirm-submit" class="btn btn-default" />
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form style="display:none;" id="frm_multi_login" name="frm_multi_login" action="<?php echo url::base()?>login/multi_login" method="post" accept-charset="utf-8">
    	<input type="hidden" name="txt_id_user_login" id="txt_id_user_login" value="">
    </form>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Login.
            </div>
            <div class="modal-body">
                The user account is already logged in. Close the other session and continue?
            </div>

  		<div class="modal-footer">
            <a href="javascript:void(0)" id="submit" class="btn btn-success success">Yes</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
    </div>
</div>
<script src="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.div_img_recommended').height($('.div_login').height());
		$( window ).resize(function() {
			//if($(window).width() < 760){
			//	$('.div_img_recommended').height('auto');
			//	$('.div_img_recommended table').height('auto');
			//}else{
			$('.div_img_recommended').height($('.div_login').height());
			$('.div_img_recommended table').height('100%');
			//}
		});
		$('#submit').click(function(){
		    $('#frm_multi_login').submit();
		});


		$('#frm').submit(function(e) {
			/* Act on the event */
			$.ajax({
				url: '<?php echo url::base()?>login/check_login_js',
				type: 'POST',
				dataType: 'json',
				data: $("#frm").serialize(),
			})
			.done(function(data) {
				if(data['type'] == 'error'){
					$.growl.error({ message: data['message'] });
				}else if(data['type'] == 'login'){
					window.location.href = data['message'];
				}else if(data['type'] == 'multi_login'){
					$('#txt_id_user_login').val(data['message']);
					$('#submitBtn').click();
				}
			});
			e.preventDefault();
		});

		$('#frm').validate({
			rules: {
				txt_password: {
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
				txt_email:{
					required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
					email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
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
	});
</script>

<script>
	(function($){
		$(window).load(function(){
			$("#myModal .modal-body").mCustomScrollbar({
				setHeight:500,
				theme:"minimal-dark"
			});
		});
	})(jQuery);
</script>