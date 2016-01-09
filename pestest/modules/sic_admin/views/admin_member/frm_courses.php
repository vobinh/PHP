<form id="frm_create_courses" name="frm_create_courses" action="<?php echo url::base() ?>admin_courses/save_ajax" method="post" enctype='multipart/form-data'>
  <link rel="stylesheet" href="<?php echo url::base()?>themes/popup/jquery-ui.css">
  <table style="margin: auto;" >
    <tbody>
      <tr>
        <td style="text-align: right;">Type:</td>
        <td>
          <input style="margin: auto;" type="radio" id="btn_type" checked name="txt_type" value="1">&nbsp;Practice test
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Test:</td>
        <td>
          <b><?php echo !empty($test['test_title'])?$test['test_title']:'';?></b>
          <input type="hidden" id="txt_test_id" checked name="txt_test_id" value="<?php echo !empty($test['uid'])?$test['uid']:'';?>" >
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Title<font color="#FF0000">*</font>:</td>
        <td>
          <input type="text" style="width:98%" id='txt_title' name="txt_title" value="<?php echo isset($courses['title'])?$courses['title']:''?>"/>
        </td>
      </tr>
      <tr>
        <td style="text-align: right; vertical-align: top;">Description:</td>
        <td>
          <textarea  class="ckeditor" style="height:50px; width:51%" id="txt_description" name="txt_description" ><?php echo isset($courses['description'])?$courses['description']:''?>
          </textarea>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Price<font color="#FF0000">*</font>:</td>
        <td>
          <input style="width:140px" type="text" name="txt_price" id="txt_price" value="<?php echo isset($courses['price'])?$courses['price']:''; ?>" placeholder="">
        </td>
      </tr>
      <tr>
        <td style="text-align: right; vertical-align: top;">Image:</td>
        <td>
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
        <td>
          <input style="width:140px" type="text" name="txt_days_valid" id="txt_days_valid" value="<?php echo isset($courses['day_valid'])?$courses['day_valid']:''; ?>" placeholder="">
        </td>
      </tr>
      <tr>
        <td style="text-align: right;">Certificate:</td>
        <td>
          <select style="width:154px" name="txt_certificate" id="txt_certificate" >
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
        <td>
          <select style="width:154px" name="txt_status" id="txt_status" >
            <?php if(isset($test['status']) && $test['status'] == 0){?>
                <option value="0" selected="selected">Inactive</option>
                    <option value="1">Active</option>
            <?php }else{?>
                    <option value="0">Inactive</option>
                    <option value="1" selected="selected">Active</option>
            <?php }?>
          </select>
        </td>
      </tr>
      <!-- phan practice test-->
      <tr id="div_list_test" <?php if(empty($courses['type'])){?>style="display:none;"<?php }elseif(!empty($courses) && $courses['type'] == 1){?>style="display:table-row;"<?php }else{?>style="display:none;"<?php }?>>
        <td style="text-align: right;">Test:</td>
        <td>

        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <button onclick="javascript:save('add');" style="padding-left:30px;padding-right:30px;" type="button">Save</button>
        </td>
      </tr>

    </tbody>
  </table>
  <input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($courses['id'])?$courses['id']:''?>"/>
</form>
<div id="wrap_crop_company" style="display:none;"></div>

<script type="text/javascript">
function show_crop_img() {
    $.ajax({
        type: 'POST',
        url: '<?php echo url::base() ?>admin_courses/list_crop_company',
        success: function (resp) { 
            $('#wrap_crop_company').html(resp);
            $( "#wrap_crop_company" ).dialog({
             draggable: false,
             modal: true,
             width:800,
             height:550,
             dialogClass: "no-close",
             autoOpen:true,
             title:"Image Crop"
         });
        }
    });   
}

function save(value)
{
  var title       = document.getElementById('txt_title').value;
  var description = document.getElementById('txt_description').value; 
  var price       = document.getElementById('txt_price').value; 
  var days_valid  = document.getElementById('txt_days_valid').value;  

  if( title.length < 3 )
  {
    $.msgbox('<?php echo Kohana::lang("test_frm_lang.val_title")?>', {
      type : 'error',
      buttons : [
        {type: 'submit', value:'Cancel'},
      ]
    });
    document.getElementById('txt_title').focus();
    return false; 
  }
  
  if($.isNumeric(price) == false || price < 0){
    $.msgbox('Input price is number, value positive', {
      type : 'error',
      buttons : [
        {type: 'submit', value:'Cancel'},
      ]
    });
    return false; 
  }
  if($.isNumeric(days_valid) == false || days_valid < 0){
    $.msgbox('Days Valid is integer, value positive', {
      type : 'error',
      buttons : [
        {type: 'submit', value:'Cancel'},
      ]
    });
    return false; 
  }
  $('input#hd_save_add').val('');
  if(value=='add') 
    $('input#hd_save_add').val(1);
  if(value=='add_lesson') 
    $('input#hd_save_add').val(2);
  document.frm_create_courses.submit();
}

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

$("#txt_days_valid").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

$("#txt_price").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});

function checkSelect(){
     if($('#sel_type_time').val() == '1' && ($('#txt_time_value').val() == '' || $('#txt_time_value').val() == '0')){
      $('#txt_time_value').focus();
      alert('Please input again. If "Type time" is Down input value > 0');
     }
}
</script>