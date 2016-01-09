</script>
<link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>pagehome/pagehome.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->site['theme_url']?>pagehome/pagehome.js"></script>
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
<?php if(empty($mr['mlist']) && empty($mr['arrayquestion'])){?>
<script>
$('#category').hide('slow');
</script>
<?php }?>

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
<div id="tabs" style="overflow: hidden;">
  <ul>
    <li><a href="#tabs-1">RESULT</a></li>
    <li <?php if(empty($mr['arrayquestion'])) echo 'style="display:none"'?> ><a href="#tabs-2">RESPONSE WRONG</a></li>
  </ul>
  <div id="tabs-1">
	<?php if(!empty($mr['mlist'])){?>
    <div id='category' style="padding:0px; margin-top: 15px; width:45% ; float:left;">

    <table width="100%" cellpadding="0" cellspacing="0" style="border: 1px solid #ccc;
box-shadow: #bbb 0 0 5px;">
	<?php
	 $i=0;
	 foreach($mr['mlist'] as $key => $value){?>
    <tr class="<?php echo 'tr'.($i++)%2;?>">
   
    	<td align="left"><?php echo $key ?>: <span style="color:red"><?php echo  ($value[1] != 0)? number_format(($value[0]*100)/$value[1], 2, ',', ' '):''; ?>% </span><br />( total <?php echo $value[1]?> - answer : <?php echo $value[0];?> )</td>
        
        <td width="15%" align="center" style="padding:0" valign="top">
        <?php if((($value[0]*100)/$value[1]) != 100) {?>
        <span id="detail<?php echo $value[2]?>" style=" border: 1px solid #AAA8A8; padding: 2px; margin: 11px;width:60px;float:right; cursor: pointer; border-radius: 5px;background: #DBD2FF;color: #5A5C5C;" 
        onclick="
         $('#contail').hide('fast');
         $('#showwrong').html($('#showdetail<?php echo $value[2]?>').html());
         $('#showwrong').show('slow');
         $('#showwrong #showdetail').attr('style','display:block;position: absolute; width: 40%;border: 1px solid #fff;background: #fff;text-align: left;top: 40%;left: 48%; box-shadow:#ccc 0 0 5px;')">Wrong</span>
         <?php }?>       
<div class="showdetail" id="showdetail<?php echo $value[2]?>"
style="display:none;position: absolute; width: 40%;border: 1px solid #fff;background: #fff;text-align: left;top: 40%;left: 48%; box-shadow:#ccc 0 0 5px; ">
	    <table width="100%">
        <tr style="text-align:center; background:#bbb; color:#555; line-height:30px;font-size:18px; font-weight:bold"><td colspan="3" class="title_test"><?php echo ucfirst($key) ?></td></tr>
        <tr style="background:#F3F3F1">
        <td style=" border:#ddd 1px solid"><strong>Question</strong></td>
        <td style=" border:#ddd 1px solid"><strong>Your Answer</strong></td>
        <td style=" border:#ddd 1px solid"><strong>Right Answer</strong></td></tr>
        <?php if(!empty($value['answer'])){
		
		
	        foreach($value['answer'] as $val){
			$question = isset($val['question'])?$val['question']:'';
        	$has = (isset($val['has']) && $val['has'] != '')?$val['has']:'No Answer';
       		$answer = isset($val['answer'])?$val['answer']:'';
					if($has != $answer){?>
					<tr>
					<td class="test_main"> <?php echo $question?></td>
					<td class="test_main"> <?php echo $has?></td>
					<td class="test_main"> <?php echo $answer?></td>
					</tr>
			<?php   }
			}
		}?> 
        </table>
        
        </div>

        </td>
    
        <td width="15%" align="center" style="padding:0"><span id="viewchart<?php echo $value[2]?>" style=" border: 1px solid #AAA8A8; padding: 2px; margin: 11px;width:60px;float:right; cursor: pointer; border-radius: 5px;background: #DBD2FF;
    color: #5A5C5C;" onclick=" 
    $('.showwrong').hide('fast');
    $('#contail').show('slows');
    getChart('<?php echo $value[2]?>','<?php echo $this->sess_cus['id']?>')"> view chart</span></td>
		
    <tr>
	<?php }?>
    </table>
    </div>
    <?php }?>
    
<div class="showwrong" id="showwrong" style="display:none; padding: 10px;border: solid 1px #ccc;margin-top: 15px; margin-left:48%; box-shadow:#bbb 0 0 5px;" >

</div>    
<div id="contail" 
<?php if(!empty($mr['mlist'])){?>
style="padding: 10px;border: solid 1px #ccc;margin-top: 15px; margin-left:48%; box-shadow:#bbb 0 0 5px;"
<?php  }else{?>
style="padding: 10px;border: solid 1px #ccc;margin-top: 15px; margin-left:0%; box-shadow:#bbb 0 0 5px;"
<?php }?>
>
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

<div id="container" >
</div>

<div id="container2" >
</div>

</div>
</div><div id="tabs-2" <?php if(empty($mr['arrayquestion'])) echo 'style="display:none"'?>>
  <?php
	 $i=0;
	 if(!empty($mr['arrayquestion']) && (isset($mr['last_test'][0]['test']['displayexplanation']) && ($mr['last_test'][0]['test']['displayexplanation'] == 'Y'))){?>
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 0px;
border: 1px solid #ccc;
box-shadow: #bbb 0 0 5px;">
   
     <tr>
     </tr>
     <?php
	 foreach($mr['arrayquestion'] as $value){?>
   	 <tr class="infowrong" style="border-bottom: 1px solid #ccc;">
        <td align="left" >
       	   <span style="color:#FF0000"> Question: <?php echo isset($value['question'])?$value['question']:'';?>
           </span>
       	   		
                
       	   		 <?php  if($value['hasimages']!='')
				 			echo'<br />Your Answer: <img src="'.url::base().'uploads/answer/'.$value['hasimages'].'" width="20px" />';
						else    if($value['hasanswer']!='')
								echo '<br />Your Answer: '.$value['hasanswer'];
								else
									echo '<br />No Answer';?>
                 
                 <?php  if($value['images']!='')
				 			    echo '<br />Right Answer:  <img src="'.url::base().'uploads/answer/'.$value['images'].'" width="20px" />';
						else    if($value['answer']!='')
								echo '<br />Right Answer: '.$value['answer']; 
							    else
								echo'';?>
                 
       	   		 <?php 
				 if(isset($value['answer_description']) && ($value['answer_description'] != "")) 
						 echo '<br />Explanation: '.$value['answer_description']; 
				 else echo '';?>
        </td>
       
     </tr>
     <?php }?>
    </table>
    <?php }?>
    <div id="gallerypaginate" class="paginationstyle" style="padding-right:8px;clear:both;text-align:center;">
        <a href="#" rel="previous"><< Previous</a>
        <span class="paginateinfo">
        </span>
        <a href="#" rel="next">Next >></a>
        </div>
</div>
<div align="center" style="padding-top: 15px;">
<form id="testagain" method="post" action="<?php echo url::base() ?>test/testing">
<input type="hidden" value="<?php echo $mr['idtest']?>" name="sel_test"/>
   <button type="button" name="btn_submit" id="btn_submit" style="display:inline-block !important"  onclick="$('#testagain').submit()"><span> Test Again </span></button>

   <button <?php if($scoreparent == 100) echo 'style="display:none"';?> type="button" name="btn_missing" id="btn_onlymissing" onclick="$('#frmcategory').submit()"><span> Only Missing</span></button>
  <?php if(!empty($mr['olist'])){?>
   <button type="button" name="btn_by_category" id="btn_by_category" ><span> By Category </span></button>
  <?php }?>
  <!-- <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="javascript:location.href='<?php echo url::base()  ?>'"><span>Home</span></button>//-->
  
   <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="
    $('.showwrong').hide('fast');
    $('#contail').show('slows');
    $('#container').hide('fast');
	$('#container2').show('fast');
 	$('#tabs').tabs('option', 'active', 0 );
    "><span>Total Chart</span></button>
</form>
</div>  
<script type="text/javascript">
var gallery=new virtualpaginate({
	piececlass: "infowrong", //class of container for each piece of content
	piececontainer: 'tr', //container element type (ie: "div", "p" etc)
	pieces_per_page: 10, //Pieces of content to show per page (1=1 piece, 2=2 pieces etc)
	defaultpage: 0, //Default page selected (0=1st page, 1=2nd page etc). Persistence if enabled overrides this setting.
	wraparound: false,
	persist: false
 //Remember last viewed page and recall it when user returns within a browser session?
})
gallery.buildpagination(["gallerypaginate"])
</script>
 