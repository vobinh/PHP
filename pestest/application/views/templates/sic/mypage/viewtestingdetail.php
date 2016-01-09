<style type="text/css" media="screen">
  .pagination2{
    margin: 5px 0;
  }
</style>
<script>
$().ready(function(){
	$('.pagination2 .pagination a').click(function(){
		url = $(this).attr('href');
		loadtestingdetail(url);
		return false;
	})
})
</script>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_testingdetail/search" method="post">
<table class="table table-condensed table-striped main_data_13" style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px; margin-bottom: 0;">
<tr style="padding:5px;font-weight:bold;background: #AED9D2;">
    <td align="center"><?php echo 'Code' ?></td>
    <td align="center"><?php echo 'Test' ?></td>
    <td align="center"><?php echo 'Type' ?></td>
    <td align="center"><?php echo 'Date' ?></td>
    <td align="center"><?php echo 'Score' ?></td>
    <td align="center"><?php echo 'Duration' ?></td>
</tr>

  <?php 
  
  if(!empty($mlist[0]['testing']) && $mlist[0]['testing']!=false){ ?>
	<tr id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_code'])?$mlist[0]['testing']['testing_code']:''?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['test']['test_title'])?$mlist[0]['testing']['test']['test_title']:''?></td>
        <td align="center"><?php
	switch($mlist[0]['testing']['testing_type']){
		case 1:
			echo 	'Whole Test';
		break;
		case 2:
			echo 	'Only Missing';
		break;
		case 3:
			echo 	'By Category';
		break;
		default :
			echo    '';
	}
	?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_date'])?date('m/d/Y',$mlist[0]['testing']['testing_date']):''?></td>
        <td align="center"><?php echo ($mlist[0]['testing']['parent_score'] + $mlist[0]['testing']['testing_score']); //if($mlist[0]['testing']['testing_type'] == 1){ echo isset($mlist[0]['testing']['parent_score'])?$mlist[0]['testing']['parent_score']:''; }else{ echo isset($mlist[0]['testing']['testing_score'])?$mlist[0]['testing']['testing_score']:''; }?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['duration'])?gmdate('H:i:s',$mlist[0]['testing']['duration']):''?></td>
     </tr>
  <?php } ?>
</table>

<table class="table table-striped table-condensed main_data_13" style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px; margin-bottom: 0;">
<tr style="padding:5px;font-weight:bold;background: #AED9D2;">
    <?php if($mlist[0]['category'] !=''){?>
    	<td align="center" width=""><?php echo  'Category' ?></td>
    <?php }?>   
    <td align="center" width=""><?php echo  'Question' ?></td>
    <td align="center" width="23%"><?php echo  'Your Answer' ?></td>
    <td align="center" width="23%"><?php echo  'Right Answer' ?></td>
    <td align="center" width="5%"><?php echo  'Result' ?></td>
</tr>
<?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
    <tr id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
        <?php if($mlist[0]['category'] !=''){?>
        <td align="left"><?php echo isset($list['category'])?$list['category']:''?></td>
        <?php }?>
        <td class="testing_img" align="left"><?php echo isset($list['question']['question'])?$list['question']['question']:''?></td>
        <td class="testing_img">
        <?php if(isset($list['answer']['images']) && !empty($list['answer']['images'])) {?>
        <img src="<?php echo linkS3 ?>answer/<?php echo $list['answer']['images']?>" />
        <?php }else { ?>
    	<?php echo isset($list['answer']['answer'])?$list['answer']['answer']:''?>
        <?php } ?>
        </td>
        <td class="testing_img">
    	<?php if(isset($list['answerright'][0]['images']) && !empty($list['answerright'][0]['images'])) {?>
        <img src="<?php echo linkS3 ?>answer/<?php echo $list['answerright'][0]['images']?>" />
        <?php }else{ ?>
    	<?php echo isset($list['answerright'][0]['answer'])? $list['answerright'][0]['answer']:''?>
        <?php } ?>
        </td>
        <td align="center"><?php echo (@$list['result']== 1)? '<span style="color: green;">Right</span>':'<span style="color: #FFD600;">Wrong</span>'?></td>
    </tr>
  <?php } // end if ?>
<?php } // end foreach ?>
</table>
</form>
<div class='pagination2 main_data_13' style="float: right;">
  <?php echo isset($this->pagination)?$this->pagination:''?> 
</div>
<div style="clear:both;text-align:right; font-weight: bold;" class="main_data_13">
  <?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</div>

<script type="text/javascript">
  $('.testing_img img').addClass('img-responsive').height('auto');
</script>
