<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<style type="text/css">
  .cssload-loader{
    display:block;
    position:absolute;
    height:6em;width:6em;
    left:50%;
    top:50%;
    margin-top:-3em;
    margin-left:-3em;
    background-color:rgb(51,136,153);
    border-radius:3.5em 3.5em 3.5em 3.5em;
    -o-border-radius:3.5em 3.5em 3.5em 3.5em;
    -ms-border-radius:3.5em 3.5em 3.5em 3.5em;
    -webkit-border-radius:3.5em 3.5em 3.5em 3.5em;
    -moz-border-radius:3.5em 3.5em 3.5em 3.5em;
    box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    -o-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    -ms-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    -webkit-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    -moz-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    background: linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background: -o-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background: -ms-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background: -webkit-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background: -moz-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background-blend-mode: multiply;
    border-top:7px solid rgb(0,153,0);
    border-left:7px solid rgb(0,153,0);
    border-bottom:7px solid rgb(204,204,0);
    border-right:7px solid rgb(204,204,0);
    animation:cssload-roto 1.15s infinite linear;
    -o-animation:cssload-roto 1.15s infinite linear;
    -ms-animation:cssload-roto 1.15s infinite linear;
    -webkit-animation:cssload-roto 1.15s infinite linear;
    -moz-animation:cssload-roto 1.15s infinite linear;
  }


  @keyframes cssload-roto {
    0%{transform:rotateZ(0deg);}
    100%{transform:rotateZ(360deg);}
  }

  @-o-keyframes cssload-roto {
    0%{-o-transform:rotateZ(0deg);}
    100%{-o-transform:rotateZ(360deg);}
  }

  @-ms-keyframes cssload-roto {
    0%{-ms-transform:rotateZ(0deg);}
    100%{-ms-transform:rotateZ(360deg);}
  }

  @-webkit-keyframes cssload-roto {
    0%{-webkit-transform:rotateZ(0deg);}
    100%{-webkit-transform:rotateZ(360deg);}
  }

  @-moz-keyframes cssload-roto {
    0%{-moz-transform:rotateZ(0deg);}
    100%{-moz-transform:rotateZ(360deg);}
  }
</style>
<style type="text/css" media="screen">
	.my_row{
		font-size: 16px;
	}
	.input_frm{
		/*display: inline;*/
    	/*width: 95%;*/
	}
</style>
<script>
$().ready(function(){
		if('<?php echo $this->session->get("error_code"); ?>' != ''){
			$.growl.error({ message: "<?php echo $this->session->get('error_code'); ?>" });
		}
});

function checkvalid() {
	check = true;
	if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#pg_billto_online_email').val()))){
			if($('#pg_billto_online_email').val() == ''){
				$.growl.error({ message: "Email is required!" });
				$('#pg_billto_online_email').focus();
			}else{
				$.growl.error({ message: "Email is invalid!" });
				$('#pg_billto_online_email').focus();
			}
			check =  false;
			return false;
	}

	if ($('#pg_billto_postal_name_first').val() == ''){
			$.growl.error({ message: "First Name is required!" });
			$('#pg_billto_postal_name_first').focus();
			check =  false;
			return false;
	}

	if ($('#pg_billto_postal_name_last').val() == ''){
			$.growl.error({ message: "Last Name is required!" });
			$('#pg_billto_postal_name_last').focus();
			check =  false;
			return false;
	}
	
	if(($('#txt_checkcode').val() != '')){
		txt_checkcode = $('#txt_checkcode').val();
		if(txt_checkcode.length != 12){
			$.growl.error({ message: "Invalid discount code." });
			check =  false;
			return false;
		}
	}
	return check;
}
</script>
<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 3;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="frame_content_top" style="font-size: 24px;padding-bottom: 10px;">
                        <a class="text-black" href="<?php echo url::base()?>courses/showlist">Purchase Courses</a> 
                        <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                        <a class="text-black" href="javascript:void(0)">Payment</a>
                    </td>
                </tr>
            </table>
		</div>
	</div>
	<div class="row my_row">
		<div class="col-sm-4 col-md-4">
			 <div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo $courses['title']?>">
					<strong>Name :</strong> <?php echo $courses['title']?>
				</div>
			</div>
		</div>
		<?php /*?>
		<div class="col-sm-4 col-md-4">
			<div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
					<strong> Qty :</strong> <?php //echo $courses['qty_question']?>
				</div>
			</div>
		</div>
		<?php */?>
		<div class="col-sm-4 col-md-4">
			<div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
					<strong>Valid for:</strong> <?php echo $courses['day_valid']?> <?php if($courses['day_valid'] > 1 )echo 'days'; else if($courses['day_valid'] == 1) echo 'day'; else if($courses['day_valid'] == 0) echo 'No Limit';?>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-md-4">
			<div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
					<strong>Price :</strong> 
		<?php echo $this->format_currency($courses['price']+(isset($courses['promotion_price'])&&$courses['promotion_price']!=0?$courses['promotion_price']:0))?>
				</div>
			</div>
		</div>
	</div>
	<?php /*?>
	<div class="row my_row">
		<div class="col-sm-4 col-md-4">
			 <div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
					<strong>Pass score :</strong> <?php //echo $courses['pass_score']?>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-md-4">
			<div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
					<strong>Price :</strong> 
		<?php echo $this->format_currency($courses['price']+(isset($courses['promotion_price'])&&$courses['promotion_price']!=0?$courses['promotion_price']:0))?>
				</div>
			</div>
		</div>
	</div>
	<?php */?>
	<?php if(isset($courses['description']) && !empty($courses['description'])) {?>
	<div class="row my_row">
		<div class="col-md-12">
			<div class="panel panel-default box_shadow" style="border-radius: 0px !important;margin-bottom: 10px;">
		        <div class="panel-body">
		        	<strong>Description :</strong> <?php echo $courses['description']?>
		        </div>
		    </div>
		</div>
	</div>
	<?php  }?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel main_data_13 box_shadow">
				<div id='checkcode' class="panel-body col-sm-12 col-md-8 col-md-offset-2 box_shadow" style="margin-top: 10px;">
					<form class="form-horizontal"  action="<?php echo $this->site['base_url']?>payment/checkcode/<?php echo $courses['id']?>" id="frm_checkcode" method="post" style="text-align:center">
						<div class="form-group" style="margin-bottom: 0px;">
							<label for="pg_billto_postal_name_company" class="col-sm-4 control-label" style="float: left;">Discount Code</label>
							<div class="col-sm-8 text-left">
								<div class="input-group">
									<input class="form-control" type="text" name="txt_checkcode" id="txt_checkcode" autocomplete="off" style="display: initial;margin-bottom: 5px;" />
									<span class="input-group-btn">
										<button style="margin-bottom: 5px;" class="btn btn-success" type="button" name="btn_checkcode" id="btn_checkcode" class="button" onclick="checkcode();"><span>Apply</span></button>
									</span>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body col-sm-12 col-md-8 col-md-offset-2">
					<form class="form-horizontal" id="frm_payment" method="post" action="<?php if($courses['price']>0) echo $courses['post_url'];else  {?><?php echo $this->site['base_url']?>payment/paycard <?php } ?>" novalidate="novalidate">
						<?php 
							$mr_user = ORM::factory('member_orm',$this->sess_cus['id'])->as_array();
						?>
						<input type="hidden" id="pg_api_login_id" name="pg_api_login_id" value="<?php echo $courses['api_login'] ?>"/>
						<input type="hidden" id="pg_transaction_type" name="pg_transaction_type" value="10"/>
						<input type="hidden" id="pg_version_number" name="pg_version_number" value="1.0"/>
						<input type="hidden" id="pg_total_amount" name="pg_total_amount" value="<?php echo $courses['price'] ?>"/> 
						<input type="hidden" id="pg_utc_time" name="pg_utc_time" value="<?php echo $courses['time'] ?>"/>
						<input type="hidden" id="pg_consumerorderid" name="pg_consumerorderid" value="<?php echo $courses['orderid'] ?>"/>
						<input type="hidden" id="pg_transaction_order_number" name="pg_transaction_order_number" value="<?php echo $courses['transaction_order_number'] ?>"/>
						<input type="hidden" id="pg_ts_hash" name="pg_ts_hash" value="<?php echo $courses['hash'] ?>"/>
						<input type="hidden" id="pg_return_url" name="pg_return_url" value="<?php echo $this->site['base_url']?>payment/paycard"/>
						<input type="hidden" id="pg_wallet_id" name="pg_wallet_id" value="<?php echo $courses['title']?>"/>
						<input type="hidden" id="txt_code_pomotion" name="txt_code_pomotion" value="<?php echo $courses['promotion_code']?>">
						<input type="hidden" id="txt_type_pomotion" name="txt_type_pomotion" value="<?php echo $courses['promotion_type']?>">

						<div class="form-group">
					    	<label for="pg_billto_postal_name_company" class="col-sm-4 control-label">Company Name</label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_postal_name_company' id='pg_billto_postal_name_company' value="<?php echo $mr_user['company_name']?>" />
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_online_email" class="col-sm-4 control-label">Email <font color="#FF0000">*</font></label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_online_email' id='pg_billto_online_email' value="<?php echo $mr_user['member_email']?>" />
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_postal_name_first" class="col-sm-4 control-label">First Name <font color="#FF0000">*</font></label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_postal_name_first' id='pg_billto_postal_name_first' value="<?php echo $mr_user['member_fname']?>" />
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_postal_name_last" class="col-sm-4 control-label">Last Name <font color="#FF0000">*</font></label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_postal_name_last' id='pg_billto_postal_name_last' value="<?php echo $mr_user['member_lname']?>" />
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_telecom_phone_number" class="col-sm-4 control-label">Phone</label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_telecom_phone_number' id='pg_billto_telecom_phone_number' value="" />
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_postal_street_line1" class="col-sm-4 control-label">Address</label>
						    <div class="col-sm-8">
						      	<input class="form-control input_frm" type='text' name='pg_billto_postal_street_line1' id='pg_billto_postal_street_line1' value=""/>
						    </div>
					  	</div>

					  	<div class="form-group">
						    <div class="col-sm-8 col-sm-offset-4">
						      	<input class="form-control input_frm" type='text' name='pg_billto_postal_street_line2' id='pg_billto_postal_street_line2' value=""/>
						    </div>
					  	</div>

					  	<div class="form-group">
					    	<label for="pg_billto_postal_street_line1" class="col-sm-4 control-label">City/State/ZipCode</label>
					    	<!-- <div class="col-sm-8"> -->
					    		<div class="col-sm-3" style="margin-bottom: 15px;">
							      	<input class="form-control input_frm" type='text' name='pg_billto_postal_city' id='pg_billto_postal_city' value=""/>
							    </div>
							    <div class="col-sm-3" style="margin-bottom: 15px;">
									<select class="form-control input_frm" name='pg_billto_postal_stateprov' id='pg_billto_postal_stateprov'>
										<option value="AL">Alabama</option> 
										<option value="AK">Alaska</option> 
										<option value="AS">American Samoa</option> 
										<option value="AZ">Arizona</option> 
										<option value="AR">Arkansas</option> 
										<option value="CA" selected="selected">California</option> 
										<option value="CO">Colorado</option>
										<option value="CT">Connecticut</option> 
										<option value="DE">Delaware</option>
										<option value="DC">District Of Columbia</option>
										<option value="FL">Florida</option>
										<option value="GA">Georgia</option> 
										<option value="GU">Guam</option>
										<option value="HI">Hawaii</option>
										<option value="ID">Idaho</option> 
										<option value="IL">Illinois</option>
										<option value="IN">Indiana</option> 
										<option value="IA">Iowa</option> 
										<option value="KS">Kansas</option>
										<option value="KY">Kentucky</option>
										<option value="LA">Louisiana</option>
										<option value="ME">Maine</option> 
										<option value="MD">Maryland</option> 
										<option value="MA">Massachusetts</option>
										<option value="MI">Michigan</option> 
										<option value="MN">Minnesota</option> 
										<option value="MS">Mississippi</option> 
										<option value="MO">Missouri</option>
										<option value="MT">Montana</option>
										<option value="NE">Nebraska</option>
										<option value="NV">Nevada</option> 
										<option value="NH">New Hampshire</option> 
										<option value="NJ">New Jersey</option> 
										<option value="NM">New Mexico</option> 
										<option value="NY">New York</option>
										<option value="NC">North Carolina</option> 
										<option value="ND">North Dakota</option>
										<option value="MP">Northern Mariana Islands</option>
										<option value="OH">Ohio</option>
										<option value="OK">Oklahoma</option> 
										<option value="OR">Oregon</option>
										<option value="PW">Palau</option>
										<option value="PA">Pennsylvania</option>
										<option value="PR">Puerto Rico</option>
										<option value="RI">Rhode Island</option>
										<option value="SC">South Carolina</option>
										<option value="SD">South Dakota</option>
										<option value="TN">Tennessee</option> 
										<option value="TX">Texas</option> 
										<option value="UT">Utah</option> 
										<option value="VT">Vermont</option>
										<option value="VI">Virgin Islands</option> 
										<option value="VA">Virginia</option> 
										<option value="WA">Washington</option> 
										<option value="WV">West Virginia</option>
										<option value="WI">Wisconsin</option> 
										<option value="WY">Wyoming</option> 
									</select>
								</div>
								<div class="col-sm-2" style="margin-bottom: 15px;">
			 						<input class="form-control input_frm" type='text' name='pg_billto_postal_postalcode' id='pg_billto_postal_postalcode' value=""/>
			 					</div>
					    	<!-- </div> -->
					  	</div>

					  	<?php if(isset($courses['promotion_price']) && !empty($courses['promotion_price'])) {?>
					  	<div class="form-group">
					    	<label class="col-sm-4 control-label">Discount Code Applied</label>
						    <div class="col-sm-8">
						      	<label class="control-label">-<?php echo $this->format_currency($courses['promotion_price']) ?></label>
						    </div>
					  	</div>
					  	<div class="form-group">
					    	<label class="col-sm-4 control-label">Price After Discount</label>
						    <div class="col-sm-8">
						      	<label class="control-label"><?php echo $this->format_currency($courses['price']) ?></label>
						    </div>
					  	</div>
					  	<?php } ?>
					  	<div class="form-group" style="margin-bottom: 0px;">
						  	<div class="col-sm-8 col-sm-offset-4">
						  		<button style="padding: 10px 30px;" class="btn btn-success" type="button" name="btn_checkcode" id="btn_checkcode" class="button" onclick="checkpayment();"  value="Payment" >Next</button>
						  		<input type="hidden" name="pg_shipto_postal_name" id="pg_shipto_postal_name" value="<?php echo isset($this->sess_cus['name'])?$this->sess_cus['name']:"" ?>"/>
						  	</div>
						</div>
						<button style="display:none;" id="customButton">Purchase</button>
					</form>
				</div>
				<div class="clearfix"></div>
		</div>
	</div>
</div>
	</div>
<div class="col-sm-12" style="padding-top:10px;">
	<div class="clearfix"></div>	
</div>


<div id="notice" style="display: none;text-align: center;border: 1px solid #ccc;height: 15px;color: #FF6D59;background: #CFFDFD;padding: 10px;font-size: 14px;border-radius: 4px;margin-top: 2px;"></div>

<?php echo $this->session->delete('error_code'); ?>
<script>
function checkcode(){
	txt_checkcode = $('#txt_checkcode').val();
	if(txt_checkcode.length != 12){
		$.growl.error({ message: "Invalid discount code." });
		return false;
	}
	$('#frm_checkcode').submit()
}
function checkpayment(){
	if(checkvalid()) {
		if(($('#txt_checkcode').val() != '')){
			$('.loading_img').show();
			$.ajax({
				url: '<?php echo $this->site["base_url"]?>payment/checkcode/<?php echo $courses["id"]?>/json',
				type: 'POST',
				dataType: 'json',
				data: {'txt_checkcode': txt_checkcode},
			})
			.done(function(data) {
				$('.loading_img').hide();
				if(data['type'] == 'error'){
					$.growl.error({ message:  data['messages']});
					return false;
				}else if(data['type'] == 'free'){
					window.location.href = data['messages'];
					return false;
				}else if(data['type'] == 'payment'){
					$('#pg_total_amount').val(data['messages']['price']);
					$('#pg_utc_time').val(data['messages']['time']);
					$('#pg_consumerorderid').val(data['messages']['orderid']);
					$('#pg_transaction_order_number').val(data['messages']['transaction_order_number']);
					$('#pg_ts_hash').val(data['messages']['hash']);
					$('#txt_code_pomotion').val(data['messages']['promotion_code']);
					$('#txt_type_pomotion').val(data['messages']['promotion_type']);
					name_first = $('#pg_billto_postal_name_first').val();
					$('#customButton').click();
					//$('#frm_payment').submit();
				}
			});
		}else{
			name_first = $('#pg_billto_postal_name_first').val();
			//$('#frm_payment').submit();
			$('#customButton').click();
		}
		
	}
}
</script>

<script>
	<?php 
		if(!empty($stripe_config)){
			if($stripe_config[0]['type'] == 1){
				$test_publishable_key = $stripe_config[0]['live_publishable_key'];
			}else{
				$test_publishable_key = $stripe_config[0]['test_publishable_key'];
			}
	?>
	var handler = StripeCheckout.configure({
	    key: '<?php echo $test_publishable_key ?>',
	    image: '/uploads/payment/marketplace.png',
	    locale: 'en',
	    token: function(token) {
	      // Use the token to create the charge with a server-side script.
	      // You can access the token ID with `token.id`
		var m_total     = ($('#pg_total_amount').val() * 100);
		var m_code      = $('#txt_code_pomotion').val();
		var m_type_code = $('#txt_type_pomotion').val();
	      $('.loading_img').show();
	      $.ajax({
	      	url: '<?php echo url::base()?>payment/paycard',
	      	type: 'POST',
	      	dataType: 'json',
	      	data: {
	      		'data_stripe': token,
	      		'm_total': m_total,
	      		'm_code': m_code,
	      		'm_type_code': m_type_code
	      	},
	      })
	      .done(function(data) {
	      	if(data['type'] == 'error'){
	      		$('.loading_img').hide();
				$.growl.error({ message:  data['messages']});
				return false;
			}else if(data['type'] == 'stripe_error'){
				$('.loading_img').hide();
				$.growl.error({ message:  data['messages']['jsonBody']['error']['message']});
				return false;
			}else if(data['type'] == 'payment'){
				$('.loading_img').hide();
				window.location.href = data['messages'];
				return false;
			}
	      });
	    }
  	});

	$('#customButton').on('click', function(e) {
	    // Open Checkout with further options
	    var m_total = ($('#pg_total_amount').val() * 100);
	    var m_title = $('#pg_wallet_id').val();
	    var m_email = $('#pg_billto_online_email').val();
	    handler.open({
	      name: 'PesTesT Payment',
	      description: m_title,
	      email: m_email,
	      allowRememberMe: false,
	      amount: m_total
	    });
	    e.preventDefault();
	});

  	// Close Checkout on page navigation
  	$(window).on('popstate', function() {
    	handler.close();
  	});
  	<?php }?>
</script>

