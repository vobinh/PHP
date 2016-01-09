<script>
    $().ready(function(){
    	$('#noticebox').show('slow');
    	$('#noticebox').delay(3000).hide('slow');
    })
</script>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="frame_content_top" style="font-size: 24px;padding-bottom: 10px;">
                        <a class="text-black" href="<?php echo url::base()?>">My Courses</a> 
                        <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                        <?php if(!empty($courses['id_test'])) {?>
                        <a class="text-black" href="javascript:void(0)"><?php echo !empty($courses['title'])?$courses['title']:'';?></a>
                        <?php }else{?>
                        <a class="text-black" href="<?php echo url::base()?>courses/study/<?php echo !empty($courses['id'])?base64_encode($courses['id']):''?>"><?php echo !empty($courses['title'])?$courses['title']:'';?></a>
                        <?php }?>
                        <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                        <a class="text-black" href="javascript:void(0)"><?php echo !empty($mr['test_title'])?$mr['test_title']:'';?></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
     <?php $category_model = new Category_Model(); $parent_category =""; ?>
        <?php if(isset($mr['test_title'])) { ?>
        <form name="catfrm" id="catfrm" action="<?php echo $this->site['base_url']?>courses/testing" method="post">
            <?php /*?>
            <div class="row font_16">
                <div class="col-sm-4 col-md-4">
                    <div class="box box_shadow bg_white" style="border-radius: 0px !important;margin-bottom: 10px;">
                        <div class="" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" title='<?php echo isset($mr['test_title'])?$mr['test_title']:"" ?>'>
                            Name: <?php echo isset($mr['test_title'])?$mr['test_title']:"" ?>             
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="box box_shadow bg_white" style="border-radius: 0px !important;margin-bottom: 10px;">
                        <div class="">
                        <?php if(!empty($lesson['id_categories'])){?>
                           Qty: <?php echo isset($mr['m_total'])?$mr['m_total']:"0" ?>
                        <?php }else{?>
                            Qty: <?php echo isset($mr['qty_question'])?$mr['qty_question']:"0" ?>
                        <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="box box_shadow bg_white" style="border-radius: 0px !important;margin-bottom: 10px;">
                        <div class="">
                        <?php if(!empty($lesson['id_categories'])){?>
                            Time: <?php
                               if(isset($mr['m_time']) && $mr['m_time'] ==1 ) echo $mr['m_time'].' minute';
                               else if(isset($mr['m_time']) && $mr['m_time'] >1 ) echo $mr['m_time'].' minutes';
                               else  echo 'No Limit';
                              ?>
                        <?php }else{?>
                            Time: <?php
                               if(isset($mr['time_value']) && $mr['time_value'] ==1 ) echo $mr['time_value'].' minute';
                               else if(isset($mr['time_value']) && $mr['time_value'] >1 ) echo $mr['time_value'].' minutes';
                               else  echo 'No Limit';
                              ?>
                        <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="box box_shadow bg_white" style="border-radius: 0px !important;margin-bottom: 10px;">
                        <div class="">
                            <?php echo isset($mr['test_description'])?$mr['test_description']:"" ?>       
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 box box_shadow font_16 bg_white" style="margin-bottom: 10px;">
                <div class="row">
                <?php if(isset($mlist_cate) && count($mlist_cate)>0) {?>
                    <div class="col-sm-12 col-md-12">
                        <h3 style="margin-top: 0; margin-bottom: 0;">Category</h3>
                    </div>
                    <?php 
                        $totalpercent = 0; 
                        for($i=0; $i<count($mlist_cate); $i++) {
                            $totalpercent += (int)$mlist_cate[$i]['category_percentage'];
 
                            $this->db->where('category.uid',$mlist_cate[$i]['parent_id']);
                            $mr_parent = $category_model->get();
                            echo $this->db->last_query();
                            echo '<br>';
                            echo '<pre>';
                            print_r($mr_parent);

                            echo isset($mr_parent[0]['category']) && $parent_category != $mr_parent[0]['category']?'<div class="col-sm-12 col-md-12" style="font-size:16px;padding-left:10px;clear:both;"><p>'.$mr_parent[0]['category'].'</p> </div>':"";
                            
                            if(isset($mr_parent[0]['category']))
                                $parent_category = $mr_parent[0]['category'];
                    ?>
                        <?php if($mlist_cate[$i]['category_percentage'] > 0) {?>
                          <div class="col-sm-6 col-md-4" style="padding-top:5px;padding-bottom:5px;font-size:16px;">
                            <?php 
                                echo $mlist_cate[$i]['category'] ?> : <?php echo isset($mlist_cate[$i]['category_percentage'])?$mlist_cate[$i]['category_percentage'].'%':"";
                            ?>
                          </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="col-sm-12 col-md-12 text-center">
                        <button  <?php if($totalpercent == 0){ echo 'style="display:none;padding: 10px 30px;"';}else{?> style="padding: 10px 30px;" <?php }?> type="submit" name="btn_submit" id="btn_submit" class="btn btn-success"><span>Start</span></button>
                    </div>
                <?php } else {?>
                    <div class="col-sm-12 col-md-12 text-center">
                        <button style="padding: 10px 30px;" type="submit" name="btn_submit" id="btn_submit" class="btn btn-success"><span>Start</span></button>
                    </div>
                <?php }?>
                </div>

                <input type="hidden" name="sel_test" id="sel_test" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" />
                <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
                <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>">
            </div>
            <?php */?>
    
            <!-- Phan moi -->
            <?php if(!empty($courses['id_test'])) {?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default box_shadow" style="border-radius: 0px;">
                        <div class="panel-body">
                            <div class="col-sm-6 col-md-4" style="padding-bottom: 5px; padding-top: 5px;">
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
                            </div>
                            <div class="col-sm-6 col-md-8 font_16" style="padding-bottom: 5px; padding-top: 5px;">
                                <h4 style="margin-top:0"><?php echo !empty($courses['title'])?$courses['title']:'';?></h4>
                                <div style="text-align: justify; margin-bottom: 15px;">
                                    <?php echo !empty($courses['description'])?$courses['description']:'';?>
                                </div>

                                <?php if(isset($mlist_cate) && count($mlist_cate) > 0) {?>
                                <h4 style="margin-top:0;margin-bottom: 0px;">Categories</h4>
                                <table class="table-condensed" style="width:100%;">
                                    <tbody>
                                        <?php 
                                        foreach ($mlist_cate as $key => $value) {
                                        ?>
                                        <tr>
                                            <td style="padding-top: 1px; padding-bottom: 1px;">
                                                <?php echo !empty($value['category'])?($value['category']):'';?>&nbsp;<?php echo !empty($value['category_percentage'])?'('.(int)$value['category_percentage'].'%)':'(0%)';?>
                                            </td>
                                        </tr>
                                        <?php }?>
                                        <tr>
                                            <td style="padding-top: 1px; padding-bottom: 1px;">
                                                <?php if(!empty($lesson['id_categories'])){?>
                                                    <?php echo isset($mr['m_total'])?$mr['m_total']:"0" ?>&nbsp;Questions
                                                <?php }else{?>
                                                    <?php echo isset($mr['total_questions'])?$mr['total_questions']:"0" ?>&nbsp;Questions
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 1px; padding-bottom: 1px;">
                                                Time limit: <?php
                                                    if(isset($mr['time_value']) && $mr['time_value'] ==1 ) echo $mr['time_value'].' minute';
                                                    else if(isset($mr['time_value']) && $mr['time_value'] >1 ) echo $mr['time_value'].' minutes';
                                                    else  echo 'No Limit';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Click "Start" to begin.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php }?>   
                            </div>
                            <div class="col-sm-12 col-md-12 text-center">
                                <button style="padding: 10px 30px;" type="submit" name="btn_submit" id="btn_submit" class="btn btn-success"><span>Start</span></button>
                                <input type="hidden" name="sel_test" id="sel_test" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" />
                                <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
                                <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php }else{?>
            <div class="row font_16">
                <div class="col-sm-12 col-md-12">
                    <div class="box box_shadow bg_white" style="border-radius: 0px !important;margin-bottom: 10px;">
                        <div class="">
                            <h4 style="margin-top:0;margin-bottom: 5px; font-weight: bold;"><?php echo !empty($lesson['title'])?'Quiz for '.$lesson['title']:'';?></h4>
                            <p>Receive a passing score to move on to the next lesson. Click "Start" to begin.</p>

                            <table class="table-condensed" style="width:100%;">
                                <tbody>
                                    <?php 
                                    if(!empty($mlist_cate)){
                                        foreach ($mlist_cate as $key => $value) {
                                    ?>
                                    <tr>
                                        <td style="padding-top: 1px; padding-bottom: 1px; padding-left: 0;">
                                            <h4 style="margin-top:0;margin-bottom: 5px; font-weight: bold;"><?php echo !empty($value['category'])?($value['category']):'';?></h4>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 1px; padding-bottom: 1px;">
                                            <?php echo floor($mr['qty_question'] * $value['category_percentage'] / 100).' Questions'; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 1px; padding-bottom: 1px;">
                                            <?php 
                                                $m_time = floor($mr['time_value'] * $value['category_percentage'] / 100);
                                                if($m_time == 1)
                                                    echo 'Time limit: '.$m_time.' minute';
                                                if($m_time > 1)
                                                    echo 'Time limit: '.$m_time.' minutes';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php }}?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button style="padding: 10px 30px;" type="submit" name="btn_submit" id="btn_submit" class="btn btn-success"><span>Start</span></button>
                            <input type="hidden" name="sel_test" id="sel_test" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" />
                            <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
                            <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>
            <!-- end phan moi -->
        </form> 
    <?php } ?>
</div>
<script>
$(function() {
    $('#sel_test').change(function() {
        document.forms['catfrm'].submit();
    });
});
</script>

 