<form name="frmlist" id="frmlist" action="<?php echo url::base()?>admin_language/save" method="post" enctype="multipart/form-data" >
	<table class="title" cellspacing="0" cellpadding="0">
        <tr>
            <td class="title_label"><?php echo Kohana::lang('language_lang.tt_page')?></td>
            <td class="title_button">
            <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_language'">
            <span><?php echo Kohana::lang('global_lang.btn_back')?></span>
            </button>
            <button type="reset" name="btn_reset" class="button reset"/>
            <span><?php echo Kohana::lang('global_lang.btn_reset')?></span>
            </button>
            <button type="submit" name="btn_save" class="button save">
            <span><?php echo Kohana::lang('global_lang.btn_save')?></span>
            </button>
        </tr>
    </table>
	<table class="form" align="center" cellspacing="0" cellpadding="5">
    	<tr>	
        	<td width="10%"><?php echo Kohana::lang('language_lang.lbl_name')?>: <font color="#FF0000">*</font></td>
            <td>
            	<input value="<?php echo isset($mr)?$mr['languages_name']:''?>" id="txt_name" name="txt_name" />
            </td>
        </tr>
        <tr>
        	<td><?php echo Kohana::lang('language_lang.lbl_code')?>: <font color="#FF0000">*</font></td>
            <td><input value="<?php echo isset($mr)?$mr['languages_code']:''?>" id="txt_code" name="txt_code"/></td>
        </tr>        
        <tr>
        	<td valign="top"><?php echo Kohana::lang('language_lang.lbl_image')?>:</td>
            <td><input type="file" id="txt_image" name="txt_image"/>
        	<?php if (isset($mr['languages_image']) && $mr['languages_image']) {?>
            	<p>
                <img src="<?php echo url::base()?>uploads/language/<?php echo isset($mr)?$mr['languages_image']:''?>">
                <a href="<?php echo url::base()?>admin_language/delete_image/<?php echo $mr['languages_id']?>"><?php echo Kohana::lang('global_lang.btn_delete')?></a>
        	<?php }?>
            <input type="hidden" id="hd_old_image" name="hd_old_image" value="<?php echo isset($mr)?$mr['languages_image']:''?>" />
        	</td>
        </tr>
        <!--<tr>
        	<td><?php //echo Kohana::lang('language_lang.lbl_dir')?>:</td>
            <td><input id="txt_directory" name="txt_directory" value="<?php //echo isset($mr)?$mr['directory']:''?>" /></td>
        </tr>-->
        <tr>
        	<td><?php echo Kohana::lang('global_lang.lbl_order')?>:</td>
            <td><input id="txt_order" name="txt_order" class="text_number" size="10" value="<?php echo isset($mr)?$mr['languages_sort_order']:''?>" /></td>
        </tr>
        <tr>
	  	<td><?php echo Kohana::lang('global_lang.lbl_status')?>:</td>
        <td>
        <select name="sel_status" id="sel_status">
            <option value="0"><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
          	<option value="1" <?php if(isset($mr) && $mr['languages_status']==1) echo 'selected'?> ><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        </select>
        </td>
	</tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="hidden" id="hd_id" name="hd_id" value="<?php echo isset($mr)?$mr['languages_id']:''?>"></td>
        </tr>
    </table>
</form>