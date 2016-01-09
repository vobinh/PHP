
<form id="frm" name="frm" action="<?php echo url::base() ?>admin_questionnaires/sm" method="post" enctype="multipart/form-data">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label">Import <?php  echo Kohana::lang('question_frm_lang.tt_frm_questionnaires') ?></td>
    <td class="title_button"><button type="submit" name="Submit"  class="button"><span>Upload</span></button></td>
</tr>
</table>

<table id="float_table" style="clear:both;" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form" >

<div style="width: 100%;">
	<div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%"><?php echo "Test" ?>:</div>
        <div class="yui3-u-4-5">
       
        <select id="sel_test" name="sel_test" style="width:30%">
        <option></option> 
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
        <div class="yui3-u-1-6 right" style="width:10%">File:</div>
        <div class="yui3-u-4-5">
       <input type="file" name="txt_import" /><br />
        <div id="status-message" style="clear:both;font-size:11px;font-style:italic;color:#af1c37;padding-left:5px;" >File types allowed: xls,xlsx.</div>
      
        
       
        </div>
    </div>
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:10%"></div>
        <div class="yui3-u-4-5">
            <img src="<?php echo url::base() ?>uploads/site/import.png" style="width:90%" />
        </div>
    </div>
    </div>
    
</td></tr>
</table>
<table  cellspacing="0" cellpadding="0" width="100%" style="display:table; float:left">
<tr>
    <td align="center"><button type="submit" name="Submit"  class="button"><span>Upload</span></button></td>
</tr>
</table>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />

</form>
