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
<div id="dialogmember" title="Transaction" ></div>
<script>
$(function() {
	$( "#datepicker" ).datepicker();
    $( "#datepicker1" ).datepicker();
	$( "#datepicker2" ).datepicker();
});
</script>
<form id="frm" name="frm" action="<?php echo url::base() ?>admin_promotion/save" method="post" enctype="multipart/form-data">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php  echo 'Promotion'?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>

<table id="float_table" class="title" cellspacing="0" cellpadding="0" width="100%">
<tr><td>
<div class="yui3-g form" >
     
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Date:</div>
        <div class="yui3-u-4-5" >
           <input style="width:200px" type="text" id="datepicker" name="txt_date" value="<?php echo (isset($pro['date'])&& $pro['date']!=0)?
		date('m/d/Y',$pro['date']):date('m/d/Y',strtotime('now'))?>" style="width:20%"/>
        </div>
    </div>
     
	<div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Test:</div>
        <div class="yui3-u-4-5">
          <select id="sel_test" name="sel_test" style="width:210px;">
            <option value="0"> </option>
            <?php foreach($test as $value){?>
            <?php if(isset($pro['test_uid']) && $pro['test_uid'] == $value['uid']){?>
            <option value="<?php echo $value['uid'] ?>" selected="selected"><?php echo $value['test_title'] ?></option>
            <?php }else{?>
            <option value="<?php echo $value['uid'] ?>"><?php echo $value['test_title'] ?></option>
            <?php }?>
            <?php }?>
          </select>
        </div>
    </div>
    
  	<div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Code <font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" name="txt_code" id="txt_code" value="<?php echo isset($pro['promotion_code'])?$pro['promotion_code']:strtoupper(text::random('alnum',12));?>" style="width:200px"/>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Company <font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" name="txt_company" id="txt_company" value="<?php echo isset($pro['company'])?$pro['company']:''?>" style="width:200px"/>
        </div>
    </div>
    
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Email <font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
        <input type="text" name="txt_email"  id="txt_email" value="<?php echo isset($pro['email'])?$pro['email']:''?>" style="width:200px"/>
        </div>
    </div>
    
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Limit :</div>
        <div class="yui3-u-4-5">
        <input type="text" name="txt_qty" value="<?php echo isset($pro['qty'])?$pro['qty']:''?>" style="width:7%;text-align:right"/>
        </div>
    </div>
    
    
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Date start:</div>
        <div class="yui3-u-4-5">
          <input type="text" id="datepicker1" name="txt_start" value="<?php echo (isset($pro['start_date'])&& $pro['start_date']!=0)?
		date('m/d/Y',$pro['start_date']):''?>" style="width:200px"/>
        </div>
    </div>
    
     <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Date end:</div>
        <div class="yui3-u-4-5">
        <input type="text" id="datepicker2" name="txt_end" value="<?php echo (isset($pro['end_date']) && $pro['end_date']!=0)?date('m/d/Y',$pro['end_date']):''?>" style="width:200px"/>
        </div>        
    </div>
     <div class="yui3-g">
     <div class="yui3-u-1-6 right" style="width:15%">Discount Value </div>
     <div  class="yui3-u-4-5" style="font-size:12px;font-weight:normal">
       <input type="radio" value="1" name="chbdiscount"  onclick="checkoption(1)"   <?php echo (isset($pro['promotion_price'])&& $pro['promotion_price']==0)?'checked="checked"':"" ?> <?php  if(!isset($pro['promotion_price'])) {?>checked="checked" <?php  } ?>/> Free
       <input type="radio" value="2" onclick="checkoption(2)"  name="chbdiscount" <?php echo (isset($pro['promotion_price'])&& $pro['promotion_price']!=0)?'checked="checked"':""?>/>Dollar Value <input type="text" name="txt_promotion_price"  id="txt_promotion_price"  style="text-align:right;width:60px;" value="<?php echo (isset($pro['promotion_price'])&& $pro['promotion_price']!=0)?$pro['promotion_price']:""?>" onkeypress="return numbersonly(this, event)" onkeyup="checkPercent(this)"/> 
        <span id="noticepercent" style=""></span>
       </div>   
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Description:</div>
        <div class="yui3-u-4-5">
        <textarea class="ckeditor" style="height:200px; width:100%" name="erea_description" id="erea_description"> 
			<?php echo isset($pro['description'])?$pro['description']:''?>
        </textarea>
        </div>
    </div>
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%"><?php echo "Status" ?>:</div>
        <div class="yui3-u-4-5">
        <?php  $liststatus = array(
								  'Active'=>'Active',
			  					  'Inactive'=>'Inactive',
								  'Expired'=>'Expired');
		?>
        <select id="sel_status" name="sel_status" >
        
        	<?php foreach($liststatus as $value){?>
           		<?php if(isset($pro['status']) && trim(strtolower($pro['status'])) == trim(strtolower($value))){?>
            			<option value="<?php echo $value ?>" selected="selected"><?php echo $value ?></option> 
                <?php }else{?>
                	    <option value="<?php echo $value ?>"><?php echo $value ?></option> 
                <?php }?>
            <?php }?>    
        </select>
        </div>
    </div>
    </div>
<div style="clear:both"></div>
 
</td>
</tr>
</table>
<input  name="hd_id" type="hidden" id="hd_id" value="<?php echo isset($pro['uid'])?$pro['uid']:''?>"/>
<table  cellspacing="0" cellpadding="0" width="90%" style="display:table; float:left">
<tr>
    <td align="center"><?php require('button.php')?></td>
</tr>
</table>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />

</form>

<?php require('frm_js.php')?>
<script>
checkoption(<?php echo (isset($pro['promotion_price'])&& $pro['promotion_price']!=0)?2:1  ?>);
function checkoption(value)
{
  if(value==1)
  {
	$(':button[name="btn_save"]').show();
	$(':button[name="btn_save_add"]').show();
	$('#txt_promotion_price').val(0);
	$("#txt_promotion_price").hide();
	$('#noticepercent').hide();
  }
  else
   $("#txt_promotion_price").show();
	
}
function numbersonly(myfield, e) {
	var key;
	var keychar;
	
	if (window.event) {
		key = window.event.keyCode;
	} else if (e) {
		key = e.which;
	} else return true;
	
	//GET KEY CHAR
	keychar = String.fromCharCode(key);
	
	if((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27)) {
		return true;
	} else if(((myfield.value).indexOf('.') > -1)) {// KIEM TRA CO DAU "."
		if((("0123456789").indexOf(keychar) > -1)) return true;
		else return false;
	//KIEM TRA PHAI LA SO KHONG
	} else if ((("0123456789.").indexOf(keychar) > -1)) return true; else return false;
}

function checkPercent(val){
			$.ajax({
				url:'<?php echo $this->site['base_url']?>admin_promotion/checkprice/'+val.value+'/'+$('#sel_test').val(),
				type: 'POST',
				beforeSend: function() {
					$('#noticepercent').html('Wating...');
					$('#noticepercent').attr('style','position: relative;margin-bottom: -25px;color:#F00');			
				},
				complete: function() {
					
				},	
				timeout: 20000 ,
				success: function(data) {
						if(parseInt($('#sel_test').val())== 0 ){
							$('#noticepercent').html('- Please choise test...');
							$('#txt_promotion_price').val(0);
							return false;	
						}
						
						$('#noticepercent').attr('style','position: relative;font-size: 10px;height: 50px;width: 224px;background-color: #E1DFFF;padding: 2px;border-radius: 9px;');	
						$('#noticepercent').hide();
						$('#noticepercent').show('slow');
						$('#noticepercent').html(data);	
						$(':button[name="btn_save"]').hide();
						$(':button[name="btn_save_add"]').hide();
						if(data==''){
						$(':button[name="btn_save"]').show();
						$(':button[name="btn_save_add"]').show();	
						}else{
							//$(':button[name="btn_save"]').hide();
							//$(':button[name="btn_save_add"]').hide();
						}
					   
				$('#noticepercent').attr('style','position: relative;margin-bottom: -25px;color:#F00');	
				},
				error:function() {
					$('.noticepercent').html('<div class="atten" >Err</div>');
				}
			});
}
</script>