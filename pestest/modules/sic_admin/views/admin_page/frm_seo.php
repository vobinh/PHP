<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_title')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_title_seo" type="text" id="txt_title_seo" value="<?php echo isset($mr['page_title_seo'])?$mr['page_title_seo']:''?>" size="50"></div>
</div>  
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_keyword')?>:</div>
    <div class="yui3-u-4-5"><input name="txt_keyword" type="text" id="txt_keyword" value="<?php echo isset($mr['page_keyword'])?$mr['page_keyword']:''?>" size="50"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('global_lang.lbl_description')?>:</div>
    <div class="yui3-u-4-5"><textarea name="txt_description" type="text" id="txt_description" cols="50" rows="5"><?php echo isset($mr['page_description'])?$mr['page_description']:''?></textarea></div>
</div>