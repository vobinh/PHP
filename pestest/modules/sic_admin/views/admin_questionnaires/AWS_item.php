<?php 
if(!empty($list_img)){
	foreach ($list_img as $key => $value) { 
		if($value['Size'] >0 ) { ?>
		<div class="cls_wap">
			<div class="cls_img">
				<img src="<?php echo linkS3.$value['Key'] ?>" alt="">
			</div>
			<div class="cls_slt">
				<a onclick="fn_slt('<?php echo linkS3.$value['Key'] ?>')" href="javascript:void(0)" class="btn btn-primary" title="">Select</a>
			</div>
		</div>
<?php }}} ?>