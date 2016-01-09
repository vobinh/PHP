<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base() ?>admin_courses/edit/<?php echo isset($id_courses)?$id_courses:''?>'">
<span><?php echo Kohana::lang('global_lang.btn_back_list') ?></span>
</button>
<button type="button" name="btn_save" class="button save" onclick="javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>
<button type="button" name="btn_save_add" class="button save" onclick="javascript:save('add');">
<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
</button>
<?php /*?>
<button type="button" name="btn_save_add"  onclick="loadmember('<?php echo url::base() ?>admin_test/member/<?php echo isset($test['uid'])?$test['uid']:''?>');$('#dialog').dialog('open');">
<span><?php echo 'Purchase Member'?></span>
</button>
<?php */?>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
