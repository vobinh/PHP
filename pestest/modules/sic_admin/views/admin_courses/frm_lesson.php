<link rel="stylesheet" href="<?php echo url::base()?>js/jquery-filestyle/src/jquery-filestyle.min.css">
<style type="text/css" media="screen">
  .btn_delete_lesson{
    background-color: #fff;
    background: transparent;
    color: red;
    border-color: #000;
  }
  .btn_delete_lesson:hover{
    background-color: red;
    background: red;
    color: #fff;
    border-color: #fff;
  }
</style>
<form id="frm" name="frm" action="<?php echo url::base() ?>admin_courses/save_lesson" method="post" enctype='multipart/form-data'>
  <link rel="stylesheet" href="<?php echo url::base()?>themes/popup/jquery-ui.css">
  <table id="float_table" class="title" cellspacing="0" cellpadding="0">
  <tr>
      <td class="title_label">
        <?php 
          if(!empty($lesson['id'])){
            echo 'Edit Lesson Module';
          }else{
            echo 'Add Lesson Module';
          }
        ?>
      </td>
      <td class="title_button"><?php require('button_lesson.php');?></td>
  </tr>
  </table>

  <table style="width: 800px;margin: auto;font-weight: bold" >
    <tbody>
      <tr>
        <td>
          Lesson Module Title<font color="#FF0000">*</font>: <input type="text" name="txt_title" id="txt_title" value="<?php echo !empty($lesson['title'])?$lesson['title']:''; ?>" placeholder="">
        </td>
      </tr>
      <tr>
        <td>
          <!-- Unlock Condition: -->
          Complete lesson:
        </td>
      </tr>
      <tr>
        <td style="padding-left: 30px;">
          if video link<font color="#FF0000">*</font>:&nbsp;<input style="width: 45%;" type="text" name="txt_video_link" id="txt_video_link" value="<?php echo !empty($lesson['video_link'])?$lesson['video_link']:''; ?>" placeholder="">&nbsp;
          video is watched&nbsp;<input style="width: 50px;" type="text" name="txt_percent_lesson_pass" id="txt_percent_lesson_pass" value="<?php echo !empty($lesson['percent_lesson_pass'])?$lesson['percent_lesson_pass']:''; ?>" placeholder="">%
        </td>
      </tr>
      <tr>
        <td style="padding-left: 30px;">
          if test:&nbsp;<select name="slt_id_test_pass" id="slt_id_test_pass" onchange="showcategory(this.value)" style="max-width: 200px;">
              <option value="0">-----</option>
              <?php 
                if(!empty($arr_test)){
                  foreach($arr_test as $stt_test => $item_test){
              ?>
                <option <?php if(!empty($lesson['id_test_pass']) && $lesson['id_test_pass'] == $item_test['uid']) echo 'selected'; ?> value="<?php echo !empty($item_test['uid'])?$item_test['uid']:'';?>"><?php echo !empty($item_test['test_title'])?$item_test['test_title']:'';?></option>
              <?php }}?>
            </select>&nbsp;

            <select name="slt_id_categories" id="slt_id_categories" style="max-width: 200px;">
              <option value="0">All Categorise</option>
              <?php 
                if(!empty($arr_categories)){
                  foreach($arr_categories as $stt_cate => $item_cate){
              ?>
                <option <?php if(!empty($lesson['id_categories']) && $lesson['id_categories'] == $item_cate['uid']) echo 'selected'; ?> value="<?php echo !empty($item_cate['uid'])?$item_cate['uid']:'';?>"><?php echo !empty($item_cate['category'])?$item_cate['category']:'';?></option>
              <?php }}?>
            </select>&nbsp;


          score is greater than&nbsp;<input style="width: 50px;" type="text" name="txt_percent_test_pass" id="txt_percent_test_pass" value="<?php echo !empty($lesson['percent_test_pass'])?$lesson['percent_test_pass']:''; ?>" placeholder="">%
        </td>
      </tr>
      <tr>
        <td>
          Lesson Annotation text:
        </td>
      </tr>
      <tr>
        <td style="padding-left: 22px;">
          <input style="width: 18px;height: 18px;" <?php if(!empty($lesson['hide_annotation']) && $lesson['hide_annotation'] == 'Y'){ ?>checked<?php }?> type="checkbox"  name="txt_hide_annotation" value="1"> Hide Annotation
        </td>
      </tr>
      <tr>
        <td style="padding-left: 22px;">
          <table id="tb_list_item_annotation">
            <tbody>
            <?php 
              if(!empty($annotation)){
                foreach($annotation as $stt => $item){
            ?>
              <tr>
                <td style="width: 20px;vertical-align: top;">
                  <button class="btn_delete_lesson" type="button">x</button>
                </td>
                <td style="vertical-align: top;">
                  <input style="width:50px;" type="text" name="txt_time[]" value="<?php echo !empty($item['time'])?$item['time']:'';?>" placeholder="time">
                </td>
                <td>
                  <textarea style="width: 61%;resize: none;" rows="5" name="txt_text[]" placeholder="text"><?php echo !empty($item['text'])?$item['text']:'';?></textarea>
                </td>
              </tr>
            <?php }}?>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding-left: 65px; padding-bottom: 20px;">
          <button type="button" id="btn_add_annotation"><span style="font-weight: bold;color: greenyellow;">+</span> Add annotation</button>
          <button type="button">Import Transcript</button>
        </td>
      </tr>
      <tr>
        <td>
          <span class="cls_text_attach" style="color: red;padding-bottom: 5px;display: block;">
            <?php if(!empty($lesson['attach_file'])) {?>
              <?php echo !empty($lesson['attach_file'])?$lesson['attach_file']:'';?>
              <a href="javascript:delete_attach_file();" class="ico_delete">Delete</a>
            <?php }?>
          </span>
          <input type="hidden" name="txt_file_acttach" id="txt_file_acttach" value="<?php echo !empty($lesson['attach_file'])?$lesson['attach_file']:'';?>">
          <input type="file" id="txt_attach_file" name="txt_attach_file" value="" class="jfilestyle">
        </td>
      </tr>
    </tbody>
  </table>
  <input name="id_courses" type="hidden" id="id_courses" value="<?php echo isset($id_courses)?$id_courses:''?>"/>
  <input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($lesson['id'])?$lesson['id']:''?>"/>
  <table  cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><?php require('button_lesson.php');?></td>
    </tr>
  </table>
</form>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery-filestyle/src/jquery-filestyle.min.js"></script>

<script type="text/javascript">
  $("#txt_attach_file:file").jfilestyle({buttonText: "Attach Note File"});

</script>
<?php require('frm_lesson_js.php'); ?>