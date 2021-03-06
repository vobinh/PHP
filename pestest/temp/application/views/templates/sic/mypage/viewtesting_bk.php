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
<div id="dialog" title="Testing Detail" >

</div>
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
     <a href="<?php echo url::base()?>">Home</a> <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
     <a href="<?php echo  url::base()?>mypage/testing">Testing History</a> </td>
</tr>
</table>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>mypage/search" method="post" style="width:1017px">
  <div colspan="6" align="left" style="height:30px">Test : 
   <select name="sel_test" id="sel_test" style="width: 180px;height: 27px;" onchange="$('#frmlist').submit()">
   <option value="empty" > Select Test </option>
      <?php foreach($test as $value){?>
        <option
        <?php if(isset($this->search['test']) && ( $this->search['test'] == $value['uid'])){ 
				 echo 'selected="selected"'; }?>  
        value="<?php echo $value['uid']?>" ><?php echo $value['test_title']?>
        </option>
      <?php }?>
   </select></div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">List</a></li>
    <li><a href="#tabs-2">Chart</a></li>
    <li><a href="#tabs-3">Chart Category</a></li>
  </ul>
  <div id="tabs-1">
<table style="width: 100%;border: 1px solid #9CF;margin-top: 10px;">
<!--<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if(isset($this->search['keyword'])) echo $this->search['keyword'] ?>"/>
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
    <td align="center"><?php echo  'Action' ?></td>
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
    <td align="center">
    <a style="cursor:pointer" onclick="loadtestingdetail('<?php echo url::base()?>mypage/viewtesting/<?php echo isset($list['uid'])?$list['uid']:''?>'+'/'+'<?php echo $list['testing_code']?>');$('#dialog').dialog('open');" class="ico_edit" id="link_testing_detail">
          <?php echo Kohana::lang('global_lang.btn_view') ?></a>
     <!-- <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a> //-->    </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>

<div class='pagination1' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?></div>
<br clear="all" />
</div>
<div id="tabs-2">
   <?php if(!empty($chartlist) && $chartlist!=false){?>
   <script>
   	$(function () {
        $('#container').highcharts({
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
   <div id="container" style="min-width: 950px; height: 400px; margin: 0 auto"></div>
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
   <div id="chartcategory" style="min-width: 950px; height: 400px; margin: 0 auto">
   ----- No Category Available -----
   </div>
  </div>
</div>
</form>
<script>
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