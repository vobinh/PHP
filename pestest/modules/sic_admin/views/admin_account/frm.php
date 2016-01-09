<form id="frm" name="frm" action="<?php echo url::base() ?>admin_account/save" method="post">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo Kohana::lang('account_lang.tt_admin_account') ?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_fname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_fname" id="txt_fname" value="<?php echo isset($mr['administrator_fname'])?$mr['administrator_fname']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_lname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_lname" id="txt_lname" value="<?php echo isset($mr['administrator_lname'])?$mr['administrator_lname']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_username') ?>: <font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_username" id="txt_username" value="<?php echo isset($mr['administrator_username'])?$mr['administrator_username']:''?>" size="30" onkeypress="return isUsername(this, event)" autofocus />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">
	<?php if (!isset($mr['administrator_id'])) { ?>
        <?php echo Kohana::lang('account_lang.lbl_pass')?>:&nbsp;<font color="#FF0000">*</font>
    <?php } else { ?>
        <?php echo Kohana::lang('account_lang.lbl_new_pass')?>:
    <?php } ?>
    </div>
    <div class="yui3-u-4-5"><input tabindex="2" name="txt_pass" type="password" id="txt_pass" value="" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email') ?>:&nbsp;<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input tabindex="3" type="text" name="txt_email" id="txt_email" value="<?php echo isset($mr['administrator_email'])?$mr['administrator_email']:''?>" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_role') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="4" name="sel_role" id="sel_role">
        <option value="1" >Superadmin</option>
         <option value="2" <?php echo (isset($mr['administrator_level']) && $mr['administrator_level']==2)?'selected="selected"':''?>>Admin</option>
    </select>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_status_access') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="4" name="sel_status" id="sel_status">
        <option value="1">Active</option>
        <option value="0"  <?php echo (isset($mr['administrator_status']) && $mr['administrator_status']==0)?'selected="selected"':''?>>Inactive</option>
         
    </select>
    </div>
</div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['administrator_id'])?$mr['administrator_id']:''?>"/>
</td></tr>
</table>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>