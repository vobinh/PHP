<script>
function loadtestting_phantrang(val1){
  $.ajax({
    url: url,
    processData: false,
    contentType: false,
    type: "POST",
    data: new FormData($('#frm_cours')[0]),
    success: function(data){
      $('#dialogtestting').html(data);
    }
  });
}
$().ready(function(){
	$('.pagination3 .pagination a').click(function(){
		url = $(this).attr('href');
		loadtestting_phantrang(url);
		return false;
	})
})
</script>
<script>
 $(function() {
    $( "#tabs" ).tabs();
  });
</script>
<form name="frm_cours" id="frm_cours" action="<?php echo url::base() ?>admin_member/search_testing" method="post" style="width:950px; font-size: 14px;">
<input type="hidden" name="txt_hd_type_change" id="txt_hd_type_change" value="">
<select class="form-control input_frm" style="width:auto;display:inline" name="sel_courses" id="sel_courses" onchange="load_corses(1)">
  <option value="empty" > Select Test </option>
  <?php 
  if(!empty($courses)){foreach($courses as $key => $value){?>
    <option
      <?php if($value['id'] == $m_id_courses){ echo 'selected="selected"'; }?> value="<?php echo $value['id']?>" ><?php echo $value['title']?>
    </option>
  <?php }}?>
</select>
 <?php 
    if(isset($m_type_courses) && $m_type_courses == 0){
      $vt_lesson = 0;
  ?>
<select class="form-control input_frm" style="width:auto;display:inline" name="sel_lesson" id="sel_lesson" onchange="load_corses(2)">
  <option value="empty" > Select Lesson </option>
  <?php
    if(!empty($lesson)){
      foreach($lesson as $key => $value){?>
        <option <?php if($value['id'] == $m_id_lesson){ echo 'selected="selected"'; }?> value="<?php echo $value['id']?>" ><?php echo $value['title']?></option>
    <?php }
    $item_lesson = $lesson[$vt_lesson];
  }?>
</select>
<?php }?>
<br/>
<br/>

  <div id="tabs">
  <ul>
    <li><a href="#tabs-1">List</a></li>
    <li><a href="#tabs-2">Chart</a></li>
    <li><a href="#tabs-3">Category</a></li>
  </ul>
  <div id="tabs-1">
<table style="width: 100%;border: 1px solid #9CF;margin-top: 10px;">
<!--<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr> //-->

<tr style="background:#9CF;font-size:14px; font-weight:bold" >
  	<td align="center"><?php echo  'Code' ?></td>
    <td align="center"><?php echo  'Test' ?></td>
    <td align="center"><?php echo  'Type' ?></td>
    <td align="center"><?php echo  'Date' ?></td>
    <td align="center"><?php echo  'Score' ?></td>
    <td align="center"><?php echo  'Duration' ?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>" >
    <td align="center"><?php echo isset($list['testing_code'])?$list['testing_code']:''?></td>
    <td align="center"><?php echo isset($list['test']['test_title'])?$list['test']['test_title']:''?></td>
    <td align="center"><?php
	switch($list['testing_type']){
		case 1:
			echo 	'Whole Test';
		break;
		case 2:
			echo 	'Only Missing';
		break;
		case 3:
			echo 	'By Category';
		break;
		default :
			echo    '';
	}
	?></td>
  	<td align="center"><?php echo isset($list['testing_date'])?$this->format_int_date($list['testing_date'],$this->site['site_short_date']):''?></td>
    <td align="center">
      <?php echo $list['parent_score'] + $list['testing_score'];//if($list['testing_type'] == 1){ echo isset($list['parent_score'])?$list['parent_score']:''; }else{ echo isset($list['testing_score'])?$list['testing_score']:''; }?>
    </td>
    <td align="center"><?php echo isset($list['duration'])?gmdate('H:i:s',$list['duration']):''?></td>
   
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<div class='pagination3' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?></div>
<div  style="clear:both; text-align:right">
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:'0'?>
</div>
<br clear="all" />
</div>
<div id="tabs-2">
   <?php if(!empty($chartlist) && $chartlist!=false){?>
   <script>
    $(function () {
        $('#container').highcharts({
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
                  /* $year = substr(date('Y'),0,2).substr($list['testing_code'],0,2);
                  $month = substr($list['testing_code'],2,2);
                  $day = substr($list['testing_code'],4,2);
                  echo $month.'/'.$day.'/'.$year 
                  echo $list['testing_code'];*/ 
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
              name: 'Retake',
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
   <div id="container" style="min-width: 900px; height: 400px; margin: 0 auto"></div>
  </div>
  
   <div id="tabs-3">
 	<?php if(!empty($arraycategory)){?>
    <script>
   	$(function () {
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
			<?php foreach($arraydate as $list){ ?>
                 '<?php echo $list ?>',
			<?php }?>		
            ]},
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
   <div id="chartcategory" style="min-width: 900px; height: 400px; margin: 0 auto"></div>
  </div>
 	
</div>
</form>

<script type="text/javascript">
  function load_corses(type){
    // var id_courses = $('#sel_courses').val();
    // var id_lesson  = $('#sel_lesson').val();
    // console.log(id_courses);
    $('#txt_hd_type_change').val(type);
    var url = '<?php echo url::base() ?>admin_member/search_testing/<?php echo $member_uid ?>';
    $.ajax({
      url: url,
      processData: false,
      contentType: false,
      type: "POST",
      data: new FormData($('#frm_cours')[0]),
      success: function(data){
        $('#dialogtestting').html(data);
      }
    });
  }
</script>