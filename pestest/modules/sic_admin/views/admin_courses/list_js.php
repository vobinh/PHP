<script type="text/javascript">
$('#btn_recommended').click(function(event) {
	$.ajax({
		url: '<?php echo url::base()?>admin_courses/get_recommended',
		type: 'GET',
		dataType: 'html',
	})
	.done(function(data) {
		$('#div_recommended').html(data);
		$( "#div_recommended" ).dialog({
			//draggable: false,
			modal: true,
			width:'auto',
			height:'auto',
			dialogClass: "no-close",
			autoOpen:true,
			title:"Recommended"
		});
	});
	
});

$('#btn_tags').click(function(event) {
	$.ajax({
		url: '<?php echo url::base()?>admin_courses/get_tags',
		type: 'GET',
		dataType: 'html',
	})
	.done(function(data) {
		$('#div_tags').html(data);
		$( "#div_tags" ).dialog({
			//draggable: false,
			modal: true,
			width:'auto',
			height:'auto',
			dialogClass: "no-close",
			autoOpen:true,
			title:""
		});
	});
	
});

$('#btn_sponsor_tags').click(function(event) {
	$.ajax({
		url: '<?php echo url::base()?>admin_courses/get_sponsor_tags',
		type: 'GET',
		dataType: 'html',
	})
	.done(function(data) {
		$('#div_sponsor_tags').html(data);
		$( "#div_sponsor_tags" ).dialog({
			//draggable: false,
			modal: true,
			width:'auto',
			height:'auto',
			dialogClass: "no-close",
			autoOpen:true,
			title:""
		});
	});
	
});

$('#btn_sponsor_img').click(function(event) {
	$.ajax({
		url: '<?php echo url::base()?>admin_courses/get_sponsor_img',
		type: 'GET',
		dataType: 'html',
	})
	.done(function(data) {
		$('#div_sponsor_img').html(data);
		$( "#div_sponsor_img" ).dialog({
			//draggable: false,
			position:['middle',10],
			modal: true,
			width:'auto',
			height:'auto',
			dialogClass: "no-close",
			autoOpen:true,
			title:""
		});
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
				$.getJSON("<?php echo $this->site['base_url']?>admin_courses/delete/"+ id,
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