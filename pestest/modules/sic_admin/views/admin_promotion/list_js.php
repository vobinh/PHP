<script type="text/javascript">
function setStatus(id,value){
	$.ajax({
 		url: "<?php echo $this->site['base_url']?>admin_promotion/setStatusPromotion/"+ id +'/'+ value,
  		type: "POST",
  		dataType: "json",
		beforeSend : function (){
			$('#notice'+ id).html('<div ><img src="<?php echo url::base(); ?>themes/admin/pics/loading.gif" alt="" /> waiting </div>');
        },
		complete: function() {
			$('#notice'+ id).html('');
		},
		success: function (data) {
			if(data['type'] == 2 && data['value'] == 'Batch'){
				$('#td_code_'+data['id']+ ' span').css('color', '#4CAF50');
			}
            //alert(data);
		}
	});
}
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
				$.getJSON("<?php echo $this->site['base_url']?>admin_promotion/delete/"+ id,
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
</script>