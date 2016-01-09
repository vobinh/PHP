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
<!--<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo url::base()?>mypage/testing">Testing History</a></td>
</tr>
</table>//-->
<table style="width: 100%;border: 1px solid #9CF;margin-top: 10px;">
<tr style="background:#9CF;font-size:14px; font-weight:bold">
    <td align="center"><?php echo  'Code' ?></td>
    <td align="center"><?php echo  'Test' ?></td>
     <td align="center"><?php echo  'Type' ?></td>
    <td align="center"><?php echo  'Date' ?></td>
    <td align="center"><?php echo  'Score' ?></td>
    <td align="center"><?php echo  'Duration' ?></td>
</tr>

  <?php 
  
  if(!empty($mlist[0]['testing']) && $mlist[0]['testing']!=false){ ?>
	<tr id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_code'])?$mlist[0]['testing']['testing_code']:''?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['test']['test_title'])?$mlist[0]['testing']['test']['test_title']:''?></td>
        <td align="center"><?php
	switch($mlist[0]['testing']['testing_type']){
		case 1:
			echo 	'New';
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
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_score'])?$mlist[0]['testing']['testing_score']:''?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['duration'])?gmdate('H:i:s',$mlist[0]['testing']['duration']):''?></td>
     </tr>
  <?php } ?>
</table>

<table style="width: 100%;border: 1px solid #9CF;margin-top: 10px;">
<tr style="background:#9CF;font-size:14px; font-weight:bold">
    <?php if($mlist[0]['category'] !=''){?>
    	<td align="center" width="25%"><?php echo  'Category' ?></td>
    <?php }?>   
    <td align="center" width="35%"><?php echo  'Question' ?></td>
    <td align="center" width="15%"><?php echo  'Your Answer' ?></td>
    <td align="center" width="15%"><?php echo  'Result' ?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
    <?php if($mlist[0]['category'] !=''){?>
    <td align="left"><?php echo isset($list['category'])?$list['category']:''?></td>
    <?php }?>
    <td align="left"><?php echo isset($list['question']['question'])?$list['question']['question']:''?></td>
    <td align="center"><?php echo isset($list['answer']['answer'])?$list['answer']['answer']:''?></td>
    <td align="center"><?php echo (@$list['result']== 1)? '<span style="color: green;">True</span>':'<span style="color: #FFD600;">False</span>'?></td>
</tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
</form>
<div class='pagination2' style="clear: both;float: right;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
</div>
