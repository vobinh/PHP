<link rel="stylesheet" href="<?php echo $this->site['base_url']?>themes/popup/jquery-ui.css">
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
<div id="dialogmember" title="Transaction" ></div>

<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_promotion/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Promotion'; ?></td>
    <td class="title_button">
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_promotion/create/multiple'"/>
      <span><?php echo 'Add Multiple' ?></span>
    </button>
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_promotion/create'"/>
    <span><?php echo 'Add New Promotion' ?></span>
    </button>
    </td>
</tr>
  
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
      <select name="sel_test" id="sel_test" style="width: 180px;">
       <option value=""></option>
      <?php foreach($courses as $value){?>
        <option
        <?php if(isset($this->search['test']) && ( $this->search['test'] == $value['id'])){ 
				 echo 'selected="selected"'; }?>  
        value="<?php echo $value['id']?>" ><?php echo $value['title']?>
        </option>
      <?php }?>
      <option value="Status" <?php if(isset($this->search['test']) && ( $this->search['test'] =='Status')) { ?> selected="selected" <?php } ?>>Status</option>
      </select>
    <input type="text" style="height: 20px;" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
</tr>
<tr class="list_header">
	<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>
  	<td width="7%">Date&nbsp;</td>
    <td width="10%">Company Name</td>
    <td width="10%">Promotion Code</td>
    <td width="10%">Discount Value</td>
    <td >Courses&nbsp;</td>
    <td>Limit&nbsp;</td>
    <td width="70" >No. of Use&nbsp;</td> 
    <td width="150" >Period&nbsp;</td>
    <td width="50" >Status&nbsp;&nbsp;</td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['uid']?>" /></td>
  	<td align="center"><?php echo isset($list['date'])?date('m/d/Y',$list['date']):''?></td>
    <td><?php echo isset($list['company'])?$list['company']:''?></td>
    <td id="td_code_<?php echo $list['uid']?>" style="font-weight: bold;">
      <?php if(isset($list['type']) && $list['type'] == '2'){
        $this->db->where('promotion_id', $list['uid']);
        $m_arr_code = $this->db->get('promotion_item')->result_array(false);
        if(!empty($m_arr_code)){
          foreach ($m_arr_code as $key => $value) { 
            if($value['status'] == '1'){?>
              <span style="color: #4CAF50;"><?php echo $value['code']; ?></span>
            <?php }else{ ?>
               <span style="color: red;"><?php echo $value['code']; ?></span>
            <?php }?>
      <?php }}}else{ ?>
          <?php echo isset($list['promotion_code'])?$list['promotion_code']:''?>
      <?php }?>
    </td>
    <td style="text-align:right"><?php echo isset($list['promotion_price'])&&  $list['promotion_price']!=0?$this->format_currency($list['promotion_price']):'Free'?></td>
    <td align="left"><?php echo isset($list['courses']['title'])?$list['courses']['title']:''?></td>
    <td align="center" ><?php echo isset($list['qty'])?(($list['qty']== 0)?'No Limit':$list['qty']):''?></td> 
    <td align="center" style="font-weight: bold;">
	<?php if($list['usage_qty'] >0)  {?>
    <?php if(isset($list['type']) && $list['type'] == '2'){ ?>
      <a style="cursor:pointer;" onclick="loadmember('<?php echo url::base() ?>admin_promotion/transaction/<?php echo $list['uid']?>/2');$('#dialogmember').dialog('open');">
    <?php }else {?>
      <a style="cursor:pointer;" onclick="loadmember('<?php echo url::base() ?>admin_promotion/transaction/<?php echo $list['promotion_code'] ?>');$('#dialogmember').dialog('open');">
    <?php }?>
    <?php echo isset($list['usage_qty'])?$list['usage_qty']:'0'?>
    </a>
    <?php }else { ?>
	<?php echo isset($list['usage_qty'])?$list['usage_qty']:'0'?>
    <?php } ?>
    </td> 
    <td align="center" >
	<?php echo (isset($list['start_date']) && ($list['start_date'] != 0))?date('m/d/Y',$list['start_date']):''?>  
    <?php echo (isset($list['end_date']) && ($list['end_date'] != 0))? ' ~ '.date('m/d/Y',$list['end_date']):'No Limit'?></td> 
    <td align="center" ><div id="notice<?php echo $list['uid'] ?>"></div>
    <?php if ($this->sess_admin['level'] == 1) { ?>
	   <select style="min-width: 118px;" name="selectstatus<?php echo $list['uid']?>" onchange="setStatus(<?php echo $list['uid'] ?>,this.value);">
    	<?php
      if(isset($list['type']) && $list['type'] == '2'){
        $liststatus = array(
                  'Batch'    => 'Batch Action',
                  'Active'   => 'Active',
                  'Inactive' => 'Inactive',
                  'Expired'  => 'Expired'
                );
      }else{
        $liststatus = array(
                  'Active'   => 'Active',
                  'Inactive' => 'Inactive',
                  'Expired'  => 'Expired'
                );
        }
		  ?>
		<?php	  	
      foreach($liststatus AS $key => $value){
				if(strtolower($key) == strtolower($list['status'])){
					 echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
				}else{
					 echo '<option value="'.$key.'">'.$value.'</option>';
				}
      }
		?>
    </select>
    <?php } else echo $list['status'] ?>
    <td align="center">
          <a href="<?php echo url::base() ?>admin_promotion/edit/<?php echo $list['uid'] ?>" class="ico_edit">
          <?php echo Kohana::lang('global_lang.btn_edit') ?></a>
           <?php if($this->sess_admin['level']==1) {?> 
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php }elseif($this->sess_admin['level']==3 && $list['status']=='Pending') {?>
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php } ?>
    
      </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
<table class="list_bottom" cellspacing="0" cellpadding="5" >
  <tr>
    <td><select name="sel_action" id="sel_action" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>  >
      <option value="Active">Active all selected</option>
      <option value="Inactive">Inactive all selected</option>
      <option value="Expired">Expired all selected</option>
    </select>
      &nbsp;
      <button type="button" class="button save" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>
      onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_promotion/saveall','Do you really change all');">
      <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>      </button>      </td>
  </tr>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_promotion/display">
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

<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 999999;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>
<?php require('list_js.php')?>