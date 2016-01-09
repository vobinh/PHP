<form name="frmlist" id="frmlist" action="<?php echo url::base()?>admin_language/search" method="post" >
	<table class="title" align="center" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_label"><?php echo Kohana::lang('language_lang.tt_page')?></td>
        <td align="right">
        	<!--<button class="button new" type="button" onclick="javascript:location.href='<?php echo url::base()?>admin_language/create'">
            <span><?php echo Kohana::lang('language_lang.btn_new_language')?></span>
            </button>-->
       	</td>
      </tr>
    </table>
    <table class="list" align="left" cellspacing="1" cellpadding="5">
      <tr class="list_header">
        <!--<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' ></td>-->
        <td><?php echo Kohana::lang('language_lang.lbl_name')?></td>
        <td width="50" align="center"><?php echo Kohana::lang('language_lang.lbl_code')?></td>
        <td width="50" align="center"><?php echo Kohana::lang('global_lang.lbl_image')?></td>
        <td width="70" align="center"><?php echo Kohana::lang('global_lang.lbl_status')?></td>       
        <!--<td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action')?></td>-->
      </tr>
      <?php for($i=0; $i<count($mlist); $i++){ ?>
      <tr class="row<?php echo $i%2==0?2:''?>">
        <!--<td>
        	<input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['languages_id']?>">
        </td>-->
        <td style="text-align:left;">
        	<?php echo $mlist[$i]['languages_name']?>
        </td>
        <td align="center">
        	<?php echo $mlist[$i]['languages_code']?>
        </td>
        <td align="center">
            	<img src="<?php echo url::base()?>uploads/language/<?php echo $mlist[$i]['languages_image']?>">
        </td>      	
        <td align="center">
		<?php if($mlist[$i]['languages_status'] == 1){ ?>
        <a href="<?php echo url::base()?>admin_language/setstatus/<?php echo $mlist[$i]['languages_id']?>/0" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active')?>" />
        </a>
        <?php } else { ?>    
        <a href="<?php echo url::base()?>admin_language/setstatus/<?php echo $mlist[$i]['languages_id']?>/1" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive')?>" />
        </a>
        <?php } ?>
        </td>
        <!--<td align="center">
       	<a href="<?php echo url::base()?>admin_language/edit/<?php echo $mlist[$i]['languages_id']?>"><img src="<?php echo url::base()?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit')?>" /></a> 
        <a href="<?php echo url::base()?>admin_language/delete/<?php echo $mlist[$i]['languages_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')" ><img src="<?php echo url::base()?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" /></a>
        </td>-->
      </tr>
      <?php }?>
    </table>
<br clear="all" />
    <!--<table class="list_bottom" cellspacing="0" cellpadding="5">
      <tr>
    <td><select name="sel_action" id="sel_action">
            <option value="block"><?php echo Kohana::lang('global_lang.sel_block_selected')?></option>
            <option value="active"><?php echo Kohana::lang('global_lang.sel_active_selected')?></option>           
            <option value="delete"><?php echo Kohana::lang('global_lang.sel_delete_selected')?></option>
          </select>
          &nbsp;
          <button class="button save" onclick="sm_frm(frmlist,'<?php echo url::base()?>admin_category/saveall','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>');">
          <span><?php echo Kohana::lang('global_lang.btn_update')?></span>
          </button>
      </td>
      </tr>
  </table>-->
</form>