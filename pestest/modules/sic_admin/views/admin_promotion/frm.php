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
  table.item_code td{
    background: none;
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
    <td class="title_label"><?php  echo 'Promotion'; ?></td>
    <td class="title_button"><?php require('button.php'); ?></td>
</tr>
</table>

<table id="float_table" class="title" cellspacing="0" cellpadding="0" width="100%">
<tr><td>
<div class="yui3-g form" >
     
    <div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Date:</div>
        <div class="yui3-u-4-5" >
            <input style="width:200px" type="text" id="datepicker" name="txt_date" value="<?php echo (isset($pro['date']) && $pro['date']!=0)?
		        date('m/d/Y',$pro['date']):date('m/d/Y',strtotime('now'))?>" style="width:20%"/>
        </div>
    </div>
     
	<div class="yui3-g">
        <div class="yui3-u-1-6 right" style="width:15%">Courses <font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <select id="sel_test" name="sel_test" style="width:210px;">
            <option value="0"> </option>
            <?php foreach($courses as $value){?>
            <?php if(isset($pro['courses_id']) && $pro['courses_id'] == $value['id']){?>
            <option value="<?php echo $value['id'] ?>" selected="selected"><?php echo $value['title'] ?></option>
            <?php }else{?>
            <option value="<?php echo $value['id'] ?>"><?php echo $value['title'] ?></option>
            <?php }?>
            <?php }?>
          </select>
        </div>
    </div>
    
  	<div class="yui3-g">
      <?php if(!empty($m_type) && $m_type =='multiple'){?>
        <?php if(isset($arr_item_code) && !empty($arr_item_code)){ ?>
          <div class="yui3-u-1-6 right" style="width:15%;height: 100%;">
            deactivate /<br>disable code:
          </div>
          <div class="yui3-u-4-5" style="font-size: 12px;">
            <p style="font-size: 13px;"><?php echo count($arr_item_code)?> Code registered</p>
            <table class="item_code" border="0">
              <?php foreach ($arr_item_code as $key => $item_code) {?>
                <tr>
                  <td width="1%">
                    <input type="checkbox" name="chk_status_code_<?php echo $item_code['idpromotion_item']; ?>" <?php if($item_code['status'] == '2'){ echo 'checked'; }?> value="<?php echo $item_code['code']; ?>">
                    <input type="hidden" name="txt_h_id_code[]" value="<?php echo $item_code['idpromotion_item']; ?>">
                  </td>
                  <td width="5%" style="font-weight: bold;"><?php echo $item_code['code']; ?></td>
                  <td style="font-weight: bold;">
                    <?php
                      if($item_code['status'] == '1'){?>
                        <span style="color: #4CAF50;">Active</span>
                      <?php }else{ ?>
                        <span style="color: red;">Deactivated on <?php echo date('m/d/Y', $item_code['date_change']); ?> by (<?php echo $item_code['user_change']; ?>)</span>
                      <?php }?>
                  </td>
                </tr>
              <?php }?>
            </table>
          </div>
        <?php }else{ ?>
          <div class="yui3-u-1-6 right" style="width:15%;height: 100%;">Number of Codes<br>to Generate <font color="#FF0000">*</font>:</div>
          <div class="yui3-u-4-5">
            <input type="text" name="txt_count_code" id="txt_count_code" onkeypress="return numbersonly(this, event)" value="" style="width:200px"/>
            <button class="btn" type="button" style="height: 26px;line-height: 16px;" id="btn_generate">Generate</button>
            <div id="div_code" style="font-size: 13px; padding-top: 5px;">
            </div>
          </div>
        <?php }?>
        <input type="hidden" name="txt_m_type" value="multiple">
      <?php }else{?>
        <div class="yui3-u-1-6 right" style="width:15%">Code <font color="#FF0000">*</font>:</div>
        <div class="yui3-u-4-5">
          <input type="text" name="txt_code" id="txt_code" value="<?php echo isset($pro['promotion_code'])?$pro['promotion_code']:strtoupper(text::random('alnum',12));?>" style="width:200px"/>
        </div>
        <input type="hidden" name="txt_m_type" value="singer">
      <?php }?>
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
        <input type="text" name="txt_qty" id="txt_qty" value="<?php echo isset($pro['qty'])?$pro['qty']:''?>" style="width:7%;text-align:right"/>
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
       <input type="radio" value="1" name="chbdiscount"  onclick="checkoption(1)" <?php echo (isset($pro['promotion_price'])&& $pro['promotion_price']==0)?'checked="checked"':"" ?> <?php  if(!isset($pro['promotion_price'])) {?>checked="checked" <?php  } ?>/> Free
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
    <td align="center"><?php require('button.php');?></td>
</tr>
</table>
<input type="hidden" name="hd_save_add" id="hd_save_add" value="" />

</form>
<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 999999;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>
<?php require('frm_js.php');?>
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