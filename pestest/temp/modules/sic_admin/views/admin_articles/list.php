<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_articles/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Articles'; ?></td>
    <td class="title_button">
    <?php if ($this->sess_admin['level'] == 1) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_articles/create'"/>
    <span><?php echo 'Input new Article' ?></span>
    </button>
    <?php } // end if level super admin ?>
    </td>
  </tr>
</table>
<table cellspacing="0" class="list_top">
  <tr>
    <td><?php echo 'Name'; ?>:
       
      <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"  />
      &nbsp;
      <button type="submit" name="btn_search" id="btn_search" class="button search" />
      <span><?php echo 'Search'; ?></span>
      </button>
      </td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
  	<td width="70%"><?php echo 'Name' ?></td>
  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
 
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['articles_id']?>">
  	<td align="left" style=" padding-left:20px"><?php echo $list['articles_name']?></td>
  
    <td align="center">
    <a href="<?php echo url::base()?>admin_articles/setstatus/<?php echo $list['articles_id']?>">
    <?php if($list['articles_status'] == 1){ ?>	            
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	 
	<?php } else { ?> 	
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
   
	<? } ?>
   </a>
    </td> 
    <td align="center">
      
    <a href="<?php echo url::base() ?>admin_articles/edit/<?php echo $list['articles_id'] ?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
  
   
    <a id="delete_<?php echo $list['articles_id']?>" href="javascript:delete_admin(<?php echo $list['articles_id']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
  
    </td>
  </tr>
 
  <?php } } // end foreach ?>
</table>
</form>
<div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_articles/display">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onchange="document.frm_display.submit();">
	<option value="">---</option>
    <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
    <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
    <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
    <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
    <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
</select>
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</form>
</div>
<?php require('list_js.php')?>