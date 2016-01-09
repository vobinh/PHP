<button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo url::base() ?>admin_member'">
<span><?php echo Kohana::lang('global_lang.btn_back_list') ?></span>
</button>
<?php if ($this->sess_admin['level'] == 1) { ?>
<button type="button" name="btn_save" class="button save" onclick="javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>
<button type="button" name="btn_save_add" class="button save" onclick="javascript:save('add');">
<span><?php echo Kohana::lang('global_lang.btn_save_add')?></span>
</button>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
<?php } // end if level super admin ?>
<?php if(isset($payment) && count($payment)>0) {?>
<button type="button" name="btn_back" class="button view" onclick="loadmytest('<?php echo url::base() ?>admin_member/mytest/<?php echo $mr['uid'] ?>');$('#dialogmytest').dialog('open');">
<span>My Test</span>
</button>
<?php } ?>
<?php if(isset($testing) && count($testing)>0) {?>
<button type="button" name="btn_back" class="button view" onclick="loadtestting('<?php echo url::base() ?>admin_member/testing/<?php echo $mr['uid'] ?>');$('#dialogtestting').dialog('open');">
<span>Testing</span>
</button>
<?php } ?>
