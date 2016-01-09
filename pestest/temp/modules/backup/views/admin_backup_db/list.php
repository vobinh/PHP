<form action="<?php echo url::base()?>admin_backup_db/backupsm" method="post">
<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="title">
	<tr>
    	<td class="text_tt"><?php echo Kohana::lang('backup_restore_lang.lbl_restore_db')?></td>
        <td align="right">  
            <button type="submit" name="btn_submit" class="button save" />
            <span><?php echo Kohana::lang('backup_restore_lang.btn_backup_db')?></span>
            </button>
    	</td>
    </tr>
</table>
</form>
<form method="POST" action="<?php echo url::base()?>admin_backup_db/showlist" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo isset($mr['upload_max_filesize'])?$mr['upload_max_filesize']:''?>">
<p><?php echo Kohana::lang('backup_restore_lang.lbl_dump_file')?>&nbsp;<input type="file" name="dumpfile" accept="*/*" size="20">
	<button type="submit" name="uploadbutton" class="button upfile" value="Upload"><span><?php echo Kohana::lang('backup_restore_lang.btn_upload')?></span></button></p>
</form>
<table width="100%" border="0" cellspacing="0" class="tbl_list_top">
	<tr>
	  <td nowrap>
      		
      </td>
    </tr>
	<tr>
    	<td nowrap>
      
        </td>
    </tr>
</table>
<table cellspacing="1" cellpadding="5" class="list">
 <tr class="list_header">
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
 ?>
 <?php if(isset($mr['upload_dir'])){?>
 	
	<?php  if ($dirhandle = opendir($mr['upload_dir'])) 
  			{ 
			$dirhead=false;
			while (false !== ($dirfile = readdir($dirhandle)))
    		{ 
				if ($dirfile != "." && $dirfile != ".." && $dirfile!=basename($_SERVER["SCRIPT_FILENAME"]))
				{
				  if (!$dirhead){$dirhead=true;}
		    ?>
			<tr class="row2">
                 <td align="center"><?php  echo date("m-d-Y H:i:s",filemtime($mr['upload_dir'].$dirfile)) ;?></td>
            	<td align="center"><?php echo $dirfile; ?></td>
                <td align="center"><?php echo  Size($mr['upload_dir'].$dirfile); ?></td>
               
                <td align="center">
                	<?php
						 if (preg_match("/\.sql$/i",$dirfile)) echo 'SQL';
						 elseif (preg_match("/\.gz$/i",$dirfile))echo "GZip";
						 elseif (preg_match("/\.zip$/i",$dirfile))echo "Zip";
						 elseif (preg_match("/\.csv$/i",$dirfile))echo "CSV";
						 else echo "Misc";
					 ?>
                </td>
               
                	<?php if ((preg_match("/\.zip$/i",$dirfile) && function_exists("gzopen")) || preg_match("/\.sql$/i",$dirfile) || preg_match("/\.csv$/i",$dirfile)){?>
                <td align="center">
                		<a href="<?php echo url::base()?>admin_backup_db/import/<?php echo urlencode($dirfile) ?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_restore')?>')">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_restore.png" title="start restore" />
                        </a>&nbsp;
                        <a href="<?php echo url::base()?>uploads/dbbackup/<?php echo urlencode($dirfile) ?>">
                             <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_download.png" title="Download file" />
                        </a>&nbsp;
                       <a href="<?php echo url::base()?>admin_backup_db/delete/<?php echo urlencode($dirfile);?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')">
                        	<img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('admin_lang.btn_delete')?>" /></a>
               
                </td>
                <?php } else { ?>
                	<td >&nbsp;</td>
                <?php } ?>
           </tr>
			<?php	
				} 
			}
			if ($dirhead) echo ('');
   		    else echo ("<tr class='row2'><td align='center' colspan='6'>No uploaded files found in the working directory</td></tr>");
			closedir($dirhandle); 
		   ?>
			
 
 <?php }
 		
 
 } ?>	
</table>

