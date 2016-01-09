<script >

function getChart(cateuid, member_uid){
	$('#container2').hide('fast');
	$('#container').show('fast');
	$.ajax({
	url: '<?php echo url::base()?>test/getChartByCategory/'+cateuid+'/'+member_uid,
	type: "GET",
	dataType: "json",
	success: function (data) {
		var category = new Array();
		var percent = new Array();
		for (var result in data) {
 		  	category.push(data[result]['testing_code']);
			percent.push(parseInt(data[result]['percentage']));
		}
				
		name = data[0]['name'];
		$('#container').highcharts({
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
				
		}
	});
}
</script> 
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
 
    $( "#btn_by_category" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
 });
</script>

<div id="dialog" title="Category Test Again">
<form id="frmcategory" action="<?php echo url::base() ?>test/testingwrong" method="post">
<input type="hidden" value="<?php echo $mr['idtest']?>" name="hd_test"/>
<input type="hidden" value="<?php echo $mr['testing_code']?>" name="testing_code"/>
<input type="hidden" value="<?php echo $mr['parent_id']?>" name="parent_id"/>

<table  style="border: 1px #ccc solid;" width="100%" >
	<?php 
	foreach($mr['olist'] as $key => $value){?>
        <tr  >
                <td  style="border: 1px #ccc solid;" ><input style="width:20px !important" type="checkbox" value="<?php echo $key ?>" name="ocategory[]"/>
                </td>
                <td style="padding: 5px;border: 1px #ccc solid;">
                     <?php echo $value ?>
                </td>
        </tr>
        
	<?php }?>
    <tr>
    		<td ><input style="width:20px !important" type="checkbox"  onclick="$('input:checkbox').attr('checked', this.checked);" /></td>
        	 <td colspan="2" align="center"><span style="float:left;margin-top: 14px;">Check all</span> <button type="submit" style="float: right;" class="button"
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
            	alert('please choise category');
                return false;
            }
           	" name="btn_wrong"><span> Start </span></button> </td>
    </tr>
</table>
</form>

</div>
 <table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
 <tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a>
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php  echo url::base()?>">Result test</td>
 </tr>
 </table>
 <div id="test" style="display:table; width:100%; padding:20px 0 0 0">
    	<div style="float:left; width:30%">
        <span class="testname">Title: <?php echo $mr['last_test'][0]['test']['test_title']?></span><br />        
        <span class="score">Score : <?php echo $mr['last_test'][0]['testing_score']?></span>
        </div>
        <div style="float:left; width:30%">
        <span class="datetest">Type: <?php  
		switch((int)$mr['last_test'][0]['testing_type']){
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
		?>
        </span><br />
        <span class="datetest">Date : <?php echo $this->format_int_date($mr['last_test'][0]['testing_date'],$this->site['site_short_date'])?></span><br />
        <span class="duration">Duration : <?php echo gmdate('H:i:s',$mr['last_test'][0]['duration'])?></span>
        </div>
</div>
<?php /*?><div style="border-radius:8px; width:100%; float:left;background:#F00">
 <?php if(isset($mr['pass']) && !empty($mr['pass'])){ ?>
 <div style="width:<?php echo (isset($mr['pass'])?$mr['pass']:"") ?>%; float:left;background:#00F;border-radius:8px 0 0 8px;line-height:24px; padding:0.5% 0%;color:#FFF;text-align:center">Pass (<?php echo(isset($mr['pass'])?$mr['pass']:"") ?>%)</div>
 <?php } ?>
 <?php if(isset($mr['fail']) && !empty($mr['fail'])){ ?>
    <div style="width:<?php echo(isset($mr['fail'])?$mr['fail']:"") ?>%; float:left;line-height:24px;color:#FFF;text-align:center;padding:0.5% 0%;">Fail (<?php echo (isset($mr['fail'])?$mr['fail']:"") ?>%)</div>
     <?php } ?>
</div><?php */?>
<div id='category' style="padding:0px;border: solid 1px #ccc;margin-top: 15px; width:45% ; float:left; box-shadow:#bbb 0 0 5px;">
	<table width="100%" cellpadding="0" cellspacing="0">
	<?php
	 $i=0;
	 foreach($mr['mlist'] as $key => $value){?>
    <tr class="<?php echo 'tr'.($i++)%2;?>">
   
    	<td align="left"><?php echo $key ?>: <span style="color:red"><?php echo  ($value[1] != 0)? number_format(($value[0]*100)/$value[1], 2, ',', ' '):''; ?>% </span><br />( total <?php echo $value[1]?> - answer : <?php echo $value[0];?> )</td><td width="15%" align="right" style="padding:0"><span style=" border: 1px solid #AAA8A8; padding: 2px; margin: 11px;width:60px;float:right; cursor: pointer; border-radius: 5px;background: #DBD2FF;
    color: #5A5C5C;" onclick="getChart('<?php echo $value[2]?>','<?php echo $this->sess_cus['id']?>')"> view chart</span></td>
		
    <tr>
	<?php }?>
    </table>
    <br />
    <table width="100%" cellpadding="0" cellspacing="0">
    <?php
	 $i=0;
	 if(!empty($mr['arrayquestion']) && (isset($mr['last_test'][0]['test']['displayexplanation']) && ($mr['last_test'][0]['test']['displayexplanation'] == 'Y'))){?>
     <tr>
     <td>
   
   	 Response wrong ! 
     <hr />
     </td>
     </tr>
     <?php
	 foreach($mr['arrayquestion'] as $value){?>
   	 <tr class="<?php echo 'tr'.($i++)%2;?>">
        <td align="left">
       	   <span style="color:#FF0000"> Question: <?php echo $value['question'];?>
           </span>
       	   <br />
       	   Explanation: <?php echo $value['answer_description'];?>
           <hr />
        </td>
     </tr>
     <?php }
	 }
	 ?>
    </table>
</div>
<div style="padding: 10px;border: solid 1px #ccc;margin-top: 15px; margin-left:48%; box-shadow:#bbb 0 0 5px;">
<?php
		$arraytest = array();
	    $arrayhas = array();
		if(!empty($chartlist) && $chartlist!=false){
			
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
   <?php }?>
<div id="container" style="min-width: 400px; height: 300px; margin: 0 auto; display:none">
</div>

<div id="container2" style="min-width: 400px; height: 300px; margin: 0 auto">
</div>
</div>


<div align="center" style="padding-top: 15px; clear:both">
<form id="testagain" method="post" action="<?php echo url::base() ?>test/testing">
<input type="hidden" value="<?php echo $mr['idtest']?>" name="sel_test"/>
   <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="$('#testagain').submit()"><span> Test Again </span></button>

   <button <?php if($scoreparent == 100) echo 'style="display:none"';?> type="button" name="btn_missing" id="btn_onlymissing" class="button" onclick="$('#frmcategory').submit()"><span> Only Missing</span></button>
  <?php if(!empty($mr['olist'])){?>
   <button type="button" name="btn_by_category" id="btn_by_category" class="button"><span> By Category </span></button>
  <?php }?>
  <!-- <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="javascript:location.href='<?php echo url::base()  ?>'"><span>Home</span></button>//-->
  
   <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="
    $('#container').hide('fast');
	$('#container2').show('fast');"><span>Total Chart</span></button>
</form>
</div>  

 