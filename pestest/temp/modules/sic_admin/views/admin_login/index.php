<?php echo $top?>
<p>&nbsp;</p>
<table class="login" align="center" cellpadding="5" cellspacing="0">
<?php if (!empty($this->site['site_logo'])) { ?>
<tr>
    <td>
    	<table align="left" cellpadding="0" cellspacing="0">
        <tr>
            <td class="login_logo">
                <a target="_blank" href="<?php echo url::base()?>"><img src="<?php echo url::base()?>uploads/site/<?php echo $this->site['site_logo']?>" <?php echo $this->get_thumbnail_size(DOCROOT.'uploads/site/'.$this->site['site_logo'],400,120)?>/></a>
            </td>
		</tr>
		</table>
	</td>
</tr>
<?php } // end if logo empty ?>
<tr class="login_caption">
    <td class="login_caption"><?php echo Kohana::lang('admin_lang.tt_page')?></td>
</tr>
<tr>
	<td><?php echo $error?></td>
</tr>
<tr>
    <td><?php echo $content?></td>
</tr>
<tr>
    <td class="login_footer" align="center"><?php echo Kohana::lang('login_lang.lbl_copyright')?></td>
</tr>
</table>
<script language="javascript">
$('body').css('background', "url(<?php echo $this->site['theme_url']?>pics/bg_login.png)");
</script>
<?php echo $bottom?>