<style>
a.ico_print {background-image:url('<?php echo url::base() ?>themes/ui/pics/icon_print.png');}
a.ico_print {background-position: center 0px; background-position-x: 50%; background-position-y: 0px; width: 18px; height: 18px; text-indent: -9999px; display: inline-block;}

</style>
<!-- <link rel="stylesheet" href="<?php //echo $this->site['base_url']?>/js/jquery/jquery-ui.css"> -->

<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_sale/search" method="post">
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label">Sales Report</td>
  </tr>
</table>
<div style="clear:both;"></div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Daily Report</a></li>
    <li><a href="#tabs-2">Monthly Report</a></li>
    <li><a href="#tabs-3">Quarterly Report</a></li>
    <li><a href="#tabs-4">Customize Report</a></li>
  </ul>
 <div id="tabs-1">
      <table>
         <tr>
         	<td width="5%" align="right">Code</td>
            <td width="10%">
            <input type="text" name="sel_code" value="<?php if(isset($this->search['sel_code'])) echo $this->search['sel_code']?>"/>
           </td>
         	<td width="5%" align="right">Date</td>
            <td width="10%"><input type="text" name="txt_date"  class="datepicker" value="<?php echo isset($mr['start_date'])?$this->format_int_date($mr['start_date'],$this->site['site_short_date']):"" ?>"  /></td>
            <td>
                <button type="button" onclick="save(1)" name="btn_search" class="button search"/>
                <span>Daily Report</span>
                </button> 
            </td>
         </tr>
      </table>
 </div> 
 <div id="tabs-2">
       <table>
         <tr>
         	<td width="5%" align="right">Month</td>
            <td width="5%">
            	<select name="sel_month" id="sel_month">
                		<?php for($i=1;$i<13;$i++) {?>
                         <?php if($i<10) $j='0'.$i;
						 else $j=$i; ?>
                         <option value="<?php echo $i ?>" <?php if(isset($mr['sel_month']) && $mr['sel_month']==$i)  {?> selected="selected" <?php }elseif(date('m')==$j) {?>  selected="selected"<?php } ?>><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </td>
            <td width="2%" align="left" nowrap="nowrap">-Year </td>
            <td width="10%">
            <?php if(!isset($mr['sel_year_month'])) $mr['sel_year_month']= date('Y');?>
                <select name="sel_year_month" id="sel_year_month">
                		<?php for($i=2013;$i<=date('Y');$i++) {?>
                         <option value="<?php echo $i ?>" <?php if(isset($mr['sel_year_month']) && $mr['sel_year_month']==$i)  {?> selected="selected" <?php } ?>><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </td>
            <td>
                <button type="button" onclick="save(2)" name="btn_search" class="button search"/>
                <span>Monthly Report</span>
                </button> 
            </td>
         </tr>
      </table>
 </div>
 <div id="tabs-3">
  <table>
         <tr>
         	<td width="5%" align="right">Quarterly</td>
            <td width="5%">
            	<select name="sel_quarterly" id="sel_quarterly">
                		<?php for($i=1;$i<5;$i++) {?>
                         <option value="<?php echo $i ?>" <?php if(isset($mr['sel_quarterly']) && $mr['sel_quarterly']==$i)  {?> selected="selected" <?php }?>><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </td>
            <td width="2%" align="left" nowrap="nowrap">-Year</td>
            <td width="10%">
             <?php if(!isset($mr['sel_quarterly_y'])) $mr['sel_quarterly_y']= date('Y');?>
                <select name="sel_quarterly_y" id="sel_quarterly_y">
                		<?php for($i=2013;$i<=date('Y');$i++) {?>
                         <option value="<?php echo $i ?>"  <?php if(isset($mr['sel_quarterly_y']) && $mr['sel_quarterly_y']==$i)  {?> selected="selected" <?php }?>><?php echo $i ?></option>
                        <?php } ?>
                </select>
            </td>
            <td>
                <button type="button" onclick="save(3)" name="btn_search" class="button search"/>
                <span>Quarterly Report</span>
                </button> 
            </td>
         </tr>
      </table>
 </div>
 <div id="tabs-4">
     <table>
         <tr>
         	<td width="5%" align="right">From</td>
            <td width="10%"><input type="text" name="txt_date_from"  class="datepicker" value="<?php echo isset($mr['date_from'])?$this->format_int_date($mr['date_from'],$this->site['site_short_date']):"" ?>"   /></td>
            <td width="5%" align="right">To</td>
            <td width="10%"><input type="text" name="txt_date_to"  class="datepicker" value="<?php echo isset($mr['date_to'])?$this->format_int_date($mr['date_to'],$this->site['site_short_date']):"" ?>"   /></td>
            <td>
                <button type="button" onclick="save(4)" name="btn_search" class="button search"/>
                <span>Report</span>
                </button> 
            </td>
            
         </tr>
      </table>
 </div>
</div>
<input type="hidden" name="type_action" id="type_action" />
<table class="list" cellspacing="1" border="0" cellpadding="5">
<?php if(isset($mr['type']) && ($mr['type']==2 || $mr['type']==4)) {?>
<?php $total=0; ?>
<tr class="list_header">
  	  <td>Date</td>
     <td>Total</td>
</tr>
<?php  foreach($mlist as $id => $list){ $total+=$list['price'];?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" >
    <td align="center">
	<a href="<?php echo url::base() ?>admin_sale/search/<?php echo isset($list['payment_date'])?$this->format_str_date($list['payment_date'],$this->site['site_short_date']):''?>/1">
	<?php echo isset($list['payment_date'])?$list['payment_date']:''?>
    </a>
    </td>
    <td align="center">
	<a href="<?php echo url::base() ?>admin_sale/search/<?php echo isset($list['payment_date'])?$this->format_str_date($list['payment_date'],$this->site['site_short_date']):''?>">
	<?php echo isset($list['price'])?$this->format_currency($list['price']):''?>
    </a>
    </td>

</tr>
<?php } ?>
<tr >
    <td align="right " style="font-weight:bold;">Sum</td>
    <td align="center" style="font-weight:bold;"><?php echo isset($total)?$this->format_currency($total):''?></td>
</tr>

<?php }else if(isset($mr['type']) && $mr['type']==3) {?>
<tr class="list_header">
  	  <td>Month</td>
     <td>Total</td>
</tr>
<?php $total=0; ?>
<?php  foreach($mlist as $id => $list){ $total+=$list['price']; ?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" >
    <td align="center">
	<a href="<?php echo url::base() ?>admin_sale/search/<?php echo isset($list['month'])?$list['month']:''?>/<?php echo isset($list['year'])?$list['year']:''?>">
	<?php echo isset($list['month'])?$list['month']:''?>
    </a>
    </td>
    <td align="center">
	<a href="<?php echo url::base() ?>admin_sale/search/<?php echo isset($list['month'])?$list['month']:''?>/<?php echo isset($list['year'])?$list['year']:''?>">
	<?php echo isset($list['price'])?$this->format_currency($list['price']):''?>
    </a>
    </td>
</tr>
<?php } ?>
<tr >
    <td align="right" style="font-weight:bold;">Sum</td>
    <td align="center" style="font-weight:bold;"><?php echo isset($total)?$this->format_currency($total):''?></td>
</tr>

<?php }else if(!isset($mr['type']) || (isset($mr['type']) && $mr['type']==1)) {?>
<?php $total=0; ?>
<tr class="list_header">
  	  <td>Date</td>
      <td>Transaction Code </td>
       <td>Promotion Code </td>
    <td width="10%">First Name</td>
    <td width="15%">Last Name</td>
    <td width="20%">Courses</td>
    <td width="10%" >Expiration Date</td>  
    <td>Price</td>
    <td width="10%" ><?php echo 'Action' ?></td>
</tr>
  <?php 
  if(isset($mlist)&& $mlist!=false){
  foreach($mlist as $id => $list){  $total+=$list['price'];?>
<tr class="row<?php if($id%2 == 0) echo 2 ?>" id="row_<?php echo $list['uid']?>">
    <td align="center"><?php echo isset($list['payment_date'])?date('m/d/Y',$list['payment_date']):''?></td>
     <td align="center"><?php echo (isset($list['transaction_code'])?$list['transaction_code']:'')?></td>
    <td align="center"><?php echo (isset($list['promotion_code'])?$list['promotion_code']:'')?></td>
    <td align="center"><?php echo (isset($list['member_fname'])?$list['member_fname']:'')?></td>
    <td align="center"><?php echo (isset($list['member_lname'])?$list['member_lname']:'')?></td>
    <td align="center"><?php echo isset($list['title'])?$list['title']:''?></td>
    <td align="center" width="10%"><?php if($list['day_valid'] >0) {echo date('m/d/Y', strtotime(date('m/d/Y',$list['payment_date']).
	(isset($list['day_valid'])?' + '.$list['day_valid'].' day':''))); }else {echo 'No Limit day';} ?></td> 
    <td align="right" width="10%"><?php echo isset($list['price'])?$this->format_currency($list['price']):''?>
<td align="center">
     
          <?php if($this->sess_admin['level']==1) {?> 
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php }elseif($this->sess_admin['level']==3 ) {?>
          <a id="delete_<?php echo $list['uid']?>" href="javascript:delete_admin(<?php echo $list['uid']?>);" 
          class="ico_delete"><?php echo Kohana::lang('global_lang.btn_delete') ?></a>
          <?php } ?>   
          <a href="<?php echo url::base() ?>admin_invoice/printInvoice/<?php echo $list['uid']?>" target="_blank" class="ico_print">invoice</a>
          </td>
  </tr>
  <?php } // end if ?>
  <?php } // end foreach ?>
  <tr>
    <td align="right" style="font-weight:bold;" colspan="7">Sum</td>
    <td align="right" style="font-weight:bold;"><?php echo isset($total)?$this->format_currency($total):''?></td>
</tr>
  <?php } ?>

</table>
<br clear="all" />
</form>
</div>
<?php require('list_js.php')?>
<script>
 $(function() {
    $( "#tabs" ).tabs({ selected: <?php echo isset($mr['type'])?($mr['type']-1):0 ?> });
  });
  $( ".datepicker" ).datepicker();
  function save(type){
  	document.frmlist.type_action.value= type;
	document.frmlist.submit();
  }
</script>