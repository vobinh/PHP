<script language="javascript" src="<?php echo $this->site['base_url']?>js/jquery/jquery.js"></script>
<?php if(isset($mr['type_time']) && $mr['type_time']==0) {?>
 <script language="javascript" src="<?php echo $this->site['base_url']?>js/Timer/timer.js"></script>
  <script>
   startStopCountUp();
   </script>
 <?php }else { ?>
  <script language="javascript" src="<?php echo $this->site['base_url']?>js/Timer/countdown.js"></script>
    <script>
   countdown("spanTimer",<?php echo $mr['time_value'] ?>, 0 );
   </script>
  
  <?php } ?>
  <script>
   $().ready(function(){
   	$('#notice').hide()
   	<?php if($mr['type_time'] == 0 ){ ?>
		time = '<?php echo (gmdate('H:i:s',($mr['time_value']*60) - 60)); ?>';
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
   startStopCountUp();
   
   
   function counteranswer()
   {
      var count = $('input:radio:checked').length;
	  $("span#counteranswer").html(count+'/'+<?php echo $mr['qty_question'] ?>);
   }
   
   function movetop()
   {
   		 window.scrollTo(0, 0);
   
   }
  
 </script>
 <link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>pagehome/pagehome.css" rel="stylesheet" type="text/css">
 <script language="javascript" src="<?php echo $this->site['theme_url']?>pagehome/pagehome.js"></script>
  <div id='notice' style="border: 1px solid rgb(238, 153, 137);background-color: rgb(248, 158, 75);padding: 10px;position: fixed;
margin-bottom: -10px;font-size: 38px;color: rgb(255, 255, 255);display: block;right: 10%;width: 150px;font-weight: bold;height: 100px;text-align: center;" >
    	
  </div>
  <div class="flip-counter clock" style=""><span id="spanTimer" style="font-size: 30px;">00:00:00:000</span></div>
  <div class="counteranswer clock" style="font-size: 18px;">Status: <span id="counteranswer" style="font-size: 22px;">0/<?php echo $mr['qty_question'] ?></span></div>
        <div style="padding-top:20px;font-size:24px;">
        <h2><?php echo isset($mr['test_title'])?$mr['test_title']:""  ?></h2>
       <?php /*?> <form name="catefrm" id="catefrm" action="<?php echo $this->site['base_url']?>test" method="post">
        <table>
        <tr>
        <td valign="middle">Test</td>
        <td  valign="middle">
        <select name="sel_test" id="sel_test" style="width:200px;height:auto;">
        <option value="">Select test</option>
        <?php for($k=0;$k<count($mtest);$k++)  {?>
          <option <?php if(isset($mr['uid'])  && $mr['uid']==$mtest[$k]['uid']) {?> selected="selected"  <?php } ?>value="<?php echo isset($mtest[$k]['uid'])?$mtest[$k]['uid']:"" ?>"><?php echo isset($mtest[$k]['test_title'])?$mtest[$k]['test_title']:"" ?></option>
        <?php } ?>
        </select>
        </td>
        </tr>
        </table>
        </form>	<?php */?>
    </div>
   
  <?php if(isset($mlist) && count($mlist) >0){ ?>

    <div style="clear:both;"></div>
   <form name="frm" id="frm" action="<?php echo $this->site['base_url']?>test/resulttest" method="post">
   
 <?php $category ="";$parent_category =""; $n=0; ?>
 <?php for($k=0;$k<count($mlist);$k++)  {?>
 
 <?php	
 $key = 0 ;
 $category_id = '';
 $category_id = $mlist[$k]['uid'];
 
 for($i=0;isset($mlist[$k]['questionnaires']) && $i<count($mlist[$k]['questionnaires']);$i++){ 
 	$key += 1;	
 }?>
 <input type="hidden" value="<?php echo $category_id.'|'.$key;?>" name="<?php echo 'category[]'?>"/>
 <?
 
  for($i=0;isset($mlist[$k]['questionnaires']) && $i<count($mlist[$k]['questionnaires']);$i++) { ?>
 	 
     <div style="width:100%; float:left;min-height:180px;" class="questionnaires">
   
    <?php if($mlist[$k]['category'] != $category) { ?>
    
    <?php
	    $category_model = new Category_Model(); 
		$this->db->where('category.uid',$mlist[$k]['parent_id']);
		$mr_parent = $category_model->get();
		echo isset($mr_parent[0]['category']) && $parent_category !=$mr_parent[0]['category']?'<div style="font-size:36px">'.$mr_parent[0]['category'].' </div>':"";
		if(isset($mr_parent[0]['category']))
		$parent_category = $mr_parent[0]['category'];
	  ?>
    <div><h1>
   
   
	<?php echo $mlist[$k]['category']; $category = $mlist[$k]['category'] ?></h1></div>
    <?php } ?>
    <div class="ques"><strong><?php echo ($n+1) ?></strong>.<strong><?php echo isset($mlist[$k]['questionnaires'][$i]['question'])?$mlist[$k]['questionnaires'][$i]['question']:"" ?></strong></div>
    <div class="ans">
        <?php for($j=0;$j<count($mlist[$k]['questionnaires'][$i]['answer']);$j++) {?>
      	 
        <div class="ans-item">
        	<div style="float:left">
                <input onClick="counteranswer()" id="radio<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>" class="radio4" type="radio"
                
                 value="<?php echo $mlist[$k]['questionnaires'][$i]['answer'][$j]['type'] ?>|<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>|<?php echo $mlist[$k]['questionnaires'][$i]['category_uid']?>"
                 name="radio<?php echo $mlist[$k]['questionnaires'][$i]['uid'] ?>" style="margin-top:7px;width:auto;" previousvalue="false">
                 
                <label for="radio<?php echo isset($mlist[$k]['questionnaires'][$i]['answer'][$j]['uid'])?$mlist[$k]['questionnaires'][$i]['answer'][$j]['uid']:"" ?>"></label>
            </div>
			<div style="line-height:1.5em; float:left; padding-left:10px;"><?php echo $mlist[$k]['questionnaires'][$i]['answer'][$j]['answer'] ?></div>
        </div>
        <?php } ?>
        <input type="hidden" value="<?php echo $mlist[$k]['questionnaires'][$i]['uid'] ?>" name="hd_question[]" />
    </div>
    </div>
    
 <?php  $n++;} ?>
 <?php } ?>
    
   <div style="padding-right:8px;clear:both;text-align:center;">
   <input type="hidden" value="" name="hd_timeduration" id="hd_timeduration"  />
   <input type="hidden" value="<?php echo isset($mr['uid'])?$mr['uid']:"" ?>" name="hd_test" id="hd_test"  />
   <input type="hidden" value="<?php echo isset($mr['type_time'])?$mr['type_time']:"" ?>" name="hd_type" id="hd_type"  />
   <input type="hidden" value="<?php echo isset($mr['time_value'])?$mr['time_value']:"" ?>" name="hd_duration" id="hd_duration"  />
   <input type="hidden" value="<?php if(isset($mr['parent_id'])) echo $mr['parent_id']?>" name="parent_id"/>
   <input type="hidden" value="<?php echo $mr['typetest']?>" name="typetest"/>
   <?php if(isset($n)&& $n >0) {?>
    <button type="submit" name="btn_submit" id="btn_submit" class="button" style="<?php if($mr['qty_question']>10) echo 'display:none';?>"><span>Finish</span></button>
    <?php } ?>
   </div>
   </form>
<div id="gallerypaginate" class="paginationstyle" style="padding-right:8px;clear:both;text-align:center;">
<a href="#" rel="previous">«&nbsp;Previous</a>
<?php /*?><span class="flatview"></span> 
<?php */?>
<span class="paginateinfo">
</span>
<a href="#" rel="next">Next&nbsp;»</a>
</div>
<script>
$(function() {
    $('#sel_test').change(function() {
        document.forms['catefrm'].submit();
    });
});
</script>
<script type="text/javascript">
var gallery=new virtualpaginate({
	piececlass: "questionnaires", //class of container for each piece of content
	piececontainer: 'div', //container element type (ie: "div", "p" etc)
	pieces_per_page: 10, //Pieces of content to show per page (1=1 piece, 2=2 pieces etc)
	defaultpage: 0, //Default page selected (0=1st page, 1=2nd page etc). Persistence if enabled overrides this setting.
	wraparound: false,
	persist: false
 //Remember last viewed page and recall it when user returns within a browser session?
})
gallery.buildpagination(["gallerypaginate"])
</script>
<?php } ?>

 