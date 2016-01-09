<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> 
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo url::base()?>test/showlist">Test</a></td>
</tr>
</table>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>showlist/search" method="post" style="width:1017px">
<table style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px">
<!--<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr> //-->

<tr class="list_header" style="padding:5px;font-weight:bold">
  	<td align="center"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td align="center"><?php echo Kohana::lang('test_list_lang.lbl_qty_question') ?></td>
    <td align="center"><?php echo Kohana::lang('test_list_lang.lbl_time_value') ?></td>
    <td align="center"><?php echo 'Pass score' ?></td>
    <td align="center"><?php echo 'Price' ?></td>
 	<td align="center"><?php echo 'Payment' ?></td>
</tr>

   <?php 
  if(!empty($mr['mlist']) && $mr['mlist']!=false){
  foreach($mr['mlist'] as $id => $list){ ?>
 <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>"  style="font-weight:bold; background:#ddd;padding:7px;">
    <td align="center"><?php echo $list['test_title'] ?></td>
  	<td align="right"><?php echo $list['qty_question'] ?></td>
    <td align="right"><?php echo $list['time_value'] ?></td>
    <td align="right"><?php echo $list['pass_score'] .'%'?></td>
    <td align="right"><?php echo $this->format_currency($list['price'])?></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>test/start/<?php
	 echo base64_encode($list['uid']) ?>"><button type="button" name="btn_submit" id="btn_submit" class="button"  value="Payment"><span> Payment </span></button></a></td>
  </tr>
  <tr class="tr<?php if($id%2 == 0)  echo 0; else echo 1 ?>"><td colspan="6"><?php echo $list['test_description'] ?></td></tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>

<div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
</form>