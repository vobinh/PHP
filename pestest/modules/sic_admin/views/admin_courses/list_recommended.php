<div>
	<form name="frm_recommended" id="frm_recommended" action="<?php echo url::base()?>admin_courses/save_recommended" method="post">
		<p style="padding-bottom: 10px;font-weight: bold;">Show priority for these course:</p>
		<table id="tb_recommended">
			<tbody>
			<?php 
				if(!empty($list_using)){
					foreach ($list_using as $key => $value) {
			?>
				<tr class="item_recommended">
					<td style="font-weight: bold;"><?php echo $value['location']; ?></td>
					<td>
						<select name="slt_id_course[]" style="width: 100%; max-width: 500px;">
							<?php 
								if(!empty($list_courses)){
									foreach ($list_courses as $sl => $item) {
							?>
								<option <?php if($item['id'] == $value['id']){?> selected <?php }?> value="<?php echo $item['id']?>"><?php echo $item['title']?></option>
							<?php }}?>
						</select>
					</td>
					<td>
						<a href="javascript:void(0)" class="ico_delete remove_sort">Delete</a>
					</td>
				</tr>
			<?php }}?>
			</tbody>
		</table>
		<div style="padding-left: 25px;padding-top: 10px;">
			<button style="background: #1a4a99;border-radius: 100%;padding: 3px;" type="button" id="btn_add_annotation"><span style="font-weight: bold;color: #fff;">+</span></button> Add more 
		</div>
		<div style="padding-top: 10px;text-align: center;">
			<button type="button" id="btn_save_recommended">Save</button>
			<button type="button" class="btn_close_recommended">Cancel</button>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		$(document).on('click', '.remove_sort', function(event) {
			event.preventDefault();
			$(this).parent('td').parent('tr').remove();
			if($('.item_recommended').length > 0){
				$('.item_recommended').each(function(index) {
					$(this).children('td:first').text(index + 1);
				});
			}
		});
		$(document).on('click', '.btn_close_recommended', function(event) {
			event.preventDefault();
			$( "#div_recommended" ).dialog('close');
		});
	});
	$('#btn_add_annotation').click(function(event) {
		var sl = $('.item_recommended').length + 1;

		$.ajax({
			url: '<?php echo url::base()?>admin_courses/add_recommended/'+sl,
			type: 'GET',
			dataType: 'html',
		})
		.done(function(data) {
			$('#tb_recommended').append(data);
		});
	});
	$('#btn_save_recommended').click(function(event) {
		if($('.item_recommended').length <= 0){
			$.msgbox('Please add courses before saving', {
				type : 'error',
				buttons : [
					{type: 'submit', value:'Cancel'}
					]
				}
			);
			return false;
		}
		$('#frm_recommended').submit();
	});
</script>