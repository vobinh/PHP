<link href="<?php echo url::base()?>js/icheck/skins/all.css?v=1.0.2" rel="stylesheet">
<script src="<?php echo url::base()?>js/icheck/jquery.js"></script>
<script src="<?php echo url::base()?>js/icheck/icheck.js?v=1.0.2"></script>
<?php if(isset($mr['type_time']) && $mr['type_time']==0) {?>
  <script language="javascript" src="<?php echo $this->site['base_url']?>js/Timer/timer.js"></script>
  <script>
  $(document).ready(function() {
    startStopCountUp();
  });
  </script>
<?php }else { ?>
  <script language="javascript" src="<?php echo $this->site['base_url']?>js/Timer/countdown.js"></script>
  <script>
 // $(document).ready(function() {
    countdown("spanTimer",<?php echo $mr['time_value'] ?>, 0 );
  //});
  </script>
<?php } ?>

<script>
  $().ready(function(){
    $('#notice').hide()
   	<?php if($mr['type_time'] == 0 ){ ?>
		  time = '<?php echo (gmdate("H:i:s",($mr["time_value"]*60) - 60)); ?>';
		  cur = 0;
	  <?php }elseif($mr['type_time'] == 1){?>
		  time = '<?php echo '00:01:00'; ?>';
		  cur = 1;
	  <?php }?>
  	setInterval(function(){
			if(cur == 1 && $('#spanTimer').text() <= time){
				$('#notice').html('ONLY '+$('#spanTimer').text());
				$('#notice').show('slow');
				
			}else if(cur == 0 && $('#spanTimer').text() >= time){
				$('#notice').html('TIME '+$('#spanTimer').text());
				$('#notice').show('slow'); 
			}
  	},1000);
  })
</script>

<script>
  $(document).ready(function() {
    //startStopCountUp();
  });
  function counteranswer(){
    var count = $('input:radio:checked').length;
    $("span#counteranswer").html(count+'/'+<?php echo $mr['qty_question'] ?>);
  }
   
  function movetop(){
   	window.scrollTo(0, 0);
  }
  $(function() {
    $('#sel_test').change(function() {
        document.forms['catefrm'].submit();
    });
    $('.demo-list input').on('ifChecked', function(event){
      this.click();
    }).iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
});
</script>

<link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>pagehome/pagehome.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->site['theme_url']?>pagehome/pagehome.js"></script>

<?php 
  $version="";
	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
  if (count($matches)>1){
	 $version = round($matches[1]);
	}
?>

<?php //if(isset($version) && $version !=8) {?>    
<?php //} ?>
<div class="col-ms-12 col-md-12">
	<div id='notice' style="border: 1px solid rgb(238, 153, 137);background-color: rgb(248, 158, 75);float:left; margin-left: 100px; padding: 5px;position: fixed;font-size: 23px;color: rgb(255, 255, 255);display: block;width: auto;min-width:175px;z-index:1;top:102px;font-weight: bold;text-align: center;" >
	    Loading
	</div>

	<div class="flip-counter clock" style="">
	  <span id="spanTimer" style="font-size: 30px;">00:00:00</span>
	</div>

	<div class="counteranswer clock" style="font-size: 18px;">
	  Status: <span id="counteranswer" style="font-size: 22px;">0/<?php echo $mr['qty_question'] ?></span>
	</div>
</div>
<div class="col-ms-12 col-md-12">
  <div class="row">
    <div class="col-ms-12 col-md-12">
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

  <div class="row">
    <div class="col-ms-12 col-md-12">
    <div class="box box_shadow bg_white" style="margin-bottom: 15px;">
      <div class="">
    <!-- data -->
    <?php if(isset($mlist) && count($mlist) >0){ ?>
      <form name="frm" id="frm" action="<?php echo $this->site['base_url']?>courses/resulttest" method="post">
        <?php $category ="";$parent_category =""; $n=0; ?>
        <?php for($k=0;$k<count($mlist);$k++){ ?>

        <?php  
          $key = 0 ;
          $category_id = '';
          $category_id = isset($mlist[$k]['uid'])?$mlist[$k]['uid']:'';
  
          for($i=0;isset($mlist[$k]['questionnaires']) && $i<count($mlist[$k]['questionnaires']);$i++){ 
            $key += 1;  
          }?>
            <input type="hidden" value="<?php echo $category_id.'|'.$key;?>" name="<?php echo 'category[]'?>"/>
            <?php
              for($i=0;isset($mlist[$k]['questionnaires']) && $i<count($mlist[$k]['questionnaires']);$i++) { ?>
                <div style="width:100%; float:left;min-height:180px; <?php if(isset($mlist[$k]['display']) && $mlist[$k]['display'] == 'no'){ echo 'display:none;'; }?>" class="questionnaires" >
                  <?php if(isset($mlist[$k]['category']) && $mlist[$k]['category'] != $category) { ?>
          
                    <?php
                      $category_model = new Category_Model(); 
                      $this->db->where('category.uid',$mlist[$k]['parent_id']);
                      $mr_parent = $category_model->get();
                      echo isset($mr_parent[0]['category']) && $parent_category != $mr_parent[0]['category']?'<div style="font-size:36px">'.$mr_parent[0]['category'].' </div>':"";

                      if(isset($mr_parent[0]['category']))
                        $parent_category = $mr_parent[0]['category'];
                    ?>
                    <div>
                      <h3>
                        <?php echo $mlist[$k]['category']; $category = $mlist[$k]['category'] ?>
                      </h3>
                    </div>
                  <?php } ?>
                  <div class="ques"><strong><?php echo ($n+1) ?></strong>.<strong><?php echo isset($mlist[$k]['questionnaires'][$i]['question'])?$mlist[$k]['questionnaires'][$i]['question']:"" ?></strong></div>
                  <div class="ans demo-list">
                    <?php for($j=0;$j<count($mlist[$k]['questionnaires'][$i]['answer']);$j++) {?>
                      <div class="ans-item" style="margin:5px 0;">
                        <div style="float:left; line-height:1.5em;width:30px;">
                            <label>
                              <input onClick="counteranswer()" id="radio<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>" class="radio4" type="radio"value="<?php echo $mlist[$k]['questionnaires'][$i]['answer'][$j]['type'] ?>|<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>|<?php echo $mlist[$k]['questionnaires'][$i]['category_uid']?>" name="radio<?php echo $mlist[$k]['questionnaires'][$i]['uid'] ?>" style="margin-top:7px;width:auto;" previousvalue="false">
                              <label onClick="counteranswer()" for="radio<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>"></label>
                            </label>
                        </div>
                        <div style="float:left; line-height:1.5em;padding-left:10px;">
                          <?php if(isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['images']) &&!empty($mlist[$k]['questionnaires'][$i]['answer'][$j]['images']) ) {?>
                              <img src="<?php echo linkS3 ?>answer/<?php echo $mlist[$k]['questionnaires'][$i]['answer'][$j]['images']?>" />
                          <?php }else { ?>
                              <?php echo $mlist[$k]['questionnaires'][$i]['answer'][$j]['answer'] ?>
                          <?php } ?>
                        </div>
                      </div>
                    <?php } ?>
                    <input type="hidden" value="<?php echo $mlist[$k]['questionnaires'][$i]['uid'] ?>" name="hd_question[]" />
                </div>
              </div>
            <?php
              if(isset($mlist[$k]['display'])){
                if($mlist[$k]['display'] == 'yes'){
                  $n++;
                }
              }else{
                $n++;
              }
              
            } ?>
          <?php } ?>
        
          <div style="padding-right:8px;clear:both;text-align:center;">
            <input type="hidden" value="" name="hd_timeduration" id="hd_timeduration"  />
            <input type="hidden" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" name="hd_test" id="hd_test"  />
            <input type="hidden" value="<?php echo isset($mr['type_time'])?$mr['type_time']:"" ?>" name="hd_type" id="hd_type"  />
            <input type="hidden" value="<?php echo isset($mr['time_value'])?$mr['time_value']:"" ?>" name="hd_duration" id="hd_duration"  />
            <input type="hidden" value="<?php if(isset($mr['parent_id'])) echo $mr['parent_id']?>" name="parent_id"/>
            <input type="hidden" value="<?php echo $mr['typetest']?>" name="typetest"/>
            <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
            <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>">
            <?php //if(isset($n)&& $n >0) {?>
              <button style="padding: 10px 15px;" class="btn btn-success" type="submit" name="btn_submit" id="btn_submit" onclick="$(this).hide('slow');$('#loading').show('fast');$('#loading_icon').show('fast')"><span>Submit</span>
              </button>
              <img id="loading_icon" src="<?php echo url::base()?>themes/ui/pics/loading_2.gif" style="display:none"/>
            <?php //} ?>
          </div>
      </form>

      <!-- PHAN TRANG -->
      <?php /*?>
      <div id="gallerypaginate" class="paginationstyle" style="padding-right:8px;clear:both;text-align:center;">
        <a href="#" rel="previous">«&nbsp;Previous</a>
        <span class="paginateinfo"></span>
        <a href="#" rel="next">Next&nbsp;»</a>
      </div>
      <?php */?>
      <!-- END PHAN TRANG -->
      <script>
        $(document).ready(function(){
          $('.ques img').addClass('img-responsive').height('auto');
        });
      </script>

      <!-- PHAN TRANG -->
      <?php /*?>
      <script type="text/javascript">
      var gallery = new virtualpaginate({
        piececlass: "questionnaires", //class of container for each piece of content
        piececontainer: 'div', //container element type (ie: "div", "p" etc)
        pieces_per_page: <?php echo $mr['questionpage']; ?>, //Pieces of content to show per page (1=1 piece, 2=2 pieces etc)
        defaultpage: 0, //Default page selected (0=1st page, 1=2nd page etc). Persistence if enabled overrides this setting.
        wraparound: false,
        persist: false
       //Remember last viewed page and recall it when user returns within a browser session?
      })
      gallery.buildpagination(["gallerypaginate"])
      </script>
      <?php */?>
      <!-- END PHAN TRANG -->
    <?php } ?>
    <!-- end data -->
    </div>
  </div>
  </div>
  </div>
</div>

 