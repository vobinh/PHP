<script type="text/javascript">
function delete_admin(id) {
	$.msgbox('Do you really want to delete?', {
		type : 'confirm',
		buttons : [
			{type: 'submit', value:'Yes'},
			{type: 'submit', value:'Cancel'}
			]
		}, 
		function(re) {
			if(re == 'Yes') {
				$('a#delete_'+id).html('<img src="<?php echo url::base(); ?>themes/admin/pics/icon_loading.gif"/>');
				$.getJSON("<?php echo $this->site['base_url']?>admin_category/delete/"+ id,
					function(obj) {
						if(obj.status) {
							$('tr#row_'+id).fadeOut('normal', function() {
								$('tr#row_'+id).remove();
							})
						} else {
							if(obj.mgs)
							$.msgbox(obj.mgs, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]});
							$('a#delete_'+id).html('<img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />');
						}
					}
				);
			}
		}
	);
};

function saveElem(val,uid,test_uid){
	
	value = val.value.split('.');
	value = value[0].split('%');
	value = value[0];
	valold = $('#hidden'+uid).val();
	if(valold != val.value){
			$.ajax({
				url:'<?php echo $this->site['base_url']?>admin_category/editPercent/'+value+'/'+uid+'/'+test_uid,
				type: 'POST',
				beforeSend: function() {
					$('#notice'+ uid).attr('style','position: relative;top: -12px;left: 158px;margin-bottom: -48px;font-size: 10px;height: 46px;width: 157px;background-color: #E1DFFF;padding: 2px;border-radius: 9px;');
					
				},
				complete: function() {
					
				},
				success: function(data) {
				if(data == 'Edit success'){
					$('#notice'+ uid).html(data);
				}else{
					$('#input'+ uid).val(valold);
					$('#notice'+ uid).attr('style','position: relative;top: -12px;left: 158px;margin-bottom: -48px;font-size: 10px;height: 46px;width: 157px;background-color: #E1DFFF;padding: 2px;border-radius: 9px;');
					$('#notice'+ uid).html(data);
					}	
				}
			})	
	}
}
</script>