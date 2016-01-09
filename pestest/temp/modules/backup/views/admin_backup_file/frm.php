<form method="post">
<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
  <tr>
    <td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.tt_page_file')?></td>
    <td align="right"></td>
  </tr>
</table>
<table width="100%" border="0" align="center" style="text-align:center;" cellpadding="0" cellspacing="0" class="form" >
  <tr>
    <td style="text-align:center">Backup data folder.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:center"><button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base()?>admin_backup_file'"/>
      <span>Back to List</span>
      </button>
      &nbsp;
      <button type="button" name="btn_download" class="button download" onclick="javascript:window.location='<?php echo url::base()?><?php if(isset($filename)) echo $filename; ?>'"> <span>Download</span> </button></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;</td>
  </tr>
</table>
</form>
