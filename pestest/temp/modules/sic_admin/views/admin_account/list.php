<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_account/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('account_lang.tt_admin_account') ?></td>
    <td class="title_button">
    <?php if ($this->sess_admin['level'] == 1) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_account/create'"/>
    <span><?php echo Kohana::lang('account_lang.btn_new_account') ?></span>
    </button>
    <?php } // end if level super admin ?>
    </td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
  	<td><?php echo Kohana::lang('account_lang.lbl_username') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_email') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_level') ?></td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
  <?php if ($this->sess_admin['level'] == 1 || $list['administrator_level'] != 1) { ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['administrator_id']?>">
  	<td align="center"><?php echo $list['administrator_username']?></td>
    <td align="center"><?php echo $list['administrator_email'] ?></td>
    <td align="center">
	<?php if ($list['administrator_level'] == 1) { ?>
		<font color="#FF0000"><?php echo Kohana::lang('account_lang.lbl_super_admin')?></font>
    <?php } else { ?>
		<font color="#0000FF"><?php echo Kohana::lang('account_lang.lbl_admin')?></font>
	<?php } // end if ?>
    <td align="center">
    <?php if ($list['administrator_id'] != $this->sess_admin['id'] && $this->sess_admin['level'] == 1) { ?>
    <a href="<?php echo url::base()?>admin_account/setstatus/<?php echo $list['administrator_id']?>"><?php } // end if ?>
    <?php if($list['administrator_status'] == 1){ ?>	            
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	 
	<?php } else { ?> 	
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
	<?php } ?>
    <?php if ($list['administrator_id'] == $this->sess_admin['id']) { echo '</a>'; } // end if ?>
    </td> 
    <td align="center">
    <?php if ($list['administrator_id'] == $this->sess_admin['id'] || $this->sess_admin['level'] == 1) { ?>     
    <a href="<?php echo url::base() ?>admin_account/edit/<?php echo $list['administrator_id'] ?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
    <?php } // end if ?>
    <?php if ($this->sess_admin['level'] == 1 && $list['administrator_id'] != $this->sess_admin['id']) { ?>
    <a id="delete_<?php echo $list['administrator_id']?>" href="javascript:delete_admin(<?php echo $list['administrator_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    <?php } // end if ?>
    </td>
  </tr>
  <?php } // end if ?>
  <?php } } // end foreach ?>
</table>
</form>
<div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_account/display">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onchange="document.frm_display.submit();">
	<option value="">---</option>
    <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
    <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
    <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
    <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
    <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
</select>
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</form>
</div>
<?php require('list_js.php')?>