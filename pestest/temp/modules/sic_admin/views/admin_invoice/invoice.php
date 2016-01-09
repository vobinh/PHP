
<div  style="border:1px solid #ccc; padding:20px; width:650px" >
<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
  <tr>
    <td style="width:20%;height:100px"><div id="logo"><img src="<?php DOCROOT?>uploads/site/logo.gif" style="height:25px; width:100px" /></div></td>
    <td style="width:50%; height:100px; font-size:30px ; text-align: center ; color:#999999 "><div id="title">INVOICE REPORT</div></td>
<td style="width:30%;height:100px"><div id="info" style=" font-size:11px ; text-align:left ;">
			<strong style="font-style:italic;font-family:Arial;"><?php echo $this->site['site_name'] ?></strong> <br/><br/>
			Adddress : <?php $this->site['site_address'] ?> <?php echo $this->site['site_city'] ?>, <?php echo $this->site['site_state'] ?>&nbsp;<?php echo $this->site['site_zipcode'] ?><br />
        <?php echo isset($this->site['site_phone'])?'Phone : '. $this->site['site_name'].'<br>':"" ?>
            <?php echo isset($this->site['site_name'])?'Fax : '. $this->site['site_name']:"" ?>
	  </div></td>
  </tr>
</table>


<table border="0" cellpadding="1" cellspacing="1" style="width:100%; margin-top:20px ; border-top: 1px solid #CCC">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
   <tr style="padding:10px"> 
    <td>
    <?php if(isset($mr['transaction_code'])&& $mr['transaction_code']!='') echo 'Transaction Code : '.$mr['transaction_code'] ;
		   else  if(isset($mr['promotion_code'])&& $mr['promotion_code']!='')
		  		 echo 'Promotion Code : '.$mr['promotion_code'];
		  		 else echo '';
	  ?>
 </td>
    <td>&nbsp;</td>
    </tr>
  <tr style="padding:10px">
    <td style="width:50%">Date : <?php echo date('m/d/Y',$mr['payment_date'])?></td>
    <td style="width:50%"><span style="width:50%">Expiration Date :
      <?php if($test['date'] >0) {echo date('m/d/Y', strtotime(date('m/d/Y',$mr['payment_date']).
	(isset($test['date'])?' + '.$test['date'].' day':''))); }else {echo 'No Limit day';} ?>
    </span></td>
  </tr>
  
  <tr style="padding:10px">
    <td >Test : <?php echo $test['test_title']?></td>
    <td >First Name : <?php echo $member['member_fname']?></td>
    </tr>
   <tr style="padding:10px">
    <td>Price : <?php echo $this->format_currency($mr['payment_price'])?></td>
    <td>Last Name : <?php echo $member['member_lname']?></td>
    </tr>
</table>
</div>