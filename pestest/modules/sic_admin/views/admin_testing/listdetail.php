

<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_testingdetail/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Testing'; ?></td>
    <td class="title_button" style=""><?php require('button.php');?></td>
</tr>
  
</table>

<table  cellspacing="1" border="0" cellpadding="5" style="border: 1px solid #ccc;margin-bottom: 10px;float: left;">
<tr class="list_header" style="background: #ccc; font-size: 16px;">
    <td align="center"><?php echo  'Code' ?></td>
    <td align="center"><?php echo  'Courses' ?></td>
    <td align="center"><?php echo  'Lesson' ?></td>
    <td align="center"><?php echo  'Test' ?></td>
    <td align="center"><?php echo  'Member' ?></td>
    <td align="center"><?php echo  'Date' ?></td>
    <td align="center"><?php echo  'Score' ?></td>
    <td align="center"><?php echo  'Duration' ?></td>
</tr>

  <?php 
  
  if(!empty($mlist[0]['testing']) && $mlist[0]['testing']!=false){ 
  ?>
	<tr id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_code'])?$mlist[0]['testing']['testing_code']:''?></td>
        <td align="center"><?php echo !empty($mlist[0]['testing']['courses'])?$mlist[0]['testing']['courses']['title']:'---'?></td>
        <td align="center"><?php echo !empty($mlist[0]['testing']['lesson'])?$mlist[0]['testing']['lesson']['title']:'---'?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['test']['test_title'])?$mlist[0]['testing']['test']['test_title']:''?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['menber']['member_fname'])?$mlist[0]['testing']['menber']['member_fname'].' '.$mlist[0]['testing']['menber']['member_lname']:''?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['testing_date'])?date('m/d/Y',$mlist[0]['testing']['testing_date']):''?></td>
        <td align="center"><?php echo ($mlist[0]['testing']['parent_score'] + $mlist[0]['testing']['testing_score']); ?></td>
        <td align="center"><?php echo isset($mlist[0]['testing']['duration'])?gmdate('H:i:s',$mlist[0]['testing']['duration']):''?></td>
     </tr>
  <?php } ?>
</table>

<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
 	<?php if($mlist[0]['category'] !=''){?>
    	<td align="center" width="25%"><?php echo  'Category' ?></td>
    <?php }?> 
    <td width="35%"><?php echo  'Question' ?></td>
    <td width="15%"><?php echo  'Your Answer' ?></td>
    <td width="15%"><?php echo  'Right Answer' ?></td>
    <td width="15%"><?php echo  'Result' ?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo isset($list['uid'])?$list['uid']:''?>">
	<?php if($mlist[0]['category'] !=''){?>
	<td align="center"><?php echo isset($list['category'])?$list['category']:''?></td>
    <?php }?>
    <td align="center"><?php echo isset($list['question']['question'])?$list['question']['question']:''?></td>
    
    <td align="center">
    
	<?php if(isset($list['answer']['images']) && !empty($list['answer']['images'])) {?>
    <img src="<?php echo linkS3 ?>answer/<?php echo $list['answer']['images']?>" />
    <?php }else { ?>
	  <?php echo isset($list['answer']['answer'])?$list['answer']['answer']:''?>
    <?php } ?>
    </td>
    <td align="center">
	 <?php
    if(isset($list['answerright'][0]['images']) && !empty($list['answer']['images']))
      echo '<img src="'.linkS3.'answer/'.$list['answerright'][0]['images'].'"/>';
    elseif(isset($list['answerright'][0]['answer']))
      echo $list['answerright'][0]['answer']; 
    else
			echo '';
  ?>
	</td>
    <td align="center"><?php echo (@$list['result']== 1)? '<span style="color: green;">Right</span>':'<span style="color: #FFD600;">Wrong</span>'?></td>
</tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo $idtesting ?>/<?php echo $id_test ?>/<?php echo $type ?>/<?php echo $id_lesson ?>">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onChange="document.frm_display.submit();">
	<option value="">---</option>
    <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
    <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
    <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
    <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
    <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
</select>
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</form>
</div>
<?php require('button.php')?>