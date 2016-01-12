<link rel="stylesheet" href="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.css">
<script src="<?php echo url::base()?>js/jquery.quicksearch.js"></script>
<style type="text/css" media="screen">
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
<?php 
  require Kohana::find_file('views/aws','init');
?>
<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <h3 style="margin-top: 0px;">
        Purchase Courses 
      </h3>
    </div>
  </div>
  <?php //if(!empty($mr['mlist']) && $mr['mlist']!=false){ ?>
    <div class="row">
        <div class="col-sm-6" >
        <button type="button" class="btn <?php if($m_sort == 'recommended'){?>btn-success<?php }else{?>btn-default<?php }?>" style="margin-bottom: 5px;min-width: 125px;" onclick="javascript:location.href='<?php echo url::base()?>courses/showlist/recommended'">Recommended</button>
        <button type="button" class="btn <?php if($m_sort == 'newest'){?>btn-success<?php }else{?>btn-default<?php }?>" style="margin-bottom: 5px;min-width: 125px;" onclick="javascript:location.href='<?php echo url::base()?>courses/showlist/newest'">Newest First</button>
        <button type="button" class="btn <?php if($m_sort == 'popular'){?>btn-success<?php }else{?>btn-default<?php }?>" style="margin-bottom: 5px;min-width: 125px;" onclick="javascript:location.href='<?php echo url::base()?>courses/showlist/popular'">Most Popular</button>
      </div>
      
      <div class="col-sm-6" >
        <form name="frm_search" id="frm_search" action="<?php echo url::base()?>courses/showlist/<?php echo $m_sort?>" method="post">
          <div class="input-group">
          <span class="input-group-addon" style="padding: 0px;margin: 0px;background: none;border: none;">
              <select name="slt_tags" id="slt_tags" style="max-width: 116px;margin-bottom: 5px;height: 34px;border-radius: 4px;border-top-right-radius: 0;border-bottom-right-radius: 0;border-color: #ccc;border-right: 0px;">
                <option value="0">All States</option>
                <?php
                if(!empty($list_tags)){
                  foreach ($list_tags as $item => $tag) {
                ?>
                  <option <?php if($slt_tags == $tag['id']) echo 'selected'; ?> value="<?php echo $tag['id']; ?>"><?php echo $tag['name']; ?></option>
                <?php
                  }//end for
                }//end if
                ?>
              </select>
            </span>
            <span class="input-group-addon" style="padding: 0px;margin: 0px;background: none;border: none;">
              <select name="slt_type_search" id="slt_type_search" style="margin-bottom: 5px;height: 34px;border-radius: 0px;border-right: 0px;border-color: #ccc;">
                <option <?php if($slt_type_search == 0) echo 'selected'; ?> value="0">All Types</option>
                <option <?php if($slt_type_search == 1) echo 'selected'; ?> value="1">E-Courses</option>
                <option <?php if($slt_type_search == 2) echo 'selected'; ?> value="2">Practice Tests</option>
              </select>
            </span>
            <input type="search" id="txt_search" class="form-control" style="margin-bottom: 5px;" placeholder="Search" results />
          </div><!-- /input-group -->
        </form>
      </div>

    </div>
  <?php //}?>
  <div class="row">
  <?php
      // echo '<pre>';
      // print_r($mr['mlist']);
    if(!empty($mr['mlist']) && $mr['mlist']!=false){
      foreach ($mr['mlist'] as $id => $list) {
        $m_tags   = array();
        if(!empty($list['tags_id'])){
          $arr_tags = array_filter(explode('|', $list['tags_id']));
          if(!empty($arr_tags)){
            $this->db->in('id',$arr_tags);
            $m_tags = $this->db->get('tags')->result_array(false);
          }
        }

        $m_sponsor_tags   = array();
        if(!empty($list['sponsor_tags_id'])){
          $arr_sponsor_tags = array_filter(explode('|', $list['sponsor_tags_id']));
          if(!empty($arr_sponsor_tags)){
            $this->db->in('id',$arr_sponsor_tags);
            $m_sponsor_tags = $this->db->get('sponsor_tags')->result_array(false);
          }
        }
  ?>
      <?php if(in_array($list['id'],$arraypayment)){ ?>
       <div class=" col-sm-6 col-md-6 col-lg-4 data_search <?php echo isset($list['type'])?'data_'.$list['type']:''?>">
        <div class="panel panel-default box_shadow my_courses" style="min-height: 300px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo !empty($list['title'])?$list['title']:''; ?>">
                <?php echo !empty($list['title'])?$list['title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-8 col-md-7 pd_reset text-center" style="margin-bottom: 10px;">
              <div style="margin: auto;background-color: #fff;" class="box_shadow text-center">
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
            <div class="col-sm-4 col-md-5 pd_reset text-center" style="margin-bottom: 5px;padding: 0px;">
              <span style="display: inline-block;font-size: 12px;font-weight: bold;height: 42px;overflow: hidden;line-height: 15px;">You have already purchased this course.</span>
              <h4 style="font-size:14px; margin: auto; font-weight: bold;white-space: nowrap;">See <a tabindex="0" class="link text-success" href="<?php echo url::base()?>">My Courses</a></h4>
              <div>
              <?php if(isset($list['type']) && $list['type'] == 0){?>
                <a style="display: inline-flex; white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_0" data-trigger="focus" title=""><b>E-Course&nbsp;</b><sapn style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
              <?php }elseif(isset($list['type']) && $list['type'] == 1){?>
                <a style="display: inline-flex; white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_1" data-trigger="focus" title=""><b>Practice Test&nbsp;</b><sapn style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
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
            <div class="col-sm-12 pd_reset" style="height: 100px;overflow: hidden; text-align: justify;">
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
              <span class="sp_my_overflow"></span>
            </div>
            <div class="tags col-sm-12 pd_reset" style="white-space: nowrap;height: 35px;overflow: hidden;">
            <?php 
            if(!empty($list['tags_id']) && !empty($m_tags)){
              foreach ($m_tags as $sl => $tag) {
            ?>
              <a href="javascript:void(0)"><?php echo $tag['name']; ?></a>
            <?php 
              }//end for
            }//end if
            ?>
            <?php 
              if($txt_int_day > 0 && !empty($list['sponsor_tags_id']) && !empty($m_sponsor_tags)){
                foreach ($m_sponsor_tags as $sl_sponsor => $sponsor_tag) {
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
      <?php }else{ ?>
         <div class="col-sm-6 col-md-6 col-lg-4 data_search <?php echo isset($list['type'])?'data_'.$list['type']:''?>">
        <div class="panel panel-default box_shadow" style="min-height: 300px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px; white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title="<?php echo !empty($list['title'])?$list['title']:''; ?>">
                <?php echo !empty($list['title'])?$list['title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-8 col-md-7 pd_reset text-center" style="margin-bottom: 10px;">
              <div style="margin: auto;background-color: #fff;" class="box_shadow text-center">
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
            <div class="col-sm-4 col-md-5 pd_reset text-center" style="margin-bottom: 5px;padding: 0px;">
              <button 
              <?php if($list['price'] != 0 && $this->site['site_cart'] == 1 ){?>
               onclick="javascript:location.href='<?php echo $this->site['base_url']?>payment/index/<?php echo base64_encode($list['id'])?>'"
              <?php }else{?>
                <?php /*?>onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php echo base64_encode($list['id'].text::random('numeric',3))?>/<?php echo text::random('numeric',3)?>'"<?php */?>
                onclick="javascript:location.href='<?php echo $this->site['base_url']?>courses/free_courses/<?php echo base64_encode($list['id'].text::random('numeric',3))?>/<?php echo text::random('numeric',3)?>'"
              <?php }?>
              style="padding: 10px 15px; min-width: 85px;" class="btn btn-success" type="button">
              <b><?php echo $this->format_currency($list['price'])?></b>
              </button>
              <br>
              <span style="color:red; white-space: nowrap;padding-top:1px; display:inline-block;">Valid for <?php echo !empty($list['day_valid'])?$list['day_valid']:'no limit'; ?><?php echo($list['day_valid'] > 1)?' days':" day"?></span>
              <div>
              <?php if(isset($list['type']) && $list['type'] == 0){?>
                <a style="display: inline-flex; white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_0" data-trigger="focus" title=""><b>E-Course&nbsp;</b><span style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
              <?php }elseif(isset($list['type']) && $list['type'] == 1){?>
                <a style="display: inline-flex; white-space: nowrap;" tabindex="0" class="link a_code_type" role="button" data-toggle="modal" data-target="#myModal_type_1" data-trigger="focus" title=""><b>Practice Test&nbsp;</b><span style="font-size: 20px;" class="glyphicon glyphicon glyphicon-question-sign"></span></a>
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
            if(!empty($list['tags_id']) && !empty($m_tags)){
              foreach ($m_tags as $sl => $tag) {
            ?>
              <a href="javascript:void(0)"><?php echo $tag['name']; ?></a>
            <?php 
              }//end for
            }//end if
            ?>
            <?php 
              if($txt_int_day > 0 && !empty($list['sponsor_tags_id']) && !empty($m_sponsor_tags)){
                foreach ($m_sponsor_tags as $sl_sponsor => $sponsor_tag) {
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
    <?php }}
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

<script type="text/javascript">
  var qs = $('input#txt_search').quicksearch('div .data_search');
  $('#slt_type_search').change(function(event) {
    $('#frm_search').submit();
  });
  $('#slt_tags').change(function(event) {
    $('#frm_search').submit();
  });
  <?php /* ?>
  $('#slt_type_search').change(function(event) {
    if($(this).val() == 0){
      $('.data_0').addClass('data_search').show();
      $('.data_1').addClass('data_search').show();
      qs.cache();
    }else if($(this).val() == 1){
      $('.data_0').addClass('data_search').show();
      $('.data_1').removeClass('data_search').hide();
      qs.cache();
    }else if($(this).val() == 2){
      $('.data_0').removeClass('data_search').hide();
      $('.data_1').addClass('data_search').show();
      qs.cache();
    }
  });
  <?php */?>
</script>
<script src="<?php echo url::base()?>js/malihu-custom-scrollbar-plugin-master/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
	(function($){
	  	$(window).load(function(){
		    $(".tags").mCustomScrollbar({
		      	axis:"x",
		      	theme: "minimal-dark",
		      	alwaysShowScrollbar: 1,
		      	advanced:{
		        	autoExpandHorizontalScroll:true
		      	}
		    });
	  	});
	})(jQuery);
</script>