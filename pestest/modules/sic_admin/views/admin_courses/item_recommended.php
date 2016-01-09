<tr class="item_recommended">
	<td style="font-weight: bold;"><?php echo $sl; ?></td>
	<td>
		<select name="slt_id_course[]" style="width: 100%; max-width: 500px;">
			<?php if(!empty($list_courses)){?>
				<?php foreach ($list_courses as $key => $value) {?>
					<option value="<?php echo $value['id']?>"><?php echo $value['title']?></option>
			<?php }}?>
		</select>
	</td>
	<td>
		<a href="javascript:void(0)" class="ico_delete remove_sort">Delete</a>
	</td>
</tr>