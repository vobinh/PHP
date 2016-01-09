<form id="frm" name="frm" action="<?php echo url::base()?>admin_config/save" method="post" enctype="multipart/form-data">
<table id="float_table" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('config_lang.tt_page')?></td>
	<td align="right">
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <?php require('button_index.php')?>
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
<?php */?></ul>
<div id="tabs-information">
<?php require('information.php')?>
</div>
<div id="tabs-setting">
<?php // require('setting.php')?>
</div>
</div>
</div>
</div>
<div align="center"><?php require('button_index.php')?></div>
</form>
<?php require('index_js.php')?>