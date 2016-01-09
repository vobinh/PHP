<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
	      	<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="frame_content_top" style="font-size: 24px;padding-bottom: 10px;">
                        <a class="text-black" href="<?php echo url::base()?>">My Courses</a> 
                        <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                        <a class="text-black" href="javascript:void(0)"><?php echo !empty($courses['title'])?$courses['title']:'Study';?></a>
                    </td>
                </tr>
            </table>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_shadow" style="border-radius: 0px;">
	            <div class="panel-body">
					<div class="col-sm-6 col-md-4">
						<div style="border-radius: 10px; margin: auto;" class="box_shadow text-center">
			                <?php if(!empty($courses['image'])){?>
			                  <?php if(s3_using == 'on'){?>
			                    <img style="border-radius: 10px;" class="img-responsive"  src="<?php echo linkS3; ?>courses_img/<?php echo $courses['image']; ?>" alt="">
			                  <?php }else{ ?>
			                    <img style="border-radius: 10px;" class="img-responsive"  src="<?php echo url::base(); ?>uploads/courses_img/<?php echo $courses['image']; ?>" alt="">
			                <?php }}else{?>
			                    <img style="border-radius: 10px;" class="img-responsive" src="<?php echo url::base(); ?>uploads/courses_img/courses_img.png" alt="">
			                <?php }?>
			              </div>
						<div style="margin-top: 15px; text-align: justify;">
							<?php echo !empty($courses['description'])?$courses['description']:'';?>
						</div>
					</div>
					<div class="col-sm-6 col-md-8">
						<h3 style="margin-top:0">Lesson Modules</h3>
						<table class="table-condensed" style="width:100%;">
							<tbody>
							<?php 
								if(!empty($list_lesson)){
									//echo('<pre>');
									//print_r($list_lesson);
									foreach ($list_lesson as $key => $value) {
							?>
								<tr>
									<td>
										<?php echo !empty($value['title'])?$value['title']:'';?>
									</td>
									<td>
									<?php if($key == 0){?>
										<button onclick="javascript:location.href='<?php echo $this->site['base_url']?>courses/lesson/<?php echo base64_encode($value['id']) ?>'" style="padding-left:15px; padding-right:15px;" class="btn btn-success btn-xs" type="button">Start</button>
									<?php }elseif($key > 0){?>
										<?php if(!empty($list_lesson[$key-1]['lesson_pass']) && $list_lesson[$key-1]['lesson_pass']=='Y'){?>
											<button onclick="javascript:location.href='<?php echo $this->site['base_url']?>courses/lesson/<?php echo base64_encode($value['id']) ?>'" style="padding-left:15px; padding-right:15px;" class="btn btn-success btn-xs" type="button">Start</button>
										<?php }else{?>
											Finish&nbsp;<?php echo !empty($list_lesson[$key-1]['title'])?$list_lesson[$key-1]['title']:'';?>&nbsp;to Unlock
										<?php }?>
									<?php }?>
									</td>
								</tr>
							<?php }}?>
							<?php if(!empty($courses['id_certificate']) && !empty($courses_unlock) && $courses_unlock == 'yes'){?>
								<tr>
									<td style="font-weight: bold;">
										Certificate
									</td>
									<td>
										<a href='<?php echo url::base()?>courses/print_certificate/<?php echo !empty($list_certificate[0]['id'])?base64_encode($list_certificate[0]['id']):'0';?>' target="_blank" style="padding-left:15px; padding-right:15px;" class="btn btn-primary btn-xs" type="button">View</a>
									</td>
								</tr>
							<?php }?>
							</tbody>
						</table>	
					</div>
				</div>
			</div>
		</div>
	</div>
</div>