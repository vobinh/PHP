<script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
<script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>
<link rel="stylesheet" href="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.css">
<style type="text/css" media="screen">
  .ui-widget {
    font-family: Arial,Verdana,sans-serif;
    font-size: 14px;
}
</style>
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
</style>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_courses/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label">Courses</td>
    <td class="title_button">
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_courses/create'"/>
    <span>Add New Courses</span>
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
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
      <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
      &nbsp;
  	  <button type="submit" name="btn_search" class="button search">
       <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
      </button>
      &nbsp;
      <button type="button" id="btn_recommended">Set Recommended</button>
      &nbsp;
      <button type="button" id="btn_tags">Register State Tags</button>
      &nbsp;
      <button type="button" id="btn_sponsor_tags">Sponsor Tags</button>
      &nbsp;
      <button type="button" id="btn_sponsor_img">Sponsor Image</button>
    </td>
</tr>
<tr class="list_header">
	<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>
    <td nowrap="nowrap">No#</td>
  	<td nowrap="nowrap"><?php echo Kohana::lang('test_list_lang.lbl_title') ?></td>
    <td><?php echo Kohana::lang('test_list_lang.lbl_description') ?></td>
    <td nowrap="nowrap">Valid for</td>
    <td >Thumbnail Image</td>
    <td >No. of Lesson Modules</td>
    <td nowrap="nowrap">Price</td>
    <td>Purchase Member</td>
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_status') ?></td> 
    <td width="80"><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
    <td width="80">Authorized Sponsor</td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  $i=0;	  
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['id']?>">
  	<td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['id']?>" /></td>
    <td><?php echo $list['id']?></td>
    <td align="center">
      <?php echo $list['title']; ?>
    </td>
  	<td width="40%">
      <?php echo $list['description']; ?>
    </td> 
    <td align="center">
      <?php if($list['day_valid'] >0){
        echo $list['day_valid'] ?>
        <?php echo($list['day_valid'] > 1)?' days':" day" ?>
        <?php }else { ?>
          No Limit
      <?php } ?>
    </td>
    <td align="center">
      <?php if(!empty($list['image'])){?>
        <?php if(s3_using == 'on'){?>
          <img height="50px" src="<?php echo linkS3; ?>courses_img/<?php echo $list['image']; ?>">
        <?php }else{ ?>
          <img height="50" src="<?php echo url::base() ?>uploads/courses_img/<?php echo $list['image']; ?>">
        <?php }?>
      <?php }?>
    </td>
    <td align="center">
      <?php echo $list['lesson_count']; ?>
    </td>
    <td align="center">
      <?php echo !empty($list['price'])?'$'.$list['price']:'$0.00'; ?>
    </td>

    <td align="center">
      <a style="cursor:pointer; background: #AEAFAE;padding: 3px;border-radius: 4px;text-decoration: none;color: white;border: #807D78 1px solid;" onclick="loadmember('<?php echo url::base() ?>admin_courses/member/<?php echo $list['id'] ?>');$('#dialogmember').dialog('open');"><?php echo 'View' ?>
      </a>
    </td>

    <td align="center">
      <?php if($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2) { ?>
        <a href="<?php echo url::base()?>admin_courses/setstatus/<?php echo $list['id']?>"><?php } // end if ?>
      <?php if($list['status']== 1){ ?>	            
		    <img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">	 
	   <?php } else { ?> 	
		    <img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
	   <?php } ?>
        <?php if ($list['id'] == $this->sess_admin['id']) { echo '</a>'; } // end if ?>
    </td>
	 <td align="center">
    <a href="<?php echo url::base() ?>admin_courses/edit/<?php echo $list['id'] ?>" class="ico_edit">
    <?php echo Kohana::lang('global_lang.btn_edit') ?></a>
            
    <a id="delete_<?php echo $list['id']?>" href="javascript:delete_admin(<?php echo $list['id']?>);" 
    class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
   </td>
   <td align="center">
      <?php
        if(!empty($list['authorized_day_using']) && !empty($list['authorized_day']) && strtotime("-". $list['authorized_day'] ." day" ) <= $list['authorized_day_using']){
          $m_date      = strtotime(date('m/d/Y',$list['authorized_day_using']). ' + '.$list['authorized_day'].' day');
          $date1       = date_create(date('Y-m-d', $m_date));
          $date2       = date_create(date('Y-m-d'));
          $diff        = date_diff($date1,$date2);
          $txt_int_day = (int)$diff->format("%a");

         // $txt_int_day = round(abs(strtotime(date('m/d/Y',$list['authorized_day_using']). ' + '.$list['authorized_day'].' day') - strtotime(date('m/d/Y')))/(60*60*24));
        }else{
          $txt_int_day = 0;
        }
        if($txt_int_day > 0){
          echo ($txt_int_day > 1)?'<b>'.$txt_int_day.' days</b>':'<b>'.$txt_int_day.' day</b>';
        }else{
          echo '<b>No</b>';
        }
      ?>
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
      onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_courses/saveall','Do you really want to delete?');">
      <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
      </button>
      </td>
      <td style="text-align: right;">
      <?php /*?>
        <button type="button" id="btn_open_import">Import Data</button>
      <?php */?>
      </td>
  </tr>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_courses/display">
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
  <form id="frm_import" action="<?php echo url::base()?>admin_courses/import" method="post" accept-charset="utf-8" style=" text-align: center;" enctype="multipart/form-data">
        <input type="file" id="txt_import" name="txt_import" placeholder="">
  </form>
</div>
<div id="div_recommended" style="display:none;"></div>
<div id="div_tags" style="display:none;"></div>
<div id="div_sponsor_tags" style="display:none;"></div>
<div id="div_sponsor_img" style="display:none;"></div>

<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 999999;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>
<?php require('list_js.php')?>