<style type="text/css" media="screen">
	.videowrapper {
	    float: none;
	    clear: both;
	    width: 100%;
	    position: relative;
	    padding-bottom: 56.25%;
	    /*padding-top: 25px;*/
	    height: 0;
	    margin-bottom: 20px;
	}
	.videowrapper iframe {
	    position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	}
	.div_inactive{
		display: none;
	}
	.div_active{
		display: block;
	}
	<?php if(isset($courses["video_control"]) && $courses["video_control"] == 0){ ?>
	#video_time {
		bottom: 6px;
		position: absolute;
		padding: 5px;
    	font-weight: bold;
    	display: none;
    	background-color: rgba(68, 68, 68, 0.44);
    	color: #ddd;
    	width: 100%;
	}
	#progress_Bar {
		width: 100%;
		height: 6px;
		display: none;
		margin-top: 1px;
		background-color: rgba(68, 68, 68, 0.44);
		bottom: 0px;
		position: absolute;
	}
	#progress_Bar div {
		height: 100%;
		width: 0;
		background-color: red;
	}
	.videowrapper:hover #progress_Bar, .videowrapper:hover #video_time{
		display: block !important;
	}
	<?php }?>
</style>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
	      	<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="frame_content_top" style="font-size: 24px;padding-bottom: 10px;">
                        <a class="text-black" href="<?php echo url::base()?>">My Courses</a> 
                        <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                        <a class="text-black" href="<?php echo url::base()?>courses/study/<?php echo !empty($courses['id'])?base64_encode($courses['id']):''?>"><?php echo !empty($courses['title'])?$courses['title']:'';?></a>
                    	<img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                    	<a class="text-black" href="javascript:void(0)"><?php echo !empty($lesson['title'])?$lesson['title']:'';?></a>
                    </td>
                </tr>
            </table>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_shadow" style="border-radius: 0px;">
	            <div class="panel-body">
	            <?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
	            	<!-- phan video -->
	            	<div class="col-sm-8 col-md-7">
		            	<div class="videowrapper box_shadow">
		            		<div id="player"></div>
		            		<div id="video_time">
		            			<div id="current_time"></div>
		            			<div id="total_time"></div>
		            		</div>
		            		<div id="progress_Bar"><div></div></div>
		            	</div>
	            		<?php 
	            			$id_video = !empty($lesson['video_link'])?$lesson['video_link']:'';
	            			if(!empty($id_video)){
	            				$url = $id_video;
								parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
								$id_video = $my_array_of_vars['v']; 

	            			}
	            			$percent_video_pass = !empty($lesson['percent_lesson_pass'])?$lesson['percent_lesson_pass']:100;
	            		?>
	            	</div>
	            	<!-- end phan video -->
	            	<!-- phan soil -->
	            	<div class="col-sm-4 col-md-5">
	            		<div class="panel panel-default box_shadow" style="border-radius: 0;">
	            			<div class="panel-body">
	            				<!-- <h3 style="margin-top: 0px;">TESTING SOIL</h3> -->
	            				<div class="content_annotation" style="overflow-y: auto;">
		            				<?php 
		            					$arr_id_text = array();
		            					if(!empty($lis_annotation)){
		            						foreach ($lis_annotation as $key => $value) {
		            							$arr_time = explode(':', $value['time']);
		            							if(!empty($arr_time)){
													$total = 0;
													$sl    = count($arr_time);
		            								foreach ($arr_time as $stt => $time) {
		            									if($sl == 2){
		            										switch ($stt) {
			            										case 0:
			            											if((int)$time>0){
			            												$total = $total + ((int)$time * 60);
			            											}
			            											break;
			            										case 1:
			            											if((int)$time>0){
			            												$total = $total + ((int)$time);
			            											}
			            											break;
			            									}
		            									}elseif($sl == 3){
		            										switch ($stt) {
			            										case 2:
			            											if((int)$time>0){
			            												$total = $total + ((int)$time);
			            											}
			            											break;
			            										case 1:
			            											if((int)$time>0){
			            												$total = $total + ((int)$time * 60);
			            											}
			            											break;
			            										case 0:
			            											if((int)$time>0){
			            												$total = $total + ((int)$time * 60 * 60);
			            											}
			            											break;
			            									}
		            									}
		            									
		            								}
		            								$arr_id_text[] = $total;

		            							}
		            				?>
	            					<div id="div_text_<?php echo $key ?>" class="div_text div_inactive">
		            					<div id="div_area_<?php echo $key ?>"></div>

		            					<textarea id="txt_area_<?php echo $key ?>" name="" style="display:none;">
		            						<?php echo $value['text']; ?>
		            					</textarea>

		            					<script type="text/javascript">
		            						$('#div_area_<?php echo $key ?>').html($('#txt_area_<?php echo $key ?>').val().replace(/\r?\n/g,'<br/>'));
		            					</script>
	            					</div>
	            					<?php }}?>
	            				</div>
	            			</div>
	            		</div>
	            	</div>
	            	<!-- end phan soil -->
	            <?php }elseif(!empty($lesson) && $lesson['hide_annotation'] == 'Y'){?>
	            	<!-- phan video -->
	            	<div class="col-sm-2 col-md-2 col-lg-2.5 visible-lg" style="margin-right: 4.16666%;"></div>
	            	<div class="col-sm-12 col-md-12 col-lg-7">
		            	<div class="videowrapper box_shadow">
		            		<div id="player"></div>
		            		<div id="video_time">
		            			<div id="current_time"></div>
		            			<div id="total_time"></div>
		            		</div>
		            		<div id="progress_Bar"><div></div></div>
		            	</div>
	            		<?php 
	            			$id_video = !empty($lesson['video_link'])?$lesson['video_link']:'';
	            			if(!empty($id_video)){
	            				$url = $id_video;
								parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
								$id_video = $my_array_of_vars['v']; 
	            			}
	            			$percent_video_pass = !empty($lesson['percent_lesson_pass'])?$lesson['percent_lesson_pass']:100;
	            		?>
	            	</div>
	            	<div class="col-sm-2 col-md-2 col-lg-2 visible-lg"></div>
	            	<!-- end phan video -->
	            <?php }?>
	            	<div class="clearfix"></div>
	            	<div class="col-md-12">
	            		<p>Clicking next will take you to the quiz for this lesson module.</p>
	            		<?php 
	            			if(!empty($lesson['video_pass']) && $lesson['video_pass'] == 'Y'){
	            		?>
	            			<button onclick="javascript:location.href='<?php echo $this->site['base_url']?>courses/start/<?php echo base64_encode($lesson['id_test_pass'].text::random('numeric',3)) ?>/<?php echo base64_encode($lesson['id'].text::random('numeric',3)) ?>'" style="padding-left: 20px;padding-right: 20px;" type="button" class="btn btn-success">Next&nbsp;<span class="glyphicon glyphicon-arrow-right"></span></button>
	            		<?php }else{?>
	            			<button onclick="check_video('<?php echo base64_encode($lesson['id_test_pass'].text::random('numeric',3)) ?>','<?php echo base64_encode($lesson['id'].text::random('numeric',3)) ?>')" style="padding-left: 20px;padding-right: 20px;" type="button" class="btn_next btn btn-danger">Next&nbsp;<span class="icon_next glyphicon glyphicon-lock"></span></button>
	            		<?php }?>
	            		<?php if(isset($using_download) && $using_download){?>
	            			<button onclick="fn_download()" style="padding-left: 20px;padding-right: 20px;float: right;" type="button" class="btn_next btn btn-primary">Download Notes&nbsp;<span class="glyphicon glyphicon-download-alt"></span></button>
	            		<?php }?>
	            		<input type="hidden" name="txt_video_pass" id="txt_video_pass" value="<?php echo !empty($lesson['video_pass'])?$lesson['video_pass']:'N'; ?>">
	            	</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<?php if(isset($using_download) && $using_download){?>
<form style="display:none;" id="frm_download" action="<?php echo url::base()?>courses/download_s3/<?php echo !empty($lesson['attach_file'])?$lesson['attach_file']:'' ?>" method="get">
</form>
<?php }?>
<script>
	/*
		get list annotation text
	*/
	<?php
		if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){
			$js_array = json_encode($arr_id_text);
			echo "var arr_id_text = ". $js_array . ";\n";
		}
	?>
	/*
		end get list annotation text
	*/
	//console.log(arr_id_text);
	var video_control      = '<?php echo !empty($courses["video_control"])?$courses["video_control"]:0 ?>';
	var percentloaded      = 0;
	var percent_video_pass = '<?php echo $percent_video_pass ?>';
	var video_pass         = '<?php echo $lesson["video_pass"] ?>';
	var video_upload       = false;
	if(video_pass == 'Y')
		video_upload = true;
	var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	function progress(percent, $element) {
	  var progressBarWidth = percent * $element.width() / 100;
	  $element.find('div').animate({ width: progressBarWidth });
	}

	function secondsTimeSpanToHMS(s) {
	    var h = Math.floor(s/3600); //Get whole hours
	    s -= h*3600;
	    var m = Math.floor(s/60); //Get remaining minutes
	    s -= m*60;
	    return (h < 10 ? '0'+h : h)+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
	}

	function onYouTubeIframeAPIReady() {
		player = new YT.Player('player', {
			height: '390',
			videoId: '<?php echo $id_video?>',
			playerVars:{
				autoplay: 1,
				modestbranding: 1,
				rel: 0,
				controls: video_control,
				disablekb: 1,
				showinfo: 0,
			},
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange,
			}
		});
		<?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
			$('.content_annotation').height($('iframe').height() - 32);
		<?php }?>
	}

	function onPlayerReady(event) {
		//event.target.playVideo();
		setInterval(updateytplayerInfo, 600);
	    updateytplayerInfo();
	    <?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
	    	$('.div_text').removeClass('div_active').addClass('div_inactive');
	    <?php }?>
	    
	    
	}
	function onPlayerStateChange(event) {
		<?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
			$('.div_text').removeClass('div_active').addClass('div_inactive');
		<?php }?>
		if(video_control == 0){
			if (event.data == YT.PlayerState.PLAYING) {
				$('#progress_Bar').show();
				var playerTotalTime = getDuration();
				$('#total_time').text('Total Duration: '+secondsTimeSpanToHMS(parseInt(playerTotalTime)));
				mytimer = setInterval(function() {
					var playerCurrentTime = getCurrentTime();
					$('#current_time').text('Playback: '+secondsTimeSpanToHMS(parseInt(playerCurrentTime)));
					var playerTimeDifference = (playerCurrentTime / playerTotalTime) * 100;

		        	progress(playerTimeDifference, $('#progress_Bar'));
		      	}, 1000);
			}else{
				clearTimeout(mytimer);
	      		$('#progress_Bar').hide();
			}
		}
	}
	function updateytplayerInfo() {
		if (player) {
			percentloaded = getDuration()*percent_video_pass/100;
			if(getCurrentTime() >= percentloaded){
				if(video_upload == false){
					video_upload = true;
					$.ajax({
						url: '<?php echo url::base()?>courses/video_pass',
						type: 'POST',
						dataType: 'json',
						data: {'id_lesson': '<?php echo $lesson["id"] ?>'},
					})
					.done(function(data) {
						if(data == 1){
							$('#txt_video_pass').val('Y');
							$('.icon_next').removeClass('glyphicon-lock').addClass('glyphicon-arrow-right');
							$('.btn_next').removeClass('btn-danger').addClass('btn-success');
						}
					})
					.fail(function() {
						//console.log("error");
					});
				}
				
			}
			<?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
				if(arr_id_text != null){
					$.each(arr_id_text, function(index, val) {
						if(getCurrentTime() >= val){
							$('.div_text').removeClass('div_active').addClass('div_inactive');
							$('#div_text_'+index).removeClass('div_inactive').addClass('div_active');
						}
					});
				}
			<?php }?>
		}
	}
	function getVideoLoadedFraction() {
	  return player.getVideoLoadedFraction();
	}

	function getDuration() {
	  return player.getDuration();
	}

	function getCurrentTime() {
	  var currentTime = player.getCurrentTime();
	  return roundNumber(currentTime, 2);
	}

	function roundNumber(number, decimalPlaces) {
	  decimalPlaces = (!decimalPlaces ? 2 : decimalPlaces);
	  return Math.round(number * Math.pow(10, decimalPlaces)) /
	      Math.pow(10, decimalPlaces);
	}

	function check_video(id,lesson_id){
		var check = $('#txt_video_pass').val();
		if(check == 'N'){
			$.growl.error({ message: "You cannot proceed to the next step until you have watched at least <?php echo $percent_video_pass ?>% of the video." });
		}
		if(check == 'Y'){
			window.location.href = '<?php echo url::base()?>courses/start/'+id+'/'+lesson_id;
		}
	}

	function fn_download(){
		$('#frm_download').submit();
	}
	<?php if(!empty($lesson) && $lesson['hide_annotation'] == 'N'){?>
		$( window ).resize(function() {
			$('.content_annotation').height($('iframe').height() - 32);
		});
	<?php }?>
</script>