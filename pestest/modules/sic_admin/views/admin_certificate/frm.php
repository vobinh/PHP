<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>

<form id="frm" name="frm" action="<?php echo url::base() ?>admin_certificate/save" method="post" enctype='multipart/form-data'>
<link rel="stylesheet" href="<?php echo url::base()?>themes/popup/jquery-ui.css">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label">Certificate</td>
    <td class="title_button"><?php require('button.php');?></td>
</tr>
</table>
<div style="position: relative;clear: both; width: 100%;">
  <table cellspacing="0" cellpadding="0" style="width: 800px;margin: auto !important;">
  <tr>
    <td>
      <b>Title<font style="color: red;">*</font>:</b>&nbsp;<input type="text" name="txt_title" id="txt_title" value="<?php echo !empty($certificate['title'])?$certificate['title']:'';?>" placeholder="" style="width:90%;">
    </td>
  </tr>
  <tr>
    <td style="padding-top: 10px;">
      <div style="background: url('<?php echo url::base()?>uploads/site/certificate.png') no-repeat; height: 600px;width: auto; position: relative;">
        <span style="position: absolute;top: 433px;left: 230px;font-size: 21px;font-weight: bold;"><?php echo date('m/d/Y');?></span>
        <input style="position: absolute;top: 433px;left: 390px;width: 200px;" type="text" name="txt_provider_name" id="txt_provider_name" value="<?php echo !empty($certificate['provider_name'])?$certificate['provider_name']:'';?>" placeholder="Certificate of Completion provider name">
        <input style="position: absolute;top: 485px;right: 42px;width: 180px;text-align: center;" type="text" name="txt_course_manager" id="txt_course_manager" value="<?php echo !empty($certificate['course_manager'])?$certificate['course_manager']:'';?>" placeholder="Course manager">
      </div>
    </td>

  </tr>
</table>
</div>

<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($certificate['id'])?$certificate['id']:''?>"/>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php'); ?></td>
</tr>
</table>
</form>
<?php require('frm_js.php'); ?>