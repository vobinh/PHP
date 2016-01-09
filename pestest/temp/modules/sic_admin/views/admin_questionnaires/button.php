<?php $page = $this->session->get('page')?>
<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base() ?>admin_questionnaires<?php echo !empty($page)?"/search/page/".$page:""?>'">

<span><?php echo Kohana::lang('global_lang.btn_back_list') ?></span>
</button>
<button type="button" name="btn_save" class="button save" onclick="javascript:save('save');">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>
<button type="button" name="btn_save_add" class="button save" onclick="javascript:save('add');">
<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
</button>
