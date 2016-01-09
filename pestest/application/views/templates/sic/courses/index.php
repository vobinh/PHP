<script>
$().ready(function(){
	<?php if(isset($mr['last_test'][0]['testing_code'])){?>
	$.ajax({
	url: '<?php echo url::base()?>test/getChartCategory/<?php echo $mr["last_test"][0]["testing_code"] ?>/<?php echo $this->sess_cus["id"]?>',
	type: "GET",
	dataType: "json",
	success: function (data) {
		var category = new Array();
		var percent = new Array();
		var total = 0;
		var i=0;
		for (var result in data) {
			i++;
  		  	category.push(data[result]['name']);
			percent.push(parseInt(data[result]['percentage']));
			total += parseInt(data[result]['percentage']);
			//if(i == 10)
				//break;
		}
		if(data.length != 0){
			$('#container').attr('style','min-width: 400px; height: 400px; margin: 0 auto');
			html = ' <table width="100%" cellpadding="0" cellspacing="0">';	
			tr=0;
			for (var result in data) {
				if(data[result]['name'] != undefined){
					html += '<tr class="tr'+tr%2+'"><td class="col1">'+data[result]['name']+'</td>';
					html += '<td class="col2">Answer: <span style="color:red">'+data[result]['percentage']+'%</span></td></tr>';
					tr ++;
				}
			}
			html += '</table>';
			$('#content_lasttest').html(html);
			
		}else{
			$('#container').attr('style','min-width: 700px; height: 400px; margin: 0 auto');
			$('#content_lasttest').hide('slow');
			$('#chart_lasttest').attr('style','width: 75%;border: solid 1px #ccc;padding: 10px; margin-left:0%;box-shadow:#bbb 0 0 5px;');
		}
			
	}
	<?php }?>
	});
	$('#listtest').hide();
});


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

<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;"><a href="<?php echo url::base()?>">Home</td>
</tr>
</table>
<div id="dialog" title="Category Test Again">
<form id="frmcategory" action="<?php  echo url::base() ?>test/testingwrong" method="post">
<input type="hidden" value="<?php echo isset($mr['last_test'][0]['test_uid'])?$mr['last_test'][0]['test_uid']:''?>" name="hd_test"/>
<input type="hidden" value="<?php echo isset($mr['last_test'][0]['testing_code'])?$mr['last_test'][0]['testing_code']:''?>" name="testing_code"/>

<input type="hidden" value="<?php echo (isset($mr['last_test'][0]['parent_code']))?$mr['last_test'][0]['parent_code']:''?>" name="parent_id"/>

<?php if(isset($mr['olist'])){?>
<table  style="border: 1px #ccc solid;" width="100%" >
  <?php
  	 if(!empty($mr['olist'])){
	 foreach($mr['olist'] as $key => $value){?>
        <tr  >
                <td  style="border: 1px #ccc solid;" ><input style="width:20px !important" type="checkbox" value="<?php echo $key ?>" name="ocategory[]"/>
                </td>
                <td style="padding: 5px;border: 1px #ccc solid;">
                     <?php echo $value ?>
                </td>
        </tr>
        
	<?php }
	}
	?>
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
<?php }?>
</form>
</div>
<?php if(!empty($mr['last_test'][0]['test'])){?>
    <div id="test" style="display:table; width:100%; padding:20px 0 0 0">
    	<div style="float:left; width:30%">
        <span class="testname">Title: <?php echo $mr['last_test'][0]['test']['test_title']?></span><br />        
        <span class="score">Score: <?php echo $mr['last_test'][0]['testing_score']?></span>
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
        <span class="datetest">Date: <?php echo $this->format_int_date($mr['last_test'][0]['testing_date'],$this->site['site_short_date'])?></span><br />
        <span class="duration">Duration: <?php echo gmdate('H:i:s',$mr['last_test'][0]['duration'])?></span>
        </div>
    </div>
    <?php }?>  
<div style="float:right;margin-top: 7px;">
 <?php if(!empty($payment)) {?>
     <button   style="width: 200px;" type="button" name="btn_mytest" id="btn_mytest" class="button" onclick="javascript:location.href='<?php echo url::base() ?>test/mytest'"><span> My Test </span></button>
     <br />
 <?php }?>
     <button  style="width: 200px;" type="button" name="btn_submit" id="btn_submit" class="button" onclick="javascript:location.href='<?php echo url::base() ?>test/showlist'"><span>Purchase New Test</span></button>
     
</div>

 <?php if(empty($payment)) {?>
 	<div id='lastest' style="margin-top: 10px;">
    	<span style="font-size: 15px;color: #8A8A8A;"> - You have not purchased any tests. Please purchase a new test to get started. </span>
    </div> 
    <?php }else if(empty($chartlist)){?>
   	<div id='lastest' style="margin-top: 10px;">
		<span style="font-size: 15px;color: #8A8A8A;"> - You have not taken any test, please select the test from 'My Test' and take the test </span>
    </div>
<?php }?>
<?php if(!empty($chartlist) && $chartlist!=false){?>
<div id='lastest' style="margin-top: 10px;">
	<div id='content_lasttest' style="width:30%;border: solid 1px #ccc;padding: 0px;float: left;box-shadow:#bbb 0 0 5px;min-height: 420px;">   <br />
   
  </div>
    <div id='chart_lasttest' <?php echo empty($mr['chartcategory'])?'style="width: 75%;border: solid 1px #ccc;padding: 10px; margin-left:0%;box-shadow:#bbb 0 0 5px; "':'style="width: 43%;border: solid 1px #ccc;padding: 10px; margin-left:32%;box-shadow:#bbb 0 0 5px; "'?>>
     
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
    <div id="container">
   
    </div>
    </div>
    
</div>
<div id='listtest' style="margin-top: 20px;">

</div>
<?php }?>
<div align="center" 
<?php echo !empty($mr['chartcategory'])?
'style="padding-top: 15px; padding-bottom: 15px; padding-left:80px;left: 34%;position: absolute;
"':
'style="padding-top: 15px; clear:both; padding-bottom: 15px; margin-left: -200px;margin-right: 20%;
"'?>>
<?php if(isset($mr['last_test'][0]['test_uid'])){?>
<form id="testagain" method="post" action="<?php echo url::base() ?>test/testing">
<input type="hidden" value="<?php echo $mr['last_test'][0]['test_uid']?>" name="sel_test"/>
   <button type="button" name="btn_submit" id="btn_submit" class="button" onclick="$('#testagain').submit()"><span> Test again </span></button>
   <button <?php if($scoreparent == 100) echo 'style="display:none"';?>  type="button" name="btn_missing" id="btn_onlymissing" class="button" onclick="$('#frmcategory').submit()"><span> Only Missing</span></button>
    <?php if(isset($mr['chartcategory'][0]['test'])){?>
  	<button type="button" name="btn_by_category" id="btn_by_category" class="button"><span> By Category </span></button>
<?php }?>
</form>
<?php }?> 
</div>  
<div style="
    height: 75px;
"></div>