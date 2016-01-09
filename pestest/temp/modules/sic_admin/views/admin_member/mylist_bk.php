<script>
$().ready(function(){
	$('.pagination2 .pagination a').click(function(){
		url = $(this).attr('href');
		loadmytest(url);
		return false;
	})
})
</script>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<!--<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr> //-->

<tr class="list_header">
  	<td align="center"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td align="center">No. of Question</td>
    <td align="center">Testing Time</td>
    <td align="center">Date Valid (day)</td>
    <td align="center">Expiration Date</td>
    <td align="center"><?php echo 'Passing Score' ?></td>
    <td align="center"><?php echo 'Price' ?></td>
    <td align="center"><?php echo 'Payment' ?></td>
</tr>

  <?php 
  if(!empty($mr['mlist']) && $mr['mlist']!=false){
  foreach($mr['mlist'] as $id => $list){ ?>
 <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo $list['test_title'] ?></td>
  	<td align="center"><?php echo $list['qty_question'] ?></td>
    <td align="center"><?php echo $list['time_value'] ?> <?php echo($list['time_value'] >1)?' minutes':" minute"?></td>
    <td align="center">
	<?php if(isset($list['date']) && ($list['date']) >0) {?>
	<?php echo $list['date']?> <?php echo($list['date'] >1)?' days':" day"?>
    <?php }else{ ?>
    No limit day
    <?php } ?>
    </td>
    <td align="center">
	<?php if(isset($list['date']) && ($list['date']) >0) {?>
	<?php echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']). ' + '.$list['date'].' day'))?>
    <?php } ?>
    </td>
    <td align="center"><?php echo $list['pass_score'] .'%'?></td>
    <td align="center"><?php echo $this->format_currency($list['price'])?></td>
    <td align="center" ><?php echo ($list['payment']==1)?'Active':'InActive' ?></td>
  </tr>
  <tr class="tr<?php if($id%2 == 0)  echo 0; else echo 1 ?>"><td colspan="8" style="padding:7px;">
  <?php echo !empty($list['test_description'])?'Description: '.$list['test_description']:"" ?></td></tr>
  <?php } // end if ?>
  <?php } else{
  echo '<tr class="tr0"><td align="center" colspan="10">Empty</td></tr>';
  } ?>
</table>

<div class='pagination2' style="text-align:right"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
<div  style="text-align:right">
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:'0'?>
</div>
