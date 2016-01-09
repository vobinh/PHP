<form action="<?php echo url::base().$this->uri->segment(1)?>/save_login" method="post">
<table align="center" cellspacing="0" cellpadding="5">
<tr>
    <td width="37%" align="right"><?php echo Kohana::lang('account_lang.lbl_login')?>:</td>
    <td width="63%">
    	<input name="txt_username" type="text" id="txt_username" style="width:12em;" class="username" value="<?php echo isset($mr['txt_username'])?$mr['txt_username']:''?>" autofocus>
	</td>
</tr>
<tr>
    <td align="right"><?php echo Kohana::lang('account_lang.lbl_pass')?>:</td>
    <td><input name="txt_pass" type="password" id="txt_pass" style="width:12em;" class="password" required></td>
</tr>
<tr>
    <td align="right">&nbsp;</td>
    <td><button type="submit" name="submit_login" class="button login"/><span><?php echo Kohana::lang('login_lang.btn_login')?></span></button>          
    <button type="button" class="button home" onclick="javascript:location.href='<?php echo $this->site['base_url']?>'"/><span><?php echo Kohana::lang('login_lang.btn_homepage')?></span></button>
</td>
</tr>
<tr height="45">
    <td>&nbsp;</td>
    <td valign="top"><a href="<?php echo url::base().$this->uri->segment(1)?>/forgot_pass"><?php echo Kohana::lang('account_lang.lbl_forgot_pass')?>?</a></td>
</tr>
</table>
<script language="javascript">set_default_focus('txt_username');</script>
</form>