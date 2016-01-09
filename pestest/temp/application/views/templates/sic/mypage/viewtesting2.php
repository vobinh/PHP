<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">History Testing</td>
</tr>
</table>


  

<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>mypage/search" method="post">

  <div colspan="6" align="left" style="height:30px">Test : 
   <select name="sel_test" id="sel_test" style="width: 180px;height: 27px;" onchange="$('#frmlist').submit()">
   <option value="0" > All Test </option>
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
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr> //-->

<tr style="background:#9CF;font-size:14px; font-weight:bold" >
  	<td align="center"><?php echo  'Code' ?></td>
    <td align="center"><?php echo  'Test' ?></td>
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
  	<td align="center"><?php echo isset($list['testing_date'])?$this->format_int_date($list['testing_date'],$this->site['site_short_date']):''?></td>
    <td align="center"><?php echo isset($list['testing_score'])?$list['testing_score']:''?></td>
    <td align="center"><?php echo isset($list['duration'])?gmdate('H:i:s',$list['duration']):''?></td>
    <td align="center">
    <a href="<?php echo $this->site['base_url']?>mypage/viewtesting/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" class="ico_edit">
          <?php echo Kohana::lang('global_lang.btn_view') ?></a>
     <!-- <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a> //-->    </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>

<div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
</form>
<br clear="all" />
</div>
  <div id="tabs-2">
	
   <script>
   	$(function () {
        $('#container').highcharts({
            title: {
                text: 'Score',
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
                 '<?php echo $list['testing_code']?>',
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
              //  valueSuffix: ''
            },
            
            series: [<?php
			if(!empty($chartlist) && $chartlist!=false){
				foreach($arraytest as $value){?>{
                name: '<?php echo $value; ?>',
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
			]
        });
    });
   </script>
   <div id="container" style="min-width: 75%; height: 400px; margin: 0 auto"></div>
  </div>
  <div id="tabs-3">
 	<?php 
	$arraycategory = array();
	$arraycode = array();
	$temp = '';
	$i=-1;
	foreach($chartcategory as $value){
		$temp =  $value['testing_code'];
 		if(!in_array($temp,$arraycode)){
			$arraycode[] = $value['testing_code'];
			$i++;
		
		}
		$arraycategory[$value['category']][$i] = $value['percentage'];
		?>		
 	<?php }?>
    
  
    <script>
   	$(function () {
        $('#chartcategory').highcharts({
            title: {
                text: 'Score',
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
              //  valueSuffix: ''
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
   <div id="chartcategory" style="min-width: 75%; height: 400px; margin: 0 auto"></div>
  </div>
</div>
</div>