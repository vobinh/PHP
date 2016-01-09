<table class="header" align="center" cellspacing="0" cellpadding="5">
<tr>
	<?php if (!empty($this->site['site_logo'])) { ?>
    <td class="header_logo">
    	<a target="_blank" href="<?php echo url::base() ?>">
        	<img height="40px" src="<?php echo url::base()?>uploads/site/<?php echo $this->site['site_logo']?>">
		</a>
	</td>
    <?php } // end if ?>
    <td><b><font style="font-family:Geneva; font-size:16px"><?php echo $this->site['site_name'] ?></font></b>
    <font style="font-family:Arial; font-size:13px; font-style:italic"><?php echo $this->site['site_slogan'] ?></font>
    </td>
    <td class="header_login"><?php require('account.php') ?></td>
</tr>
</table>