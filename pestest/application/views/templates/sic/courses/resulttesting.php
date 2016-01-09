<link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>pagehome/pagehome.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->site['theme_url']?>pagehome/pagehome.js"></script>
<style type="text/css" media="screen">
  #tabs_result{
    background: #fff;
    border: none;
    border-radius: 0;
  }
</style>
<script >
  function movetop(){
    window.scrollTo(0, 0);
  }
  function getChart(cateuid, member_uid, id_lesson,id_courses){
  	$('#container2').hide('fast');
  	$('#container').show('fast');
  	$.ajax({
  	url: '<?php echo url::base()?>courses/getChartByCategory/'+cateuid+'/'+member_uid+'/'+id_lesson+'/'+id_courses,
  	type: "GET",
  	dataType: "json",
  	success: function (data) {
  		var category = new Array();
  		var percent = new Array();
  		for (var result in data) {
   		  category.push(data[result]['str_date']);
  			percent.push(parseInt(data[result]['percentage']));
  		}
  				
  		name = data[0]['name'];
  		$('#container').highcharts({
        chart: {
          type: 'column'
        },
              title: {
                  text: 'Percent',
                  x: -20 //center
              },
              subtitle: {
                  text: '',
                  x: -20
              },
  			credits: {
              	enabled: false
         		},
              xAxis: {
  				labels: {
  					style: {
  					color: '#6D869F',
  					fontSize: '10px',  
  				},
  				rotation: -50 },
                  categories: category,
              },
              yAxis: {
  				max: 100,
  				min:0,
                  title: {
                      text: 'Percent'
                  },
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }]
              },
              tooltip: {
                  valueSuffix: '%'
              },
              
              series: [{
                  name: name,
                  data: percent
              }]
          });
          setTimeout(function(){
            $('#container').highcharts().reflow();
          }, 100);
          
  		}
  	});
  }
</script> 
<script>
  $(function() {
      $( "#dialog" ).dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        show: {
          effect: "blind",
          duration: 1000
        },
        hide: {
          duration: 1000
        }
      });
   
      $("#btn_by_category").click(function() {
        $( "#dialog" ).dialog( "open" );
      });
   });
</script>

<?php if(empty($mr['mlist']) && empty($mr['arrayquestion'])){?>
  <script>
    $('#category').hide('slow');
  </script>
<?php }?>

<!-- dialog -->
<div id="dialog" title="Category Test Again" style="display:none;">
  <form id="frmcategory" action="<?php echo url::base() ?>courses/testingwrong" method="post">
    <input type="hidden" value="<?php echo $mr['idtest']?>" name="hd_test"/>
    <input type="hidden" value="<?php echo $mr['testing_code']?>" name="testing_code"/>
    <input type="hidden" value="<?php echo $mr['parent_id']?>" name="parent_id"/>

    <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
    <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"" ?>">

    <table class="table table-striped table-bordered table-condensed main_data_13" width="100%" >
    	<?php 
    	foreach($mr['olist'] as $key => $value){?>
        <tr>
          <td >
            <input style="width:20px !important" type="checkbox" value="<?php echo $key ?>" name="ocategory[]"/>
          </td>
          <td>
            <?php echo $value ?>
          </td>
        </tr>
    	<?php }?>
        <tr>
        	<td >
            <input style="width:20px !important" type="checkbox" onclick="$('input:checkbox').attr('checked', this.checked);" />
          </td>
        	<td colspan="2">
            <span>Check all</span> 
            <button type="submit" style="float: right;" class="btn btn-success btn-sm"
                onclick="
                val = 0
                $('input:checkbox').each(function () {
                    if (this.checked) {
                  	 val = 1; 
              		}
    			      });
                if(val == 1){
                	 $('#frmcategory').attr('action','<?php echo url::base() ?>test/testingcategory');
                	 $('#frmcategory').submit();
                }
                else {
                  $.growl.error({ message: 'Please choise category' });
                  return false;
                }
               	" name="btn_wrong">
              <span> Start </span>
            </button>
          </td>
        </tr>
    </table>
  </form>
</div>
<!-- end dialog -->
<div class="col-sm-12 col-md-12">
  <div class="row">
    <div class="col-sm-12 col-md-12">
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
                <a class="text-black" href="javascript:void(0)"><?php echo !empty($mr['last_test'][0]['test']['test_title'])?$mr['last_test'][0]['test']['test_title']:'';?></a>
            </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="row" style="position: relative;">
    <div class="col-sm-4 col-lg-4" style="min-height: 115px;">
      <span class="testname" style="margin-top: 10px;display: inline-block;"><?php echo $mr['last_test'][0]['test']['test_title']?></span><br />        
      <span class="score">Score : <?php echo $scoreparent;//($mr['last_test'][0]['testing_type'] == 1)?$mr['last_test'][0]['parent_score']:$mr['last_test'][0]['testing_score']?></span>
    </div>
    <div class="col-sm-3 col-lg-3" style="min-height: 115px;">
      <span class="datetest" style="margin-top: 10px;display: inline-block;">Type: <?php  
        switch((int)$mr['last_test'][0]['testing_type']){
          case 1:
            echo  'Whole Test';
          break;
          case 2:
            echo  'Only Missing';
          break;
          case 3:
            echo  'By Category';
          break;
          default :
            echo    '';
        }
        ?>
      </span><br />
      <span class="datetest">Date : <?php echo $this->format_int_date($mr['last_test'][0]['testing_date'],$this->site['site_short_date'])?></span><br />
      <span class="duration">Duration : <?php echo gmdate('H:i:s',$mr['last_test'][0]['duration'])?></span>
    </div>
    <div class="col-sm-5 col-lg-5 text-right" style="min-height: 115px;">
              <!-- button -->
          <form id="testagain" method="post" action="<?php echo url::base() ?>courses/testing">
            <input type="hidden" value="<?php echo $mr['idtest']?>" name="sel_test"/>
            <input type="hidden" name="txt_id_courses" id="txt_id_courses" value="<?php echo isset($courses['id'])?$courses['id']:"" ?>">
            <input type="hidden" name="txt_id_lesson" id="txt_id_lesson" value="<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>">
            <input type="hidden" name="txt_finish" id="txt_finish" value="">
            <?php 
              if(!empty($courses) && $courses['type'] == 0){
                if((!empty($study[0]['lesson_pass']) && $study[0]['lesson_pass'] == 'Y') ||$scoreparent >= $lesson['percent_test_pass']) {?> 
                  <div>
                    <button style="min-width:110px; margin-top: 10px;" class="btn btn-success btn-lg" type="button" name="btn_finish" id="btn_finish" onclick="fn_finish()">
                    Finish
                    </button>
                  </div>
                <?php }else{?>
                  <div style="text-align: center;margin-top: 10px;font-weight: bold;">
                    <span>Your score is not high enough to proceed to the next lesson. Click either "Test Again" or "Only Missing" to improve your score.</span>
                  </div>
                <?php }?>
              <?php }elseif(!empty($courses) && $courses['type'] == 1){
                if((!empty($study[0]['course_pass']) && $study[0]['course_pass'] == 'Y') || $scoreparent >= $chartlist[0]['test']['pass_score']) {?> 
                  <div>
                    <button style="min-width:110px; margin-top: 10px;" class="btn btn-success btn-lg" type="button" name="btn_finish" id="btn_finish" onclick="fn_finish()">
                      Finish
                    </button>
                  </div>
                <?php }else{?>
                  <div style="text-align: center;margin-top: 10px;font-weight: bold;">
                    <span>Your score is not high enough to proceed to the next lesson. Click either "Test Again" or "Only Missing" to improve your score.</span>
                  </div>
                <?php }?>
            <?php }?>
            
            <button style="min-width:110px; margin-top: 10px;margin-left: 5px;" class="btn btn-success" type="button" name="btn_submit" id="btn_submit" onclick="$('#testagain').submit()" title="Retake the entire test." data-toggle="tooltip">
              Test Again
            </button>

            <button <?php if($scoreparent >= 99.9) echo 'style="display:none"';?> style="min-width:110px; margin-top: 10px;margin-left: 5px;" class="btn btn-success" type="button" name="btn_missing" id="btn_onlymissing" onclick="$('#frmcategory').submit()" title="Retake just the portion you got wrong." data-toggle="tooltip">
              <span>Only Missing</span>
            </button>
     
            <button style="min-width:110px; margin-top: 10px;margin-left: 5px;" class="btn btn-success" type="button" name="btn_submit" id="btn_submit" onclick=" $('.showwrong').hide('fast'); $('#contail').show('slows'); $('#container').hide('fast'); $('#container2').show('fast'); $('#tabs_result').tabs('option', 'active', 0 );">
              <span>Total Score</span>
            </button>
          </form>
        <!-- end button -->
    </div>
  </div>
  <div class="clearfix" style="padding: 5px;"></div>
  <!-- tab -->
  <div class="row">
    <div class="col-md-12">
      <div id="tabs_result" class="main_data_13 box_shadow" style="overflow: hidden;">
        <ul>
          <li><a href="#tabs-1">RESULT</a></li>
          <?php 
            /*
              remove Missed Questions
            
          ?>
          <li <?php if(empty($mr['arrayquestion'])) echo 'style="display:none"'?> ><a href="#tabs-2">Missed Questions</a></li>
          <?php */?>
        </ul>
        <div id="tabs-1" style="overflow: hidden;">
          <div class="col-md-6">
              <?php if(!empty($mr['mlist'])){
                  //echo '<pre>';
                  //print_r($mr['mlist']);
                  //die();
              ?>
              <div class="box_shadow" id='category'>
              <div class="table-responsive">
                <table class="table table-striped table-condensed main_data_13" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
                <?php
                  $i=0;
                  foreach($mr['mlist'] as $key => $value){ ?>
                    <tr>
                      <td align="left">
                        <?php echo $key ?>: <span style="color:red"><?php echo  ($value[1] != 0)? number_format(($value[0]*100)/$value[1], 2, ',', ' '):''; ?>% </span><br />
                        ( total <?php echo $value[1]?> - answer : <?php echo $value[0];?> )
                      </td>
                    
                      <td width="15%" align="center" style="vertical-align: middle;">
                        <?php if((($value[0]*100)/$value[1]) != 100) {?>
                          <button type="button" class="btn" id="detail<?php echo $value[2]?>" onclick="
                            $('#contail').hide('fast');
                            $('#showwrong').html($('#showdetail<?php echo $value[2]?>').html());
                            $('#showwrong').show('slow');
                            $('#showwrong #showdetail').attr('style','display:block;position: absolute; width: 40%;border: 1px solid #fff;background: #fff;text-align: left;top: 40%;left: 48%; box-shadow:#ccc 0 0 5px;')">Missed Questions</button>
                        <?php }?>       
                        <div class="showdetail" id="showdetail<?php echo $value[2]?>" style="display:none;position: absolute; width: 40%;border: 1px solid #fff;background: #fff;text-align: left;top: 40%;left: 48%; box-shadow:#ccc 0 0 5px; ">
                          <table class="table table-striped table-condensed main_data_13 table-bordered" style="margin-bottom: 0;" width="100%">
                            <tr style="text-align:center; background:#bbb; color:#555; line-height:30px;font-size:16px; font-weight:bold">
                              <td colspan="3" class="title_test"><?php echo ucfirst($key) ?></td>
                            </tr>
                            <tr style="background:#F3F3F1">
                              <td class="text-center"><strong>Question</strong></td>
                              <td class="text-center"><strong>Your Answer</strong></td>
                              <td class="text-center"><strong>Right Answer</strong></td>
                            </tr>
                            <?php if(!empty($value['answer'])){
                              foreach($value['answer'] as $val){
                                 $question = isset($val['question'])?$val['question']:'';
                                 $has    = (isset($val['has']) && $val['has'] != '')?$val['has']:'No Answer';
                                 $answer = isset($val['answer'])?$val['answer']:'';

                                 $m_images    = (isset($val['images']) && $val['images'] != '')?$val['images']:'';
                                 $hasimages = isset($val['hasimages'])?$val['hasimages']:'No Answer';
                                 if(!empty($val['images'])){
                                    if($m_images != $hasimages){ ?>
                                      <tr>
                                        <td class="ques_img"> <?php echo $question?></td>
                                        <td>
                                          <?php if(isset($val['hasimages']) && $val['hasimages'] != ''){ ?>
                                             <img alt="" src="<?php echo linkS3?>answer/<?php echo $val['hasimages'] ?>" class="img-responsive">
                                          <?php }else{ echo 'No Answer'; } ?>
                                        </td>
                                        <td>
                                          <?php if(isset($val['images']) && $val['images'] != ''){ ?>
                                             <img alt="" src="<?php echo linkS3?>answer/<?php echo $val['images'] ?>" class="img-responsive">
                                          <?php }?>
                                        </td>
                                      </tr>
                                    <?php }
                                 }else
                                 if($has != $answer){?>
                                    <tr>
                                      <td class="ques_img"> <?php echo $question?></td>
                                      <td> <?php echo $has?></td>
                                      <td> <?php echo $answer?></td>
                                    </tr>
                                <?php }
                              }
                            }?> 
                          </table>
                        </div>
                      </td>
                
                      <td width="15%" align="center" style="vertical-align: middle;">
                        <button type="button" class="btn" id="viewchart<?php echo $value[2]?>" onclick=" $('.showwrong').hide('fast'); $('#contail').show('slows'); getChart('<?php echo $value[2]?>','<?php echo $this->sess_cus['id']?>','<?php echo isset($lesson['id'])?$lesson['id']:"0" ?>','<?php echo isset($courses['id'])?$courses['id']:"0" ?>')">view chart</button>
                      </td>
                    </tr>
                  <?php }?>
                </table>
                </div>
              </div>
            <?php }?>
          </div> <!-- end div left -->

          <div class="col-md-6">
            <div class="showwrong box_shadow" id="showwrong" style="display:none;">
            </div>
            <div class="box_shadow" id="contail" >
            <?php
              $arraytest = array();
              $arrayhas = array();
              if(!empty($chartlist) && $chartlist!=false){
                // echo '<pre>';
                // print_r($chartlist);
                // die();
                for($i = 0 ; $i<count($chartlist);$i++){
                  $temp = $chartlist[$i]['test']['test_title'];
                  if(!in_array($temp ,$arrayhas))
                  {
                    $arraytest[] = $chartlist[$i]['test']['test_title'];
                  }
                  $arrayhas[$i] = $chartlist[$i]['test']['test_title'];
                }
              }
               
            if(!empty($chartlist) && $chartlist!=false){?>
              <script>
               $(function () {
                  $('#container2').highcharts({
                    chart: {
                      zoomType: 'xy',
                      type: 'column'
                    },
                    credits: {
                      enabled: false
                    },
                    colors: [
                     '#2f7ed8', 
                     '#F00000', 
                     '#0d233a', 
                     '#910000', 
                     '#1aadce', 
                     '#492970',
                     '#f28f43', 
                     '#77a1e5', 
                     '#c42525', 
                     '#a6c96a'
                    ]
                    ,
                    title: {
                      text: '<?php echo $arraytest[0]; ?>',
                      x: -20 //center
                    },
                    subtitle: {
                      text: 'pestest.com',
                      x: -20
                    },
                    xAxis: {
                      labels: {
                        style: {
                          color: '#6D869F',
                          fontSize: '10px',  
                        },
                        rotation: -50
                      },
                      categories:[
                        <?php if(!empty($chartlist) && $chartlist!=false){foreach($chartlist as $id => $list){ ?>
                          '<?php
                            echo $this->format_int_date($list["testing_date"],"d/m/y H:i");
                          ?>',
                        <?php }}?>    
                      ]
                    },
                    yAxis: [
                      { 
                        max: 100,
                        min:0,// Primary yAxis
                        labels: {
                          format: '',
                          style: {
                            color: '#89A54E'
                          }
                        },
                        title: {
                          text: 'Score',
                          style: {
                            color: '#89A54E'
                          }
                        }
                      },
                      { // Secondary yAxis
                        title: {
                          text: '',
                          style: {
                              color: '#4572A7'
                          }
                        },
                        labels: {
                          format: '{value} mm',
                          style: {
                            color: '#4572A7'
                          }
                        },
                        opposite: true
                      }
                    ],
                    tooltip: {
                      formatter: function() {
                        //console.log(this);
                        var strdate = this.x.split('-',-1)[0];  
                        var datetermp = strdate.substr(2,2)+'/'+strdate.substr(4,2)+'/20'+strdate.substr(0,2);
                        return 'Date: ' + this.x + '<br>Score: ' + this.y + '</b>';
                      }
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal'
                        }
                    },
                     // legend: {
                       //   layout: 'vertical',
                      //    align: 'left',
                      //    x: 120,
                      //    verticalAlign: 'top',
                      //    y: 100,
                      //    floating: true,
                     //     backgroundColor: '#FFFFFF'
                    //  },
                    series: [
                    <?php
                      if(!empty($chartlist) && $chartlist!=false){
                        foreach($arraytest as $value){?>
                      {
                        name: 'only missing',
                        color: '#ADFF2F',
                        data: [
                          <?php
                            $i=0;
                            foreach($chartlist as $id => $list){
                              if($value == $list['test']['test_title']){ ?>
                                [
                                  <?php echo $i;?> , <?php echo $list['testing_score'];?>
                                ],
                              <?php } $i++;
                          }?> 
                        ],
                      },
                      <?php }
                      }?>
                      
                      <?php
                      if(!empty($chartlist) && $chartlist!=false){
                        foreach($arraytest as $value){?>
                      {
                        name: 'New Test',
                        color: '#22B14C',
                        data: [
                          <?php
                            $i=0;
                            foreach($chartlist as $id => $list){
                              if($value == $list['test']['test_title']){ ?>
                                [
                                  <?php echo $i;?> , <?php echo $list['parent_score'];?>
                                ],
                              <?php } $i++;
                          }?> 
                        ],
                      },
                      <?php }
                      }?>
                <?php /*
                if(!empty($chartlist) && $chartlist!=false){
                  foreach($arraytest as $value){?>{
                          name: 'Score',
                          type: 'spline',
                          data: [
                  <?php
                    $i=0;
                    foreach($chartlist as $id => $list){
                      if($value == $list['test']['test_title']){ ?>
                      [
                                <?php echo $i;?> , <?php echo $list['parent_score'];?>
                      ],
                      <?php } $i++;
                    }?> 
                  ]},

                  <?php }
                }*/?>

                {
                    name: 'Pass Score',
                    color: '#F00000',
                    type: 'spline',
                    data:[
                    
                      <?php 
                        if(!empty($courses) && $courses['type'] == 0){
                          echo '[ 0,'.(!empty($lesson['percent_test_pass'])?$lesson['percent_test_pass']:'0').'],';
                          echo '[ '.(count($chartlist)- 1).','.(!empty($lesson['percent_test_pass'])?$lesson['percent_test_pass']:'0').'],';
                        }elseif(!empty($courses) && $courses['type'] == 1){
                          echo '[ 0,'.(!empty($chartlist[0]['test']['pass_score'])?$chartlist[0]['test']['pass_score']:'0').'],';
                          echo '[ '.(count($chartlist)- 1).','.(!empty($chartlist[0]['test']['pass_score'])?$chartlist[0]['test']['pass_score']:'0').'],';
                        }
                        
                      ?>
                    
                    ],
                    enableMouseTracking: false,
                        marker: {
                              enabled: false
                          },
                      }]
                  });
              });
              
             </script>
             <?php }?>

          <div id="container" >
          </div>

          <div id="container2" >
          </div>
            </div>
          </div>
        </div> <!-- end tab 1 -->

        <?php /*?>
        <div id="tabs-2" <?php if(empty($mr['arrayquestion'])){ echo 'style="display:none"'; }else{ ?>style="overflow: hidden;"<?php }?> >
          <div class="col-sm-12 col-md-12">
          <!-- data -->
            <?php
              $i=0;
              if(!empty($mr['arrayquestion']) && (isset($mr['last_test'][0]['test']['displayexplanation']) && ($mr['last_test'][0]['test']['displayexplanation'] == 'Y'))){?>
              <div class="box_shadow">
                <table class="table table-striped table-condensed main_data_13" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 0;">
                  <?php
                  foreach($mr['arrayquestion'] as $value){?>
                    <tr class="infowrong" style="border-bottom: 1px solid #ccc;">
                      <td align="left" style="border-top: 0;">
                        <span style="color:#FF0000">
                          Question: <?php echo isset($value['question'])?$value['question']:'';?>
                        </span>
                        <?php  
                          if($value['hasimages']!='')
                            echo'<br />Your Answer: <img src="'.url::base().'uploads/answer/'.$value['hasimages'].'" width="20px" />';
                          elseif($value['hasanswer']!='')
                            echo '<br />Your Answer: '.$value['hasanswer'];
                          else
                            echo '<br />No Answer';
                        ?>
                           
                        <?php  
                          if($value['images']!='')
                            echo '<br />Right Answer:  <img src="'.url::base().'uploads/answer/'.$value['images'].'" width="20px" />';
                          elseif($value['answer']!='')
                            echo '<br />Right Answer: '.$value['answer']; 
                          else
                            echo'';
                        ?>
                           
                        <?php 
                          if(isset($value['answer_description']) && ($value['answer_description'] != "")) 
                            echo '<br />Explanation: '.$value['answer_description']; 
                          else echo '';
                        ?>
                      </td>
                    </tr>
                  <?php }?>
                </table>
                </div>
              <?php }?>
              <!-- phan trang -->
              <div id="gallerypaginate" class="paginationstyle" style="padding-right:8px;clear:both;text-align:center;">
                <a href="javascript:void(0)" rel="previous"><< Previous</a>
                <span class="paginateinfo"></span>
                <a href="javascript:void(0)" rel="next">Next >></a>
              </div>
            <!-- end phan trang -->
            <!-- end data -->
          </div>
        </div>
        <?php */?>
        <!-- end tab 2 -->
      </div>
    </div>
  </div>
  <!-- end tab -->
</div> <!-- div main --> 
<div class="col-sm-12" style="padding-top:10px;">
  <div class="clearfix"></div>  
</div>
<script type="text/javascript">
  $(document).ready( function() {
    $("#tabs_result").tabs({
      beforeActivate: function(event, ui) {
        setTimeout(function(){
          if($('#container').html().length > 50){
            $('#container').highcharts().reflow();
          }
          $('#container2').highcharts().reflow();
        }, 10);
      },
      create: function( event, ui ) {
        setTimeout(function(){
          $('#container2').highcharts().reflow();
        }, 10);
      }
    });
  });
  $('.ques_img img').addClass('img-responsive').height('auto');
  var gallery = new virtualpaginate({
  	piececlass: "infowrong", //class of container for each piece of content
  	piececontainer: 'tr', //container element type (ie: "div", "p" etc)
  	pieces_per_page: 10, //Pieces of content to show per page (1=1 piece, 2=2 pieces etc)
  	defaultpage: 0, //Default page selected (0=1st page, 1=2nd page etc). Persistence if enabled overrides this setting.
  	wraparound: false,
  	persist: false
   //Remember last viewed page and recall it when user returns within a browser session?
  })
  gallery.buildpagination(["gallerypaginate"])

  function fn_finish(){
    $('#txt_finish').val(1);
    $('#testagain').submit();
  }
</script>
 