<script>
$().ready(function(){
	$('#noticebox').show('slow');
	$('#noticebox').delay(10000).hide('slow');
})
</script>
<div class="error_msg" id='noticebox' align="center" style="position: fix;left:20%;;background: #E0F7BE;padding: 10px;top: 0;margin-top: 1px;color: #5D45DA;font-size: 13px;">
	<div class="msg_error"><?php if (isset($error_msg))  echo $error_msg; ?>
    <?php if (isset($warning_msg))  echo $warning_msg; ?>
    <?php if (isset($info_msg))  echo $info_msg; ?>
    <?php if (isset($success_msg))  echo $success_msg; ?></div>
</div> 