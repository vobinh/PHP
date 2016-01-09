<form id="frm" name="frm" action="<?php echo url::base()?>admin_banner/save" method="post" enctype="multipart/form-data">
<table cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td class="title_label"><?php echo $mr['page_title']?></td>
    <td align="right"><?php require('button.php')?></td>
  </tr>
</table>
<div class="yui3-g form">
<?php if (isset($mr['banner_id'])) { ?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_id')?></div>
    <div class="yui3-u-4-5"><?php echo $mr['banner_id']?></div>
</div>
<?php } // end if ?>
<?php if (empty($mr['banner_name']) || $mr['banner_type'] == 'image') {?>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_image')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    	
    	<?php if (!empty($mr['banner_name'])) { 
		$src = "uploads/banner/".$mr['banner_name'];
		
		$arr = MyImage::thumbnail(300,300,$src);
		
		?>        	
            <img src="<?php echo url::base()?>uploads/banner/<?php echo $mr['banner_name']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):@$maxh?>"/>&nbsp;         
            <button type="button" name="btn_delete" id="btn_delete" class="button delete" onclick="javascript:location.href='<?php echo url::base()?>admin_banner/delete_file/<?php echo $mr['banner_id']?>'"/><span><?php echo Kohana::lang('global_lang.btn_delete') ?></span></button>   		
        <?php } else { ?>
        	<input tabindex="1" type="file" name="attach_image" id="attach_image" onclick="set_type(0,1);">			
		<?php } // end if ?>
    </div>
</div>
<?php } // end if banner file is image ?>

<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_width')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="3" type="text" name="txt_width" size="10" value="<?php echo isset($mr['banner_width'])?$mr['banner_width']:''?>" onkeypress="return numbersonly(this,event)"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_height')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="4" type="text" name="txt_height" size="10" value="<?php echo isset($mr['banner_height'])?$mr['banner_height']:''?>" onkeypress="return numbersonly(this,event)"/></div>
</div>
<!--
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_link')?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input tabindex="5" type="text" name="txt_link" id="txt_link" size="50" value="<?php echo isset($mr['banner_link'])?$mr['banner_link']:''?>"/>      
        (Link: http://www.abc.com)
    </div>
</div>

<div class="yui3-g">
	<div class="yui3-u-1-6 right">Alt:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input tabindex="5" type="text" name="txt_alt" id="txt_alt" size="50" value="<?php echo isset($mr['banner_alt'])?$mr['banner_alt']:''?>"/>      
	</div>
</div>
-->
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo 'Status'?>:</div>
    <div class="yui3-u-4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <select tabindex="6" name="sel_status" id="sel_status">
    		<option value="1" <?php echo isset($mr['banner_status']) && $mr['banner_status']=='1'?'selected':''?>>Active</option>
            <option value="0" <?php echo isset($mr['banner_status']) && $mr['banner_status']=='0'?'selected':''?>>Inactive</option>
          
    </select>      
	</div>
</div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input type="hidden" name="hd_id" id="hd_id" value="<?php echo isset($mr['banner_id'])?$mr['banner_id']:''?>" />
</form>
<?php require('frm_js.php')?>