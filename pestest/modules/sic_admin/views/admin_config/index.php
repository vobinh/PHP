<form id="frm" name="frm" action="<?php echo url::base()?>admin_config/save" method="post" enctype="multipart/form-data">
<table id="float_table" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('config_lang.tt_page')?></td>
	<td align="right">
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <?php require('button_index.php');?>
    </td>
    <?php }//edit,delete?>
  </tr>
</table>
<div class="yui3-g form">
	<div class="yui3-g"> 
		<div id="tabs">
			<ul>
			    <li><a href="#tabs-information"><span><?php echo Kohana::lang('global_lang.lbl_info')?></span></a></li>
			<?php /*?>    <li><a href="#tabs-setting"><span><?php echo Kohana::lang('config_lang.lbl_setting')?></span></a></li>
			<?php */?>
				<li><a href="#tabs-mailgun"><span><?php echo 'MailGun';?></span></a></li>
				<li><a href="#tabs-s3"><span>S3 AWS</span></a></li>
				<li><a href="#tabs-stripe"><span>Payment Stripe</span></a></li>
				<li><a href="#tabs-bg"><span>Background</span></a></li>
				<li><a href="#tabs-about"><span>About US</span></a></li>
			</ul>
			<div id="tabs-information">
				<?php require('information.php');?>
			</div>

			<div id="tabs-setting">
				<?php // require('setting.php')?>
			</div>
			<div id="tabs-mailgun">
				<?php require('form_mailgun.php');?>
			</div>
			<div id="tabs-s3">
				<?php require('form_s3.php');?>
			</div>
			<div id="tabs-stripe">
				<?php require('frm_stripe.php');?>
			</div>
			<div id="tabs-bg">
				<?php require('list_bg.php');?>
			</div>
			<div id="tabs-about">
				<?php require('about_us.php');?>
			</div>
		</div>
	</div>
</div>
<div align="center"><?php require('button_index.php');?></div>
</form>
<div style="display:none;" id="div_add_bg">
    <form id="frm_bg" name="frm_bg" action="<?php echo url::base()?>admin_config/save_bg" method="post" enctype="multipart/form-data" >
        <div>
            <div id="div_img" style="width:100%;min-height: 200px;webkit-box-shadow: 0px 5px 20px 0px gray;-moz-box-shadow: 0px 5px 20px 0px gray;box-shadow: 0px 5px 20px 0px gray;">
                
            </div>
            <div style="padding-top: 15px;padding-bottom: 15px;">
            	Opacity(from 0.x to 1):&nbsp;<input type="text" name="txt_img_opacity" id="txt_img_opacity" value="" placeholder="" style="width: 130px;text-align: right;"><br>
                <input id="uploadFile" type="file" name="uploadFile" value="" placeholder="" style="margin-top: 5px;">
            </div>
        </div>
        <div style="text-align: center;">
            <button id="btn_save" style="padding-left: 50px;padding-right: 50px;" type="button">Save</button>
            <input type="hidden" name="txt_hd_id" id="txt_hd_id" value="">
        </div>
    </form>
</div>
<?php require('index_js.php');?>