<style type="text/css" media="screen">
  .pagination{
    margin: 5px 0;
  }
</style>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>showlist/search" method="post" style="">
<div class="table-responsive">
  <table class="table table-striped" style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px">
    <tr style="padding:5px;font-weight:bold;background: #AED9D2;">
        <td align="center">Date</td>
        <td align="center">Transaction Code </td>
        <td align="center">Promotion Code </td>
        <td align="center">Test</td>
        <td align="center">Validation Day</td>
        <td align="center">Expiration Date</td>
        <td align="center">Price</td>
    </tr>
    <?php 
      if(!empty($mr['mlist']) && $mr['mlist']!=false){
        foreach($mr['mlist'] as $id => $list){ ?>
   <tr id="row_<?php echo $list['uid']?>">
      <td align="center"><?php echo isset($list['payment_date'])?date('m/d/Y',$list['payment_date']):''?>&nbsp;</td>
      <td align="center"><?php echo isset($list['transaction_code'])?$list['transaction_code']:''?>&nbsp;</td>
      <td align="center"><?php echo isset($list['promotion_code'])?$list['promotion_code']:''?>&nbsp;</td>
      <td align="left"><?php echo isset($list['test']['test_title'])?$list['test']['test_title']:''?>&nbsp;</td>
      <td align="center">
        <?php if(isset($list['test']['date']) && ($list['test']['date']) >0) {?>
          <?php echo isset($list['test']['date'])?$list['test']['date']:''?><?php echo($list['test']['date'] >1)?' days':" day"?>&nbsp;
        <?php }else { ?>
           No limit day
        <?php } ?>
      </td>
      <td align="center">
        <?php if(isset($list['test']['date']) && ($list['test']['date']) >0) {?>
    	   <?php echo (isset($list['test']['date']) && isset($list['payment_date']) )?date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']). ' + '.$list['test']['date'].' day')):'' ?>
        <?php } ?>
  	  </td>
      <td align="center">
        <?php echo isset($list['payment_price'])?$this->format_currency($list['payment_price']):''?>&nbsp;
      </td>
    </tr>
      <?php if(!empty($list['test_description'])) {?>
    
      <?php } ?>
    <?php } // end if ?>
  <?php } // end foreach ?>
  </table>
</div>
<div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
</form>