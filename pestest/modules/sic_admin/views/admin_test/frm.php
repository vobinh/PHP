<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<script>
$(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
	  width:1000,
	  modal: true,
	  position:['middle',20],
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
      
        duration: 1000
      }
    });
 });
</script>
<script >
	function loadmember(val1){
	$.ajax({
		url: val1,
		type: "GET",
		success: function(data){
			$('#dialog').html(data);
		}
	});
	}
	$().ready(function(){
		  if($("#answerno1").length == 0){
		  for(i=0;i<4;i++)
		  		addAnswer();
		  }
		  
	})
	function addAnswer(){
		 $('#tableanwer').append(
		 '<tr id="answerno1">'+
            '<td style="background: none;"><input name="answer_ans[]" id="answer_ans[]" type="text" /></td>'+
            '<td style="background: none;"><input name="type_ans[]" id="type_ans[]" type="checkbox" onclick="$(\':checkbox\').attr(\'checked\',false);$(this).attr(\'checked\',true)"/><input type="hidden" name="type_ans[]" value="0" /></td>'+
            '<td style="background: none;"><img onclick="$( this ).parent().parent().remove()" width="15px" src="<?php echo url::base() ?>themes/admin/pics/b_drop.png"/></td>'+
          '</tr>');
		  
	}
	
</script>

<form id="frm" name="frm" action="<?php echo url::base() ?>admin_test/save" method="post" enctype='multipart/form-data'>
<link rel="stylesheet" href="<?php echo url::base()?>themes/popup/jquery-ui.css">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php  echo Kohana::lang('test_frm_lang.tt_frm_questionnaires') ?></td>
    <td class="title_button"><?php require('button.php');?></td>
</tr>
</table>

<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>

<div class="yui3-g form" >

<div style="width: 90%;padding: 10px;">
  <div class="yui3-g">
        <div class="yui3-u-1-6 right"><?php echo Kohana::lang('test_frm_lang.lbl_title') ?><font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5"><input type="text" style="width:50%" id='txt_title' name="txt_title" value="<?php echo isset($test['test_title'])?$test['test_title']:''?>"/></div>
    </div>
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right"><?php echo 'Pass Score (%)' ?>:</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" name="txt_pass_score" id="txt_pass_score" onchange="numericFilter(this)"
          value="<?php echo isset($test['pass_score'])?$test['pass_score']:'0'?>" maxlength="3"/>
        </div>
    </div>
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right">No Question<font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" id="txt_question" name="txt_question" onchange="numericFilter(this)" 
          value="<?php echo isset($test['qty_question'])?$test['qty_question']:'0'?>"/>
        </div>
    </div>
    
     <div class="yui3-g">
        <div class="yui3-u-1-6 right">Question Page:</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" name="txt_questionpage" id="txt_questionpage" onchange="numericFilter(this)"
          value="<?php echo isset($test['questionpage'])?$test['questionpage']:'10'?>"/>
        </div>
    </div>
    
     <div class="yui3-g">
        <div class="yui3-u-1-6 right">Display Explanation:</div>
        <div class="yui3-u-4-5" style="font-size:14px;font-weight:normal">
            <input type="radio" name="displatex"  value="Y"  <?php if(isset($test['displayexplanation']) && $test['displayexplanation']=='Y')  {?> checked="checked" <?php } ?>/>Yes
            <?php
				$check_test = '';
				if(isset($test['displayexplanation']))
					if($test['displayexplanation'] == 'N')
						$check_test = 'checked="checked"';
					else 
						$check_test = '';
				else
					$check_test = 'checked="checked"';
			?>
            <input type="radio" name="displatex" value="N"  <?php echo $check_test ?> />No
        </div>
    </div>
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right">Date Valid (day):</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" name="txt_date" id="txt_date" onchange="numericFilter(this)"
          value="<?php echo isset($test['date'])?$test['date']:''?>"/>
        </div>
    </div>
    
     <div class="yui3-g">
        <div class="yui3-u-1-6 right"><?php echo 'Price' ?><font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" name="txt_price" id="txt_time_value" 
          value="<?php echo isset($test['price'])?$test['price']:''?>" />
        </div>
    </div>
    
   <div class="yui3-g">
        <div class="yui3-u-1-6 right">Testing Time (minute)<font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" style="width:10%;text-align:right" name="txt_time_value" id="txt_time_value" onchange="numericFilter(this)"
          value="<?php echo isset($test['time_value'])?$test['time_value']:''?>" onblur="checkSelect()"/>
        </div>
    </div>
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right">Tracking Type:</div>
        <div class="yui3-u-4-5">
        <select style="width:200px" name="sel_type_time" id="sel_type_time" onchange="checkSelect();"> 
        <?php if(isset($test['type_time']) && $test['type_time'] == 0){?>
        		<option value="0" selected="selected">Stopwatch</option>
           	    <option value="1">Countdown</option>
        <?php }else{?>
               	<option value="0" >Stopwatch</option>
           	    <option value="1" selected="selected">Countdown</option>
	    <?php }?>
        </select>
        </div>
    </div>
    <?php /*?>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right">Image:</div>
        <div class="yui3-u-4-5">
          <div>
            <?php if(!empty($test['img'])){?>
              <?php if(s3_using == 'on'){?>
                <img height="100px" src="<?php echo linkS3; ?>courses_img/<?php echo $test['img']; ?>">
              <?php }else{ ?>
                <img height="100" src="<?php echo url::base() ?>uploads/courses_img/<?php echo $test['img']; ?>">
              <?php }?>
            <?php }?>
          </div>
          <input type="file" name="txt_courses_img" id="txt_courses_img" value="" placeholder="">
        </div>
    </div>
    <?php */?>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right"><?php echo Kohana::lang('test_frm_lang.lbl_description') ?>:</div>
        <div class="yui3-u-4-5">
        <textarea  class="ckeditor" style="height:50px; width:51%" id="erea_description" name="erea_description" ><?php echo isset($test['test_description'])?$test['test_description']:''?></textarea>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right"><?php echo Kohana::lang('test_frm_lang.lbl_status') ?>:</div>
        <div class="yui3-u-4-5">
        <select style="width:200px" name="sel_status" id="sel_status" >
        <?php if(isset($test['status']) && $test['status'] == 0){?>
        		<option value="0" selected="selected">Inactive</option>
           	    <option value="1">Active</option>
        <?php }else{?>
               	<option value="0">Inactive</option>
                <option value="1" selected="selected">Active</option>
	    <?php }?>
        	
        </select></div>
    </div>
</div>

</div>

</td></tr>
</table>
<div id="dialog" title="Purchase Member" >

</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($test['uid'])?$test['uid']:''?>"/>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>