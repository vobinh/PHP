<?php
   // echo '<pre>';
   //   print_r($this->site);
   // echo '</pre>';
   // die();
?>
<div style="width:100%;" >
    <table border="0" cellpadding="1" cellspacing="1" style="width:100%;">
      <tr>
        <td style="width:30%;">
          <div style="position: relative; width:0%;">
            <img src="<?php echo linkS3 ?>site/<?php echo $this->site['site_logo'] ?>" style="width:100%;" />
          </div>
            
        </td>
        <td style="width:35%; font-size:30px ; text-align: center ; color:#999999;vertical-align: top;">
          INVOICE REPORT
        </td>
        <td style="width:35%;font-size:12px ; text-align:left ;">
          <b style="font-style:italic;font-family:Arial;">
          <?php echo $this->site['site_name'] ?>
          </b><br>
          Adddress : <?php echo $this->site['site_address']; ?> <?php echo $this->site['site_city']; ?>, <?php echo $this->site['site_state']; ?>&nbsp;<?php echo $this->site['site_zipcode']; ?><br />
          <?php echo !empty($this->site['site_phone'])?'Phone : '. $this->site['site_phone'].'<br>':"" ?>
          <?php echo !empty($this->site['site_fax'])?'Fax : '. $this->site['site_fax']:"" ?>
      </td>
      </tr>
    </table>
    <div style="border-top: 1px double #000; width:100%;border-bottom: 1px double #000; padding:1px;">
      
    </div>
    <table border="0" cellpadding="1" cellspacing="1" style="width:100%;">
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
      <tr style="padding:10px;">
        <td style="width:50%">Date : <?php echo date('m/d/Y',$mr['payment_date'])?></td>
        <td style="width:50%">
          <span style="width:50%">Expiration Date :
            <?php if($courses['day_valid'] > 0) {echo date('m/d/Y', strtotime(date('m/d/Y',$mr['payment_date']).
           (isset($courses['day_valid'])?' + '.$courses['day_valid'].' day':''))); }else {echo 'No Limit day';} ?>
          </span>
        </td>
      </tr>
      
      <tr style="padding:10px;">
        <td >Courses : <?php echo $courses['title']?></td>
        <td >First Name : <?php echo $member['member_fname']?></td>
        </tr>
       <tr style="padding:10px">
        <td>Price : <?php echo $this->format_currency($mr['payment_price'])?></td>
        <td>Last Name : <?php echo $member['member_lname']?></td>
        </tr>
    </table>
</div>