<script type="text/javascript">  
function generate(type,msg) {
	var n = noty({
		text: msg,
		type: type,
		dismissQueue: true,
		layout: 'topCenter',
		theme: 'default',
		timeout: 2500
	});
} 
</script>
<?php if(isset($error_msg)) { ?>
	<script type="text/javascript">
	generate('error','<?php echo $error_msg?>');
	</script>
<?php } ?>

<?php if(isset($success_msg)) { ?>
	<script type="text/javascript">
	generate('success','<?php echo $success_msg?>');
	</script>
<?php } ?>

<?php if(isset($info_msg)) { ?>
	<script type="text/javascript">
	generate('information','<?php echo $info_msg?>');
	</script>
<?php } ?>

<?php if(isset($warning_msg)) { ?>
	<script type="text/javascript">
	generate('warning','<?php echo $warning_msg?>');
	</script>
<?php } ?>