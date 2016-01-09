<link rel="stylesheet" href="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="<?php echo url::base()?>js/jquery.quicksearch.js"></script>
<style type="text/css" media="screen">
/*  .pd_reset{
    padding-right: 0;
    padding-left: 0;
  }*/
  .right-inner-addon {
    position: relative;
}
.right-inner-addon input {
    padding-right: 30px;    
}
.right-inner-addon i {
    position: absolute;
    right: 0px;
    padding: 10px 12px;
    pointer-events: none;
}
.mCSB_horizontal.mCSB_inside > .mCSB_container {
  padding-left: 8px;
}
.mCSB_horizontal.mCSB_outside > .mCSB_container{
  padding-left: 8px;
}
</style>
<script>
$(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
      
        duration: 1000
      }
    });
 });
</script>
<?php 
  require Kohana::find_file('views/aws','init');
?>

<div id='dialog'>
	
</div>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <h3 style="margin-top: 0px;">
        My Courses 
      </h3>
      <?php if(empty($mr['mlist'])){?>
        <h4>
          Welcome to <?php echo $this->site['site_name']?>.<br>
          Click on the button below to browse courses.
        </h4>
        <button onclick="javascript:location.href='<?php echo url::base()?>courses/showlist'" style="padding: 10px 30px;" class="btn btn-success" type="button">Browse Courses</button>
      <?php }?>
    </div>
  </div>
  <?php if(!empty($mr['mlist']) && $mr['mlist']!=false){ ?>
    <div class="row">
        <div class="col-sm-6" >
        <?php /*?>
        <button type="button" class="btn <?php if($m_sort == 0){?>btn-success<?php }else{?>btn-default<?php }?>" style="margin-bottom: 5px;min-width: 115px;" onclick="javascript:location.href='<?php echo url::base()?>courses/index/0'">Newest First</button>
        <button type="button" class="btn <?php if($m_sort == 1){?>btn-success<?php }else{?>btn-default<?php }?>" style="margin-bottom: 5px;min-width: 115px;" onclick="javascript:location.href='<?php echo url::base()?>courses/index/1'">Most Popular</button>
      	<?php */?>
      </div>
      <div class="col-sm-6" >
        <input type="search" id="txt_search" class="form-control" style="margin-bottom: 5px;" placeholder="Search" results />
      </div>
    </div>
  <?php }?>
  <div class="row">
  <?php
      // echo '<pre>';
      // print_r($mr['mlist']);
      // die();
    if(!empty($mr['mlist']) && $mr['mlist']!=false){
      foreach ($mr['mlist'] as $id => $list) { ?>
       <div class="col-sm-6 col-md-6 col-lg-4 data_search">
        <div class="panel panel-default box_shadow" style="min-height: 300px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo !empty($list['title'])?$list['title']:''; ?>">
                <?php echo !empty($list['title'])?$list['title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-8 col-md-7 pd_reset text-center" style="margin-bottom: 10px;">
              <div style="margin: auto;" class="box_shadow text-center">
                <?php if(!empty($list['image'])){?>
                  <?php 
                    if(s3_using == 'on'){
                      $check_img = $s3Client->doesObjectExist($s3_bucket, "courses_img/".$list['image']);
                      if($check_img == '1'){ ?>
                        <img class="img-responsive" src="<?php echo linkS3; ?>courses_img/<?php echo $list['image']; ?>" alt="">
                      <?php }else{ ?>
                        <img class="img-responsive" src="<?php echo url::base(); ?>uploads/courses_img/courses_img.png" alt="">
                      <?php }
                    }else{ ?>
                      <img class="img-responsive" src="<?php echo url::base(); ?>uploads/courses_img/<?php echo $list['image']; ?>" alt="">
                <?php }}else{?>
                  <img class="img-responsive" src="<?php echo url::base(); ?>uploads/courses_img/courses_img.png" alt="">
                <?php }?>
              </div>
            </div>
            <div class="col-sm-4 col-md-5 pd_reset text-center" style="margin-bottom: 5px;padding: 0;">
              <!-- Course type -->
              <?php if(isset($list['type']) && $list['type'] == 0){?>
                <button onclick="javascript:location.href='<?php echo url::base()?>courses/study/<?php
                echo base64_encode($list['id']) ?>'" style="padding: 10px 15px; min-width: 85px;" class="btn btn-success" type="button">Launch</button><br>
              <?php }elseif(isset($list['type']) && $list['type'] == 1){?>
                <button onclick="javascript:location.href='<?php echo url::base()?>courses/start/<?php echo base64_encode($list['id_test'].text::random('numeric',3)) ?>/<?php echo base64_encode('0'.text::random('numeric',3)) ?>/<?php echo base64_encode($list['id'].text::random('numeric',3)) ?>'" style="padding: 10px 15px; min-width: 85px;" class="btn btn-success" type="button">Launch</button><br>
              <?php }?>
              <span style="color:red;white-space: nowrap;">Valid for <?php echo !empty($list['day_valid'])?$list['days_left']:'no limit'; ?><?php echo($list['days_left'] > 1)?' days':" day"?></span>
              <div>
                <?php if(isset($list['type']) && $list['type'] == 0){?>
                  <a style="display: inline-flex;white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_0" data-trigger="focus" title=""><b>E-Course&nbsp;</b><span style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
                <?php }elseif(isset($list['type']) && $list['type'] == 1){?>
                  <a style="display: inline-flex;white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_1" data-trigger="focus" title=""><b>Practice Test&nbsp;</b><span style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
                <?php }?>
              </div>
              <div style="height: 40px;margin-top: 5px;">
                <?php if(!empty($list['sponsor_icon'])){
                  if(!empty($list['authorized_day_using']) && !empty($list['authorized_day']) && strtotime("-". $list['authorized_day'] ." day" ) <= $list['authorized_day_using']){
                    $m_date      = strtotime(date('m/d/Y',$list['authorized_day_using']). ' + '.$list['authorized_day'].' day');
                    $date1       = date_create(date('Y-m-d', $m_date));
                    $date2       = date_create(date('Y-m-d'));
                    $diff        = date_diff($date1,$date2);
                    $txt_int_day = (int)$diff->format("%a");
                    //$txt_int_day = round(abs(strtotime(date('m/d/Y',$list['authorized_day_using']). ' + '.$list['authorized_day'].' day') - strtotime(date('m/d/Y')))/(60*60*24));
                  }else{
                    $txt_int_day = 0;
                  }
                  if($txt_int_day > 0){
                    $check_icon = $s3Client->doesObjectExist($s3_bucket, "sponsor_icon/".$list['sponsor_icon']);
                      if($check_icon == '1'){ ?>
                        <img height="40px" width="120px" src="<?php echo linkS3; ?>sponsor_icon/<?php echo $list['sponsor_icon']; ?>" alt="Sponsor Image">
                      <?php } ?>
                <?php }}?>
              </div>
            </div>
            <div class="col-sm-12 pd_reset" style="height: 100px;overflow: hidden;text-align: justify;">
              <?php 
                $m_description = !empty($list['description'])?$list['description']:'';
                echo $m_description;
              ?>
              <!-- Modal -->
              <div class="modal fade" id="myModal_<?php echo $list['id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">
                        <?php echo !empty($list['title'])?$list['title']:''; ?>
                      </h4>
                    </div>
                    <div class="modal-body">
                      <?php 
                        echo $m_description;
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <!--End Modal -->

              <span class="sp_new_overflow"></span>
            </div>
            <div class="tags col-sm-12 pd_reset" style="white-space: nowrap;height: 35px;overflow: hidden;">
            <?php 
            if(!empty($list['tags_id'])){
              $arr_tags = array_filter(explode('|', $list['tags_id']));
              $this->db->in('id',$arr_tags);
              $m_tags = $this->db->get('tags')->result_array(false);
              foreach ($m_tags as $sl => $tag) {
            ?>
              <a href="javascript:void(0)"><?php echo $tag['name']; ?></a>
            <?php 
              }//end for
            }//end if
            ?>
            <?php 
            if($txt_int_day > 0 && !empty($list['sponsor_tags_id'])){
              $arr_sponsor_tags = array_filter(explode('|', $list['sponsor_tags_id']));
              $this->db->in('id',$arr_sponsor_tags);
              $m_sponsor_tags = $this->db->get('sponsor_tags')->result_array(false);
              foreach ($m_sponsor_tags as $sponsor_sl => $sponsor_tag) {
            ?>
              <a href="javascript:void(0)"><?php echo $sponsor_tag['name']; ?></a>
            <?php 
              }//end for
            }//end if
            ?>
            </div>
            <div class="col-sm-12 pd_reset text-right">
              <a tabindex="0" class="link a_read_more" role="button" data-toggle="modal" data-target="#myModal_<?php echo $list['id']?>" data-trigger="focus" title="">Read more&nbsp;<i class="glyphicon glyphicon-circle-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    <?php }
    }
  ?>
  </div>
</div>

<!-- Modal type 0 -->
<div class="modal fade" id="myModal_type_0" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          E-course
        </h4>
      </div>
      <div class="modal-body">
        <b>E-Courses</b> are comprehensive courses consisting of multiple lessons, complete with video lectures, quizzes, tests, score tracking, and completion certificates.
      </div>
    </div>
  </div>
</div>
<!--End Modal -->

<!-- Modal type 1 -->
<div class="modal fade" id="myModal_type_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          Practice Test
        </h4>
      </div>
      <div class="modal-body">
        <b>Practice Tests</b> are test modules which contain just practice tests.
      </div>
    </div>
  </div>
</div>
<!--End Modal -->

<script>
	function showdialog(val , id){
		$.ajax({
			url: '<?php echo url::base()?>test/getTestCategory/'+val+'/'+id,
			type: "GET",
			success: function (data) {
				$('#dialog').html(data);
				$( "#dialog" ).dialog( "open" );
			},
			error: function () {
       		 	alert('Category not set');
  		    }
		});
	}

  $('input#txt_search').quicksearch('div .data_search');
</script>

<script src="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
  <script>
    (function($){
      $(window).load(function(){
        
        $(".tags").mCustomScrollbar({
          axis:"x",
          theme:"minimal-dark",
          alwaysShowScrollbar: 1,
          advanced:{
            autoExpandHorizontalScroll:true
          }
        });
      });
    })(jQuery);
  </script>