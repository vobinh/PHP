<div class="yui3-g">
    <div class="yui3-u-1-6 right"><font color="#FF0000">*</font>&nbsp;<?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_title" type="text" id="txt_title" value="<?php echo isset($mr['page_title'])?$mr['page_title']:''?>" size="50"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('page_lang.lbl_page_parent')?>:</div>
    <div class="yui3-u-4-5"><select name="sel_parent" id="sel_parent"><?php echo $this->show_sel_page(isset($mr['page_id'])?$mr['page_id']:'')?></select></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_type')?>:</div>
    <div class="yui3-u-4-5">
   	<?php if (isset($mr['page_type_special']) && $mr['page_type_special'] == 1) { ?>
    	<?php echo strtoupper(ORM::factory('page_type_orm',$mr['page_type_id'])->page_type_name)?>
	<?php } else { ?>    
		<select id="sel_type" name="sel_type" onchange="sh_content(this);">    	
		<?php echo $this->_show_sel_page_type_detail(isset($mr)?$mr:'')?>   
		</select>        
	<?php } // end if ?>
    </div>
</div>
<div class="yui3-g" id="page_content" style="display:none;">
  	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_content')?>:</div>
  	<div class="yui3-u-4-5"><textarea class="ckeditor" cols="50" name="txt_content" id="txt_content" style="width:500px;height:200px"><?php echo isset($mr['page_content'])?$mr['page_content']:''?></textarea></div>
</div>  
<div class="yui3-g" id="page_menu" style="display:none;">
  	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('page_lang.lbl_link')?>:</div>
  	<div class="yui3-u-4-5"><input type="text" name="txt_content_url" id="txt_content_url" size="50" value="<?php echo (isset($mr['page_content'])&&isset($mr['page_type_name'])&&$mr['page_type_name']=='menu'&&!empty($mr['page_content']))?$mr['page_content']:'http://'?>"></div>
</div>
<?php if(!empty($mr)&&$mr['page_type_special']!=1){
  $page_type_name = ORM::factory('page_type_orm',$mr['page_type_id'])->page_type_name;
  if(!in_array("menu",array($page_type_name))){?>
<div class="yui3-g" id="page_menu">
    <div class="yui3-u-1-6 right">Link:</div>
    <div class="yui3-u-4-5"><?php echo $this->site['base_url']?><?php echo $page_type_name?>/pid/<?php echo $mr['page_id']?></div>
</div>
<?php }}//Link?>
<div class="yui3-g" id="page_target" style="display:none">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('banner_lang.lbl_target')?>:</div>
	<div class="yui3-u-4-5"><select name="sel_target" id="sel_target">
    		<option value="_blank" <?php echo isset($mr['page_target']) && $mr['page_target']=='_blank'?'selected':''?>>_blank</option>
            <option value="_parent" <?php echo isset($mr['page_target']) && $mr['page_target']=='_parent'?'selected':''?>>_parent</option>
            <option value="_self" <?php echo isset($mr['page_target']) && $mr['page_target']=='_self'?'selected':''?>>_self</option>
            <option value="_top" <?php echo isset($mr['page_target']) && $mr['page_target']=='_top'?'selected':''?>>_top</option>
    </select></div>
</div>
<div class="yui3-g" id="page_form" style="display:none;">
  	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('page_lang.lbl_form')?>:</div>
  	<div class="yui3-u-4-5"><input type="text" name="txt_content_form" id="txt_content_form" size="25" value="<?php echo (isset($mr['page_content'])&&isset($mr['page_type_name'])&&$mr['page_type_name']=='form')?$mr['page_content']:''?>"></div>
</div>
<div class="yui3-g" id="page_status">
	<div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_status')?>:</div>
    <div class="yui3-u-4-5">
        <select name="sel_status" id="sel_status">            
        <option value="1" <?php echo isset($mr['page_status'])?($mr['page_status']?'selected':''):'selected'?>><?php echo Kohana::lang('global_lang.lbl_active')?></option>
        <option value="0" <?php echo isset($mr['page_status'])?($mr['page_status']?'':'selected'):''?>><?php echo Kohana::lang('global_lang.lbl_inactive')?></option>	
        </select>
	</div>
</div>