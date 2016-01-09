<form id="frm" name="frm" action="<?php echo url::base()?>admin_myaccount/save" method="post">
<table cellspacing="0" cellpadding="0" class="title">
<tr>
    <td class="title_label"><?php echo Kohana::lang('account_lang.tt_my_account')?></td>
    <td align="right"><?php require('button.php')?></td>
</tr>
</table>
<div class="yui3-g form">
  <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_username')?>:</div>
    <div class="yui3-u-4-5"><?php echo isset($mr['administrator_username'])?$mr['administrator_username']:''?></div>
  </div>
  <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_old_pass')?>:</div>
    <div class="yui3-u-4-5"><input tabindex="1" type="password" name="txt_old_pass" id="txt_old_pass" value="" size="50" autofocus /></div>
  </div>
  <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_new_pass')?>:</div>
    <div class="yui3-u-4-5"><input tabindex="2" type="password" name="txt_new_pass" id="txt_new_pass" size="50"/></div>
  </div>
  <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_cf_new_pass')?>:</div>
    <div class="yui3-u-4-5"><input tabindex="3" type="password" name="txt_cf_new_pass" id="txt_cf_new_pass" size="50"/></div>
  </div>
  <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email')?>: <font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input tabindex="4" type="text" name="txt_email" id="txt_email" value="<?php echo isset($mr['administrator_email'])?$mr['administrator_email']:''?>" size="50" /></div>
  </div>
  <div class="yui3-g center"><?php require('button.php')?></div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['user_id'])?$mr['user_id']:''?>"/>
</form>
<?php require('frm_js.php')?>