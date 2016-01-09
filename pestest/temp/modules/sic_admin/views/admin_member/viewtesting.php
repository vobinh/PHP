<script>
$().ready(function(){
	$('.pagination3 .pagination a').click(function(){
		url = $(this).attr('href');
		loadtestting(url);
		return false;
	})
})
</script>
<script>
 $(function() {
    $( "#tabs" ).tabs();
  });
</script>
<select onchange="loadtestting('<?php echo url::base() ?>admin_member/testing/<?php echo $member_uid ?>/'+this.value)">
<option value="empty" > Select Test </option>
<?php 
foreach($test as $key => $value){?>{
	<?php if($value['uid'] == $test_uid){?>
	<option value="<?php echo $value['uid']?>" selected="selected" > <?php echo $value['test_title']?></option>
    <?php } else{?>
    <option value="<?php echo $value['uid']?>" > <?php echo $value['test_title']?></option>
    <?php }?>
<?php }?>   
</select>
<br/>
<br/>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>mypage/search" method="post" style="width:950px">
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
			echo 	'New';
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
    <td align="center"><?php echo isset($list['testing_score'])?$list['testing_score']:''?></td>
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
                zoomType: 'xy'
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
				  echo $month.'/'.$day.'/'.$year */ 
				  echo $list['testing_code'];
				  ?>',
			<?php }}?>		
            ]},
            yAxis: [{ 
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
				return 'Date: ' + datetermp +'<br>Code: ' + this.x + '<br>Score: ' + this.y + '</b>';
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
                name: 'New Test',
                color: '#89A54E',
                type: 'column',
                data: [<?php
					$i=0;
				 	foreach($chartlist as $id => $list){
						if($value == $list['test']['test_title']){ ?>
						[
                			<?php echo $i;?> , <?php echo ($list['testing_type']==1)?$list['testing_score']:0 ;?>
						],
						<?php }	$i++;
					}?>	],
                
            },
			<?php }
			}?>
			<?php
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
						<?php }	$i++;
					}?>	
				]},
				<?php }
			}?>
			{
					name: 'Pass Score',
					data:[
					
						<?php 
						echo '[ 0,'.$chartlist[0]['test']['pass_score'].'],';
						echo '[ '.(count($chartlist)- 1).','.$chartlist[0]['test']['pass_score'].'],';?>
					
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
			<?php foreach($arraycode as $list){ ?>
                 '<?php echo $list ?>',
			<?php }?>		
            ]},
            yAxis: {
	        max: 100,
			min:0,
	        title: {
                    text: 'Score'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
				formatter: function() {
				var strdate = this.x.split('-',-1)[0];	
				var datetermp = strdate.substr(2,2)+'/'+strdate.substr(4,2)+'/20'+strdate.substr(0,2);
				return 'Date: ' + datetermp +'<br>Code: ' + this.x + '<br>Score: ' + this.y + '</b>';
				}
			},
            
            series: [<?php
				foreach($arraycategory as $key => $value){?>{
                name: '<?php echo $key; ?>',
                data: [
					<?php
					$i=0;
				 	foreach($value as $list => $percent){?>
						[
                			<?php echo $list;?> , <?php echo $percent;?>
						],
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