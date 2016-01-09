<div class="yui3-g" style="background-color:#5C9CCC; color:#FFF;">
	<strong>&nbsp;<?php echo Kohana::lang('global_lang.lbl_contact')?></strong>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('config_lang.lbl_site_name')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_name" name="txt_name" type="text" size="50" value="<?php echo $this->site['site_name']?>"/></div>
</div>       	
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_phone_fax')?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_phone" name="txt_phone" type="text" value="<?php echo $this->site['site_phone']?>" size="20"/>&nbsp;&nbsp;<input id="txt_fax" name="txt_fax" type="text" value="<?php echo $this->site['site_fax']?>" size="20"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_email')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_email" name="txt_email" type="text" size="50" value="<?php echo $this->site['site_email']?>"/></div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_address')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_address" name="txt_address" type="text" size="50" value="<?php echo $this->site['site_address']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_city')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_city" name="txt_city" type="text" size="50" value="<?php echo $this->site['site_city']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_state')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_state" name="txt_state" type="text" size="50" value="<?php echo $this->site['site_state']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_zipcode')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_zipcode" name="txt_zipcode" type="text" size="50" value="<?php echo $this->site['site_zipcode']?>"/></div>
</div>

<div class="yui3-g" style="background-color:#5C9CCC;color:#FFF;">
	<strong>&nbsp;<?php echo Kohana::lang('global_lang.lbl_site')?></strong>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_logo')?>:</div>
    <div class="yui3-u-4-5">
    	<?php 
            //echo '<pre>';
            //print_r($this->site);
        if ($this->site['site_logo'] == '') { ?>
        <input type="file" name="attach_logo" />
        <button type="submit" class="button upimage" name="up_logo"><span><?php echo Kohana::lang('global_lang.btn_upload')?></span></button>
        <?php } else { ?>
        <img src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>" />
        <button type="button" class="button delete" name="del_logo" onclick="javascript:location.href='<?php echo url::base()?>admin_config/del_logo'"><span><?php echo Kohana::lang('global_lang.btn_delete')?></span></button>
        <?php } // end if ?><p>
        <?php echo Kohana::lang('global_lang.lbl_width')?>:&nbsp;<input type="text" name="txt_width" size="10" value="<?php echo $this->site['site_logo_width']?>"/>&nbsp;
        <?php echo Kohana::lang('global_lang.lbl_height')?>:&nbsp;<input type="text" name="txt_height" size="10" value="<?php echo $this->site['site_logo_height']?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('config_lang.lbl_slogan')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_slogan" name="txt_slogan" type="text" size="50" value="<?php echo $this->site['site_slogan']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_title" type="text" id="txt_title" size="50" value="<?php echo $this->site['site_title']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_keyword')?>:</div>
    <div class="yui3-u-4-5"><input id="txt_keyword" name="txt_keyword" type="text" size="50" value="<?php echo $this->site['site_keyword']?>"/></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_description')?>:</div>
    <div class="yui3-u-4-5"><textarea name="txt_description" cols="40" rows="5"><?php echo $this->site['site_description']?></textarea></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">Enable cart:</div>
    <div class="yui3-u-4-5"><input name="rdo_enable_cart" type="radio" value="1" <?php echo $mr['site_cart']==1?'checked="checked"':''?> >
      Enable 
      <input name="rdo_enable_cart" type="radio" value="0" <?php echo $mr['site_cart']==0?'checked="checked"':''?> >
      Disable</div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">Sub title</div>
    <div class="yui3-u-4-5">
        <p>Using tag h2 and h3</p>
        <textarea class="ckeditor" id="txt_sub_title" name="txt_sub_title" cols="50" style="width:100%;height:100px"><?php echo isset($mr['site_sub_title'])?$mr['site_sub_title']:''?></textarea>
    </div>
</div>
