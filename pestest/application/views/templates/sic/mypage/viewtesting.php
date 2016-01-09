<style type="text/css" media="screen">
  .pagination{
    margin: 5px 0;
  }
</style>
<script>
  $(function() {
    $( "#dialog" ).dialog({
      autoOpen: false,
      width:1000,
      modal: true,
      position:['middle',20],
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
<div id="dialog" title="Testing Detail" ></div>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>mypage/search" method="post">
<input type="hidden" name="txt_hd_type_change" id="txt_hd_type_change" value="">
<div class="col-md-12 main_data_13">
  <div class="row">
      <div class="col-md-12">
        <h3 style="margin-top: 0px;">
          Testing History
        </h3>
        <h4>
          <span style="white-space: nowrap;padding-bottom: 5px;display: inline-block;">
            Courses : 
            <select class="form-control input_frm" style="width:auto;display:inline" name="sel_courses" id="sel_courses" onchange="fn_courses()">
              <?php if(!empty($courses)){foreach($courses as $key => $value){?>
                <option
                <?php if(isset($this->search['courses']) && ( $this->search['courses'] == $value['id'])){ 
                 echo 'selected="selected"'; }?>  
                value="<?php echo $value['id']?>" ><?php echo $value['title']?>
                </option>
              <?php }}?>
            </select>
          </span>
          <?php if(isset($m_type_courses) && $m_type_courses == 0){?>
          <span style="white-space: nowrap;padding-bottom: 5px;display: inline-block;">
            Lesson:
            <select class="form-control input_frm" style="width:auto;display:inline" name="sel_lesson" id="sel_lesson" onchange="fn_lesson()">
              <?php
                $vt_lesson = 0; 
                if(!empty($lesson)){
                  foreach($lesson as $key => $value){?>
                <option
                <?php if(isset($this->search['lesson']) && ( $this->search['lesson'] == $value['id'])){ 
                  $vt_lesson = $key;
                  echo 'selected="selected"'; }?>  
                value="<?php echo $value['id']?>" ><?php echo $value['title']?>
                </option>
              <?php }
                $item_lesson = $lesson[$vt_lesson];
              }?>
            </select>
          </span>
          <?php }?>
        </h4>
      </div>
  </div>
  <!-- tab -->
  <div class="row">
    <div class="col-md-12">
      <div id="tabs_1" class="main_data_13 box_shadow" style="border: none;border-radius: 0; background-color: #fff;">
        <ul>
          <li><a href="#tabs-1">List</a></li>
          <li><a href="#tabs-2">Chart</a></li>
          <li><a href="#tabs-3">Chart Category</a></li>
        </ul>
        <div id="tabs-1">
          <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="table-responsive">
                  <table class="table table-striped" style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px;">
                    <tr style="padding:5px;font-weight:bold;background: #AED9D2;">
                      <td align="center"><?php echo  'Code' ?></td>
                      <td align="center"><?php echo  'Test' ?></td>
                      <td align="center"><?php echo  'Type' ?></td>
                      <td align="center"><?php echo  'Date' ?></td>
                      <td align="center"><?php echo  'Score' ?></td>
                      <td align="center"><?php echo  'Duration' ?></td>
                      <td align="center"><?php echo  'Action' ?></td>
                    </tr>

                    <!-- data -->
                    <?php 
                      if(!empty($mlist) && $mlist!=false){
                        foreach($mlist as $id => $list){ ?>
                        <tr id="row_<?php echo $list['uid']?>" >
                            <td align="center">
                              <?php echo isset($list['testing_code'])?$list['testing_code']:''?>
                            </td>
                            <td align="center">
                              <?php echo isset($list['test']['test_title'])?$list['test']['test_title']:''?>
                            </td>
                            <td align="center">
                              <?php
                                switch($list['testing_type']){
                                    case 1:
                                        echo    'Whole Test';
                                    break;
                                    case 2:
                                        echo    'Only Missing';
                                    break;
                                    case 3:
                                        echo    'By Category';
                                    break;
                                    default :
                                        echo    '';
                                }
                              ?>
                            </td>
                            <td align="center">
                              <?php echo isset($list['testing_date'])?$this->format_int_date($list['testing_date'],$this->site['site_short_date']):''?>
                            </td>
                            <td align="center">
                              <?php echo ($list['parent_score'] + $list['testing_score']); //(isset($list['testing_type']) && $list['testing_type'] == 1)?(isset($list['parent_score'])?$list['parent_score']:''):(isset($list['testing_score'])?$list['testing_score']:'')?>
                            </td>
                            <td align="center">
                              <?php echo isset($list['duration'])?gmdate('H:i:s',$list['duration']):''?>
                            </td>
                            <td align="center">
                              <a style="cursor:pointer" onclick="loadtestingdetail('<?php echo url::base()?>mypage/viewtesting/<?php echo isset($list['uid'])?$list['uid']:''?>'+'/'+'<?php echo $list['testing_code']?>');$('#dialog').dialog('open');" class="ico_edit" id="link_testing_detail">
                                    <?php echo Kohana::lang('global_lang.btn_view') ?></a>
                              <?php /*?>
                              <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
                                  class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
                              <?php */?>   
                            </td>
                          </tr>
                      <?php } // end if ?>
                      <?php } // end foreach ?>
                    <!-- end data -->
                  </table>
                </div>
                <div class='pagination1' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?></div>
            </div>
          </div>
        </div>
        <!-- end tab 1 -->
        <div id="tabs-2">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <?php if(!empty($chartlist) && $chartlist!=false){?>
              <script>
              $(document).ready(function() {
                var char_1 =  $('#container').highcharts({
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
                         '#a6c96a']
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
                      <?php  if(!empty($chartlist) && $chartlist!=false){foreach($chartlist as $id => $list){ ?>
                          '<?php
                            echo $this->format_int_date($list["testing_date"],"d/m/y H:i");
                          ?>',
                      <?php }}?>      
                      ]},
                      yAxis: [{ // Primary yAxis
                          max: 100,
                          min:0,
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
                      }, { // Secondary yAxis
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
                      }],
                      tooltip: {
                          formatter: function() {
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
                          ]},
                          <?php }
                      }*/?>
                      {
                        name: 'Pass Score',
                        color: '#F00000',
                        type: 'spline',
                        data:[
                          <?php
                            if(isset($m_type_courses) && $m_type_courses == 0){
                              echo '[ 0,'.(!empty($item_lesson['percent_test_pass'])?$item_lesson['percent_test_pass']:'0').'],';
                              echo '[ '.(count($chartlist)- 1).','.(!empty($item_lesson['percent_test_pass'])?$item_lesson['percent_test_pass']:'0').'],';
                            }elseif(isset($m_type_courses) && $m_type_courses == 1){
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
               <div id="container" style="height: 400px; margin: 0 auto"></div>
                <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <!-- end tab 2 -->
        <div id="tabs-3">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <?php if(!empty($arraycategory)){?>
                <script>
                  $(document).ready(function() {
                      $('#chartcategory').highcharts({
                        chart: {
                          type: 'column'
                        },
                        title: {
                            text: '<?php echo $arraytest[0]; ?>',
                            x: -20 //center
                        },
                        credits: {
                            enabled: false
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
                            <?php foreach($arraydate as $list){ ?>'<?php echo $list ?>',<?php }?>
                          ]
                        },
                        yAxis: {
                          max: 100,
                          min:0,
                          title: {
                             text: 'Percent'
                          }
                        },
                        tooltip: {
                          valueSuffix: '%'
                        },
                        series: [<?php

                            foreach($arraycategory as $key => $value){?>{
                            name: '<?php echo $key; ?>',
                            data: [
                              <?php
                                $i=0;
                                foreach($arraycode as $list => $percent){?>
                                    [<?php echo !empty($value[$list])?$value[$list]:0; ?>],
                                <?php
                                }?> 
                            ]},
                            <?php 
                            }?>
                        ]
                    });
                });
               </script>
               <?php }?>
               <div id="chartcategory" style="height: 400px; margin: 0 auto">
               ----- No Category Available -----
               </div>
            </div>
          </div>
        </div>
        <!-- end tab 3 -->
    </div>
  </div>
</div>
  </div>  
</form>
  <div class="col-sm-12" style="padding-top:10px;">
    <div class="clearfix"></div>    
</div>

<script type="text/javascript">
  $(document).ready( function() {
    $("#tabs_1").tabs({
      beforeActivate: function(event, ui) {
        setTimeout(function(){
          <?php if(!empty($chartlist) && $chartlist!=false){?>
            $('#container').highcharts().reflow();
          <?php }?>
          <?php if(!empty($arraycategory)){?>
            $('#chartcategory').highcharts().reflow();
          <?php }?>
        }, 10);
      },
      create: function( event, ui ) {
        setTimeout(function(){
          <?php if(!empty($chartlist) && $chartlist!=false){?>
            $('#container').highcharts().reflow();
          <?php }?>
          <?php if(!empty($arraycategory)){?>
            $('#chartcategory').highcharts().reflow();
          <?php }?>
        }, 10);
      }
    });
   });
</script>
<script>
function fn_lesson(){
  $('#txt_hd_type_change').val(2);
  $('#frmlist').submit();
}

function fn_courses(){
  $('#txt_hd_type_change').val(1);
  $('#frmlist').submit();
}

function loadtestingdetail(val1){
	$.ajax({
		url:val1,
		type: "GET",
		success: function(data){
			$('#dialog').html(data);
		}
	});
}
</script>