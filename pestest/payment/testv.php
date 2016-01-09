<?php /*?><?php $pg_transaction_order_number = rand(10000,99999);
       $time = time() + 62135596800;
 ?>
<?php $pg_ts_hash = hash_hmac("md5","oLB6Tq03g1|10|1.0|105.00|".$time."|".$pg_transaction_order_number,"SECURETRANSACTIONKEY");
// echo $pg_ts_hash;
 echo $pg_transaction_order_number;
 echo('<br/>');
 // echo date("Y-m-d H:i:s", time()); 
?>
<html><form method='post' action= 'https://sandbox.paymentsgateway.net/swp/co/default.aspx'><table cellSpacing='0' cellPadding='0' border='0'><tr><td align='right' width='300'></td><td align='left' width='200'><input type='hidden' name='pg_api_login_id' value='oLB6Tq03g1'/></td></tr><tr><td align='right' width='300'></td><td align='left' width='200'></td></tr><tr><td align='right' width='300'><b></b></td><td align='left' width='200'><input type='hidden' name='pg_total_amount'value='105.00' /><br></td></tr><tr><td align='right' width='300'></td><td align='left' width='200'>
<input type="hidden" name="pg_transaction_order_number" value="<?php echo $pg_transaction_order_number ?>"/>
<input type="hidden" name="pg_return_url" value="http://www.pestest.com"/>

<INPUT TYPE=SUBMIT value='Pay Now'><br></td></tr></table></form></html>
<html><?php */?>
<?php session_start();?>
<?php  $pg_transaction_order_number = rand(10000,99999);
       $time = number_format((time() * 10000000) + 621355968000000000 , 0, '.', '');
	   echo number_format((time() * 10000000) + 621355968000000000 , 0, '.', '');
	   $pg_ts_hash = hash_hmac("md5","HVMk1w00u0|10|1.0|105.00|".$time."|".$pg_transaction_order_number,"2OWvP53q");
	   $_SESSION['orderid']=$time;
 ?>
 
<FORM METHOD="post" ACTION="https://sandbox.paymentsgateway.net/swp/co/default.aspx">
<input name="pg_billto_postal_name_first" type="text" value="Bob"/>
<input name="pg_billto_postal_name_last" type="text" value="Smith"/>
<input type="hidden" name="pg_api_login_id" value="HVMk1w00u0"/>
<input type="hidden" name="pg_transaction_type" value="10"/>
<input type="hidden" name="pg_version_number" value="1.0"/>
<input type="hidden" name="pg_total_amount" value="105.00"/> 
<input type="hidden" name="pg_utc_time" value="<?php echo $time ?>"/>
<input type="hidden" name="pg_consumerorderid" value="<?php echo $time ?>"/>
<input type="hidden" name="pg_transaction_order_number" value="<?php echo $pg_transaction_order_number ?>"/>
<input type="hidden" name="pg_ts_hash" value="<?php echo $pg_ts_hash ?>"/>
<input type="text" name="pg_return_url" value="http://pestest.com/payment/send_emai.php"/>
<input TYPE=SUBMIT>
</FORM>
 

       
