<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_configuration/search" method="post">
<table id="float_table" class="title" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('configuration_lang.tt_page')?></td>
    <td align="right"><?php require('button.php')?></td>
  </tr>
</table>
<table class="list_top" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    &nbsp;
    <button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
  </tr>
</table> 
<table class="list" align="left" cellspacing="1" cellpadding="5">
  <tr class="list_header">
    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' ></td>
    <td><?php echo Kohana::lang('configuration_lang.lbl_title')?></td>
    <td><?php echo Kohana::lang('configuration_lang.lbl_key')?></td>
    <td align="center"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td width="100" align="center"><?php echo Kohana::lang('configuration_lang.lbl_desc')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
  </tr> 
   <?php for($i=0; $i<count($mlist); $i++){ ?>
   <tr class="row<?php echo $i%2==0?2:''?>">
    <td align="center"><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['configuration_id']?>">
    <input type="hidden" id="hd_id[]" name="hd_id[]" value="<?php echo $mlist[$i]['configuration_id']?>">
    </td>
    <td align="center"><input id="txt_title[]" name="txt_title[]" value="<?php echo $mlist[$i]['configuration_title']?>" size="35"/></td>
    <td align="center"><input id="txt_key[]" name="txt_key[]" value="<?php echo $mlist[$i]['configuration_key']?>" size="45"/></td>
    <td align="center"><input class="text_number" id="txt_value[]" name="txt_value[]" value="<?php echo $mlist[$i]['configuration_value']?>" size="8" maxlength="500"/></td>
    
    <td align="center"><input id="txt_desc[]" name="txt_desc[]" value="<?php echo $mlist[$i]['configuration_description']?>" size="20"/></td>
    <td align="center">
    	<a onclick="sm_form(frmlist,'<?php echo $this->site['base_url']?>admin_configuration/save_list/<?php echo $i?>')">
        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_save.png" title="Save" />
        </a>
        <a href="<?php echo $this->site['base_url']?>admin_configuration/delete/<?php echo $mlist[$i]['configuration_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="Delete" />
        </a>
    </td>
  </tr>
  <?php  } ?>
</table>
<div align="center"><?php require('button.php')?></div>
</form>
<div class='pagination'><?php if(isset($this->pagination)) echo $this->pagination ?></div>
<?php require('list_js.php')?>