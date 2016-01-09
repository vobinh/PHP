<style type="text/css" media="screen">
	#tb_sponsor_img tr:hover{
		background-color: #ffffcc;
	}
</style>
<div style="position: relative;">
	<p style="text-align: center;padding-bottom: 10px;">Sponsor Image</p>
	<div style="max-height: 385px; overflow-y: scroll;border-bottom: 2px solid #000;">
		<table id="tb_sponsor_img">
			<tr>
				<th></th>
				<th></th>
				<th>Image ID</th>
			</tr>
			<?php if(!empty($list_img)){
				foreach ($list_img as $key => $value) { ?>
				<tr id="row_<?php echo $value['id'] ?>">
					<td>
						<a id="delete_<?php echo !empty($value['id'])?$value['id']:'' ?>" href="javascript:delete_img(<?php echo !empty($value['id'])?$value['id']:'' ?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
					</td>
					<td>
						<img src="<?php echo linkS3 ?>sponsor_img/<?php echo $value['name_img'] ?>" width="150px" alt="">
					</td>
					<td align="center" style="font-weight: bold;font-size: 16px;">
						<?php echo $value['id'];?>
					</td>
				</tr>	
			<?php }}?>
		</table>
	</div>
	
	<div style="text-align: center;padding-top: 10px;font-size: 14px;">
		<p style="margin-bottom:5px;"><button style="width: 150px;" onclick="show_crop_img()" type="button" class="btn">Upload...</button></p>
		<p><button style="width: 150px;" onclick="close_crop_img()" type="button" class="btn">Close</button></p>
	</div>
</div>
<div id="wrap_crop_img" style="display:none;">
	
</div>
<script type="text/javascript">
	function close_crop_img(){
		$("#div_sponsor_img" ).dialog('close');
	}
	function show_crop_img() {
	    $.ajax({
	        type: 'POST',
	        url: '<?php echo url::base() ?>admin_courses/crop_img',
	        success: function (resp) { 
	            $('#wrap_crop_img').html(resp);
	            $( "#wrap_crop_img" ).dialog({
					draggable: false,
					modal: true,
					width:'auto',
					height:'auto',
					dialogClass: "no-close",
					autoOpen:true,
					title:"Image Crop"
	         	});
	        }
	    });   
	}
	function delete_img(id){
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
					$.getJSON("<?php echo $this->site['base_url']?>admin_courses/delete_img/"+ id,
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
	}
</script>