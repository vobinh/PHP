<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base() ?>admin_test'">
<span><?php echo Kohana::lang('global_lang.btn_back_list') ?></span>
</button>
<button type="button" name="btn_save" class="button save" onclick="javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>
<button type="button" name="btn_save_add" class="button save" onclick="javascript:save('add');">
<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
</button>
<button type="button" name="btn_save_add"  onclick="loadmember('<?php echo url::base() ?>admin_test/member/<?php echo isset($test['uid'])?$test['uid']:''?>');$('#dialog').dialog('open');">
<span><?php echo 'Purchase Member'?></span>
</button>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
