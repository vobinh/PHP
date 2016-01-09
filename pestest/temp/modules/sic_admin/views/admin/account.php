<table cellpadding="0" cellspacing="0">
    <tr>
    <td nowrap="nowrap"><strong><?php echo $this->sess_admin['username']?></strong>, <a href="<?php echo url::base()?>admin_myaccount"><?php echo Kohana::lang('account_lang.lbl_my_acc') ?></a> | <a href="<?php echo url::base()?>admin_login/log_out"><?php echo Kohana::lang('account_lang.lbl_logout') ?></a></td>
    </tr>
    <tr>
      <td height="35">
      	<?php if(!empty($list_lang)&&count($list_lang)>1){$list_lang = ORM::factory('languages')->where('languages_status',1)->find_all(); ?>        
		<?php foreach ($list_lang as $ll) { ?>
        &nbsp;<a href="<?php echo url::base() ?>admin_language/set_lang/<?php echo $ll->languages_id?>"><img src="<?php echo url::base() ?>uploads/language/<?php echo ($ll->languages_id==$this->get_admin_lang()?'selected_':'').$ll->languages_image?>" /></a>
        <?php }} ?>
      </td>
    </tr>
</table>