<table name="forgotpass" width="100%" align="center" cellspacing="0" cellpadding="0" border="0" class="forgotpass">
<tr>
    <td class="forgotpass_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;"><?php echo Kohana::lang('login_lang.lbl_forgotpass') ?>&nbsp;</td>
</tr>
</table>
<?php if(isset($mr->member_fname)) {?>
<!--<p><b>Dear <?php echo !empty($mr->member_fname)?$mr->member_fname.' '.$mr->member_lname:$mr->member_lname?></b>,</p> //-->
<?php } ?>
<p>Your new password has been sent to your e-mail successfully.  If you do not receive the e-mail within the next 30 minutes, please check your junk-email  folder just in case the email was routed to your spam folder. If you still do  not receive your new password, please contact us using the contact form on our  site.</p>
<p>
Thank you,<br />
<?php echo $this->site['site_name']?>  
</p>
<br />
<div align="center">
  <button type="button" name="btn_back" class="btn_1" onclick="location.href='<?php echo $this->site['base_url']?>'"><span><?php echo Kohana::lang('login_lang.btn_homepage') ?></span></button>
</div>