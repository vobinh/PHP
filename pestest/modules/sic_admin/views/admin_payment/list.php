<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_payment/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Payment' ; ?></td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr>
    <td colspan="10"><?php echo Kohana::lang('global_lang.lbl_search')?>:
      <select name="sel_test" id="sel_test" style="width: 180px;">
       <option value=""></option>
      <?php foreach($test as $value){?>
        <option
        <?php if(isset($this->search['test']) && ( $this->search['test'] == $value['uid'])){ 
				 echo 'selected="selected"'; }?>  
        value="<?php echo $value['uid']?>" ><?php echo $value['test_title']?>        </option>
      <?php }?>
      <option value="Status" <?php if(isset($this->search['test']) && ( $this->search['test'] =='Status')) { ?> selected="selected" <?php } ?>>Status</option>
      </select>
    
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if(isset($this->search['keyword'])) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr>
<tr class="list_header">
	<!--<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>//-->
  	<td width="10%">No</td>
    <td width="10%">First Name</td>
    <td width="15%">Last Name</td>
    <td width="20%">Test</td>
    <td >Date</td>
    <td width="10%" >Expiration Date</td>  
    <td>Price</td>
    <td width="10%" ><?php echo 'Action' ?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<!--<td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['uid']?>" /></td>//-->
  	<td align="center"><?php echo $list['uid']?></td>
    <td><?php echo (isset($list['member_fname'])?$list['member_fname']:'')?></td>
    <td><?php echo (isset($list['member_lname'])?$list['member_lname']:'')?></td>
    <td><?php echo isset($list['test_title'])?$list['test_title']:''?></td>
    <td align="center"><?php echo isset($list['payment_date'])?date('m/d/Y',$list['payment_date']):''?></td>
    <td align="center" width="10%"><?php echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']).
	(isset($list['date'])?' + '.$list['date'].' day':''))) ?></td> 
    <td align="center" width="10%"><?php echo isset($list['price'])?$this->format_currency($list['price']):''?>
<td align="center">
     
          <?php if($this->sess_admin['level']==1) {?> 
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php }elseif($this->sess_admin['level']==3 ) {?>
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php } ?>      </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_payment/display">
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
<?php require('list_js.php')?>