<script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
<script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>
<link rel="stylesheet" href="http://pestest.com/js/jquery/jquery-ui.css">

<script>
$().ready(function(){
	$('.pagination2 .pagination a').click(function(){
		url = $(this).attr('href');
		loadmember(url);
		return false;
	})
})

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
<div id="dialogtestting" title="Testing" >

</div>
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo isset($mr['test_title'])?$mr['test_title']:"" ?></td>
    <td class="title_button">
    </td>
  </tr>
</table>
<table class="list" cellspacing="1" border="0" cellpadding="5">
<tr class="list_header">
    <td align="center">Date</td>
    <td align="center">Expiration Date</td>
    <td align="center">First Name</td>
    <td align="center">Last Name</td>
    <td align="center">Email</td>
    <td align="center">Company Name</td>
    <td align="center">Company Email</td>
     <td width="80" ><?php echo 'Testing' ?></td>  
</tr>

  <?php 
  if(!empty($mlist) && $mlist!=false){
  foreach($mlist as $id => $list){ ?>
 <tr class="tr<?php if($id%2 == 0) echo 0; else echo 1?>" id="row_<?php echo $list['uid']?>">
     <td align="center">
	 <?php echo $this->format_int_date($list['payment_date'],$this->site['site_short_date']) ?></td>
  	<td align="center"><?php echo ($list['daytest'] == 0)?'No limit ':$this->format_int_date(($list['payment_date']+($list['daytest']*24*60*60)),$this->site['site_short_date']) ?></td>
    <td align="center"><?php echo $list['member_fname'] ?></td>
    <td align="center"><?php echo $list['member_lname'] ?></td>
    <td align="center"><?php echo $list['member_email'] ?></td>
    <td align="center"><?php echo $list['company_name'] ?></td>
    <td align="center"><?php echo $list['company_contact_email'] ?></td>
    <td align="center">
     <a style="cursor:pointer; background: #AEAFAE;padding: 3px;border-radius: 4px;text-decoration: none;color: white;border: #807D78 1px solid;"
     onclick="loadtestting('<?php echo url::base() ?>admin_member/testing/<?php echo $list['member_uid'] ?>');$('#dialogtestting').dialog('open');"><?php echo 'View' ?></a>
    </td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>

<div class='pagination2' style="text-align:right"><?php echo isset($this->pagination)?$this->pagination:''?>
</div>
<div  style="text-align:right">
<?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:'0'?>
</div>
