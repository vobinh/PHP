<script language="javascript" src="<?php echo $this->site['base_url']?>js/jquery/jquery.js"></script>
<script>
$().ready(function(){
	$('#noticebox').show('slow');
	$('#noticebox').delay(3000).hide('slow');
})
</script>
</br>
<?php if(isset($insert)){?>
	<div id='noticebox' style="position: fixed;right: 50%;;background: #E0F7BE;padding: 10px;border-radius: 5px;top: 0;
    color: #5D45DA;font-size: 13px; display:none"><?php
    	echo $insert;
	?></div>
<?php }?>
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> 
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo  url::base()?>test/start/<?php echo isset($mr['uid'])?$mr['uid']:"" ?>">Start</a>
    </td>
</tr>
</table>
<?php $category_model = new Category_Model(); $parent_category =""; ?>
    <?php if(isset($mr['test_title'])) {?>
    <form name="catfrm" id="catfrm" action="<?php echo $this->site['base_url']?>test/testing" method="post">

    <div style="clear:both; width:100%">
    <table cellspacing="5" cellpadding="5" class="testheader" width="100%" align="center" style="margin-bottom: 15px;">
    	<tr>
        	<td class="title">Name: <?php echo isset($mr['test_title'])?$mr['test_title']:"" ?></td>
            <td  class="qty">Qty: <?php echo isset($mr['qty_question'])?$mr['qty_question']:"" ?></td>
             <td  class="timeout">Time: <?php
			   if(isset($mr['time_value']) && $mr['time_value'] ==1 ) echo $mr['time_value'].' minute';
			   else if(isset($mr['time_value']) && $mr['time_value'] >1 ) echo $mr['time_value'].' minutes';
			   else  echo 'No Limit';
			  ?> </td>
        </tr>
        <tr>
        	<td valign="middle" colspan="3" class="description"><?php echo isset($mr['test_description'])?$mr['test_description']:"" ?></td>
        </tr>
        <?php if(isset($mlist_cate) && count($mlist_cate)>0) {?>
        <tr>
        	<td valign="middle" colspan="3" class="description" align="left"><h3>Category</h3>
             <?php 
			 $totalpercent = 0; 
			 for($i=0;$i<count($mlist_cate);$i++) {
			 $totalpercent += (int)$mlist_cate[$i]['category_percentage'];
			 ?>
               <?php 
			    
				 $this->db->where('category.uid',$mlist_cate[$i]['parent_id']);
		         $mr_parent = $category_model->get();
				echo isset($mr_parent[0]['category']) && $parent_category != $mr_parent[0]['category']?'<div style="font-size:20px;padding-left:10px;width:100%;clear:both;"><p>'.$mr_parent[0]['category'].'</p> </div>':"";
				if(isset($mr_parent[0]['category']))$parent_category = $mr_parent[0]['category'];
				 ?>
               <?php if($mlist_cate[$i]['category_percentage'] >0) {?>
                  <div style="padding:10px 1%;float:left;width:31%;font-size:16px;"><?php echo $mlist_cate[$i]['category'] ?> : <?php echo isset($mlist_cate[$i]['category_percentage'])?$mlist_cate[$i]['category_percentage'].'%':"" ?></div>
             <?php } ?>
             <?php } ?>
            </td>
            
        </tr>
      
        <tr>
        	<td align="center" valign="middle" colspan="3">
             <button  <?php if($totalpercent == 0) echo 'style="display:none"';?>  type="submit" name="btn_submit" id="btn_submit" class="button"><span>Start</span></button>
            </td>
        </tr>
        <?php } else {?>
        <tr>
        	<td align="center" valign="middle" colspan="3">
             <button type="submit" name="btn_submit" id="btn_submit" class="button"><span>Start</span></button>
            </td>
        </tr>
        <?php }?>
    </table>
    <input type="hidden" name="sel_test" id="sel_test" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" />
    </div>
</form>	
<?php } ?>
<script>
$(function() {
    $('#sel_test').change(function() {
        document.forms['catfrm'].submit();
    });
});
</script>

 