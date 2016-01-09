<script type="text/javascript">
$('#btn_open_import').click(function(event) {
	$('#txt_import').val('');
	$( "#div_import" ).dialog({
      resizable: false,
      height:'auto',
      width:500,
      modal: true,
      open: function(event, ui) { 
        $(this).parent().css('fontSize', '14px');
    },
      buttons: {
        "Import": function() {
        	var ext = $('#txt_import').val().split('.').pop().toLowerCase();
        	if(ext === ''){
        		$.msgbox('File empty!', {
					type : 'confirm',
					buttons : [
						{type: 'submit', value:'Cancel'},
						]
					}
				);
        	}else if(ext != 'xml'){
        		$.msgbox('Please, Selected file .xml!', {
					type : 'confirm',
					buttons : [
						{type: 'submit', value:'Cancel'},
						]
					}
				);
        	}else{
        		$('#frm_import').submit();
        	}
          //$( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
});
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
				$.getJSON("<?php echo $this->site['base_url']?>admin_test/delete/"+ id,
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