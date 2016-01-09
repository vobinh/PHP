<?php   	$session=Session::instance();   ?>
<?php 
			$se_category_uid = $session->get('se_category_uid');
			$se_test_uid = $session->get('se_test_uid');
			$se_status = $session->get('se_status');
			$se_numasnwer = $session->get('se_numasnwer'); 


?>

<form id="frm" name="frm" action="<?php echo url::base() ?>admin_questionnaires/save" method="post" enctype="multipart/form-data">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php  echo Kohana::lang('question_frm_lang.tt_frm_questionnaires') ?></td>
    <td class="title_button"><?php require('button.php');?></td>
</tr>
</table>

<table id="float_table" style="clear:both;" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form" >

<div style="width: 100%;">
	<div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%"><?php echo "Test" ?>:</div>
        <div class="yui3-u-4-5">
       
        <select id="sel_test" name="sel_test" style="width:30%" onchange="$('select[name=\'sel_category\']').load('<?php echo $this->site['base_url']?>admin_questionnaires/getCateggory/'+this.value)">
        <option value="0">  </option> 
        	<?php 
			if(!empty($se_test_uid))
			{
				$que['test_uid']=$se_test_uid;
			}
			foreach($test as $value){?>
            	 <?php if(isset($que['test_uid']) && $que['test_uid'] == $value['uid']){?>
           		 
                   	   <option value="<?php echo $value['uid'] ?>" selected="selected"><?php echo $value['test_title'] ?></option>
                
                        <?php }else{?>
       
                 	   <option value="<?php echo $value['uid'] ?>"><?php echo $value['test_title'] ?></option>
                 
				 <?php }?> 
            <?php }?>
        </select>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%"><?php echo Kohana::lang('question_frm_lang.lbl_category_uid') ?>:</div>
        <div class="yui3-u-4-5">
       
        <select id="sel_category" name="sel_category" style="width:30%">
        	<?php 
			if(!empty($se_category_uid))
			{
				$que['category_uid']= $se_category_uid;
			}
			foreach($category as $value){?>
           		 <?php				
				 $expand = '';
				 for ($k=1;$k<$value['level'];$k++)
				 $expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				 $expand .= '|----';
				 if($value['level'] == 1 || $value['category']=='')
				 	$expand = '';
		  		 ?>
				 <?php if(isset($que['category_uid']) && $que['category_uid'] == $value['uid']){?>
            		
                       <option value="<?php echo $value['uid'] ?>" selected="selected"><?php echo $expand.$value['category'] ?></option>
                 <?php }else{?>
       
                 	   <option value="<?php echo $value['uid'] ?>"><?php echo $expand.$value['category'] ?></option>
                 
				 <?php }?>
            <?php }?>
            
        </select>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%"><?php echo Kohana::lang('question_frm_lang.lbl_liststatus') ?>:</div>
        <div class="yui3-u-4-5">
       	<?php  $liststatus = array(
								  'Pending'=>Kohana::lang('question_list_lang.lbl_pending'),
			  					  'Active'=>Kohana::lang('question_list_lang.lbl_active'),
								  'Deative'=>Kohana::lang('question_list_lang.lbl_deative'),
								  'Obsoleted'=>Kohana::lang('question_list_lang.lbl_obsoleted')); ?>
        <select id="sel_liststatus" name="sel_liststatus" style="width:30%">
        
        	<?php 
			if(!empty($se_status))
			{
				$que['status']=$se_status;
			}
			foreach($liststatus as $value){?>
           		<?php if(isset($que['status']) && trim(strtolower($que['status'])) == trim(strtolower($value))){?>
            			<option value="<?php echo $value ?>" selected="selected"><?php echo $value ?></option> 
                <?php }elseif($this->sess_admin['level']==1){?>
					<option value="<?php echo $value ?>"><?php echo $value ?></option> 
					
					<? }else{?>
                       <?php if($this->sess_admin['level']==3 && $value =='Pending' ) {?>
                	    <option value="<?php echo $value ?>"><?php echo $value ?></option> 
                        <?php } ?>
                <?php }?>
            <?php }?>    
        </select>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%; vertical-align:top"><?php echo Kohana::lang('question_frm_lang.lbl_question') ?>:</div>
        <div class="yui3-u-4-5">
        <textarea class="ckeditor" style="height:100px; width:100%" name="erea_question" id="erea_question"><?php echo isset($que['question'])?$que['question']:''?></textarea>
        </div>
    </div>
    
</div>



<div style="clear:both;" >
<table style="width: 80%;margin-left: 10.9%;border: 1px solid #E6E6E6;border-radius: 7px;">
<tr><td style="width: 50%;border-right: 1px solid #E6E6E6;">
<div>
 	 <table style="width:48% !important; padding: 10px;" id="tableanwer">
          <tr style="background:#FCFCFC ; ">
            <td style="background: none; padding: 10px;"><div class="yui3-u-1-6 right" style="width: 100%;text-align: left;"><?php echo Kohana::lang('question_frm_lang.lbl_content_answer') ?></div></td>
            <td style="background: none; padding: 10px;"><div class="yui3-u-1-6 right" style="width: 100%;text-align: left;">Images</div></td>
            <td style="background: none;"><div class="yui3-u-1-6 right" style="width: 100%;text-align: left;"><?php echo Kohana::lang('question_frm_lang.lbl_good_answer') ?></div></td>
            <td>Random</td>
            <td style="background: none;"><?php echo Kohana::lang('question_frm_lang.lbl_delete_answer') ?></td>
          </tr>
          <?php $k=0;$e=1; ?>
          <?php if(!empty($ans)){?>
			  <?php foreach($ans as $value){?>
                  <tr id="answerno<?php echo $k ?>">
                  	<input type="hidden" name="id_ans[]" value="<?php echo $value['uid']?>"/>
                    <td style="background: none;"><input style="width:150px; height:20px;" name="answer_ans[]" id="answer_ans[]" type="text" class="txt"  value="<?php echo $value['answer']?>"/></td>
                    <?php if(isset($value['images']) && !empty($value['images'])) {?>
                       <td style="background: none;"><img src="<?php echo linkS3 ?>answer/<?php echo $value['images']?>" />
                       <a href="<?php echo url::base()?>admin_questionnaires/del_images/<?php echo $value['uid']?>/<?php echo $que['uid']?>">Delete</a>
                  </td>
                     <?php }else { ?>
                     <td style="background: none;"><input style="width: 150px;" name="answer_file<?php echo $k+1 ?>" id="answer_file[]" type="file" class="txt" /></td>
                     <?php } ?>
                    <td style="background: none;padding-left:16px"><input name="type_ans[]" id="type_ans" type="checkbox"  <?php echo ($value['type']==1)?'checked="checked"':'';?> onclick="$('#type_ans:checked').attr('checked',false);$(this).attr('checked',true)" />
                    <input type="hidden" name="type_ans[]" value="0" /></td>

                    <td style="background: none;">
                     
                        <input name="random<?php echo $e ?>" id="random<?php echo $e ?>" type="checkbox"  <?php echo ($value['random']==1)?'checked="checked"':'';?>  />
                     
                      </td>
                    </td>
                  </td>

                    <td style="background: none;"><img onclick="
                    deleteAnswer('<?php echo $value['uid']?>',this);" 
                    width="15px" src="<?php echo url::base() ?>themes/admin/pics/b_drop.png"/></td>
                  </tr>
              <?php $k++;$e++; }?>
		  <?php }?>
	</table>
    <div id='addanswer' style=""><img style="width: 15px;margin: 15px;border: solid 1px #ccc;padding: 5px;border-radius: 5px;"  src="<?php echo url::base() ?>themes/admin/pics/icon_plus.png" onclick="addAnswer()"/> </div>
</div>
</td>
<td style="vertical-align: top;padding: 22px;"><div class="yui3-g">
		
        <div class="yui3-u-1-6 right" style="width:15%"><?php echo Kohana::lang('question_frm_lang.lbl_answer_description') ?>:</div>
        <br /><br />
        <div class="yui3-u-4-5">
        <textarea  style="height:150px; width:125%" id="erea_description" name="erea_description" ><?php echo isset($que['answer_description'])?$que['answer_description']:''?></textarea>
        </div>
    </div>
    </td></tr>
</table>
 <table  cellspacing="0" cellpadding="8" style="width:91%;margin-top: 5px;">
 <tr>
    		<td align="right" valign="top" style="width:12%;background:none"><?php echo Kohana::lang('question_frm_lang.lbl_note') ?>: </td>
            <td align="left" style="padding:0px; margin-top:10px;">
            <textarea  class="ckeditor" cols="50"  id="erea_note" name="erea_note" ><?php echo isset($que['note'])?$que['note']:''?></textarea>
             </td>
    </tr>
 	<tr>
    		<td style="width:9%;background:none" align="right" valign="top"><?php echo Kohana::lang('question_frm_lang.lbl_note_ext') ?>: </td>
            <td style="padding:10px 0 0 0" align="left">
            <textarea  class="ckeditor" id="erea_note_extend" name="erea_note_extend"
         cols="50" style="width:40%;height:100px"><?php echo isset($que['note_ext'])?$que['note_ext']:''?></textarea>
             </td>
    </tr>
 </table>  
   
   
</div>
 </div>
</td></tr>
</table>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($que['uid'])?$que['uid']:''?>"/>
<table  cellspacing="0" cellpadding="0" width="100%" style="display:table; float:left">
<tr>
    <td align="center"><?php require('button.php') ?></td>
</tr>
</table>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />

</form>
<script >
	$().ready(function(){
		  if($("#answerno1").length == 0){
		  for(i=0;i< <?php echo !empty($se_numasnwer)?$se_numasnwer - 1:'4';?>;i++)
		  		addAnswer();
		  }
	})
	var row_table = <?php echo $k ?>;
	function addAnswer(){
		row_table +=1;
		 $('#tableanwer').append(
		 '<tr id="answerno1">'+
            '<td style="background: none;"><input style="width: 150px;height: 20px;" name="answer_ans[]" id="answer_ans[]" type="text" /></td>'+
			 '<td style="background: none;"><input style="width: 150px;" name="answer_file'+row_table+'" id="answer_file[]" type="file" /></td>'+
            '<td style="background: none;padding-left:16px"><input name="type_ans[]" id="type_ans" type="checkbox" onclick="$(\'#type_ans:checked\').attr(\'checked\',false);$(this).attr(\'checked\',true)" /><input type="hidden" name="type_ans[]" value="0" /></td>'+
            '<td style="background: none;"><input name="random'+row_table+'" id="random'+row_table+'" type="checkbox" checked="checked"/></td>'+
            '<td style="background: none;"><img onclick="$( this ).parent().parent().remove()" width="15px" src="<?php echo url::base() ?>themes/admin/pics/b_drop.png"/></td>'+
          '</tr>');
		  
	}
</script>
<?php require('frm_js.php');?>
<?php 
		$this->session->delete('se_category_uid');
		$this->session->delete('se_test_uid');
		$this->session->delete('se_status');
		$this->session->delete('se_numasnwer');
?>