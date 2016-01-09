
    <div class="yui3-u-1-6 right">Questions per test:</div>
    <div class="yui3-u-4-5">
    <input name="txt_per_test" type="text" id="txt_per_test" value="<?php echo $test[0]['qty_question']?>" style="width:186px;text-align:right;margin-left: -4px;" />
    
    
    </div>
    <table class="list" cellspacing="1" border="0" cellpadding="5">
    <tr class="list_header">
        <td>Category</td>
        <td>Percentage</td>
        <td>Total Questions</td>
    </tr>
    <?php  foreach($mlist as $id => $list){ ?> 
     <tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
        <td align="center"><?php echo $list['category']?></td>
        <td align="center"><input size="8" style="text-align:right" type="text" name="txt_percentage_<?php echo $list['uid']?>" value="<?php echo $list['questionnaires_total']==0?0:$list['category_percentage'] ?> "/>%
        <input size="8" style="text-align:right" type="hidden" name="txt_category[]" value="<?php echo $list['uid']?>"/>
         <input size="8" style="text-align:right" type="hidden" name="txt_questions_<?php echo $list['uid']?>" value="<?php echo $list['questionnaires_total']?>"/>
          <input size="8" style="text-align:right" type="hidden" name="txt_name_<?php echo $list['uid']?>" value="<?php echo $list['category']?>"/>
        </td>
        <td align="center"><?php echo ($list['category_percentage']*$test[0]['qty_question'])/100?><?php echo '/'.$list['questionnaires_total']; ?> </td>
     </tr>
    <?php } ?>
    </table>
