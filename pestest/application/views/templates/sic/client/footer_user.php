    	<div class="user_footer">
    		<?php echo !empty($user_footer)?$user_footer:''; ?>
    	</div>

    	<!-- auto logout -->
    	<input style="display:none;" type="button" name="btn" value="Submit" id="btn_auto_logout" data-toggle="modal" data-target="#confirm-auto-logout" class="btn btn-default" />
		<div class="modal fade" id="confirm-auto-logout" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog">
		        <div class="modal-content">
		            <div class="modal-header">
		                Confirm Logout.
		            </div>
		            <div class="modal-body">
		                You were logged out because either your session was ended, or your account was logged in from another location. Please refresh to log in again.
		            </div>

		  		<div class="modal-footer">
		            <a href="<?php echo url::base()?>" class="btn btn-success success">Refresh</a>
		        </div>
		    </div>
		</div>

    		<!-- jQuery -->
		<script src="<?php echo url::base()?>themes/client/styleSIC/bootstrap/js/jquery.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="<?php echo url::base()?>themes/client/styleSIC/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			var $jQuery1_10 = $.noConflict(true);
		</script>
		<script type="text/javascript">
		 	$(function() {
		 		if($( "#tabs" ).length > 0){
		 			$( "#tabs" ).tabs();	
		 		}
		   		
		  	});
		  	
			$(function () {
			  $jQuery1_10('[data-toggle="popover"]').popover()
			  $jQuery1_10('[data-toggle="tooltip"]').tooltip()
			});
			
			$(document).ready(function() {
				/*
				* Set user active (120000 2p)
				*/
				var fn_set_user_active = setInterval(function(){ set_user_active() }, 120000);//2p
				function set_user_active(){
					$.ajax({
						url: '<?php echo url::base()?>login/set_user_active',
						type: 'GET',
						dataType: 'json',
					})
					.done(function(data) {
						if(data == 'stop'){
							 clearInterval(fn_set_user_active);
						}
					});
					
				}

				/*
				* set logout auto
				*/
				<?php if($this->sess_cus !== false){?>
					var fn_auto_logout = setInterval(function(){ auto_logout() }, 15000);//15s
					function auto_logout(){
						$.ajax({
							url: '<?php echo url::base()?>login/auto_logout',
							type: 'GET',
							dataType: 'json',
						})
						.done(function(data) {
							if(data == 'logout'){
								 clearInterval(fn_set_user_active);
								 clearInterval(fn_auto_logout);
								 $('#btn_auto_logout').click();
							}
						});
					}
				<?php }?>

				<?php 
					// notification error
		            if (isset($error_msg)){ ?>
		              $.growl.error({ message: "<?php echo $error_msg ?>" });
		            <?php }
		            // notification warning_msg
		            if (isset($warning_msg)){ ?>
		              $.growl.warning({ message: "<?php echo $warning_msg ?>" });
		            <?php }
		            if (isset($info_msg)){ ?>
		              $.growl.notice({ message: "<?php echo $info_msg ?>" });
		            <?php }
		            if (isset($success_msg)){ ?>
		              $.growl.notice({ message: "<?php echo $success_msg ?>" });
		            <?php }
		        ?>

		        // selected menu
				var main_url = '<?php echo $this->uri->segment(1)?>';
				var child_url = '<?php echo $this->uri->segment(2)?>';
				if(main_url == 'courses'){
					if(child_url == '' || child_url == 'mytest' || child_url == 'index'){
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_1').addClass('active');
					}else if(child_url == 'showlist'){
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_3').addClass('active');
					}else if(child_url == 'certificate'){
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_6').addClass('active');
					}else{
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_1').addClass('active');
					}
				}else
				if(main_url == 'mypage'){
					if(child_url == 'testing' || child_url == 'search'){
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_2').addClass('active');
					}else if(child_url == '' || child_url == 'index' || child_url == 'viewaccount'){
						$jQuery1_10('.list_menu').removeClass('active');
						$jQuery1_10('.m_4').addClass('active');
					}
				}else if(main_url == 'payment'){
					$jQuery1_10('.list_menu').removeClass('active');
					$jQuery1_10('.m_3').addClass('active');
				}else{
					$jQuery1_10('.list_menu').removeClass('active');
				}
				
			});

    		/* icon top menu*/
			$( "#m_menu" ).click(function() {
			  $( ".m_menu" ).toggle();
			});


			$jQuery1_10('#openBtn').click(function(){
			  	$jQuery1_10('.modal-body').load('<?php echo url::base()?>article/detail_js/2',function(result){
				    $jQuery1_10('#myModal').modal({show:true});
				});
			  
				
			});
		</script>

		<script type="text/javascript">
		 function keyPhone(e){
			var keyword=null;
				if(window.event){
					keyword = window.event.keyCode;
				}else{
					keyword=e.which; //NON IE;
				}
				if(keyword==32 ){
					return false;
				}
			}
		</script>
	</body>
</html>
