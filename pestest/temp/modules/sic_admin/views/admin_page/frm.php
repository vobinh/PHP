<form id="frm" name="frm" action="<?php echo url::base().uri::segment(1)?>/save" method="post">
<table cellspacing="0" cellpadding="0" class="title">
<tr >
    <td width="200" class="title_label"><?php echo $title?>
	</td>
    <td align="right"><?php require('button.php')?></td>        
</tr>
</table>
<div id="tabs">
<ul>
    <li><a href="#tabs-info"><span><?php echo Kohana::lang('global_lang.lbl_info')?></span></a></li>
	<li><a href="#tabs-seo"><span><?php echo Kohana::lang('global_lang.lbl_seo')?></span></a></li>
</ul>
<div class="yui3-g form">
<div id="tabs-info"><?php require('frm_info.php')?></div>
<div id="tabs-seo"><?php require('frm_seo.php')?></div>
<div class="yui3-g center"><?php require('button.php')?></div>
</div>
</div>
<input type="hidden" name="hd_id" value="<?php echo isset($mr['page_id'])?$mr['page_id']:''?>" />
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
</form>
<?php require('frm_js.php')?>