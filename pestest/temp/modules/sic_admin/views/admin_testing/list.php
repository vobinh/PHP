<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_testing/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo 'Testing'; ?></td>
</tr>
  
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr>

    <td colspan="10">
	
	<?php echo Kohana::lang('global_lang.lbl_search')?>:
    
    <select id="sel_option" name="sel_option" onChange="checkSelectOption(this);">
        <?php $arrayoption = array('testing_code'=>'Code','test_title'=>'Test','member_fname'=>'Member','testing_date'=>'Date','testing_score'=>'Score');
		foreach($arrayoption as $key=>$value){
			if(isset($this->search['sel_option']) && $this->search['sel_option'] == $key )
				echo '<option value="'.$key.'" selected="selected">'.$value.'</option>';
			else
				echo '<option value="'.$key.'">'.$value.'</option>';
		}
		?>
    </select>
	<span id='search'>
    </span>
    &nbsp;
	<button type="submit" name="btn_search" class="button search"/>
    <span><?php echo Kohana::lang('global_lang.btn_search')?></span>
    </button>    </td>
</tr>
<tr class="list_header">
  	<td><?php echo  'Code' ?></td>
    <td><?php echo  'Date' ?></td>
    <td><?php echo  'Test' ?></td>
    <td><?php echo  'Member' ?></td>
    <td><?php echo  'Score' ?></td>
    <td><?php echo  'Duration' ?></td>
    <td><?php echo  'Action' ?></td>
</tr>
  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>" >
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['testing_code'])?$list['testing_code']:''?></a></td>
     	<td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['testing_date'])?$this->format_int_date($list['testing_date'],$this->site['site_short_date']):''?></a></td>

    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['test_title'])?$list['test_title']:''?></a></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['member_fname'])?$list['member_fname'].' '.$list['member_lname']:''?></a></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['testing_score'])?$list['testing_score']:''?></a></td>
    <td align="center"><a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" style="cursor:pointer"><?php echo isset($list['duration'])?gmdate('H:i:s',$list['duration']):''?></a></td>
    <td align="center">
    <a href="<?php echo $this->site['base_url']?>admin_testing/showlistdetail/<?php echo isset($list['uid'])?$list['uid']:''?>/<?php echo $list['testing_code']?>" class="ico_edit">
          <?php echo Kohana::lang('global_lang.btn_edit') ?></a>
    <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
    </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
</table>
<br clear="all" />
</form>
<div class='pagination' style="clear: both;"><?php echo isset($this->pagination)?$this->pagination:''?><br />
<form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_testing/display">
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