<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>contact/sm" method="post">
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
 <tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a>
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a>Contact Us</a></td>
 </tr>
</table>
<table class="contact" align="center" cellpadding="5" cellspacing="0" width="100%">
<?php /*?><tr>
    <td class="contact_title" align="left" style="font-size:24px;padding:10px;"><strong>Contact Us</strong></td>
</tr><?php */?>
<?php /*?><tr>	
    <td align="center" class="contact_content">
        <table class="contact_info">
        <?php if(!empty($this->site['site_address'])){ ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_address')?></b> :</td>
            <td align="left"><?php echo $this->site['site_address']?> <?php echo $this->site['site_city'] ?>, <?php echo $this->site['site_state'] ?>&nbsp;<?php echo $this->site['site_zipcode'] ?></td>
        </tr>
        <?php } ?>
         <?php if(!empty($this->site['site_phone'])){ ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_phone')?></b> :</td>
            <td align="left"><?php echo $this->site['site_phone']?></td>
        </tr>
        <?php } ?>
        <?php if(!empty($this->site['site_fax'])){ ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_fax')?></b> :</td>
            <td align="left"><?php echo $this->site['site_fax']?></td>
        </tr>
        <?php } ?>
         <?php if(!empty($this->site['site_email'])){ ?>
        <tr>
            <td width="37%" align="right"><b><?php echo Kohana::lang('account_lang.lbl_email')?></b> :</td>
            <td align="left"><?php echo $this->site['site_email']?></td>
        </tr>
       <?php } ?>
        </table>    
    </td>
</tr>
<tr>
    <td align="center" class="contact_title" style="font-size:24px;padding:10px;">
        <strong><?php echo Kohana::lang('customer_lang.lbl_customer')?></strong>
    </td>
</tr><?php */?>
<tr>
    <td align="left"  class="contact_content">
        <table class="contact_form" style="width:100%;">
        <tr>
            <td width="10%" align="right"><?php echo Kohana::lang('account_lang.lbl_name') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" name="txt_name" id="txt_name" size="45" value="<?php echo isset($mr['txt_name'])?$mr['txt_name']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_email') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" onkeypress="return  keyPhone(event)" name="txt_email" id="txt_email" size="45" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_phone') ?></td>
            <td align="left"><input type="text" name="txt_phone" id="txt_phone" size="45" value="<?php echo isset($mr['txt_phone'])?$mr['txt_phone']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('global_lang.lbl_subject') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" name="txt_subject" id="txt_subject" style="width:65%;" value="<?php echo isset($mr['txt_subject'])?$mr['txt_subject']:''?>" /></td>
        </tr>
        <tr>
            <td align="right" valign="top">Message&nbsp;<font color="#FF0000">*</font></td>
            <td align="left">
                <textarea style="height:180px;width:90%;" id="txt_content" name="txt_content"  rows="20"><?php isset($mr['txt_content'])?$mr['txt_content']:''?></textarea>
            </td>
        </tr>       
        <tr>
           <td align="right" valign="top"></td>
            <td align="left"><button type="submit" name="submit"><?php echo Kohana::lang('global_lang.btn_send') ?></button>
            &nbsp;<button id="reset" name="reset" type="reset"><?php echo Kohana::lang('global_lang.btn_reset') ?></button>
            </td>
        </tr>
        </table>    
    </td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>