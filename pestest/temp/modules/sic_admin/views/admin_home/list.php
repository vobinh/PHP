<p style="font-weight:bold; border: 1px solid #CCC;padding: 10px;margin-top: 30px;background-color: #E6E6E6; clear:both">Recent Sales Report</p>
  <table class="list" cellspacing="1" border="0" cellpadding="5" width="100%">

<tr class="list_header">
  	  <td>Date</td>
      <td>Transaction Code </td>
       <td>Promotion Code </td>
    <td width="10%">First Name</td>
    <td width="15%">Last Name</td>
    <td width="20%">Test</td>
    <td width="10%" >Expiration Date</td>  
    <td>Price</td>
</tr>
 <?php  if(!empty($mlistpayment) && $mlistpayment!=false){
	    foreach($mlistpayment as $id => $list){
	  ?>
 <tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo isset($list['payment_date'])?date('m/d/Y',$list['payment_date']):''?></td>
     <td align="center"><?php echo (isset($list['transaction_code'])?$list['transaction_code']:'')?></td>
    <td align="center"><?php echo (isset($list['promotion_code'])?$list['promotion_code']:'')?></td>
    <td align="center"><?php echo (isset($list['member_fname'])?$list['member_fname']:'')?></td>
    <td align="center"><?php echo (isset($list['member_lname'])?$list['member_lname']:'')?></td>
    <td align="center"><?php echo isset($list['test_title'])?$list['test_title']:''?></td>
    <td align="center" width="10%"><?php if($list['date'] >0) {echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']).
	(isset($list['date'])?' + '.$list['date'].' day':''))); }else {echo 'No Limit day';} ?></td> 
    <td align="right" width="10%"><?php echo isset($list['price'])?$this->format_currency($list['price']):''?>
  </td>
 <?php } ?>
  <?php } ?>
  </table>
  <?php  if(!empty($mlistquestion) && $mlistquestion!=false){ ?>
  <br style="clear:both" />
<p style="font-weight:bold; border: 1px solid #CCC;padding: 10px;margin-top: 30px;background-color: #E6E6E6; clear:both">Recent Question</p>

  <table class="list" cellspacing="1" border="0" cellpadding="5" width="100%">
<tr class="list_header">
	<td width="13%">No</td>
    <td width="22%" align="center"><?php echo 'Test' ?></td>
    <td width="21%" align="center"><?php echo 'Category' ?></td>
    <td width="34%" align="center"><?php echo Kohana::lang('question_list_lang.lbl_question') ?></td>
<?php /*?>    <td><?php echo Kohana::lang('question_list_lang.lbl_answer') ?></td>
<?php */?>    <td align="center"><?php echo Kohana::lang('question_list_lang.lbl_auth_add') ?></td>
    
</tr>
  <?php 
  
  foreach($mlistquestion as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<td align="center"><?php echo $list['uid'] ?></td>
    <td align="center"><?php echo isset($list['test']['test_title'])? $list['test']['test_title']:' '?></td>
    <td><?php echo isset($list['category']['category'])? $list['category']['category']:' ' ?></td>
    <td align="left"><?php echo $list['question'] ?></td>
  	<?php /*?><td align="left">
		<?php foreach($list['answer'] as $value){?>
       
        	<?php print_r($value['answer'])?>
            <?php if($value['type']==1){?>
            	<?php echo '<span style="color: red">(true)</span>'?>
            <?php }?>
            <br />
		<?php }?>
    </td><?php */?>
    <td align="center" width="10%"><?php echo (isset($list['author'][0]['fname']))?($list['author'][0]['fname']):''?></td> 
   
  </tr>
  <?php } // end if ?>
 
</table>
<?php  } ?>
  <br style="clear:both" />
<p style="font-weight:bold; border: 1px solid #CCC;padding: 10px;margin-top: 20px;background-color: #E6E6E6; clear:both">Recent Testing</p>

<table class="list" cellspacing="1" border="0" cellpadding="5" width="100%">
<tr class="list_header">
  	<td align="center"><?php echo  'Code' ?></td>
    <td align="center"><?php echo  'Date' ?></td>
    <td align="center"><?php echo  'Test' ?></td>
    <td align="center"><?php echo  'Member' ?></td>
    <td align="center"><?php echo  'Score' ?></td>
    <td align="center"><?php echo  'Duration' ?></td>
  
</tr>
  <?php 

  if(!empty($mlisttesting) && $mlisttesting!=false){
  
  foreach($mlisttesting as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>" >
    <td align="center"><?php echo isset($list['testing_code'])?$list['testing_code']:''?></td>
    <td align="center"><?php echo isset($list['testing_date'])?$this->format_int_date($list['testing_date'],$this->site['site_short_date']):''?></td>

    <td align="center"><?php echo isset($list['test_title'])?$list['test_title']:''?></td>
    <td align="left"><?php echo isset($list['member_fname'])?$list['member_fname'].' '.$list['member_lname']:''?></td>
    <td align="center"><?php echo isset($list['testing_score'])?$list['testing_score']:''?></td>
    <td align="center"><?php echo isset($list['duration'])?gmdate('H:i:s',$list['duration']):''?></td>
    
  </tr>
  <?php } // end if ?>
  <?php }else{
  echo '<tr><td colspan="5">-- EMPTY DATA --</td><tr>';
  }  // end foreach ?>
</table><br style="clear:both" />
<p style="font-weight:bold; border: 1px solid #CCC;padding: 10px;margin-top: 20px;background-color: #E6E6E6; clear:both">Recent Member</p>
<table class="list" cellspacing="1" border="0" cellpadding="5" width="100%">
<tr class="list_header">
 
    <td align="center"><?php echo Kohana::lang('account_lang.lbl_fname') ?></td>
    <td align="center"><?php echo Kohana::lang('account_lang.lbl_lname') ?></td>
    <td align="center"><?php echo Kohana::lang('account_lang.lbl_email') ?></td>
    <td align="center"><?php echo 'Company Name' ?></td>
    <td align="center"><?php echo 'Company Email' ?></td>
    
</tr>
  <?php 
  if(!empty($mlistmenber) && $mlistmenber!=false){
  foreach($mlistmenber as $id => $list){ ?>
 
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	
    <td align="left"><?php echo $list['member_fname'] ?></td>
    <td align="left"><?php echo $list['member_lname'] ?></td>
    <td align="left"><?php echo $list['member_email']?></td>
    <td align="left"><?php echo $list['company_name'] ?></td>
    <td align="left"><?php echo $list['company_contact_email'] ?></td>
   
   
   
  </tr>
 
  <?php } } else{
  echo '<tr><td colspan="5">-- EMPTY DATA --</td><tr>';
  } // end foreach ?>
</table>
  <?php require('list_js.php')?>
</p>
