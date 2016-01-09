<script>
function mytest(val1){
	$.ajax({
		url:val1,
		type: "GET",
		success: function(data){
			$('#mytest').html(data);
		}
	});
}

function history(val1){
	$.ajax({
		url:val1,
		type: "GET",
		success: function(data){
			$('#history').html(data);
		}
	});
}
$().ready(function(){
	mytest('<?php echo url::base();?>test/dialogmytest');
	//history('<?php echo url::base();?>mypage/testing');
});
</script>
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
	<a href="<?php echo url::base()?>">Home</a> <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo  url::base()?>mypage">  <?php echo Kohana::lang('client_lang.lbl_acc_info')?></a></td>
</tr>
</table>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Account</a></li>
    <li><a href="#tabs-2">Purchase History</a></li>
  </ul>
  <div id="tabs-1">
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
	<a href="<?php echo url::base()?>">Home</a> <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo  url::base()?>mypage">  <?php echo Kohana::lang('client_lang.lbl_acc_info')?></a></td>
</tr>
<tr>
    <td class="frame_content_middle">
        <table border="0" cellspacing="0" cellpadding="3" align="center" width="100%">
        <form method="post" action="<?php echo url::base()?>mypage/update_account" >
        <tr>
            <td width="30%"></td>
            <td><font class="text_error"><?php echo isset($mr['frm_error'])?$mr['frm_error']:''?></font></td>
        </tr>
        <tr style="background-color:#CCC">
        	<td align="center" colspan="2"><strong>Information</strong></td>
        </tr>
       
       <tr>
            <td align="right"><?php echo Kohana::lang('client_lang.lbl_email')?></td>
            <td align="left"><?php echo $mr['member_email']?><input name="txt_email" type="hidden" id="txt_email" value="<?php echo $mr['member_email']?>" size="50" style="width:250px;">
            </td>
        </tr>
         <tr>
            <td align="right"><?php echo Kohana::lang('myaccount_lang.lbl_first_name')?></td>
            <td align="left"><input name="txt_first_name" type="text" id="txt_first_name" value="<?php echo isset($mr['member_fname'])?$mr['member_fname']:''?>" size="50" style="width:250px;"></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('myaccount_lang.lbl_last_name')?></td>
            <td align="left"><input name="txt_last_name" type="text" id="txt_last_name" value="<?php echo isset($mr['member_lname'])?$mr['member_lname']:''?>" size="50" style="width:250px;"></td>
        </tr>
        <tr>
            <td align="right">Company Name</td>
            <td align="left"><input name="txt_company_name" type="text" id="txt_company_name" value="<?php echo $mr['company_name']?>" size="50" style="width:250px;"></td>
        </tr>
       
        
        <tr>
            <td align="right">Sponsor Name</td>
            <td align="left"><input name="txt_contact_name" id="txt_contact_name" type="text"  value="<?php echo $mr['company_contact_name']?>" style="width:250px;" ></td>
        </tr>
       
        <tr>
            <td align="right">Sponsor  E-mail</td>
            <td align="left"><input name="txt_contact_email" type="text" id="txt_contact_email" value="<?php echo $mr['company_contact_email']?>" style="width:250px;" maxlength="50"></td>
        </tr>
     
        <tr>
            <td colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr style="background-color:#CCC">
            <td colspan="2" align="center"><strong><?php echo Kohana::lang('myaccount_lang.lbl_change_pass')?></strong></td>
        </tr>
        <tr>
            <td colspan="2">
                <table width="100%" name="set_new_pass" id="set_new_pass">
                <tr>
                    <td width="30%" align="right"><?php echo Kohana::lang('myaccount_lang.lbl_old_password')?></td>
                    <td align="left"><input type="password" name="txt_old_pass" id="old_pass" autocomplete = "off" /></td>
                </tr>
                <tr>
                    <td align="right"><?php echo Kohana::lang('myaccount_lang.lbl_new_password')?></td>
                    <td align="left"><input type="password" name="txt_new_pass" id="new_pass" autocomplete = "off"/></td>
                </tr>
                <tr>
                    <td align="right"><?php echo Kohana::lang('myaccount_lang.lbl_confirm_password')?></td>
                    <td align="left"><input type="password" name="txt_cf_new_pass" id="cf_new_pass" autocomplete = "off"/></td>
                </tr>
                </table>
            </td>
        </tr>        
     
        <tr>           
            <td align="left" style="text-align:center; padding-right:145px;" colspan="2"><button class="btn_1" type="submit" name="Submit"><?php echo Kohana::lang('client_lang.btn_save')?></button>&nbsp;
            <button type="reset" name="reset" class="btn_1"><?php echo Kohana::lang('client_lang.btn_reset')?></button>&nbsp;
            <button type="button" name="btn_back" class="btn_1" id="btn_back" onclick="location.href='<?php echo url::base().$this->site['history']['back']?>'" /><?php echo Kohana::lang('client_lang.btn_back')?></button></td>
        </tr>
        </form>
        </table>
	</td>
</tr>
<tr><td class="frame_content_bottom">&nbsp;</td></tr>
</table>
</div>
<div id="tabs-2">
<div id='mytest'></div>
<br/>
</div>
