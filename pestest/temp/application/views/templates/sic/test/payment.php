<script>
$().ready(function(){
		if('<?php echo $this->session->get('error_code'); ?>' != ''){
			$('#notice').html('<?php echo $this->session->get('error_code'); ?>');
			$('#notice').show('slow');
			$('#notice').delay( 3000 ).hide('slow');
		}
});

function checkvalid() {
	check = true;
	if ($('#pg_billto_postal_name_last').val() == ''){
		    $('#notice').html('Last Name is required!.');
			$('#notice').show('slow');
			$('#notice').delay( 3000 ).hide('slow');
			$('#pg_billto_postal_name_last').focus();
			check =  false;
	}
	
	if ($('#pg_billto_postal_name_first').val() == ''){
		    $('#notice').html('First Name is required!.');
			$('#notice').show('slow');
			$('#notice').delay( 3000 ).hide('slow');
			$('#pg_billto_postal_name_first').focus();
			check =  false;
	}
	
	if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#pg_billto_online_email').val()))){
			if($('#pg_billto_online_email').val() == ''){
				$('#notice').html('Email is required!.');
				$('#notice').show('slow');
				$('#notice').delay( 3000 ).hide('slow');
				$('#pg_billto_online_email').focus();
			}else{
				$('#notice').html('Email is invalid!.');
				$('#notice').show('slow');
				$('#notice').delay( 3000 ).hide('slow');
				$('#pg_billto_online_email').focus();
			}
			check =  false;
	}
	return check;
}
</script><style type="text/css">
<!--
.style1 {font-size: 17px}
-->
</style>
<div id="notice" 
style="
display: none;
text-align: center;
border: 1px solid #ccc;
height: 15px;
color: #FF6D59;
background: #CFFDFD;
padding: 10px;
font-size: 14px;
border-radius: 4px;
margin-top: 2px;
}"></div>

<div id='container'>

<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
 <tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a>
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a>Payment</a></td>
 </tr>
</table>

<div id='info' style="width:100%; display:table">
	<div style="width:100%; display:table; float:left">
        <div class="qty" style="float:left; margin:5px; font-size:18px;"> <strong>Name :</strong> <?php echo $test['test_title']?></div>
        <div class="qty" style="float:left; margin:5px; font-size:18px;"><strong> Qty :</strong> <?php echo $test['qty_question']?></div>
        <div class="qty" style="float:left; margin:5px; font-size:18px;"> <strong>Time :</strong> <?php echo $test['time_value']?> <?php if($test['time_value'] > 1 )echo 'minutes'; else if($test['time_value'] == 1) echo 'minute'; else if($test['time_value'] == 0) echo 'No Limit';?> </div>
        <div class="qty" style="float:left; margin:5px; font-size:18px;"> <strong>Pass score :</strong> <?php echo $test['pass_score']?></div>
        <div class="qty" style="float:left; margin:5px; font-size:18px;"> <strong>Price :</strong> 
		<?php echo $this->format_currency($test['price']+(isset($test['promotion_price'])&&$test['promotion_price']!=0?$test['promotion_price']:0))?></div>
    </div>
	<div>
        <?php if(isset($test['test_description']) && !empty($test['test_description'])) {?>
        <div class="qty" style="float:left; margin:5px; font-size:18px;; width:96.5%"> <strong>Description :</strong> <?php echo $test['test_description']?></div>
        <?php  }?>
    </div>

</div>
<br />
<div id="tabs">
  <ul>
    <li><a href="#tabs-1"><span style="font-size:17px">Card</span></a></li>
    <li><a href="#tabs-2"><span class="style1">Promotion</span><br /></a></li>
  </ul>
<div id="tabs-1">
<div id='card'>
<form id="frm_payment" method="post" action="<?php if($test['price']>0) echo $test['post_url'];else  {?><?php echo $this->site['base_url']?>payment/paycard <?php } ?>">
	
<?php 
	$mr_user = ORM::factory('member_orm',$this->sess_cus['id'])->as_array();
	
	
 ?>
<input type="hidden" name="pg_api_login_id" value="<?php echo $test['api_login'] ?>"/>
<input type="hidden" name="pg_transaction_type" value="10"/>
<input type="hidden" name="pg_version_number" value="1.0"/>
<input type="hidden" name="pg_total_amount" value="<?php echo $test['price'] ?>"/> 
<input type="hidden" name="pg_utc_time" value="<?php echo $test['time'] ?>"/>
<input type="hidden" name="pg_consumerorderid" value="<?php echo $test['orderid'] ?>"/>
<input type="hidden" name="pg_transaction_order_number" value="<?php echo $test['transaction_order_number'] ?>"/>
<input type="hidden" name="pg_ts_hash" value="<?php echo $test['hash'] ?>"/>
<input type="hidden" name="pg_return_url" value="<?php echo $this->site['base_url']?>payment/paycard"/>
<input type="hidden" name="pg_wallet_id" value="<?php echo $test['test_title']?>"/>
<table style="margin:auto" width="100%">
<tr>
<td colspan="2" style="background-color:#CCCCCC; font-weight:bold">Information</td>
</tr>
<tr>
<td align="right" width="10%">Company Name</td>
<td align="left"><input type='text' name='pg_billto_postal_name_company' id='pg_billto_postal_name_company' value="<?php echo $mr_user['company_name']?>" /></td>
</tr>
<tr>
<td align="right"><span style="color:#FF0000">*</span>Email</td>
<td align="left"><input type='text' name='pg_billto_online_email' id='pg_billto_online_email' value="<?php echo $mr_user['member_email']?>" /></td>
</tr>
<tr>
<td align="right"><span style="color:#FF0000">*</span>First Name</td>
<td align="left"><input type='text' name='pg_billto_postal_name_first' id='pg_billto_postal_name_first' value="<?php echo $mr_user['member_fname']?>" /></td>
</tr>
<tr>
<td align="right"><span style="color:#FF0000">*</span>Last Name	</td>
<td align="left"><input type='text' name='pg_billto_postal_name_last' id='pg_billto_postal_name_last' value="<?php echo $mr_user['member_lname']?>" /></td>
</tr>
<tr>
<td align="right">Phone</td>
<td align="left"><input type='text' name='pg_billto_telecom_phone_number' id='pg_billto_telecom_phone_number' value="" /></td>
</tr>
<tr>
<td align="right" valign="middle">Address</td>
<td align="left"><input type='text' name='pg_billto_postal_street_line1' id='pg_billto_postal_street_line1' value="" style="width:300px;" /></td>
</tr>
<tr>
<td align="right"></td>
<td align="left"><input type='text' name='pg_billto_postal_street_line2' id='pg_billto_postal_street_line2' value="" style="width:300px;"/>

</td>
</tr>
<tr>
<td align="right" valign="middle">City/State/ZipCode</td>
<td align="left"><input type='text' name='pg_billto_postal_city' id='pg_billto_postal_city' value="" style="width:10em"/>
<select name='pg_billto_postal_stateprov' id='pg_billto_postal_stateprov' style="width:7em">
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
 <input type='text' name='pg_billto_postal_postalcode' id='pg_billto_postal_postalcode' value="" style="width:50px;" />
</td>
</tr>
<?php if(isset($test['promotion_price']) && !empty($test['promotion_price'])) {?>
<tr>
<td align="right" width="25%">Discount Code Applied</td>
<td align="left">-<?php echo $this->format_currency($test['promotion_price']) ?></td>
</tr>
<tr>
<td align="right" width="25%">Price After Discount</td>
<td align="left"><?php echo $this->format_currency($test['price']) ?></td>
</tr>
<?php } ?>
<tr>
<td align="right" width="25%">&nbsp;</td>
<td align="left">
<button style="width: 160px;" type="button" name="btn_checkcode" id="btn_checkcode" class="button" onclick="checkpayment();"  value="Payment" ><span> Pay Now  </span></button></td>
<input type="hidden" name="pg_shipto_postal_name" id="pg_shipto_postal_name" value="<?php echo isset($this->sess_cus['name'])?$this->sess_cus['name']:"" ?>"/>

</tr>


</table>

</form>
</div>
</div>
<div id="tabs-2">
<div id='checkcode'>
<form  action="<?php echo $this->site['base_url']?>payment/checkcode/<?php echo $test['uid']?>" id="frm_checkcode" method="post" style="text-align:center">
<input type="text" name="txt_checkcode" id="txt_checkcode" autocomplete="off" style="height: 40px;padding: 1px;margin: 10px;background: #F7F7F7;border-radius: 5px;" />
<button style="width: 160px;" type="button" name="btn_checkcode" id="btn_checkcode" class="button" onclick="checkcode();"><span> Submit </span></button>
</form>

</div>
</div>
</div>
<?php echo $this->session->delete('error_code'); ?>
<script>
function checkcode(){
	txt_checkcode = $('#txt_checkcode').val();
	if(txt_checkcode.length != 12){
		$('#notice').html('Correction invalid code.');
		$('#notice').show('slow');
		$('#notice').delay( 3000 ).hide('slow');
		return false;
	}
	$('#frm_checkcode').submit()
}
function checkpayment(){
	if(checkvalid()) {
		name_first = $('#pg_billto_postal_name_first').val();
		$('#frm_payment').submit();
	}
}
</script>