<div>
	<form name="frm_tags" id="frm_tags" action="<?php echo url::base()?>admin_courses/save_tags" method="post">
		<p style="padding-bottom: 10px;font-weight: bold;text-align: center;">Register tags</p>
		<table id="tb_tags">
			<tbody>
			<?php 
				if(!empty($list_tags)){
					foreach ($list_tags as $key => $value) {
			?>
				<tr class="item_tags">
					<td style="font-weight: bold;">
						<input type="text" name="txt_tags_name[]" value="<?php echo $value['name']; ?>" placeholder="">
						<input type="hidden" name="txt_tags_id[]" value="<?php echo $value['id']; ?>">
					</td>
					<td>
						<a href="javascript:void(0)" class="ico_delete remove_tags">Delete</a>
					</td>
				</tr>
			<?php }}?>
			</tbody>
		</table>
		<div style="padding-left: 25px;padding-top: 10px;">
			<button style="background: #1a4a99;border-radius: 100%;padding: 3px;" type="button" id="btn_add_tag"><span style="font-weight: bold;color: #fff;">+</span></button> Add tags 
		</div>
		<div style="padding-top: 10px;text-align: center;">
			<button type="button" id="btn_save_tags">Save</button>
			<button type="button" class="btn_close_tags">Cancel</button>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		$(document).on('click', '.remove_tags', function(event) {
			event.preventDefault();
			$(this).parent('td').parent('tr').remove();
		});
		$(document).on('click', '.btn_close_tags', function(event) {
			event.preventDefault();
			$( "#div_tags" ).dialog('close');
		});
	});
	$('#btn_add_tag').click(function(event) {
		$('#tb_tags').append('<tr class="item_tags"><td style="font-weight: bold;"><input type="text" name="txt_tags_name[]" value="" placeholder=""><input type="hidden" name="txt_tags_id[]" value=""></td><td><a href="javascript:void(0)" class="ico_delete remove_tags">Delete</a></td></tr>');
	});
	$('#btn_save_tags').click(function(event) {
		var flag = true;
		var data = $('input[name="txt_tags_name[]"]');
		$.each(data,function(index, val) {
			if($(this).val() == ''){
				flag = false;
				return false;
			}
		});
		
		if(!flag){
			$.msgbox('Tag name must not be blank', {
				type : 'error',
				buttons : [
					{type: 'submit', value:'Cancel'}
					]
				}
			);
			return false;
		}

		if($('.item_tags').length <= 0){
			$.msgbox('Please add tags before saving', {
				type : 'error',
				buttons : [
					{type: 'submit', value:'Cancel'}
					]
				}
			);
			return false;
		}
		$('#frm_tags').submit();
	});
</script>