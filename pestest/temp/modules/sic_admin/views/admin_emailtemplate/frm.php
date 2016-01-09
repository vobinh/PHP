<form id="frm" name="frm" action="<?php echo url::base() ?>admin_emailtemplate/save" method="post">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo 'Email Template' ?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Name' ?>:<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_name" id="txt_name" value="<?php echo isset($mr['configuration_title'])?$mr['configuration_title']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
	<div class="yui3-u-1-6 right"> <?php echo Kohana::lang('global_lang.lbl_content')?>:<font color="#FF0000">*</font></div>
	<div class="yui3-u-4-5">
	<textarea class="ckeditor" id="txt_content" name="txt_content" cols="50" style="width:100%;height:100px"><?php echo isset($mr['configuration_value'])?$mr['configuration_value']:''?></textarea>
    </div>
</div>

</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['configuration_id'])?$mr['configuration_id']:''?>"/>
</td></tr>
</table>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>