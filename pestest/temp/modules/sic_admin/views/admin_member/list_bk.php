<script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
<script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>
<link rel="stylesheet" href="<?php echo $this->site['base_url']?>/js/jquery/jquery-ui.css"><script>
$(function() {
    $( "#dialogmytest" ).dialog({
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

$(function(){
    $( "#dialogtestting" ).dialog({
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
<div id="dialogmytest" title="My Test" >

</div>
<div id="dialogtestting" title="Testing" >

</div>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_member/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Member'; ?></td>
    <td class="title_button">
    <?php if ($this->sess_admin['level'] == 1) { ?>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_member/create'"/>
    <span><?php echo 'Input new Member' ?></span>
    </button>
    <?php } // end if level super admin ?>
    </td>
  </tr>
</table>
<table cellspacing="0" class="list_top">
  <tr>
    <td><?php echo 'Search'; ?>:
        <select name="sel_search" style="width:100px;">
      
        <option <?php echo (isset($option) && $option == '1') ? 'selected="selected"' : '' ?> value="1">Name</option>
        <option <?php echo (isset($option) && $option == '2') ? 'selected="selected"' : '' ?> value="2">Email</option>
        </select>
      <input name="txt_keyword" type="text" id="txt_keyword" value="<?php if(isset($keyword)) echo $keyword ?>"  />
      &nbsp;
      <button type="submit" name="btn_search" id="btn_search" class="button search" />
      <span><?php echo 'Search'; ?></span>
      </button>
      </td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
 
    <td><?php echo Kohana::lang('account_lang.lbl_fname') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_lname') ?></td>
    <td><?php echo Kohana::lang('account_lang.lbl_email') ?></td>
    <td><?php echo 'Company Name' ?></td>
     <td><?php echo 'Company Email' ?></td>
     <td width="80" ><?php echo 'My test' ?></td>  
    <td width="80" ><?php echo 'Testing' ?></td>  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
    
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
 
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	
    <td align="center"><?php echo $list['member_fname'] ?></td>
    <td align="center"><?php echo $list['member_lname'] ?></td>
    <td align="center"><?php echo $list['member_email']?></td>
    <td align="center"><?php echo $list['company_name'] ?></td>
    <td align="center"><?php echo $list['company_contact_email'] ?></td>
    <td align="center">
      
    <a style="cursor:pointer; background: #AEAFAE;padding: 3px;border-radius: 4px;text-decoration: none;color: white;border: #807D78 1px solid;"
    onclick="loadmytest('<?php echo url::base() ?>admin_member/mytest/<?php echo $list['uid'] ?>');$('#dialogmytest').dialog('open');"><?php echo 'View' ?></a>
    </td>
    
     <td align="center">
     <a style="cursor:pointer; background: #AEAFAE;padding: 3px;border-radius: 4px;text-decoration: none;color: white;border: #807D78 1px solid;"
     onclick="loadtestting('<?php echo url::base() ?>admin_member/testing/<?php echo $list['uid'] ?>');$('#dialogtestting').dialog('open');"><?php echo 'View' ?></a>
     
     </td>
    <td align="center">
    <a href="<?php echo url::base()?>admin_member/setstatus/<?php echo $list['uid']?>">
    <?php if($list['status'] == 1){ ?>	            
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	 
	<?php } else { ?> 	
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
   
	<? } ?>
   </a>
    </td> 
    
      <td align="center">
      <a  href="<?php echo url::base() ?>admin_member/edit/<?php echo $list['uid'] ?>" class="ico_edit"><?php echo Kohana::lang('global_lang.btn_edit') ?></a>
  
   
    <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
  
    </td>
  </tr>
 
  <?php } } // end foreach ?>
</table>
</form>
<div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_member/display">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onchange="document.frm_display.submit();">
	<option value="">---</option>
    <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
    <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
    <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
    <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
    <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
</select>
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
</form>
</div>
<?php require('list_js.php')?>
<script>
function loadmytest(val1){
	$.ajax({
		url: val1,
		type: "GET",
		success: function(data){
			$('#dialogmytest').html(data);
		}
	});
}
function loadtestting(val1){
	$.ajax({
		url: val1,
		type: "GET",
		success: function(data){
			$('#dialogtestting').html(data);
		}
	});
}
</script>