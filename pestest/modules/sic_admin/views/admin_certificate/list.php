<style type="text/css" media="screen">
  .ui-widget {
    font-family: Arial,Verdana,sans-serif;
    font-size: 14px;
}
</style>
<link rel="stylesheet" href="<?php echo $this->site['base_url']?>themes/popup/jquery-ui.css">

<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_certificate/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label">Certificate</td>
    <td class="title_button">
    <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base()?>admin_certificate/create'"/>
    <span><?php echo 'Add New Certificate'; ?></span>
    </button>
    </td>
</tr>
  
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
  <td>Title</td>
  <td>Certificate of Completion provider name</td>
  <td>course manager</td>
  <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
</tr>
  <?php 
  
  if(!empty($mlist) && $mlist!=false){
  $i=0;	  
  foreach($mlist as $id => $list){ ?>
  <tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['id']?>">
    <td><?php echo $list['title'];?></td>
    <td><?php echo $list['provider_name'];?></td>
    <td><?php echo $list['course_manager'];?></td>
	 <td align="center">
    <a href="<?php echo url::base() ?>admin_certificate/edit/<?php echo $list['id'] ?>" class="ico_edit">
    <?php echo Kohana::lang('global_lang.btn_edit') ?></a>
      
    <a id="delete_<?php echo $list['id']?>" href="javascript:delete_admin(<?php echo $list['id']?>);" 
    class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
   </td>
  </tr>
  <?php $i++;} // end if ?>
  <?php } // end foreach ?>
</table>
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_certificate/display">
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