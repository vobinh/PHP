<script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
<script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>
<link rel="stylesheet" href="<?php echo $this->site['base_url']?>themes/popup/jquery-ui.css"><script>
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
<div id="dialogmytest" title="My Test" >

</div>
<div id="dialogtestting" title="Testing" >

</div>

<form id="frm" name="frm" action="<?php echo url::base() ?>admin_member/save" method="post">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo 'Member ' ?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_fname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_fname" id="txt_fname" value="<?php echo isset($mr['member_fname'])?$mr['member_fname']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_lname') ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_lname" id="txt_lname" value="<?php echo isset($mr['member_lname'])?$mr['member_lname']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Email'; ?>:&nbsp;<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5"><input tabindex="3" type="text" name="txt_email" id="txt_email" value="<?php echo isset($mr['member_email'])?$mr['member_email']:''?>" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right">
	<?php if (!isset($mr['uid'])) { ?>
        <?php echo Kohana::lang('account_lang.lbl_pass')?>:&nbsp;<font color="#FF0000">*</font>
    <?php } else { ?>
        <?php echo Kohana::lang('account_lang.lbl_new_pass')?>:
    <?php } ?>
    </div>
    <div class="yui3-u-4-5"><input tabindex="2" name="txt_pass" type="password" id="txt_pass" value="" size="30"></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Company Name' ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_comname" id="txt_comname" value="<?php echo isset($mr['company_name'])?$mr['company_name']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo  'Contact Name' ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_comcontact_name" id="txt_comcontact_name" value="<?php echo isset($mr['company_contact_name'])?$mr['company_contact_name']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Contact Email' ?>:</div>
    <div class="yui3-u-4-5"><input tabindex="3" type="text" name="txt_comcontact_email" id="txt_comcontact_email" value="<?php echo isset($mr['company_contact_email'])?$mr['company_contact_email']:''?>" size="30"></div>
</div>

<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_status_access') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="4" name="sel_status" id="sel_status">
        <option value="1">Active</option>
        <option value="0"  <?php echo (isset($mr['status']) && $mr['status']==0)?'selected="selected"':''?>>Inactive</option>
         
    </select>
    </div>
</div>
</div>
<input name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($mr['uid'])?$mr['uid']:''?>"/>
</td></tr>
</table>
<table  cellspacing="0" cellpadding="0">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>