<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_payment_method/save" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Payment' ; ?></td>
     <td class="title_button"><button type="submit" name="btn_save" class="button save">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button></td>
  </tr>
</table>
<table align="center" cellspacing="0" cellpadding="5">
 <?php /*?> <tr>
    <td style="border-bottom:1px solid #666"><strong><?php echo isset($mr_aut['payments_method_name'])?$mr_aut['payments_method_name']:''?></strong></td>
    <td style="border-bottom:1px solid #666">&nbsp;</td>
  </tr>
  <?php /*?><tr>
    <td width="15%"><?php echo Kohana::lang('payment_method_lang.lbl_code')?>: <font color="#FF0000">*</font></td>
    <td><input type="text" id="txt_aut_code" name="txt_aut_code" readonly="readonly" value="<?php echo isset($mr_aut['payments_method_code'])?$mr_aut['payments_method_code']:''?>" size="5"></td>
  </tr>
  <tr>
    <td><?php echo Kohana::lang('payment_method_lang.lbl_name')?>: <font color="#FF0000">*</font></td>
    <td><input type="text" id="txt_aut_name" name="txt_aut_name" value="<?php echo isset($mr_aut['payments_method_name'])?$mr_aut['payments_method_name']:''?>" size="50"></td>
  </tr>
  <?php */?>
  <tr>
    <td  width="15%"><?php echo Kohana::lang('payment_method_lang.lbl_api_login')?>: <font color="#FF0000">*</font></td>
    <td><input type="text" id="txt_aut_api_login" name="txt_aut_api_login" value="<?php echo isset($mr_aut['api_login'])?$mr_aut['api_login']:''?>" size="50">
      <strong>HVMk1w00u0</strong> (<?php echo Kohana::lang('payment_method_lang.lbl_test')?>) </td>
  </tr>
  <tr>
    <td><?php echo Kohana::lang('payment_method_lang.lbl_transaction_login')?>: <font color="#FF0000">*</font></td>
    <td><input type="text" id="txt_aut_transaction_key" name="txt_aut_transaction_key" value="<?php echo isset($mr_aut['transaction_key'])?$mr_aut['transaction_key']:''?>" size="50">
      <strong>3pgV34H1VlOx</strong> (<?php echo Kohana::lang('payment_method_lang.lbl_test')?>) </td>
  </tr>
  <tr>
    <td><?php echo Kohana::lang('payment_method_lang.lbl_server')?>: <font color="#FF0000">*</font></td>
    <td><select name="sel_aut_post_url" id="sel_aut_post_url">
        <option value="https://sandbox.paymentsgateway.net/swp/co/default.aspx"><?php echo Kohana::lang('payment_method_lang.lbl_test')?></option>
        <option value="https://swp.paymentsgateway.net/co/default.aspx" <?php if(isset($mr_aut['post_url']) && $mr_aut['post_url'] == 'https://swp.paymentsgateway.net/co/default.aspx') echo 'selected'?>  ><?php echo Kohana::lang('payment_method_lang.lbl_production')?></option>
      </select></td>
  </tr>
  <tr>
    <td valign="top"><?php echo Kohana::lang('payment_method_lang.lbl_sample_credit_card')?>:</td>
    <td valign="top"><strong>Visa</strong>: 4007000000027, 4012888818888 <br>
      <strong>American Express</strong>: 370000000000002 <br>
      <strong>Discover</strong>: 6011000000000012 <br>
      <strong>JCB</strong>: 3088000000000017 <br>
      <strong>Diners Club/ Carte Blanche</strong>: 38000000000006
      </td>
  </tr>
  <tr>
    <td>Status:</td>
    <td><select name="sel_aut_status" id="sel_aut_status">
      <option value="0"><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>
      <option value="1" <?php if(isset($mr_aut['payments_method_status']) && $mr_aut['payments_method_status']==1) echo 'selected'?> ><?php echo Kohana::lang('global_lang.lbl_active') ?></option>
      </select>
    <input type="hidden" name="hd_aut_id" id="hd_aut_id" value="<?php echo isset($mr_aut['payments_method_id'])?$mr_aut['payments_method_id']:''?>"/>
    <input type="hidden" name="hd_aut_api_login" id="hd_aut_api_login" value="<?php echo isset($mr_aut['api_login'])?$mr_aut['api_login']:''?>">  
    </td>
  </tr>
</table>
</form>
