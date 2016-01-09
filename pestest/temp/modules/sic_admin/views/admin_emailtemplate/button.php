<?php if ($this->sess_admin['level'] == 1) { ?>
<button type="button" name="btn_save" class="button save" onclick="javascript:save();">
<span><?php echo Kohana::lang('global_lang.btn_save') ?></span>
</button>

<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />
<?php } // end if level super admin ?>
