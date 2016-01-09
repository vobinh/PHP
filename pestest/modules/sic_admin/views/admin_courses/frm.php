<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="<?php echo url::base()?>js/jquery-filestyle/src/jquery-filestyle.min.css">
<style type="text/css">
  .cssload-loader{
    display:block;
    position:absolute;
    height:6em;width:6em;
    left:50%;
    top:50%;
    margin-top:-3em;
    margin-left:-3em;
    background-color:rgb(51,136,153);
    border-radius:3.5em 3.5em 3.5em 3.5em;
      -o-border-radius:3.5em 3.5em 3.5em 3.5em;
      -ms-border-radius:3.5em 3.5em 3.5em 3.5em;
      -webkit-border-radius:3.5em 3.5em 3.5em 3.5em;
      -moz-border-radius:3.5em 3.5em 3.5em 3.5em;
    box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
      -o-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
      -ms-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
      -webkit-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
      -moz-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
    background: linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
      background: -o-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
      background: -ms-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
      background: -webkit-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
      background: -moz-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
    background-blend-mode: multiply;
    border-top:7px solid rgb(0,153,0);
    border-left:7px solid rgb(0,153,0);
    border-bottom:7px solid rgb(204,204,0);
    border-right:7px solid rgb(204,204,0);
    animation:cssload-roto 1.15s infinite linear;
      -o-animation:cssload-roto 1.15s infinite linear;
      -ms-animation:cssload-roto 1.15s infinite linear;
      -webkit-animation:cssload-roto 1.15s infinite linear;
      -moz-animation:cssload-roto 1.15s infinite linear;
  }


  @keyframes cssload-roto {
    0%{transform:rotateZ(0deg);}
    100%{transform:rotateZ(360deg);}
  }

  @-o-keyframes cssload-roto {
    0%{-o-transform:rotateZ(0deg);}
    100%{-o-transform:rotateZ(360deg);}
  }

  @-ms-keyframes cssload-roto {
    0%{-ms-transform:rotateZ(0deg);}
    100%{-ms-transform:rotateZ(360deg);}
  }

  @-webkit-keyframes cssload-roto {
    0%{-webkit-transform:rotateZ(0deg);}
    100%{-webkit-transform:rotateZ(360deg);}
  }

  @-moz-keyframes cssload-roto {
    0%{-moz-transform:rotateZ(0deg);}
    100%{-moz-transform:rotateZ(360deg);}
  }

  .tags_wap{
    clear: both;
    padding: 5px;
    border: 1px solid #000;
    margin-right: 5px;
    margin-bottom: 5px;
    font-weight: bold;
  }
  .tags_remove{
    color: red;
    cursor: pointer;
    font-weight: bold;
    padding-left: 5px;
  }
  .add_tags{
    text-align: center;
    border: 2px solid;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    padding: 2px;
    background-color: rgb(0,153,0);
    min-width: 25px;
    display: block;
    font-size: 20px;
    float: left;
  }

  .sponsor_tags_wap{
    clear: both;
    padding: 5px;
    border: 1px solid #000;
    margin-right: 5px;
    margin-bottom: 5px;
    font-weight: bold;
  }
  .sponsor_tags_remove{
    color: red;
    cursor: pointer;
    font-weight: bold;
    padding-left: 5px;
  }
  .add_sponsor_tags{
    text-align: center;
    border: 2px solid;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    padding: 2px;
    background-color: rgb(0,153,0);
    min-width: 25px;
    display: block;
    font-size: 20px;
    float: left;
  }

  .day_using{
    height: 17px;
    padding: 5px;
    display: inline-block;
    font-size: 16px;
    color: red;
    float: left;
    font-weight: bold;
  }
</style>
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
<script >
	function loadmember(val1){
	$.ajax({
		url: val1,
		type: "GET",
		success: function(data){
			$('#dialog').html(data);
		}
	});
	}
	$().ready(function(){
		  if($("#answerno1").length == 0){
		  for(i=0;i<4;i++)
		  		addAnswer();
		  }
		  
	})
	function addAnswer(){
		 $('#tableanwer').append(
		 '<tr id="answerno1">'+
            '<td style="background: none;"><input name="answer_ans[]" id="answer_ans[]" type="text" /></td>'+
            '<td style="background: none;"><input name="type_ans[]" id="type_ans[]" type="checkbox" onclick="$(\':checkbox\').attr(\'checked\',false);$(this).attr(\'checked\',true)"/><input type="hidden" name="type_ans[]" value="0" /></td>'+
            '<td style="background: none;"><img onclick="$( this ).parent().parent().remove()" width="15px" src="<?php echo url::base() ?>themes/admin/pics/b_drop.png"/></td>'+
          '</tr>');
		  
	}
	
</script>
<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 1;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>


<form id="frm" name="frm" action="<?php echo url::base() ?>admin_courses/save" method="post" enctype='multipart/form-data'>
  <link rel="stylesheet" href="<?php echo url::base()?>themes/popup/jquery-ui.css">
  <table id="float_table" class="title" cellspacing="0" cellpadding="0">
  <tr>
      <td class="title_label">Courses</td>
      <td class="title_button"><?php require('button.php');?></td>
  </tr>
  </table>

  <table style="width: 800px;margin: auto;" >
    <tbody>
      <tr>
        <td style="text-align: right;">Type:</td>
        <td colspan="2">
          <input type="radio" id="btn_type_0" <?php if(empty($courses['type'])){?>checked<?php }elseif(!empty($courses) && $courses['type'] == 0){?>checked<?php }?> name="txt_type" value="0">&nbsp;E-Course<br>
          <input type="radio" id="btn_type_1" <?php if(!empty($courses) && $courses['type'] == 1){?>checked<?php }?> name="txt_type" value="1">&nbsp;Practice test
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Title<font color="#FF0000">*</font>:</td>
        <td colspan="2">
          <input type="text" style="width:98%" id='txt_title' name="txt_title" value="<?php echo isset($courses['title'])?$courses['title']:''?>"/>
        </td>
      </tr>
      <tr>
        <td style="text-align: right; vertical-align: top;">Description:</td>
        <td colspan="2">
          <textarea  class="ckeditor" style="height:50px; width:51%" id="txt_description" name="txt_description" ><?php echo isset($courses['description'])?$courses['description']:''?>
          </textarea>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Price<font color="#FF0000">*</font>:</td>
        <td colspan="2">
          <input style="width:140px" type="text" name="txt_price" id="txt_price" value="<?php echo isset($courses['price'])?$courses['price']:''; ?>" placeholder="">
        </td>
      </tr>
      <tr>
        <td style="text-align: right; vertical-align: top;">Image:</td>
        <td colspan="2">
          <div id="image_company_menu">
            <?php if(!empty($courses['image'])){?>
              <?php if(s3_using == 'on'){?>
                <img height="100px" src="<?php echo linkS3; ?>courses_img/<?php echo $courses['image']; ?>">
              <?php }else{ ?>
                <img height="100px" src="<?php echo url::base() ?>uploads/courses_img/<?php echo $courses['image']; ?>">
              <?php }?>
            <?php }?>
            <input type="hidden" value="<?php echo !empty($courses['image'])?$courses['image']:''; ?>" name="image_company">
          </div>
          <!-- <input type="file" name="txt_courses_img" id="txt_courses_img" value="" placeholder=""> -->
          <button style="width: 110px;" onclick="show_crop_img()" type="button" class="btn">Select File</button>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;white-space: nowrap;">Days Valid<font color="#FF0000">*</font>:</td>
        <td colspan="2">
          <input style="width:140px" type="text" name="txt_days_valid" id="txt_days_valid" value="<?php echo isset($courses['day_valid'])?$courses['day_valid']:''; ?>" placeholder="">
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Certificate:</td>
        <td colspan="2">
          <select style="width:154px" name="txt_certificate" id="txt_certificate" >
            <option value="0">----------</option>
          <?php
            if(!empty($certificate)){
              foreach ($certificate as $key => $value) {
          ?>
            <option <?php if(!empty($courses['id_certificate']) && $courses['id_certificate'] == $value['id']){?>selected<?php }?> value="<?php echo $value['id']; ?>"><?php echo $value['title']; ?></option>
          <?php }}?>
          </select>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Status:</td>
        <td colspan="2">
          <select style="width:154px" name="txt_status" id="txt_status" >
            <?php if(isset($courses['status']) && $courses['status'] == 0){?>
                <option value="0" selected="selected">Inactive</option>
                <option value="1">Active</option>
            <?php }else{?>
                <option value="0">Inactive</option>
                <option value="1" selected="selected">Active</option>
            <?php }?>
          </select>
        </td>
      </tr>
      <tr id="div_control" <?php if(empty($courses['type'])){?>style="display:table-row;"<?php }elseif(!empty($courses) && $courses['type'] == 0){?>style="display:table-row;"<?php }else{?>style="display:none;"<?php }?> >
        <td style="text-align: right;"><b>Controls</b></td>
        <td colspan="2">
          <select style="width:154px" name="slt_control" id="slt_control" >
            <?php if(isset($courses['video_control']) && $courses['video_control'] == 1){?>
                <option value="0" >Disable</option>
                <option value="1" selected="selected">Enable</option>
            <?php }else{?>
                <option value="0" selected="selected">Disable</option>
                <option value="1">Enable</option>
            <?php }?>
          </select>
        </td>
      </tr>
      <!-- phan practice test-->
      <tr id="div_list_test" <?php if(empty($courses['type'])){?>style="display:none;"<?php }elseif(!empty($courses) && $courses['type'] == 1){?>style="display:table-row;"<?php }else{?>style="display:none;"<?php }?>>
        <td style="text-align: right;">Test:</td>
        <td colspan="2">
          <select name="slt_id_test_pass" id="slt_id_test_pass">
            <option value="0">-----</option>
            <?php 
              if(!empty($arr_test)){
                foreach($arr_test as $stt_test => $item_test){
            ?>
              <option <?php if(!empty($courses['id_test']) && $courses['id_test'] == $item_test['uid']) echo 'selected'; ?> value="<?php echo !empty($item_test['uid'])?$item_test['uid']:'';?>"><?php echo !empty($item_test['test_title'])?$item_test['test_title']:'';?></option>
            <?php }}?>
          </select>
        </td>
      </tr>
       <!-- phan tag-->
      <tr>
        <td style="text-align: right;padding-top: 10px;">Tag:</td>
        <td id="wap_main_tags" style="padding-top: 10px;">
        <?php
        if(isset($tags) && !empty($tags)){
        	foreach ($tags as $key => $value) {
        ?>
          <span class="tags_wap">
            <span><?php echo $value['name']; ?></span>
            <span class="tags_remove">x</span>
            <input type="hidden" name="txt_tags_id[]" value="<?php echo $value['id']; ?>">
          </span>
        <?php
        	} //end for
        }//end if
        ?>
        </td>
        <td style="padding-top: 10px;">
          <span class="add_tags" onclick="fn_add_tags()">+</span>
        </td>
      </tr>
      <!-- phan Authorized Sponsor -->
      <tr>
        <td style="text-align: right;">Authorized Sponsor</td>
        <td colspan="2">
        <?php 
          if(!empty($courses['authorized_day_using']) && !empty($courses['authorized_day']) && strtotime("-". $courses['authorized_day'] ." day" ) <= $courses['authorized_day_using']){
            $m_date      = strtotime(date('m/d/Y',$courses['authorized_day_using']). ' + '.$courses['authorized_day'].' day');
            $date1       = date_create(date('Y-m-d', $m_date));
            $date2       = date_create(date('Y-m-d'));
            $diff        = date_diff($date1,$date2);
            $txt_int_day = (int)$diff->format("%a");
            //$txt_int_day = round(abs(strtotime(date('m/d/Y',$courses['authorized_day_using']). ' + '.$courses['authorized_day'].' day') - strtotime(date('m/d/Y')))/(60*60*24));
          }else{
            $txt_int_day = 0;
          }
          
        ?>
          <span class="day_using">in effect for <span class="txt_day"><?php echo $txt_int_day; ?></span> days</span>
          <span style="height: 17px;padding: 5px;font-size: 16px;min-width: 90px;" class="add_tags" onclick="fn_add_day()">Add Days</span>
          <span style="height: 17px;padding: 5px;font-size: 16px;min-width: 90px;background-color:#000;" class="add_tags" onclick="fn_disable_day()">Disable</span>
          <span style="height: 17px;padding: 5px;font-size: 16px;min-width: 90px;" class="add_tags" onclick="fn_add_sponsor()">Add sponsor image</span>
          <span id="div_sponsor_img" style="display: inline-block;position: relative;background-color: #fff;">
          <?php if(isset($courses['sponsor_icon']) && !empty($courses['sponsor_icon'])){?>
            <img height="40px" width="120px" src="<?php echo linkS3; ?>sponsor_icon/<?php echo $courses['sponsor_icon']; ?>">
            <a title="Delete" style="line-height: 79px;position: absolute;right: 0;top: 0;" href="javascript:0;" class="ico_delete delete_sponsor_img">Delete</a>
          <?php }?>
          </span>
          <input type="hidden" name="txt_sponsor_img" id="txt_sponsor_img" value="<?php echo !empty($courses['sponsor_icon'])?$courses['sponsor_icon']:''; ?>">
          <input type="hidden" name="txt_day_using" id="txt_day_using" value="<?php echo $txt_int_day; ?>">
        </td>
      </tr>
      <!-- phan sponsor tag-->
      <tr>
        <td style="text-align: right;padding-top: 10px;">Sponsor Tag:</td>
        <td id="wap_main_sponsor_tags" style="padding-top: 10px;">
        <?php
        if(isset($sponsor_tags) && !empty($sponsor_tags)){
          foreach ($sponsor_tags as $key => $value) {
        ?>
          <span class="sponsor_tags_wap">
            <span><?php echo $value['name']; ?></span>
            <span class="sponsor_tags_remove">x</span>
            <input type="hidden" name="txt_sponsor_tags_id[]" value="<?php echo $value['id']; ?>">
          </span>
        <?php
          } //end for
        }//end if
        ?>
        </td>
        <td style="padding-top: 10px;">
          <span class="add_sponsor_tags" onclick="fn_add_sponsor_tags()">+</span>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- phan E-course -->
  <div id="div_list_lesson" <?php if(empty($courses['type'])){?>style="display:block;"<?php }elseif(!empty($courses) && $courses['type'] == 0){?>style="display:block;"<?php }else{?>style="display:none;"<?php }?> >
    <div style="width: 800px;margin: auto; margin-top:10px; margin-bottom:10px; font-family: Arial, Helvetica, sans-serif;">
      <div style="text-align: center;border-top: 3px solid #000;padding-top: 10px;">
        <p style="font-weight: bold;">Lesson Modules Configuration</p>
      </div>
      <div style="text-align: right; padding: 20px 0;">
        <button type="button" class="button new" onclick="javascript:save('add_lesson');">
          <span>Add Lesson Module</span>
        </button>
      </div>
      <div>
        <table style="width: 450px;margin: auto;font-weight: bold;">
          <tbody>
          <?php if(!empty($list_lesson)) {?>
            <?php foreach($list_lesson as $id => $lesson){ ?>
              <tr id="row_lesson_<?php echo $lesson['id'];?>">
                <td><?php echo !empty($lesson['title'])?$lesson['title']:''; ?></td>
                <td style="width:50px; position: relative;">
                  <a href="<?php echo url::base() ?>admin_courses/edit_lesson/<?php echo $lesson['id'];?>" class="ico_edit">
                  <?php echo Kohana::lang('global_lang.btn_edit') ?></a>&nbsp;<span style="position: absolute; display: inline-block; top: 3px;">Edit</span>
                </td>
                <td style="width:50px; position: relative;">
                <?php //if(isset($lesson['type']) && $lesson['type'] == 2){ ?>
                  <a id="delete_<?php echo $lesson['id']; ?>" href="javascript:delete_lesson(<?php echo $lesson['id'];?>);" 
                    class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a> <span style="position: absolute; display: inline-block; top: 3px;">Del</span>
                <?php //}?>
                </td>
              </tr>
            <?php }}?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  

  <div id="dialog" title="Purchase Member" >
  </div>
  <input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($courses['id'])?$courses['id']:''?>"/>
  <table  cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><?php require('button.php');?></td>
    </tr>
  </table>
</form>

<div id="wrap_crop_company" style="display:none;"></div>
<div id="wrap_crop_sponsor" style="display:none;"></div>
<div id="wrap_add_tags" style="display:none;"></div>
<div id="wrap_add_sponsor_tags" style="display:none;"></div>

<div id="div_add_day" style="display:none;">
  <div style="padding: 20px;">
    <p><input style="width: 150px;text-align: center;" type="text" name="txt_day" id="txt_day" value=""> days</p>
  </div>
  <div style="padding-top: 10px;text-align: center;">
    <button type="button" id="btn_save_add_day">Save</button>
    <button type="button" class="btn_close_add_day">Cancel</button>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $(document).on('click', '.delete_sponsor_img', function(event) {
      event.preventDefault();
      var item = $(this);
      console.log(item);
      $.msgbox('Do you really want to delete?', {
        type : 'confirm',
        buttons : [
          {type: 'submit', value:'Yes'},
          {type: 'submit', value:'Cancel'}
          ]
        }, 
        function(re) {
          if(re == 'Yes') {
            item.parent('span').empty();
            $('#txt_sponsor_img').val('');
          }
        }
      );
    });
    $(document).on('click', '.btn_close_add_day', function(event) {
      event.preventDefault();
      $( "#div_add_day" ).dialog('close');
    });
    $(document).on('click', '#btn_save_add_day', function(event) {
      event.preventDefault();
      var txt_day = $('#txt_day').val();
      if($.isNumeric(txt_day) == false || txt_day <= 0){
        $.msgbox('Days Valid is integer, value positive', {
          type : 'error',
          buttons : [
            {type: 'submit', value:'Cancel'},
          ]
        });
        return false; 
      }
      $('#txt_day_using').val(txt_day);
      $('.txt_day').text(txt_day);
      $( "#div_add_day" ).dialog('close');
    });
  });
	$(document).ready(function() {
		<?php 
			if(!empty($courses)){
				if($courses['type'] == 0){ ?>
					$('#btn_type_1').prop('disabled', true);
		<?php	}else{ ?>
					$('#btn_type_0').prop('disabled', true);
		<?php	}} ?>
	});

  function fn_disable_day(){
    $.msgbox('Do you really want to disable?', {
      type : 'confirm',
      buttons : [
        {type: 'submit', value:'Yes'},
        {type: 'submit', value:'Cancel'}
        ]
      }, 
      function(re) {
        if(re == 'Yes') {
          $('#txt_day_using').val(0);
          $('.txt_day').text('0');
        }
      }
    );
  }

  function fn_add_day(){
    $( "#div_add_day" ).dialog({
      //draggable: false,
      modal: true,
      width:'auto',
      height:'auto',
      dialogClass: "no-close",
      autoOpen:true,
      title:"",
      open : function(event, ui) { 
        $('#txt_day').val('');
      },
      close : function(event, ui) { 
        $('#txt_day').val('');
      }
    });
  }

  function fn_add_tags(){
    $.ajax({
      url: '<?php echo url::base()?>admin_courses/get_tags_courses',
      type: 'GET',
      dataType: 'html',
    })
    .done(function(data) {
      $('#wrap_add_tags').html(data);
      $( "#wrap_add_tags" ).dialog({
        //draggable: false,
        modal: true,
        width:'auto',
        height:'auto',
        dialogClass: "no-close",
        autoOpen:true,
        title:""
      });
    });
  }

  $(function() {
    $(document).on('click', '.tags_remove', function(event) {
      event.preventDefault();
      $(this).parent('span.tags_wap').remove();
    });
  });
  // -----------
  function fn_add_sponsor_tags(){
    $.ajax({
      url: '<?php echo url::base()?>admin_courses/get_sponsor_tags_courses',
      type: 'GET',
      dataType: 'html',
    })
    .done(function(data) {
      $('#wrap_add_sponsor_tags').html(data);
      $( "#wrap_add_sponsor_tags" ).dialog({
        //draggable: false,
        modal: true,
        width:'auto',
        height:'auto',
        dialogClass: "no-close",
        autoOpen:true,
        title:""
      });
    });
  }

  $(function() {
    $(document).on('click', '.sponsor_tags_remove', function(event) {
      event.preventDefault();
      $(this).parent('span.sponsor_tags_wap').remove();
    });
  });
</script>

<?php require('frm_js.php');?>
