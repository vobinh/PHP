
<table class="list" cellspacing="1" border="1" cellpadding="5">

<tr class="list_header">
  	<td width="7%">No</td>
    <td width="10%"><?php echo 'Test' ?></td>
    <td width="10%"><?php echo 'Category' ?></td>
    <td nowrap="nowrap"><?php echo Kohana::lang('question_list_lang.lbl_question') ?></td>
    <?php for($i=0;$i<($mr['max_answers']);$i++) {?>
    <td nowrap="nowrap"><?php echo $i==0?'Good Answers':'Answers' ?></td>
    <?php }?>
    <td><?php echo Kohana::lang('question_list_lang.lbl_auth_add') ?></td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
 </tr>
  <?php 
  
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<td align="center"><?php echo $list['uid'] ?></td>
    <td><?php echo isset($list['test']['test_title'])? mb_convert_encoding($list['test']['test_title'],'HTML-ENTITIES','utf-8'):' '?></td>
    <td><?php echo isset($list['category']['category'])? mb_convert_encoding($list['category']['category'],'HTML-ENTITIES','utf-8'):' ' ?></td>
    <td align="left"><?php echo mb_convert_encoding($list['question'],'HTML-ENTITIES','utf-8') ?></td>
    <?php for($i=0;$i<($mr['max_answers']);$i++) {?>
    <td nowrap="nowrap"><?php
      echo isset($list['answer'][$i]['answer'])?mb_convert_encoding($list['answer'][$i]['answer'],'HTML-ENTITIES','utf-8'):"";
	?></td>
    <?php }?>
    <td align="center" width="10%"><?php echo (isset($list['author'][0]['fname']))?($list['author'][0]['fname']):''?></td> 
    <td align="center" width="10%"><div id="notice<?php echo $list['uid'] ?>"></div>
    <?php echo $list['status'] ?>
   
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
