<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<?php 
  $test_model = new Test_Model();
  $courses_model = new Courses_Model();
?>
<style type="text/css" media="screen">
  #tb_convert tr.rowtd td{
    background-color: rgba(204, 204, 204, 0.66); 
  }
</style>
<table>
  <thead>
    <tr>
      <th><p style="font-size: 20px;">Convert Data<p></th>
    </tr>
  </thead>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
    <td><?php echo Kohana::lang('account_lang.lbl_fname') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_lname') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_email') ?></td>
    <td><?php echo 'Company Name' ?></td>
    <td>Data Convert</td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
 
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  <td align="center"><?php echo $list['member_fname'] ?></td>
  <td align="center"><?php echo $list['member_lname'] ?></td>
  <td align="center"><?php echo $list['member_email']?></td>
  <td align="center"><?php echo $list['company_name'] ?></td>
  <td>
    <table cellspacing="0" id="tb_convert">
      <thead>
        <tr>
          <th style="background-color: rgba(206, 18, 115, 0.73);color: #040000;text-align: center;width:40%;">Test</th>
          <th style="background-color: rgba(206, 18, 115, 0.73);color: #040000;text-align: center;">converted</th>
          <th style="background-color: rgba(206, 18, 115, 0.73);color: #040000;text-align: center;width:40%;">Course</th>
          <th style="background-color: rgba(206, 18, 115, 0.73);color: #040000;text-align: center;"></th>
        </tr>
      </thead>
      <?php 
      if(!empty($list['id_test'])){
        foreach ($list['id_test'] as $key => $value) {
          $result = $test_model->get($value);
          // get code 
          $this->db->where('type',1);
          $this->db->where('id_test',$value);
          $course = $courses_model->get();
          //print_r($course);
        ?>
          <tbody>
          <?php if(!empty($result)){?>
          <tr class="row<?php if($key%2 == 0) echo 'td' ?>">
            <td><?php if(!empty($result)) echo $result['test_title']; ?></td>
            <td <?php if(!empty($course[0])){?>style="background-color: rgba(18, 206, 48, 0.73);text-align: center;"<?php }else{?>style="background-color: rgba(206, 18, 18, 0.73);text-align: center;"<?php }?> ><?php if(!empty($course[0])) echo 'YES'; else echo 'NO'; ?></td>
            <td><?php if(!empty($course[0])) echo $course[0]['title']; else{ echo 'Create a new course with Test: ('.$result['test_title'].') before converting the data point';} ?></td>
            <td style="text-align: right;">
              <?php if(!empty($course[0])){?>
                <button type="button" style="width: 80px; background-color: #4ED764;background-image: linear-gradient(to bottom, #4ED764, rgb(70, 117, 78));" onclick="javascript:location.href='<?php echo url::base()?>admin_member/transfer_data/<?php echo $list['id_payment'][$key] ?>/<?php echo $result['uid'] ?>/<?php echo $course[0]['id'] ?>'">Convert</button>
              <?php }else{?>
                <button type="button" style="width: 80px; background-color: #DB5252;background-image: linear-gradient(to bottom, #DB5252, rgb(183, 97, 97));" onclick="fn_create_courses(<?php echo $result['uid'] ?>)">Create</button>
              <?php }?>
            </td>
          </tr>
          </tbody>
        <?php }}
      }
    ?>
    </table>
  </td>
</tr>
 
  <?php } } // end foreach ?>
</table>

<div id="div_create_courses" style="display:none;">
</div>
<script type="text/javascript">

  function fn_create_courses(id_test){
    $.ajax({
      url: '<?php echo url::base()?>admin_member/create_courses/'+id_test,
      type: 'GET',
    })
    .done(function(data) {
      $('#div_create_courses').html(data);
      CKEDITOR.replace('txt_description',{toolbarGroups :[{ name: 'basicstyles', groups: [ 'basicstyles','undo','align']}]});
      $( "#div_create_courses" ).dialog({
        draggable: false,
        modal: true,
        width:800,
        height:600,
        dialogClass: "no-close",
        autoOpen:true,
        title:"Create Courses"
     });
    })
    .fail(function() {
      console.log("error");
    });
    
  }
</script>