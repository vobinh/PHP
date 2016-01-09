<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_category/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Category' ?></td>
    <td class="title_button">
    <?php if ($this->sess_admin['level'] == 1) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_category/create'"/>
    <span><?php echo ('Input new Category') ?></span>
    </button>
    <?php } // end if level super admin ?>
    </td>
  </tr>
</table>
<table cellspacing="0" class="list_top">
  <tr>
    <td><?php echo 'Search'; ?>:
        <select name="sel_search" style="width:120px;">
                <option <?php echo (isset($option) && $option == '2') ? 'selected="selected"' : '' ?> value="2">Test</option>
        <option <?php echo (isset($option) && $option == '1') ? 'selected="selected"' : '' ?> value="1">Category</option>
        </select>
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
  	 <td width="80" ><?php echo "Test" ?></td> 
    <td><?php echo 'Category' ?></td>
    <td><?php echo 'Percentage' ?></td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>

  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
 <?php if ($list['left'] != 1) { ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  
    	 <td align="center" nowrap="nowrap"><?php echo isset($list['test']['test_title'])? $list['test']['test_title']:'' ?></td>
        <td align="left">
		<?php				
			$expand = '';
			for ($k=1;$k<$list['level'];$k++)
			$expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			$expand .= '|----';
	   ?>
		<?php echo $expand.$list['category']?></td>
        
    <td align="center" >
    
    <?php if($list['hidenParent'] == 0){?>
    <div id="notice<?php echo $list['uid']?>" onclick="$(this).hide('slow')" style="float:right;width:50px"> </div><input id="input<?php echo $list['uid']?>"  type="text" style="text-align:right" value="<?php if(@$list['category_percentage']!='')echo $list['category_percentage'].'%' ?>"  onfocus="$('#hidden<?php echo $list['uid']?>').val(this.value)" onblur="saveElem(this,<?php echo $list['uid']?>,<?php echo $list['test_uid']?>)" />
    <input id="hidden<?php echo $list['uid']?>" type="hidden" value="" />
    <?php } else {?>
   		 
	<?php }?>
    </td>
    
   
    <td align="center">
    <?php if($list['status'] == 1){ ?>	
        <a href="<?php echo url::base() ?>admin_category/setstatus/<?php echo $list['uid'] ?>">            
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	
        </a> 
	<?php } else { ?>
     <a href="<?php echo url::base() ?>admin_category/setstatus/<?php echo $list['uid'] ?>">  	
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
        </a
	><?php } ?>
  
    </td> 
    <td align="center">
     
    <a href="<?php echo url::base() ?>admin_category/edit/<?php echo $list['uid'] ?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
  
 
    <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
  
    </td>
  </tr>
  <?php } ?>
  <?php } } // end foreach ?>
</table>
</form>
<?php /*?><div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_category/display">
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
<?php */?><?php require('list_js.php')?>