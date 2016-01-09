<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_backup_file/restore" method="post" enctype="multipart/form-data">

<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
	<tr>
    	<td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.tt_page_file')?></td>
        <td align="right"> 
       <button type="button" name="btn_backup" class="button save" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_backup_file/backup'"/>
    <span><?php echo Kohana::lang('backup_restore_lang.btn_backup_file')?></span>    </button>
        </td>
    </tr>
</table>
<table cellspacing="1" cellpadding="5" class="list">
 <tr class="list_header">
 	<td><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' ></td>
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_date_time')?></td>
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_file_name')?></td>
    <td><?php echo Kohana::lang('backup_restore_lang.lbl_size')?></td>
     
    <td width="80" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_type')?></td>
     <td width="150" align="center"><?php echo Kohana::lang('backup_restore_lang.lbl_action')?></td>
 </tr>
 <?php 
	function Size($path)
	{
	$bytes = sprintf('%u', filesize($path));
	
	if ($bytes > 0)
	{
		$unit = intval(log($bytes, 1024));
		$units = array('B', 'KB', 'MB', 'GB');
	
		if (array_key_exists($unit, $units) === true)
		{
			return sprintf('%d %s', $bytes / pow(1024, $unit), $units[$unit]);
		}
	}
	
	return $bytes;
	}
 $dir='backups/';
 $dirhead=false;
 
	 foreach($arrayFile as $dirfile)	
	 { 
			
			if ($dirfile['file'] != "." && $dirfile['file'] != ".." && $dirfile['file']!=basename($_SERVER["SCRIPT_FILENAME"]))
			{
			if (!$dirhead){$dirhead=true;}
			$filenameArr = explode('.',$dirfile['file']);
			$filename = $filenameArr[0];
				
?>
			
			<tr class="row2">
            	<td align="center"><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $filename?>"></td>
                 <td align="center"><?php  echo date("m-d-Y H:i:s",filemtime($dir.$dirfile['file'])) ;?></td>
            	<td align="center"><?php echo $dirfile['file']; ?></td>
                <td align="center"><?php echo  Size($dir.$dirfile['file']); ?></td>
                <td align="center">
                	<?php
						 if (preg_match("/\.sql$/i",$dirfile['file'])) echo 'SQL';
						 elseif (preg_match("/\.gz$/i",$dirfile['file']))echo "GZip";
						 elseif (preg_match("/\.zip$/i",$dirfile['file']))echo "Zip";
						 elseif (preg_match("/\.csv$/i",$dirfile['file']))echo "CSV";
						 else echo "Misc";
					 ?>
                </td>
               
                	<?php if ((preg_match("/\.zip$/i",$dirfile['file']) && function_exists("gzopen")) || preg_match("/\.sql$/i",$dirfile['file']) || preg_match("/\.csv$/i",$dirfile['file'])){?>
                <td align="center">
                		<a href="<?php echo url::base();?>admin_backup_file/restore/<?php echo $filename ?>" onclick="return confirm('<?php echo Kohana::lang('backup_restore_lang.lbl_restore')?>')">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_restore.png" title="Restore" />
                        </a>&nbsp;
                        <a href="<?php echo url::base(); echo $dir?><?php echo urlencode($dirfile['file']) ?>">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_down.png" title="Download" />
                        </a>&nbsp;
                       <a href="<?php echo url::base()?>admin_backup_file/delete/<?php echo $filename;?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
                        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('admin_lang.btn_delete')?>" /></a>
                </td>
                <?php } else { ?>
                	<td >&nbsp;</td>
                <?php } ?>
           </tr>
			
 
 <?php 
 
 	}
  
 } ?>	
 
</table>
<table class="list_bottom" cellspacing="0" cellpadding="5">
  <tr>
    <td>
	<select name="sel_action" id="sel_action">
		<option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected')?></option>
	</select>
    &nbsp;
	<button type="button" name="btn_update" class="button save" onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_backup_file/deleteall','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>');"/>
    <span>Updated</span>
    </button>
    </td>
  </tr>
</table>
</form>
