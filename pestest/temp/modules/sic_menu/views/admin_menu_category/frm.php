<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>admin_menu_category/save" method="post" enctype="multipart/form-data">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('menu_category_lang.tt_page')?></td>
    <td class="title_button"><button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_menu_category'">
      <span><?php echo Kohana::lang('global_lang.btn_back')?></span>
      </button>
      &nbsp;
      <button type="submit" name="btn_save_add" class="button save">
      <span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
      </button>
      &nbsp;
      <button type="submit" name="btn_save" class="button save">
      <span><?php echo Kohana::lang('global_lang.btn_save')?></span>
      </button>      
    </td>
  </tr>
</table>
<table class="form" align="center" cellspacing="0" cellpadding="5">
  <tr>
    <td nowrap="nowrap" width="10%"><?php echo Kohana::lang('menu_category_lang.lbl_parent_name')?>:</td>
    <td><div style="float:left;">
        <select id="sel_parent_name" name="sel_parent_name">
          <?php 			
			{ 				
				$root = ORM::factory('menu_category_orm')->__get('root');							
				$selected = '';
				
				if (isset($mr))
					$parent = ORM::factory('menu_category_orm',$mr[0]['menu_categories_id'])->__get('parent');
				else	$selected = 'selected';				
			}
		  ?>          
		  <option value="<?php echo $root->menu_categories_id;?>" <?php echo $selected;?>>- - - None - - -</option>	<!-- show root categories -->          
		  <?php for($i=0; $i<count($list_category); $i++) { ?>
		  <?php if ($list_category[$i]['menu_categories_left'] != 1) { ?>
          <option value="<?php echo $list_category[$i]['menu_categories_id']?>"
					<?php if(isset($mr[0]) && $list_category[$i]['menu_categories_id'] == $parent->menu_categories_id) { ?> selected <?php } ?>
                    <?php if(isset($mr[0]['menu_categories_id'])) $cur_cate = ORM::factory('menu_category_orm',$mr[0]['menu_categories_id']);?>
                    <?php $sel_cate = ORM::factory('menu_category_orm',$list_category[$i]['menu_categories_id']);?>
                    <?php if(isset($mr[0]) && ( $list_category[$i]['menu_categories_id']==$mr[0]['menu_categories_id'] || $sel_cate->is_descendant($cur_cate))) { ?> disabled <?php } ?>
    	  >    	  
    	  <?php	 				
				$expand = '';
				for ($k=1;$k<$list_category[$i]['menu_categories_level'];$k++)
					$expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$expand .= '|----';			
		  ?>
		  <?php echo $expand;?>                  
          <?php $languages = $list_category[$i]['languages']?>
          <?php for($j=0; $j<count($list_language); $j++){ ?>
          <?php if($j>0) echo '|'?>
		  <?php echo $languages[$j]['menu_categories_name']?>
          <?php } ?>
          </option>         
		  <?php } // end if ?> 
          <?php } // end for?>
        </select>
      </div>
      </td>
  </tr>
  <tr>
    <td valign="top" nowrap="nowrap"><?php echo Kohana::lang('menu_category_lang.lbl_link')?>:</td>
    <td><div style="float:left;">
        <input value="<?php if (isset($mr[0]['menu_categories_link'])) echo $mr[0]['menu_categories_link']; ?>" id="txt_link" name="txt_link" size="30"/>
      </div>
  </tr>
  <?php  for($i=0; $i<count($list_language); $i++){ ?>
  <tr>
    <td valign="top" nowrap="nowrap"><?php echo Kohana::lang('menu_category_lang.lbl_name')?>: <font color="#FF0000">*</font> [<?php echo $list_language[$i]['languages_name']?>]</td>
    <td><div style="float:left;">
        <input value="<?php if (isset($mr[$i]['menu_categories_name'])) echo $mr[$i]['menu_categories_name']; ?>" id="txt_name<?php echo $list_language[$i]['languages_id']?>" name="txt_name<?php echo $list_language[$i]['languages_id']?>" size="30"/>
        <input type="hidden" id="hd_old_image" name="hd_old_image" value="<?php if (isset($mr[0]['menu_categories_image'])) echo $mr[0]['menu_categories_image']; ?>" />
      </div>
      <div style="float:left;padding-left:10px;"> <img src="<?php echo $this->site['base_url']?>uploads/language/<?php echo $list_language[$i]['languages_image']?>" width="20" id="img_language1" name="img_language1" height="20" /> </div></td>
  </tr>
  <?php }?>
  <?php for($i=0; $i<count($list_language); $i++){ ?>
  <tr>
    <td valign="top" nowrap="nowrap"><?php echo Kohana::lang('menu_category_lang.lbl_description')?>: [<?php echo $list_language[$i]['languages_name']?>]</td>
    <td><img src="<?php echo $this->site['base_url']?>uploads/language/<?php echo $list_language[$i]['languages_image']?>" width="20" id="img_language2" name="img_language2" height="20" /></td>
  </tr>
  <tr>
    <td colspan="2" valign="top">
      <textarea cols="50" rows="5" style="width:100%;height:100px" id="txt_description<?php echo $list_language[$i]['languages_id']?>" name="txt_description<?php echo $list_language[$i]['languages_id']?>"><?php if (isset($mr[$i]['menu_categories_description'])) echo $mr[$i]['menu_categories_description'] ?></textarea>
    </td>
  </tr>
  <?php }?>
  <tr>
    <td valign="top" nowrap="nowrap"><?php echo Kohana::lang('menu_category_lang.lbl_image')?>:</td>
    <td><input type="file" id='txt_image' name="txt_image" />
    <?php if(isset($mr[0]['menu_categories_image']) && $mr[0]['menu_categories_image']){ ?>
    	<p><img src="<?php echo $this->site['base_url']?>uploads/menu_category/<?php echo $mr[0]['menu_categories_image']?>">
        <a href="<?php echo $this->site['base_url']?>admin_menu_category/delete_image/<?php echo $mr[0]['menu_categories_id']?>"><?php echo Kohana::lang('global_lang.btn_delete')?></a>
        </p>
    <?php } ?>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap"><?php echo Kohana::lang('global_lang.lbl_status')?>:</td>
    <td><select id="sel_status" name="sel_status" >
        <option value="1" 
						<?php if (isset($mr[0]['menu_categories_status'])){?> 
                        <?php if($mr[0]['menu_categories_status']==1){ echo 'selected'; }}?>
                    > <?php echo Kohana::lang('global_lang.lbl_active')?> </option>
        <option value="0" 
						<?php if (isset($mr[0]['menu_categories_status'])){?> 
                        <?php if($mr[0]['menu_categories_status']!=1){ echo 'selected'; }}?>
                    > <?php echo Kohana::lang('global_lang.lbl_inactive')?></option>
      </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="hidden" id="hd_id" name="hd_id" value="<?php if (isset($mr[0]['menu_categories_id'])) echo $mr[0]['menu_categories_id']; ?>" /></td>
  </tr>
</table>
</form>
<script type="text/javascript">
active_opt_disabled(document.getElementById('sel_parent_name'));
function active_opt_disabled(sel)
{	
	sel.onchange = function() {
		if(this.options[this.selectedIndex].disabled){
			if(this.options.length<=1){
				this.selectedIndex = -1;
			}else if(this.selectedIndex < this.options.length - 1){
				this.selectedIndex++;
			}else{
				this.selectedIndex--;
			}
		}
		if(this.options[this.selectedIndex].disabled){
			this.onchange();
		}
	}	
	
	for(var j=0; j < sel.options.length; j++){ 
		if(sel.options[j].disabled){
			sel.options[j].style.color = '#CCC';
		}
	}   	
}
</script>