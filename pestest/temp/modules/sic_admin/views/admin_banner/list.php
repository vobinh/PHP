<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_banner/delete" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo $mr['page_title']?></td>
	<td class="title_button">
    <td align="right">
    <?php //if($this->permisController('add')) { ?>
	<button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_banner/create'"/>
    <span><?php echo Kohana::lang('banner_lang.btn_new_banner') ?></span>
    </button>
    <?php // }//add ?>
    </td>
  </tr>
</table>


<table class="list" cellspacing="1" cellpadding="5">
	<tr class="list_header">   
    <td width="5%"><?php echo Kohana::lang('banner_lang.lbl_id')?></td>     
   
    <td><?php echo Kohana::lang('global_lang.lbl_view')?></td>
    <td width="10%"><?php echo 'Status' ?></td>  
    <?php //if($this->permisController('edit') || $this->permisController('delete')) { ?>      
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
    <?php //}//edit,delete ?>
	</tr>
	<?php 
	if(!empty($mlist) && $mlist!=false){
		foreach($mlist as $id => $list){  ?>
	<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['banner_id']?>"> 
    <td align="center"><?php echo $list['banner_id']?></td>   
   
    <td align="center">    
    <?php if (@$list['banner_type'] == 'image' && file_exists($list['path_file']) && is_file($list['path_file'])) { 
		$src = "uploads/banner/".$list['path_file'];
		$maxw = Configuration_Model::get_value('MAX_WIDTH_BANNER_ADMIN_LIST');
		$maxh = Configuration_Model::get_value('MAX_HEIGHT_BANNER_ADMIN_LIST');
		$arr = MyImage::thumbnail($maxw,$maxh,$src);
	?>
        <a href="<?php echo url::base()?>uploads/banner/<?php echo $list['banner_name']?>">
        <img src="<?php echo url::base()?>uploads/banner/<?php echo $list['banner_name']?>" width="<?php echo !empty($arr['width'])?($arr['width'].'px'):'auto'?>" height="<?php echo !empty($arr['height'])?($arr['height'].'px'):$maxh?>"> 
		</a>
	<?php } elseif (file_exists($list['path_file']) && is_file($list['path_file'])) { ?>
            <embed src="<?php echo url::base()?>uploads/banner/<?php echo $list['banner_name']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
	<?php } // end if banner_type ?>
    </td>    
    <?php //if($this->permisController('edit') || $this->permisController('delete')) { ?> 
    <td align="center"> <a href="<?php echo url::base() ?>admin_banner/setstatus/<?php echo $list['banner_id']?>">  
    <?php if($list['banner_status'] == '1'){ ?>	
             
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	
	<?php } else { ?> 	
   
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
       
	<?php } ?>
    </a></td>
    <td align="center"> 		  
    <a href="<?php echo url::base() ?>admin_banner/edit/<?php echo $list['banner_id']?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
    <?php //if($this->permisController('delete')) { ?>
    <a id="delete_<?php echo $list['banner_id']?>" href="javascript:delete_banner(<?php echo $list['banner_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    <?php //}//delete ?>
    </td>
    <?php //}//edit,delete ?>
  	</tr>
  	<?php } }//end foreach ?>
</table>
</form>
<div class='pagination'><?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($mlist)?count($mlist):''?></div>
<?php require('list_js.php')?>