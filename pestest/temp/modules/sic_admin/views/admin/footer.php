<table class="footer" align="center" cellspacing="0" cellpadding="0">
<tr><td height="25" align="center"><?php echo Kohana::lang('login_lang.lbl_copyright')?> <br />
 <? 
		$file_name = "./version/version.txt";
		
		if(file_exists($file_name)) {
			if($handle = fopen($file_name, "r")){
				$version = fgets($handle);
				
				fclose($handle);	
			}
		}
		else{
			$version='';
		}
		
		?>
   &nbsp;<?php echo $version;?>     
</td></tr>
</table>