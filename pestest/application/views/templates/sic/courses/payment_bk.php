<script>
$().ready(function(){
		if('<?php echo $this->session->get('error_code'); ?>' != ''){
			$('#notice').html('<?php echo $this->session->get('error_code'); ?>');
			$('#notice').show('slow');
			$('#notice').delay( 3000 ).hide('slow');
		}
});
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
        <div class="qty" style="float:left; margin:5px; font-size:18px;"> <strong>Price :</strong> <?php echo $this->format_currency($test['price'])?></div>
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
<form id="frm_payment" method="post" action="<?php echo $test['post_url'] ?>">
	
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

<table style="margin:auto" width="100%">
<tr>
<td colspan="2" style="background-color:#CCCCCC; font-weight:bold">Information</td>
</tr>
<tr>
<td align="right" width="10%">Company Name</td>
<td align="left"><input type='text' name='pg_billto_postal_name_last' value="<?php echo $mr_user['company_name']?>" /></td>
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
<td align="right" width="25%">&nbsp;</td>
<td align="left">
<button style="width: 160px;" type="button" name="btn_checkcode" id="btn_checkcode" class="button" onclick="checkpayment();"  value="Payment" ><span> Pay Now  </span></button></td></tr>
<input type="hidden" name="pg_shipto_postal_name" id="pg_shipto_postal_name" value="<?php echo isset($this->sess_cus['name'])?$this->sess_cus['name']:"" ?>"/>

<?php /*?><tr>
<td align="right">Address 1</td>
<td align="right"><input type='text' name='pg_billto_postal_street_line1' value="" /></td>
</tr>
<tr>
<td align="right">Address 2</td>
<td align="right"><input type='text' name='pg_billto_postal_street_line2' value="" />
</td>
</tr>
<tr>
<td align="right">City</td>
<td align="right"><input type='text' name='pg_billto_postal_city' value="" /></td>
</tr>
<tr>
<td align="right">States</td>
<td align="right"><input type='text' name='pg_billto_postal_stateprov' value="" /></td>
</tr>
<tr>
<td align="right">Zip Code</td>
 <td><input type="text" name="pg_shipto_postal_postalcode" id="pg_shipto_postal_postalcode" value=""/></td>
 </tr><?php */?>
<?php /*?><input type="text" name="pg_shipto_postal_street_line1" id="pg_shipto_postal_street_line1" value=""/>
<input type="text" name="pg_shipto_postal_street_line2" id="pg_shipto_postal_street_line2" value=""/>
<input type="text" name="pg_shipto_postal_city" id="pg_shipto_postal_city" value=""/>
<input type="text" name="pg_shipto_postal_stateprov" id="pg_shipto_postal_stateprov" value=""/>
<input type="text" name="pg_shipto_postal_postalcode" id="pg_shipto_postal_postalcode" value=""/>
<?php */?> 
<?php /*?><tr>
<td align="right"><span style="color:#FF0000">*</span>Card type</td><td><select name="">
<option>Visa</option>
<option>Matercard</option>
<option>Express</option>
<option>Palladium</option>

</select></td>
</tr>
<td align="right"><span style="color:#FF0000">*</span>Payment card number</td><td><input name="" type="text" /></td>
</tr>
<td align="right"><span style="color:#FF0000">*</span>Card holder name</td><td><input name="" type="text" /></td></tr>
<td align="right"><span style="color:#FF0000">*</span>Card expiration date</td><td><select name="">
<?php $month = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
foreach($month as $key => $value){?>
<option value="<?php echo $key?>"><?php echo $value?></option>

<?php }
?>
</select><select name="">
<?php
           $year = time();
            for($i=0;$i<10;$i++){                
                $Y = date("Y",$year);
                $year=$year+31536000;
            ?>
              <option value="<?php echo $Y ?>"><?php echo $Y ?></option>
              <?php } ?>

</select></td></tr>
<td align="right" width="25%"><span style="color:#FF0000">*</span>Card indentification number</td><td><input name="" type="text" /></td></tr>
<td align="right"><span style="color:#FF0000">*</span>Biling zipcode</td><td><input name="" type="text" /></td></tr>
<tr>
<td align="right" width="25%">&nbsp;</td>
<td align="left"><a href="<?php echo $this->site['base_url']?>payment/paycard/<?php echo $test['uid']?>">
<button style="width: 160px;" type="submit" name="btn_checkcode" id="btn_checkcode" class="button"  value="Payment" ><span> Pay Now  </span></button></a></td></tr><?php */?>
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
		$('#notice').html('Code have to 12 charter!...');
		$('#notice').show('slow');
		$('#notice').delay( 3000 ).hide('slow');
		return false;
	}
	$('#frm_checkcode').submit()
}
function checkpayment(){
	name_first = $('#pg_billto_postal_name_first').val();
	
	$('#frm_payment').submit()
}
</script>