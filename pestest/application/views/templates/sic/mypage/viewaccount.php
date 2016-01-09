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
	mytest('<?php echo url::base();?>courses/dialogmytest');
	//history('<?php echo url::base();?>mypage/testing');
});
</script>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
          <h3 style="margin-top: 0px;">
            <?php echo Kohana::lang('client_lang.lbl_acc_info')?>
          </h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="tabs" class="main_data_13 box_shadow" style="border: none;border-radius: 0;">
                <ul>
                    <li><a href="#tabs-1">Account</a></li>
                    <li><a href="#tabs-2">Purchase History</a></li>
                </ul>
                <div id="tabs-1" class="main_data_13">
                <form class="form-horizontal" method="post" action="<?php echo url::base()?>mypage/update_account" novalidate="novalidate">
                    <div class="panel-heading" style="border-color: #e6e6e6;padding-bottom: 0;">
                        <label>Information</label>
                    </div>
                    <div class="panel-body col-sm-12 col-md-8 col-md-offset-2">
                    <?php /*?>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <font class="text_error"><?php echo isset($mr['frm_error'])?$mr['frm_error']:''?></font>
                                </div>
                            </div>
                    <?php */?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"><?php echo Kohana::lang('client_lang.lbl_email')?></label>
                                <label class="col-sm-8 control-label" style="text-align: left;">
                                    <?php echo $mr['member_email']?>
                                    <input name="txt_email" type="hidden" id="txt_email" value="<?php echo $mr['member_email']?>" size="50" style="width:250px;">
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="txt_first_name" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_first_name')?></label>
                                <div class="col-sm-8">
                                    <input class="form-control input_frm" name="txt_first_name" type="text" id="txt_first_name" value="<?php echo isset($mr['member_fname'])?$mr['member_fname']:''?>" size="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txt_last_name" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_last_name')?></label>
                                <div class="col-sm-8">
                                    <input class="form-control input_frm" name="txt_last_name" type="text" id="txt_last_name" value="<?php echo isset($mr['member_lname'])?$mr['member_lname']:''?>" size="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txt_company_name" class="col-sm-4 control-label">Company Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control input_frm" name="txt_company_name" type="text" id="txt_company_name" value="<?php echo $mr['company_name']?>" size="50">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txt_contact_name" class="col-sm-4 control-label">Sponsor Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control input_frm" name="txt_contact_name" id="txt_contact_name" type="text"  value="<?php echo $mr['company_contact_name']?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="txt_contact_email" class="col-sm-4 control-label">Sponsor E-mail</label>
                                <div class="col-sm-8">
                                    <input class="form-control input_frm" name="txt_contact_email" onkeypress="return  keyPhone(event)" type="text" id="txt_contact_email" value="<?php echo $mr['company_contact_email']?>" maxlength="50">
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom: 0;">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <input type="checkbox" name="chk_sendmail" id="chk_sendmail" style="width: 16px;height: 16px;vertical-align: middle;margin: 0;" <?php  if($mr['send_mail']==1) echo 'checked="checked"';?> />&nbsp;<span>Disable e-mail notifications from Pestest.com.</span>
                                </div>
                            </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <!-- PASS -->
                    <div class="panel-heading" style="border-color: #e6e6e6;padding-bottom: 0;">
                        <label><?php echo Kohana::lang('myaccount_lang.lbl_change_pass')?></label>
                    </div>
                    <div class="panel-body col-sm-12 col-md-8 col-md-offset-2">
                        <div class="form-group">
                            <label for="old_pass" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_old_password')?></label>
                            <div class="col-sm-8">
                                <input class="form-control input_frm" type="password" name="txt_old_pass" id="old_pass" autocomplete = "off" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_pass" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_new_password')?></label>
                            <div class="col-sm-8">
                                <input class="form-control input_frm" type="password" name="txt_new_pass" id="new_pass" autocomplete = "off"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cf_new_pass" class="col-sm-4 control-label"><?php echo Kohana::lang('myaccount_lang.lbl_confirm_password')?></label>
                            <div class="col-sm-8">
                                <input class="form-control input_frm" type="password" name="txt_cf_new_pass" id="cf_new_pass" autocomplete = "off"/>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                        <div class="col-sm-8 col-sm-offset-4">
                             <button style="padding: 10px 15px;" class="btn btn-success btn_1" type="submit" name="Submit"><?php echo Kohana::lang('client_lang.btn_save')?></button>&nbsp;
                        <button style="padding: 10px 15px;" class="btn btn-default" type="reset" name="reset"><?php echo Kohana::lang('client_lang.btn_reset')?></button>
                        <?php /*?>
                        &nbsp;
                        <button style="padding: 10px 15px;" class="btn btn-success" type="button" name="btn_back"  id="btn_back" onclick="location.href='<?php echo url::base().$this->site['history']['back']?>'" /><?php echo Kohana::lang('client_lang.btn_back')?></button>
                        <?php */?>
                        </div>
                    </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    </form>
                    
                </div>
                <div id="tabs-2" class="main_data_13">
                    <div class="row">
                        <div class="col-sm-12">
                            <div id='mytest'></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12" style="padding-top:10px;">
    <div class="clearfix"></div>    
</div>



<?php /*?>
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
            <td align="left"><input name="txt_contact_email" onkeypress="return  keyPhone(event)" type="text" id="txt_contact_email" value="<?php echo $mr['company_contact_email']?>" style="width:250px;" maxlength="50"></td>
        </tr>
         <tr>
              <td >&nbsp;</td>
              <td  align="left">
                <input type="checkbox" name="chk_sendmail" id="chk_sendmail"   style="width:15px;vertical-align: middle;" <?php  if($mr['send_mail']==1) echo 'checked="checked"';?> />
              <span>Disable e-mail notifications from Pestest.com.</span></td>
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
<?php */?>
