<table class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo Kohana::lang('page_lang.tt_page')?></td>
    <td class="title_button">
    <?php if($this->permisController('add')) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base().uri::segment(1)?>/create'"/>
    <span><?php echo Kohana::lang('page_lang.btn_new_page') ?></span>
    </button>
    <?php }//add ?>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <?php if ($mr['view_list'] == 'list') { ?>
    <button type="button" name="btn_save_mo" id="btn_save_mo" class="button save" onclick="save_menu_order();"/>
    <span><?php echo Kohana::lang('page_lang.btn_save_menu_order')?></span>
    </button>
    <?php } //end if view page as list ?>
    <?php }//edit,delete?>
    </td>
</tr>
</table>
<form id="frmlist" action="<?php echo url::base().uri::segment(1)?>" method="post">
<table class="list" cellspacing="1" cellpadding="5">
<tr class="list_header">
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
	<?php if ($mr['view_list'] == 'mptt') { ?>
    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);'></td>
    <?php }// end if view page as mptt ?>
    <?php }//edit,delete ?>
    <td><?php echo Kohana::lang('global_lang.lbl_title')?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center" width="5%"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td align="center" width="90"><?php echo Kohana::lang('page_lang.lbl_read')?></td>
    <td align="center" width="90"><?php echo Kohana::lang('page_lang.lbl_write')?></td>
    <?php }//edit,delete ?>
    <td align="center" width="90"><?php echo Kohana::lang('page_lang.lbl_com_man')?></td>
    <td align="center" width="5%"><?php echo Kohana::lang('layout_lang.tt_page')?></td>
    <td align="center" width="90"><?php echo Kohana::lang('global_lang.lbl_type')?></td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <!--<td align="center" width="5%"><?php echo Kohana::lang('global_lang.lbl_order')?></td>-->
    <td width="90" align="center"><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
    <?php }//edit,delete ?>
</tr>
<?php foreach($mlist as $id => $list){ ?>
<tr id="node-<?php echo $list['page_id']?>" class="row<?php echo ($id%2)?'2':''?> <?php echo ($list['page_level'] == 1)?'':'child-of-node-'.ORM::factory('page_mptt',$list['page_id'])->__get('parent')->page_id?>">
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
	<?php if ($mr['view_list'] == 'mptt') { ?>
    <td><input name="chk_id[]" type="checkbox" value="<?php echo $list['page_id'] ?>"></td>
    <?php }// end if view page as mptt ?>	
    <?php }//edit,delete ?>
	<td style="text-align:left;"><?php echo $list['page_title']?></td>
	<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
    <td align="center">
    <?php if ($list['page_type_name'] !== 'menu') { ?>
      <?php if($list['page_status'] == 1){ ?>
      <a href="<?php echo url::base().uri::segment(1)?>/setstatus/<?php echo $list['page_id']?>" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active')?>" />
        </a>
      <?php } else { ?>    
      <a href="<?php echo url::base().uri::segment(1)?>/setstatus/<?php echo $list['page_id']?>" >
        <img src="<?php echo url::base()?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive')?>" />
        </a>
      <?php } ?>
    <?php } // end if not page type menu ?>
    </td> 
    <td align="center">
    <?php if ($list['page_type_name'] !== 'menu') { ?>
        <a style="text-decoration:none;" href="<?php echo url::base().uri::segment(1)?>/change_permission/<?php echo $list['page_id']?>/read" title="<?php echo Kohana::lang('admin_lang.lbl_click_change')?>">
        <?php if ($list['page_read_permission'] == 1) { ?> 
        	<b><font color="#FF0000"><?php echo Kohana::lang('account_lang.lbl_sadmin')?></font></b>
        <?php } elseif ($list['page_read_permission'] == 2) { ?>        
            <font color="#FF0000"><?php echo Kohana::lang('account_lang.lbl_admin')?></font>  
        <?php } elseif ($list['page_read_permission'] == 3) { ?>
            <b><font color="#000000"><?php echo Kohana::lang('account_lang.lbl_staff')?></font></b>  
        <?php } elseif ($list['page_read_permission'] == 4) { ?>
            <font color="#0000FF"><?php echo Kohana::lang('account_lang.lbl_registered')?></font>            
        <?php } else { ?>
        	<font color="#7D00FB"><?php echo Kohana::lang('page_lang.lbl_everybody')?></font>
        <?php } ?>
        </a>
    <?php } // end if page not special ?>
    </td>
    <td align="center">
    <?php if (strpos('bbs,blog,album,news',$list['page_type_name']) !== FALSE) { ?>
        <a style="text-decoration:none;" href="<?php echo url::base().uri::segment(1)?>/change_permission/<?php echo $list['page_id']?>/write" title="<?php echo Kohana::lang('admin_lang.lbl_click_change')?>">
        <?php if ($list['page_write_permission'] == 1) { ?> 
        	<b><font color="#FF0000"><?php echo Kohana::lang('account_lang.lbl_sadmin')?></font></b>
        <?php } elseif ($list['page_write_permission'] == 2) { ?>        
            <font color="#FF0000"><?php echo Kohana::lang('account_lang.lbl_admin')?></font>  
        <?php } elseif ($list['page_write_permission'] == 3) { ?>
            <b><font color="#000000"><?php echo Kohana::lang('account_lang.lbl_staff')?></font></b>  
        <?php } elseif ($list['page_write_permission'] == 4) { ?>
            <font color="#0000FF"><?php echo Kohana::lang('account_lang.lbl_registered')?></font>            
        <?php } else { ?>
        	<font color="#7D00FB"><?php echo Kohana::lang('page_lang.lbl_everybody')?></font>
        <?php } ?>
        </a>
    <?php } // end if page not special ?>
    </td>
    <?php }//edit,delete ?>
    <td align="center">        
		<?php if (strpos('bbs,blog,album,news',$list['page_type_name']) !== FALSE) { ?>
            <a href="<?php echo url::base()?>admin_<?php echo $list['page_type_name']?>/search/pid/<?php echo $list['page_id']?>">
        <?php } elseif(strpos('home,contact,support,comment',$list['page_type_name']) !== FALSE) { ?>
            <a href="<?php echo url::base()?>admin_<?php echo $list['page_type_name']?>">
        <?php } elseif(strpos('tab',$list['page_type_name']) !== FALSE) { ?>
            <a href="<?php echo url::base()?>admin_<?php echo $list['page_type_name']?>/config/<?php echo $list['page_id']?>">
        <?php } else { ?>
        	<a href="<?php echo url::base()?>admin_page/edit/<?php echo $list['page_id']?>">
        <?php } // end if page type detail ?>       
    	<img src="<?php echo $this->site['theme_url']?>pics/icon_<?php echo $list['page_type_name']?>.png" title="<?php echo $list['page_type_name']?>" /></a>
    </td>
	<td align="center">
    <?php if ($list['page_type_name'] !== 'menu') { ?>
    <?php if (Page_Layout_Model::has_layout($list['page_id'])) { ?>
    	<a href="<?php echo url::base()?>admin_layout/set_global/<?php echo $list['page_id']?>">
    	<img src="<?php echo $this->site['theme_url']?>pics/icon_customize.png" title="<?php echo Kohana::lang('layout_lang.lbl_set_global')?>" />
        </a>
	<?php } else { ?>
    <img src="<?php echo $this->site['theme_url']?>pics/icon_global.png" title="<?php echo Kohana::lang('global_lang.lbl_global')?>" />
    <?php } // end if has layout ?>
    <?php } // end if not page type menu ?>
    </td>
    <td align="center">
		<?php if ($list['page_type_special'] == 1) { ?>
        	<font color="#FF0000" style="font-weight:bolder;" title="<?php echo Kohana::lang('page_lang.lbl_page_special')?>">
		<?php } // end if page special ?>
		<?php echo $list['page_type_name']?>
        <?php if ($list['page_type_special'] == 1) { ?></font><?php } // end if page special ?>
	</td>
    <?php if($this->permisController('edit') || $this->permisController('delete')) { ?>    
    <?php if ($mr['view_list'] == 'mptt') { ?>
    <!--<td align="center"><?php echo $this->show_mptt_order('page_mptt','chop',$list['page_id'])?></td>-->
    <?php } else { // end view list as mptt ?>
    <td align="center">
    	<input align="center" type="text" name="txt_order_<?php echo $list['page_id']?>" value="<?php echo $list['page_order']?>" size="1" />
    </td>
    <?php } // end if order page as mptt | list ?>
    <td align="center">    
    <a href="<?php echo url::base().uri::segment(1)?>/edit/<?php echo $list['page_id'] ?>">
    <img src="<?php echo url::base() ?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit') ?>" />
    </a>    
    <?php if ($list['page_type_name'] !== 'menu') { ?>
    <?php if (Page_Layout_Model::has_layout($list['page_id'])) { ?>
    	<a href="<?php echo url::base()?>admin_layout/edit/<?php echo $list['page_id'] ?>">
        <img src="<?php echo url::base()?>themes/admin/pics/icon_edit_layout.png" title="<?php echo Kohana::lang('layout_lang.btn_edit_layout') ?>" />
        </a>
        <a href="<?php echo url::base()?>admin_layout/delete/<?php echo $list['page_id'] ?>">
        <img src="<?php echo url::base()?>themes/admin/pics/icon_delete_layout.png" title="<?php echo Kohana::lang('layout_lang.btn_delete_layout') ?>" />
        </a>
    <?php } else { ?>    	
        <a href="javascript:create_layout('<?php echo url::base() ?>admin_layout/create/<?php echo $list['page_id']?>/');">
        <img src="<?php echo url::base() ?>themes/admin/pics/icon_create_layout.png" title="<?php echo Kohana::lang('layout_lang.btn_create_layout') ?>" />
        </a>
    <?php } // end if has layout ?>
    <?php } // end if not page type menu ?>
    <?php if ($list['page_type_special'] == 0) { ?>
    <?php if($this->permisController('delete')) { ?>  
    <a href="<?php echo url::base().uri::segment(1)?>/delete/<?php echo $list['page_id'] ?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del'); ?>')" >	
    <img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />
    </a>
    <?php }//delete ?>
    <?php } // end if page special ?>
    </td>
    <?php }//edit,delete ?>
</tr>
<?php } // end foreach?>
</table>
<?php if($this->permisController('edit') || $this->permisController('delete')) { ?>
<?php if ($mr['view_list'] == 'mptt') { ?>
<table class="list_bottom" cellspacing="0" cellpadding="5">
<tr>
	<td>
    <select name="sel_method_move" id="sel_method_move">
        <option value="">-- <?php echo Kohana::lang('page_lang.lbl_sel_method')?> --</option>
        <option value="next_sibling"><?php echo Kohana::lang('page_lang.lbl_next_sibling')?></option>
        <option value="prev_sibling"><?php echo Kohana::lang('page_lang.lbl_prev_sibling')?></option>
        <option value="first_child"><?php echo Kohana::lang('page_lang.lbl_first_child')?></option>
        <option value="last_child"><?php echo Kohana::lang('page_lang.lbl_last_child')?></option>
		<option value="copy_layout"><?php echo Kohana::lang('page_lang.lbl_copy_layout')?></option>
    </select>
	<select name="sel_move" id="sel_move">
		<?php echo $this->show_sel_page()?>
    </select>
	<button type="button" name="btn_move" id="btn_move" class="button save" onclick="check_method();"/>
    <span><?php echo Kohana::lang('page_lang.btn_move_to')?></span>
    </button>
    </td>
</tr>	
</table>
<?php } // end if view page as mptt ?>
<?php }//edit,delete ?>
</form>
<?php require('list_js.php')?>