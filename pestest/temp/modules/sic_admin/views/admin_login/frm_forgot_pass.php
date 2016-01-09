<form action="<?php echo $this->site['base_url'].uri::segment(1)?>/request_newpass" method="post">
<table width="100%" border="0" cellpadding="5" class="tbl_frm_login">
<tr>
    <td width="30%" align="right"><?php echo Kohana::lang('account_lang.lbl_email')?>&nbsp;<font color="#FF0000">*</font></td>
    <td width="74%">&nbsp;<input name="txt_email" type="text" id="txt_email" size="35" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>"></td>
</tr>
<tr>
    <td align="right" >&nbsp;</td>
    <td>
        <button name="submit2" type="submit" class="button password"><span><?php echo Kohana::lang('login_lang.btn_get_new_pass')?></span></button>
    <button type="button" class="button login" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_login'" /><span><?php echo Kohana::lang('login_lang.btn_login')?></span></button></td>
</tr>
<tr height="45">
    <td  >&nbsp;</td>
    <td  valign="top" >&nbsp;</td>
</tr>
</table>
<script language="javascript">set_default_focus('txt_email');</script>
</form>