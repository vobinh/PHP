<form method="post">
<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
	<tr>
    	<td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.tt_page_db')?></td>
        <td align="right"> 
        <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_backup_db'"/>
            <span>Back to List</span>
            </button>
        </td>
    </tr>
</table>
<table width="1000" border="0" align="center" style="text-align:center;border-top:none;background-color:#FAFAFA;border:1px solid #D0D0D0;" cellpadding="0" cellspacing="0">
<tr><td>&nbsp;&nbsp;</td></tr>
<tr>
    <td align="center">
    <?php if(isset($mr['file'])){ ?>
    <button type="button" name="btn_download" class="button" onclick="javascript:window.location='<?php echo url::base()?>/uploads/dbbackup/		<?php if(isset($mr['file'])) echo $mr['file']; ?>'">
             <span><?php echo Kohana::lang('backup_restore_lang.btn_download')?></span>
    </button>
    <?php } ?>
	</td>
</tr>
<tr><td>&nbsp;&nbsp;</td></tr>
</table>
</form>
