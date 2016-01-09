<style type="text/css" media="screen">
  .ui-widget {
    font-family: Arial,Verdana,sans-serif;
    font-size: 14px;
}
</style>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_test/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('test_list_lang.tt_page') ; ?></td>
    <td class="title_button">
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_test/create'"/>
    <span><?php echo Kohana::lang('test_list_lang.btn_new_question') ?></span>
    </button>
    </td>
</tr>
  
<link rel="stylesheet" href="<?php echo $this->site['base_url']?>themes/popup/jquery-ui.css">
<script>
$(function() {
    $( "#dialogmember" ).dialog({
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
 
function loadmember(val1){
	$.ajax({
		url: val1,
		type: "GET",
		success: function(data){
			$('#dialogmember').html(data);
		}
	});
}
 </script>
<div id="dialogmember" title="Purchase Member" ></div>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr>
    <td colspan="14"><?php echo Kohana::lang('global_lang.lbl_search')?>:
      <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
      &nbsp;
  	  <button type="submit" name="btn_search" class="button search"/>
       <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
      </button>
    </td>
</tr>
<tr class="list_header">
	<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>
  	<td nowrap="nowrap"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td><?php echo Kohana::lang('test_list_lang.lbl_description') ?></td>
    <td nowrap="nowrap">No. of Question</td>
    <td nowrap="nowrap">Tracking Type</td>
    <td nowrap="nowrap">Testing Time</td>
    <td nowrap="nowrap">Validation Day</td>
    <td><?php echo 'Pass Score' ?></td>
    <td><?php echo 'Price' ?></td>
    <!-- <td>Purchase Member</td> -->
    <td width="80">Order</td>
<?php /*?>    <td width="80">Download</td>
<?php */?>    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td> 
    <td>Export</td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
</tr>
  <?php 
  
  if(!empty($mlist) && $mlist!=false){
  $i=0;	  
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['uid']?>" /></td>
    <td align="center" nowrap="nowrap"><?php echo $list['test_title'] ?></td>
    <td align="left"><?php echo $list['test_description'] ?></td>
  	<td align="right"><?php echo $list['qty_question'] ?></td>
    <td align="center"><?php echo $list['type_time']==1?'Countdown':'Stopwatch' ?></td> 
    <td align="right"><?php echo $list['time_value'] ?> <?php echo($list['time_value'] >1)?' minutes':" minute"?></td>
    <td align="right"><?php if($list['date'] >0) 
	                        { echo $list['date'] ?><?php echo($list['date'] >1)?' days':" day" ?><?php }else { ?>No Limit<?php } ?>
    
    </td>
    <td align="right"><?php echo $list['pass_score'] .'%'?></td>
    
    <td align="right"><?php echo $this->format_currency($list['price'])?></td>
     <!-- <td align="center">
     <a style="cursor:pointer; background: #AEAFAE;padding: 3px;border-radius: 4px;text-decoration: none;color: white;border: #807D78 1px solid;"
    onclick="loadmember('<?php //echo url::base() ?>admin_test/member/<?php //echo $list['uid'] ?>');$('#dialogmember').dialog('open');"><?php //echo 'View' ?></a>
     </td> -->
     <td align="center">
    <?php if($i==0){?>
        <a href="<?php echo url::base()?>admin_test/order_file/down/<?php echo $list['uid']?>"><img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" border="0"/></a>
        <?php }elseif($i==count($mlist)-1){ ?>
        </a>
        <a href="<?php echo url::base()?>admin_test/order_file/up/<?php echo $list['uid']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" border="0"/>
        </a>
        <?php }else {?>
        
         <a href="<?php echo url::base()?>admin_test/order_file/down/<?php echo $list['uid']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_down.png" border="0"/>
        </a>
        <a href="<?php echo url::base()?>admin_test/order_file/up/<?php echo $list['uid']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_up.png" border="0"/>
        </a>
        <?php }?>
    </td> 
    <?php /*?><td align="center">
     <a href="<?php echo url::base()?>admin_test/download/<?php echo $list['uid']?>">
            <img src="<?php echo url::base()?>themes/admin/pics/icon_download.png" border="0" alt="Download"/>
        </a>
    </td><?php */?>
    <td align="center">
    <?php if($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2) { ?>
    <a href="<?php echo url::base()?>admin_test/setstatus/<?php echo $list['uid']?>"><?php } // end if ?>
    <?php if($list['status']== 1){ ?>	            
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	 
	<?php } else { ?> 	
		<img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
	<?php } ?>
    <?php if ($list['uid'] == $this->sess_admin['id']) { echo '</a>'; } // end if ?>
    </td>
  <td>
    <a href="<?php echo url::base() ?>admin_test/export/<?php echo $list['uid'] ?>" target="_blank">Export</a>
  </td>
	<td align="center">
          <a href="<?php echo url::base() ?>admin_test/edit/<?php echo $list['uid'] ?>" class="ico_edit">
          <?php echo Kohana::lang('global_lang.btn_edit') ?></a>
            
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    
   </td>
  </tr>
  <?php $i++;} // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
<table class="list_bottom" cellspacing="0" cellpadding="5" >
  <tr>
    <td>
      <select name="sel_action" id="sel_action" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>  >
        <option value="active"><?php echo Kohana::lang('test_list_lang.lbl_active_all')?></option>
        <option value="inactive"><?php echo Kohana::lang('test_list_lang.lbl_unactive_all')?></option>
        <option value="delete"><?php echo Kohana::lang('test_list_lang.lbl_delete_all')?></option>
        </select>
      &nbsp;
      <button type="button" class="button save" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>
      onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_test/saveall','Do you really want to delete?');">
      <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
      </button>
      </td>
      <td style="text-align: right;">
        <button type="button" id="btn_open_import">Import Data</button>
      </td>
  </tr>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_test/display">
<?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onChange="document.frm_display.submit();">
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

<div id="div_import" style="display:none;" title="Import Data">
  <form id="frm_import" action="<?php echo url::base()?>admin_test/import" method="post" accept-charset="utf-8" style=" text-align: center;" enctype="multipart/form-data">
        <input type="file" id="txt_import" name="txt_import" placeholder="">
  </form>
</div>
<?php require('list_js.php')?>