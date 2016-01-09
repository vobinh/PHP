<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <h3 style="margin-top: 0px;">
        Purchase Courses 
      </h3>
    </div>
  </div>

  <div class="row">
  <?php
      // echo '<pre>';
      // print_r($mr['mlist']);
    if(!empty($mr['mlist']) && $mr['mlist']!=false){
      foreach ($mr['mlist'] as $id => $list) { ?>
      <?php if(in_array($list['uid'],$arraypayment)){ ?>
       <div class="col-sm-6 col-md-6 col-lg-4">
        <div class="panel panel-default box_shadow my_courses" style="min-height: 350px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px;">
                <?php echo !empty($list['test_title'])?$list['test_title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-7 col-md-6 pd_reset text-center">
              <div style="width:165px; height:150px; margin: auto;background-color: #fff;" class="box_shadow text-center">
                <?php if(!empty($list['img'])){?>
                  <?php if(s3_using == 'on'){?>
                    <img width="165px" height="150px" src="<?php echo linkS3; ?>courses_img/<?php echo $list['img']; ?>" alt="">
                  <?php }else{ ?>
                    <img width="165px" height="150px" src="<?php echo url::base(); ?>uploads/courses_img/<?php echo $list['img']; ?>" alt="">
                <?php }}?>
              </div>
            </div>
            <div class="col-sm-1 col-md-1 pd_reset text-center">
            &nbsp;
            </div>
            <div class="col-sm-4 col-md-5 pd_reset">
              You have already purchased this course.
              <h4>See <a tabindex="0" class="link text-success" href="<?php echo url::base()?>">My Courses</a></h4>
            </div>
            <div class="col-sm-12 pd_reset" style="padding-top:10px;">
              <?php 
                $m_description = !empty($list['test_description'])?$list['test_description']:'';
                if(strlen($m_description) > 230){
                  echo $m_description = substr($m_description,0,230).'...'; ?>
                  <a tabindex="0" class="link" role="button" data-html="true" data-toggle="popover" data-trigger="focus" title="" data-content="<?php echo !empty($list['test_description'])?$list['test_description']:''; ?>">Read more</a>
                <?php }else{
                  echo $m_description;
                }
              ?>
            </div>
          </div>
        </div>
      </div>
      <?php }else{ ?>
         <div class="col-sm-6 col-md-6 col-lg-4">
        <div class="panel panel-default box_shadow" style="min-height: 350px;">
          <div class="panel-body">
            <div class="col-sm-12 pd_reset">
              <h4 style="margin-top: 0px;">
                <?php echo !empty($list['test_title'])?$list['test_title']:''; ?>
              </h4>
            </div>
            <div class="col-sm-7 col-md-6 pd_reset text-center">
              <div style="width:165px; height:150px; margin: auto;background-color: #fff;" class="box_shadow text-center">
                <?php if(!empty($list['img'])){?>
                  <?php if(s3_using == 'on'){?>
                    <img width="165px" height="150px" src="<?php echo linkS3; ?>courses_img/<?php echo $list['img']; ?>" alt="">
                  <?php }else{ ?>
                    <img width="165px" height="150px" src="<?php echo url::base(); ?>uploads/courses_img/<?php echo $list['img']; ?>" alt="">
                <?php }}?>
              </div>
            </div>
            <div class="col-sm-1 col-md-1 pd_reset text-center">
            &nbsp;
            </div>
            <div class="col-sm-4 col-md-5 pd_reset text-center">
              <button 
              <?php if($list['price'] != 0 && $this->site['site_cart'] == 1 ){?>
               onclick="javascript:location.href='<?php echo $this->site['base_url']?>payment/index/<?php echo base64_encode($list['uid'])?>'"
              <?php }else{?>
               onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php echo base64_encode($list['uid'].text::random('numeric',3))?>/<?php echo text::random('numeric',3)?>'"
              <?php }?>
              style="padding: 10px 15px;" class="btn btn-success" type="button">
              <b><?php echo $this->format_currency($list['price'])?></b>
              </button>
              <br>
              <span style="color:red;">Valid for <?php echo !empty($list['date'])?$list['date']:'no limit'; ?> days</span>
            </div>
            <div class="col-sm-12 pd_reset" style="padding-top:10px;">
              <?php 
                $m_description = !empty($list['test_description'])?$list['test_description']:'';
                if(strlen($m_description) > 230){
                  echo $m_description = substr($m_description,0,230).'...'; ?>
                  <a tabindex="0" class="link" role="button" data-html="true" data-toggle="popover" data-trigger="focus" title="" data-content="<?php echo !empty($list['test_description'])?$list['test_description']:''; ?>">Read more</a>
                <?php }else{
                  echo $m_description;
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    <?php }}
    }
  ?>
  </div>
</div>

<?php /*?>
<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> 
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="<?php echo url::base()?>test/showlist">Purchase New Test </a></td>
</tr>
</table>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>showlist/search" method="post" style="width:1017px">
<table style="width: 100%;border: 1px solid #c8eae5;margin-top: 10px">
<!--<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
    <input type="text" id="txt_keyword" name="txt_keyword" value=""/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr> //-->

<tr class="list_header" style="padding:5px;font-weight:bold">
  	<td align="center"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td align="center">No. of Question</td>
    <td align="center">Testing Time</td>
    <td align="center">Validation Day</td>
    <td align="center">Passing Score</td>
    <td align="center"><?php echo 'Price' ?></td>
 	<td align="center"><?php echo 'Purchase' ?></td>
</tr>

   <?php 
  if(!empty($mr['mlist']) && $mr['mlist']!=false){
  foreach($mr['mlist'] as $id => $list){ ?>
 <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo $list['test_title'] ?></td>
  	<td align="right"><?php echo $list['qty_question'] ?></td>
    <td align="right"><?php echo $list['time_value'] ?> <?php echo($list['time_value'] >1)?' minutes':" minute"?></td>
    <td align="right"><?php if($list['date']==0)echo 'No Limit day' ; else {echo $list['date'] ?><?php echo($list['date'] >1)?' days':" day"?> <?php } ?></td>
    <td align="right"><?php echo $list['pass_score'] .'%'?></td>
    <td align="right"><?php echo $this->format_currency($list['price'])?></td>
    <td align="center">
    <?
	if(in_array($list['uid'],$arraypayment)){
		echo('Already Purchased.<br/> Check \'My Tests\' tab.');
	}
	else{	
	?>
    <button 
    <?php if($list['price'] != 0 && $this->site['site_cart'] == 1 ){?>
     onclick="javascript:location.href='<?php echo $this->site['base_url']?>payment/index/<?php echo base64_encode($list['uid'])?>'"
    <?php }else{?>
     onclick="javascript:location.href='<?php echo $this->site['base_url']?>test/start/<?php echo base64_encode($list['uid'].text::random('numeric',3))?>/<?php echo text::random('numeric',3)?>'"
    <?php }?>
    
    type="button" name="btn_submit" id="btn_submit" class="button"  value="Purchase"><span> Purchase </span></button>
    <?php }?>
    </td>
  </tr>
  
  <?php 
  if(!empty($list['test_description'])) {?>
  <tr class="tr<?php if($id%2 == 0)  echo 0; else echo 1 ?>"><td colspan="10" style="font-weight:bold; background:#ddd;padding:7px;"><?php
   echo isset($list['test_description'])?'Description: '.$list['test_description']:'' 
   ?></td></tr>
  <?php } ?>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>

<div class='pagination' style="float: right;"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
</form>
<?php */?>