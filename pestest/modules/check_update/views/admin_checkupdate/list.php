<? 
$mr['time']=explode(',',$mr['time']);

?>
<table cellspacing="0" cellpadding="0" class="title">
<tbody><tr>
    <td class="title_label">Update History</td>
    <td class="title_button" style="padding-top:10px;">
        
    </td> 
</tr>
</tbody></table>
<table cellspacing="1" class="list_top"  border="1" bordercolor="#CCCCCC" style=" font-size:14px; width:90%; margin-left:50px; margin-top:50px; margin-bottom:50px" >
<tr style="  color:#00F; line-height:20px">
	<th width="20%" align="center">Version</th>
    <th width="20%" align="center">Date</th>
    <th align="center">Content</th>
</tr>
<? for ( $i=count($mr['version'])-1;$i>=0;$i--){ ?>
<tr>
	<td width="20%" align="center"><? echo $mr['version'][$i];?> </td>
    <td width="20%" align="center"><? echo date('m/d/Y H:i:s',$mr['time'][$i])?> </td>
    <td align="left" style=" padding-left:10px; padding-top:2px; padding-bottom:2px;">
    	<? for ($j=0;$j<count($mr['content'][$i]);$j++) { 
		 	echo '-'.$mr['content'][$i][$j] ;  ?><br />

			
        <? } ?>
    </td>
   
</tr>
<? } ?>

</table>