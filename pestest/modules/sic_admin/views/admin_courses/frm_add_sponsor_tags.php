<div>
	<form name="frm_recommended" id="frm_recommended" action="<?php echo url::base()?>admin_courses/save_recommended" method="post">
		<p style="padding-bottom: 10px;font-weight: bold; text-align: center;">Attach tag</p>
		<table>
			<tbody>
				<tr>
					<td>
						<select name="slt_id_sponsor_course" id="slt_sponsor_tags" style="width: 100%; max-width: 500px;">
							<?php 
								if(!empty($list_tags)){
									foreach ($list_tags as $sl => $item) {
							?>
								<option value="<?php echo $item['id'].'|'.$item['name'];?>"><?php echo $item['name']?></option>
							<?php }}?>
						</select>
					</td>
				</tr>
			</tbody>
		</table>
		<div style="padding-top: 10px;text-align: center;">
			<button type="button" id="btn_save_add_sponsor_tags">Save</button>
			<button type="button" class="btn_close_add_sponsor_tags">Cancel</button>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		$(document).on('click', '.btn_close_add_sponsor_tags', function(event) {
			event.preventDefault();
			$( "#wrap_add_sponsor_tags" ).dialog('close');
		});
	});
	
	$('#btn_save_add_sponsor_tags').click(function(event) {
		var slt  = $('#slt_sponsor_tags');
		var data = slt.val().split('|');
		$('#wap_main_sponsor_tags').append('<span class="sponsor_tags_wap"><span>'+data[1]+'</span><span class="sponsor_tags_remove">x</span><input type="hidden" name="txt_sponsor_tags_id[]" value="'+data[0]+'"></span>');
	});
</script>