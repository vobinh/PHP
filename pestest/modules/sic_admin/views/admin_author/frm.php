<form id="frm" name="frm" action="<?php echo url::base() ?>admin_author/save" method="post">
<? //print_r($mlist[0]['administrator_email']);die();?>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label">Author</td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_fname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_fname" id="txt_fname" value="<?php echo isset($mr['fname'])?$mr['fname']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_lname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_lname" id="txt_lname" value="<?php echo isset($mr['lname'])?$mr['lname']:''?>" size="30" />
    </div>
</div>
<?php /*?><div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_username') ?>: <font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_username" id="txt_username" value="<?php echo isset($mlist[0]['administrator_username'])?$mlist[0]['administrator_username']:''?>" size="30" onkeypress="return isUsername(this, event)" autofocus />
    </div>
</div><?php */?>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email') ?>:&nbsp;<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input tabindex="3" type="text" name="txt_email" id="txt_email" value="<?php echo isset($mr['email'])?$mr['email']:''?>" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">
	<?php if (!isset($mr['uid'])) { ?>
        <?php echo Kohana::lang('account_lang.lbl_pass')?>:&nbsp;<font color="#FF0000">*</font>
    <?php } else { ?>
        <?php echo Kohana::lang('account_lang.lbl_new_pass')?>:
    <?php } ?>
    </div>
    <div class="yui3-u-4-5"><input tabindex="2" name="txt_pass" type="password" id="txt_pass" value="" size="30"></div>
</div>

<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_status_access') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="4" name="sel_status" id="sel_status">
        <option value="1">Active</option>
        <option value="0"  <?php echo (isset($mr['status']) && $mr['status']==0)?'selected="selected"':''?>>Inactive</option>
         
    </select>
    </div>
</div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['uid'])?$mr['uid']:''?>"/>
<input name="hd_id_author" type="hidden" id="hd_id_author" value="<?php echo isset($mr['uid'])?$mr['uid']:''?>"/>
</td></tr>
</table>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>