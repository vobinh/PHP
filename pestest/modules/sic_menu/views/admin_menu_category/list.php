<form name="frmlist" id="frmlist" action="<?php echo $this->site['base_url']?>admin_menu_category/search" method="post" >
<table class="title" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('menu_category_lang.tt_page')?></td>
    <td align="right">
    <button type="button" name="btn_new" class="button new" onclick="location.href='<?php echo $this->site['base_url']?>admin_menu_category/create'">
    <span><?php echo Kohana::lang('menu_category_lang.btn_new_category')?></span>
    </button>
    </td>
  </tr>
</table>
<table class="list_top" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
  </tr>
</table>
<table class="list" align="left" cellspacing="1" cellpadding="5">
  <tr class="list_header">
    <td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>
    <?php for($i=0; $i<count($list_language); $i++){ ?>
    <td><?php echo Kohana::lang('menu_category_lang.lbl_name')?> <img src="<?php echo $this->site['base_url']?>uploads/language/<?php echo $list_language[$i]['languages_image']?>"/> </td>
    <?php }?>
    <td width="50" align="center"><?php echo Kohana::lang('menu_category_lang.lbl_link')?></td>
    <td width="50" align="center"><?php echo Kohana::lang('global_lang.lbl_order')?></td>
    <td width="50" align="center"><?php echo Kohana::lang('global_lang.lbl_status')?></td>
    <td width="80" align="center"><?php echo Kohana::lang('global_lang.lbl_action')?></td>
  </tr>
  <?php 
  for($i=0; $i<count($mlist); $i++){?>
  <?php if(isset($mlist[$i]['languages'])){ ?>
  <?php if ($mlist[$i]['menu_categories_left'] != 1) { ?>
  <tr class="row<?php if($i%2 == 0) echo 2 ?>">
    <td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $mlist[$i]['menu_categories_id']?>" /></td>
    <?php $languages = $mlist[$i]['languages']?>
    <?php for($j=0;$j<count($languages);$j++){?>
    <td style="text-align:left;"><?php {
				$expand = '';
				for ($k=1;$k<$mlist[$i]['menu_categories_level'];$k++)
            		$expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$expand .= '|----';
		}
	?>
        <a href="<?php echo $this->site['base_url']?>admin_menu_category/edit/<?php echo $mlist[$i]['menu_categories_id']?>">
        <?php if(isset($languages[$j]['menu_categories_name'])) echo $expand.$languages[$j]['menu_categories_name']?>
      </a> </td>
    <?php } ?>
        <td><a href="<?php echo $this->site['base_url']?>admin_menu_category/edit/<?php echo $mlist[$i]['menu_categories_id']?>">
      <?php if(isset($mlist[$i]['menu_categories_link'])) echo $mlist[$i]['menu_categories_link']?>
    </a></td>

    <!-- Categories Order -------------------------------------------------------------------------------------------------------------------------->
    <td align="center"><?php 
			{
				$category_mpt = ORM::factory('menu_category_orm',$mlist[$i]['menu_categories_id']);
				$category_siblings = $category_mpt->__get('siblings')->count();
				
				// show 'down' when category is first child and must have siblings (num of its siblings > 0)
				if ($category_mpt->is_first_child() && $category_siblings>0)
					echo '<a href="'.$this->site['base_url'].'admin_menu_category/chcateo/down/id/'.$mlist[$i]['menu_categories_id'].'">
					<img src="'.$this->site['theme_url'].'pics/icon_down.png" title="Down" />
					<a>';				
				// show 'up' when category is last child and must have siblings (num of its siblings > 0)	
				elseif ($category_mpt->is_last_child() && $category_siblings>0)
					echo '<a href="'.$this->site['base_url'].'admin_menu_category/chcateo/up/id/'.$mlist[$i]['menu_categories_id'].'">
					<img src="'.$this->site['theme_url'].'pics/icon_up.png" title="Up" />
					<a>';
				// show 'down up' when page have siblings (num of siblings > 0)
				elseif ($category_siblings>0) { 					
					echo '<a href="'.$this->site['base_url'].'admin_menu_category/chcateo/down/id/'.$mlist[$i]['menu_categories_id'].'">
					<img src="'.$this->site['base_url'].'themes/admin/pics/icon_down.png" title="Down" />
					<a> ';
					echo '<a href="'.$this->site['base_url'].'admin_menu_category/chcateo/up/id/'.$mlist[$i]['menu_categories_id'].'">
					<img src="'.$this->site['theme_url'].'pics/icon_up.png" title="Up" />
					<a>'; 
				}
				// otherwise show nothing
			}							
		?>    </td>
    <!-- ******************************************************************************************************************************************* -->
    <td align="center"><?php if($mlist[$i]['menu_categories_status']){ ?>
      <a href="<?php echo $this->site['base_url']?>admin_menu_category/setstatus/<?php echo $mlist[$i]['menu_categories_id']?>/0" title="click to change status" > <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_active.png" title="Active" /> </a>
      <?php }else{?>
      <a href="<?php echo $this->site['base_url']?>admin_menu_category/setstatus/<?php echo $mlist[$i]['menu_categories_id']?>/1" title="click to change status" > <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_inactive.png" title="Inactive" /> </a>
      <?php }?></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_menu_category/edit/<?php echo $mlist[$i]['menu_categories_id']?>"> <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_edit.png" title="<?php echo Kohana::lang('global_lang.btn_edit')?>" /> </a> <a href="<?php echo $this->site['base_url']?>admin_menu_category/delete/<?php echo $mlist[$i]['menu_categories_id']?>" onclick="return confirm('<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>')" > <img src="<?php echo $this->site['base_url']?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete')?>" /> </a></td>
  </tr>
  <?php } // end if ?>
  <?php } // end if ?>
  <?php } // end for ?>
</table>
<br clear="all" />
<table class="list_bottom" cellspacing="0" cellpadding="5">
  <tr>
    <td><select name="sel_action" id="sel_action">
        <option value="block"><?php echo Kohana::lang('admin_lang.sel_block_selected')?></option>
        <option value="active"><?php echo Kohana::lang('admin_lang.sel_active_selected')?></option>
        <option value="delete"><?php echo Kohana::lang('admin_lang.sel_delete_selected')?></option>
      </select>
      &nbsp;
      <button class="button save" onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_menu_category/saveall','<?php echo Kohana::lang('errormsg_lang.msg_confirm_del')?>');">
      <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
      </button>
      </td>
  </tr>
</table>
<div class='pagination'><?php echo $this->pagination ?></div>
</form>
