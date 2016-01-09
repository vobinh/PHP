<style type="text/css" media="screen">
  .pd_reset{
    padding-right: 0;
    padding-left: 0;
  }
</style>
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
 });
</script>
<div id='dialog'>
	
</div>

<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <h3 style="margin-top: 0px;">
        My Courses 
      </h3>
      <?php if(empty($mr['mlist'])){?>
        <h4>
          Welcome to <?php echo $this->site['site_name']?>.<br>
          Click on the button below to browse courses.
        </h4>
        <button onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/showlist'" style="padding: 10px 30px;" class="btn btn-success" type="button">Purchase Courses</button>
      <?php }?>
    </div>
  </div>
  <div class="row">
  <?php
      // echo '<pre>';
      // print_r($mr['mlist']);
    if(!empty($mr['mlist']) && $mr['mlist']!=false){
      foreach ($mr['mlist'] as $id => $list) { ?>
       <div class="col-sm-6 col-md-6 col-lg-4">
        <div class="panel panel-default box_shadow" style="min-height: 350px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px;">
                <?php echo !empty($list['test_title'])?$list['test_title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-7 col-md-6 pd_reset text-center">
              <div style="width:200px; height:150px; margin: auto;" class="box_shadow text-center">
                <?php if(!empty($list['img'])){?>
                  <?php if(s3_using == 'on'){?>
                    <img width="200px" height="150px" src="<?php echo linkS3; ?>courses_img/<?php echo $list['img']; ?>" alt="">
                  <?php }else{ ?>
                    <img width="200px" height="150px" src="<?php echo url::base(); ?>uploads/courses_img/<?php echo $list['img']; ?>" alt="">
                <?php }}?>
              </div>
            </div>
            <div class="col-sm-1 col-md-2 pd_reset text-center">
            &nbsp;
            </div>
            <div class="col-sm-4 col-md-4 pd_reset text-center">
              <button onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php
   echo base64_encode($list['uid'].text::random('numeric',3)) ?>'" style="padding: 10px 15px;" class="btn btn-success" type="button">Launch</button><br>
              <span style="color:red;">Valid for <?php echo !empty($list['date'])?$list['date']:'0'; ?> days</span>
            </div>
            <div class="col-sm-12 pd_reset" style="padding-top:10px;">
              <?php 
                $m_description = !empty($list['test_description'])?$list['test_description']:'';
                if(strlen($m_description) > 230){
                  echo $m_description = substr($m_description,0,230).'...'; ?>
                  <a tabindex="0" class="link" data-html="true" role="button" data-toggle="popover" data-trigger="focus" title="" data-content="<?php echo !empty($list['test_description'])?$list['test_description']:''; ?>">Read more</a>
                <?php }else{
                  echo $m_description;
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    <?php }
    }
  ?>
  </div>
</div>
<?php /*?>
  <table style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px">

  <tr class="list_header" style="padding:5px;font-weight:bold">
    	<td align="center"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
      <td align="center">No. of Question</td>
      <td align="center">Testing Time</td>
      <td align="center">Validation Day</td>
      <td align="center">Expiration Date</td>
      <td align="center">Passing Score</td>
      <!--<td align="center"><?php echo 'Price' ?></td> //-->
  </tr>

     <?php 
    if(!empty($mr['mlist']) && $mr['mlist']!=false){
    foreach($mr['mlist'] as $id => $list){ ?>
    

   <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>">
      <td align="center"><?php echo $list['test_title'] ?></td>
    	<td align="right"><?php echo $list['qty_question'] ?></td>
      <td align="right"><?php echo $list['time_value'] ?> <?php echo($list['time_value'] >1)?' minutes':" minute"?></td>
      <td align="right">
  	<?php if(isset($list['daytest']) && ($list['daytest']) >0) {?>
  	  <?php echo $list['daytest']?><?php echo($list['daytest'] >1)?' days':" day"?>
        <?php }else { ?>
        No limit day
        <?php } ?>
        </td>
      <td align="center">
      <?php if(isset($list['daytest']) && ($list['daytest']) >0) {?>
  	 <?php echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']). ' + '.$list['daytest'].' day')) ?>
       <?php } ?>
       </td>
      <td align="right"><?php echo $list['pass_score'] .'%'?></td>
      <!-- <td align="right"><?php echo $this->format_currency($list['price'])?></td> //-->
      
    </tr>
    <tr class="tr<?php if($id%2 == 0)  echo 0; else echo 1 ?>">
    <td colspan="8" style="font-weight:bold; background:#ddd;padding:7px;">
    <div style="float:left;text-align:left"><?php echo isset($list['test_description'])?'Description: '.$list['test_description']:'' ?>
    </div>
    <div style="float:right;text-align:right">
    <form id="testagain<?php echo $list['uid']?>" method="post" action="<?php echo url::base() ?>test/testingwrong">
      
       <a><button 
    	 onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php
  	 echo base64_encode($list['uid'].text::random('numeric',3)) ?>'"
  	 type="button" style="width: 130px;" name="btn_submit" id="btn_submit" class="button"  value="Test Now"><span > Test Now </span></button></a>
       <?php if(!empty($list['list'])){?>
       <input type="hidden" value="<?php echo $list['uid']?>" name="sel_test"/>
       <input type="hidden" value="<?php echo isset($list['list'][0]['test_uid'])?$list['list'][0]['test_uid']:''?>" name="hd_test"/>
       <input type="hidden" value="<?php echo isset($list['list'][0]['testing_code'])?$list['list'][0]['testing_code']:''?>" name="testing_code"/>
       <input type="hidden" value="<?php echo (isset($list['list'][0]['parent_code']))?$list['list'][0]['parent_code']:''?>" name="parent_id"/>
       
       <a <?php 
  	 if(!isset($list['scoreparent']))
  	 	$list['scoreparent'] = 100;	
  	 if((int)$list['scoreparent'] == 100) echo 'style="display:none"' ?> ><button   type="submit" style="width: 130px;" name="btn_missing" id="btn_onlymissing" class="button" ><span >Only Missing</span></button></a>
       <?php  if((int)$list['scoreparent'] != 100) ?>
       <a <?php if(empty($list['category'])) echo 'style="display:none"'; ?> ><button type="button"   style="width: 130px;" name="btn_by_category" id="btn_by_category" class="button" onclick="showdialog('<?php echo($list['list'][0]['testing_code']) ?>','<?php echo $list['list'][0]['test_uid']?>')" ><span >By Category</span></button></a>
       <?php }?>
       </form>
    </div>
    
   </td></tr>
    <?php } // end if ?>
    <?php } // end foreach ?>
  </table>

  <div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
  </div>
<?php */?>
<script>
	function showdialog(val , id){
		$.ajax({
			url: '<?php echo url::base()?>test/getTestCategory/'+val+'/'+id,
			type: "GET",
			success: function (data) {
				$('#dialog').html(data);
				$( "#dialog" ).dialog( "open" );
			},
			error: function () {
       		 	alert('Category not set');
  		    }
		});
	}
</script>