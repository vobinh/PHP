<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_questionnaires/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('question_list_lang.tt_page') ; ?></td>
    <td class="title_button">
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_questionnaires/create'"/>
    <span><?php echo Kohana::lang('question_list_lang.btn_new_question') ?></span>
    </button>
    </td>
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
        value="<?php echo $value['uid']?>" ><?php echo $value['test_title']?>
        </option>
      <?php }?>
      <option value="Status" <?php if(isset($this->search['test']) && ( $this->search['test'] =='Status')) { ?> selected="selected" <?php } ?>>Status</option>
      </select>
    
    <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>"/>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>
    </td>
</tr>
<tr class="list_header">
	<td width="10"><input type="checkbox" name="checkbox" value="checkbox" onclick='checkedAll(this.checked);' /></td>
  	<td width="7%">No</td>
    <td width="10%"><?php echo 'Test' ?></td>
    <td width="10%"><?php echo 'Category' ?></td>
    <td><?php echo Kohana::lang('question_list_lang.lbl_question') ?></td>
<?php /*?>    <td><?php echo Kohana::lang('question_list_lang.lbl_answer') ?></td>
<?php */?>    <td><?php echo Kohana::lang('question_list_lang.lbl_auth_add') ?></td>
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>  
    <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action')?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
  	<td><input name="chk_id[]" type="checkbox" id="chk_id[]" value="<?php echo $list['uid']?>" /></td>
  	<td align="center"><?php echo $list['uid'] ?></td>
    <td><?php echo isset($list['test']['test_title'])? $list['test']['test_title']:' '?></td>
    <td><?php echo isset($list['category']['category'])? $list['category']['category']:' ' ?></td>
    <td align="left"><?php echo $list['question'] ?></td>
  	<?php /*?><td align="left">
		<?php foreach($list['answer'] as $value){?>
       
        	<?php print_r($value['answer'])?>
            <?php if($value['type']==1){?>
            	<?php echo '<span style="color: red">(true)</span>'?>
            <?php }?>
            <br />
		<?php }?>
    </td><?php */?>
    <td align="center" width="10%"><?php echo (isset($list['author'][0]['fname']))?($list['author'][0]['fname']):''?></td> 
    <td align="center" width="10%"><div id="notice<?php echo $list['uid'] ?>"></div>
    <?php if ($this->sess_admin['level'] == 1) { ?>
	<select name="selectstatus<?php echo $list['uid']?>" onchange="setStatus(<?php echo $list['uid'] ?>,this.value);">
    	<?php  
			  $liststatus = array('pending'=>Kohana::lang('question_frm_lang.lbl_pending'),
			  					  'active'=>Kohana::lang('question_frm_lang.lbl_active'),
								  'deative'=>Kohana::lang('question_frm_lang.lbl_deative'),
								  'obsoleted'=>Kohana::lang('question_frm_lang.lbl_obsoleted'));
								  	
              foreach($liststatus AS $key => $value){
					if($key == strtolower($list['status'])){
						 echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
					}else{
						 echo '<option value="'.$key.'">'.$value.'</option>';
					}
              }
		?>
    </select>
    <?php } else echo $list['status'] ?>
    <td align="center">
          <a href="<?php echo url::base() ?>admin_questionnaires/edit/<?php echo $list['uid'] ?>" class="ico_edit">
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
    <td>
      <select name="sel_action" id="sel_action" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>  >
        <option value="pending"><?php echo Kohana::lang('question_list_lang.lbl_pending').' all selected'?></option>
        <option value="active"><?php echo Kohana::lang('question_list_lang.lbl_active').' all selected'?></option>
        <option value="deative"><?php echo Kohana::lang('question_list_lang.lbl_deative').' all selected'?></option>
        <option value="obsoleted"><?php echo Kohana::lang('question_list_lang.lbl_obsoleted').' all selected'?></option>
      </select>
      &nbsp;
      <button type="button" class="button save" <?php if(!($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2)) echo 'disabled';?>
      onclick="sm_frm(frmlist,'<?php echo $this->site['base_url']?>admin_questionnaires/saveall','Do you really want to delete?');">
      <span><?php echo Kohana::lang('global_lang.btn_update') ?></span>
      </button>
      </td>
  </tr>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_questionnaires/display">
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