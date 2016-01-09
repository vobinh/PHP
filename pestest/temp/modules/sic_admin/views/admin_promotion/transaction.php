<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_promotion/search" method="post">
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
  	  <td>Date</td>
      <td>Promotion Code </td>
    <td width="10%">First Name</td>
    <td width="15%">Last Name</td>
    <td width="20%">Test</td>
    <td width="10%" nowrap="nowrap">Expiration Date</td>  
</tr>
  <?php 
  if(isset($mlist)&& $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo isset($list['payment_date'])?date('m/d/Y',$list['payment_date']):''?></td>
    <td align="center"><?php echo (isset($list['promotion_code'])?$list['promotion_code']:'')?></td>
    <td align="center"><?php echo (isset($list['member_fname'])?$list['member_fname']:'')?></td>
    <td align="center"><?php echo (isset($list['member_lname'])?$list['member_lname']:'')?></td>
    <td align="center"><?php echo isset($list['test_title'])?$list['test_title']:''?></td>
    <td align="center" width="10%"><?php if($list['date'] >0) {echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']).
	(isset($list['date'])?' + '.$list['date'].' day':''))); }else {echo 'No Limit day';} ?></td> 

  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
