
<script>
$(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
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
<div id='dialog'>
	
</div>
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> 
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo url::base()?>test/showlist">My Test</a></td>
</tr>
</table>
<table style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px">

<tr class="list_header" style="padding:5px;font-weight:bold">
  	<td align="center"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td align="center">No. of Question</td>
    <td align="center">Testing Time</td>
    <td align="center">Validation Day</td>
    <td align="center">Expiration Date</td>
    <td align="center">Passing Score</td>
    <td align="center"><?php echo 'Price' ?></td>
</tr>

   <?php 
  if(!empty($mr['mlist']) && $mr['mlist']!=false){
  foreach($mr['mlist'] as $id => $list){ ?>
  

 <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo $list['test_title'] ?></td>
  	<td align="right"><?php echo $list['qty_question'] ?></td>
    <td align="right"><?php echo $list['time_value'] ?> <?php echo($list['time_value'] >1)?' minutes':" minute"?></td>
    <td align="right">
	<?php if(isset($list['daytest']) && ($list['daytest']) >0) {?>
	  <?php echo $list['daytest']?><?php echo($list['daytest'] >1)?' days':" day"?>
      <?php }else { ?>
      No limit day
      <?php } ?>
      </td>
    <td align="center">
    <?php if(isset($list['daytest']) && ($list['daytest']) >0) {?>
	 <?php echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']). ' + '.$list['daytest'].' day')) ?>
     <?php } ?>
     </td>
    <td align="right"><?php echo $list['pass_score'] .'%'?></td>
    <td align="right"><?php echo $this->format_currency($list['price'])?></td>
    
  </tr>
  <tr class="tr<?php if($id%2 == 0)  echo 0; else echo 1 ?>">
  <td colspan="8" style="font-weight:bold; background:#ddd;padding:7px;">
  <div style="float:left;text-align:left"><?php echo isset($list['test_description'])?'Description: '.$list['test_description']:'' ?>
  </div>
  <div style="float:right;text-align:right">
  <form id="testagain<?php echo $list['uid']?>" method="post" action="<?php echo url::base() ?>test/testingwrong">
    
     <a><button 
  	 onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php
	 echo base64_encode($list['uid'].text::random('numeric',3)) ?>'"
	 type="button" style="width: 130px;" name="btn_submit" id="btn_submit" class="button"  value="Test Now"><span > Test Now </span></button></a>
     <?php if(!empty($list['list'])){?>
     <input type="hidden" value="<?php echo $list['uid']?>" name="sel_test"/>
     <input type="hidden" value="<?php echo isset($list['list'][0]['test_uid'])?$list['list'][0]['test_uid']:''?>" name="hd_test"/>
     <input type="hidden" value="<?php echo isset($list['list'][0]['testing_code'])?$list['list'][0]['testing_code']:''?>" name="testing_code"/>
     <input type="hidden" value="<?php echo (isset($list['list'][0]['parent_code']))?$list['list'][0]['parent_code']:''?>" name="parent_id"/>
     
     <a <?php 
	 if(!isset($list['scoreparent']))
	 	$list['scoreparent'] = 100;	
	 if((int)$list['scoreparent'] == 100) echo 'style="display:none"' ?> ><button   type="submit" style="width: 130px;" name="btn_missing" id="btn_onlymissing" class="button" ><span >Only Missing</span></button></a>
     <?php  if((int)$list['scoreparent'] != 100) ?>
     <a <?php if(empty($list['category'])) echo 'style="display:none"'; ?> ><button type="button"   style="width: 130px;" name="btn_by_category" id="btn_by_category" class="button" onclick="showdialog('<?php echo($list['list'][0]['testing_code']) ?>','<?php echo $list['list'][0]['test_uid']?>')" ><span >By Category</span></button></a>
     <?php }?>
     </form>
  </div>
  
 </td></tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>

<div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
<script>
	function showdialog(val , id){
		$.ajax({
			url: '<?php echo url::base()?>test/getTestCategory/'+val+'/'+id,
			type: "GET",
			success: function (data) {
				$('#dialog').html(data);
				$( "#dialog" ).dialog( "open" );
			},
			error: function () {
       		 	alert('Category not set');
  		    }
		});
	}
</script>