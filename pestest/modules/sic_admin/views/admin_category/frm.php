<form id="frm" name="frm" action="<?php echo url::base() ?>admin_category/save" method="post">
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr>
    <td class="title_label"><?php echo 'Category' ?></td>
    <td class="title_button"><?php require('button.php')?></td>
</tr>
</table>
<table id="float_table" class="title" cellspacing="0" cellpadding="0">
<tr><td>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Test' ?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_test" id="sel_test" style="width: 224px;height: 27px;margin-left: 2px;" onchange="var value = this.value;   
	var text = this.options[this.selectedIndex].text;$('#sel_cate_per').trigger('onblur');" >
    <option value="">-- Please choise --</option>
    <?php foreach($test as $value){?>
    	<?php if(isset($mr['test_uid']) && $value['uid'] == $mr['test_uid']){?>
    	<option value="<?php echo $value['uid']?>" selected="selected"><?php echo $value['test_title']?></option>
        <?php } else {?>
   		<option value="<?php echo $value['uid']?>"><?php echo $value['test_title']?></option>
    <?php }?>
    <?php } ?>
        
    </select>
    </div>
</div>
<div class="yui3-g form">
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Parent' ?>:</div>
    <div class="yui3-u-4-5">
    <select name="sel_parent_name" id="sel_parent_name" style="width: 224px;height: 27px;margin-left: 2px;" onchange="getTest(this);$('#sel_cate_per').trigger('onblur');">
    <?php 			
			{ 				
				$root = ORM::factory('category_orm')->__get('root');							
				$selected = '';
				
				if (isset($mr['uid']))
					$parent = ORM::factory('category_orm',$mr['uid'])->__get('parent');
				else	$selected = 'selected';				
			}
		  ?>          
		  <option value="<?php echo $root->uid;?>" <?php echo $selected;?>>- - - None - - -</option>	<!-- show root categories -->          
		  <?php for($i=0; $i<count($list_category); $i++) { ?>
		  <?php if ($list_category[$i]['left'] != 1) { ?>
          <option value="<?php echo $list_category[$i]['uid']?>"
					<?php if(isset($mr['uid']) && $list_category[$i]['uid'] == $parent->uid) { ?> selected <?php } ?>
                    <?php if(isset($mr['uid'])) $cur_cate = ORM::factory('category_orm',$mr['uid']);?>
                    <?php $sel_cate = ORM::factory('category_orm',$list_category[$i]['uid']);?>
                    <?php if(isset($mr[0]) && ( $list_category[$i]['uid']==$mr['uid'] || $sel_cate->is_descendant($cur_cate))) { ?> disabled <?php } ?>
    	  >    	  
    	  <?php	 				
				$expand = '';
				for ($k=1;$k<$list_category[$i]['level'];$k++)
					$expand .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$expand .= '|----';			
		  ?>
		  <?php echo $expand;?>                  
		  <?php echo $list_category[$i]['category']?>
          </option>         
		  <?php } // end if ?> 
          <?php } // end for?>
        
    </select>
    </div>
</div>



<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Category' ?>:&nbsp;<font color="#FF0000">*</font></div>
    <div class="yui3-u-4-5">
    <input tabindex="1" type="text" name="txt_cate" id="txt_cate" value="<?php echo isset($mr['category'])?$mr['category']:''?>" size="30" />
    <input type="hidden" id="hid_uidcategory" value="<?php echo isset($mr['uid'])?$mr['uid']:''?>" /></div>
    </div>
</div>
<?php /*?><div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'State' ?>:</div>
    <div class="yui3-u-4-5">
    <input tabindex="2" type="text" name="txt_state" id="txt_state" value="<?php echo isset($mr['state'])?$mr['state']:''?>" size="30" />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Industry' ?>: </div>
    <div class="yui3-u-4-5">
    <input tabindex="3" type="text" name="txt_indus" id="txt_indus" value="<?php echo isset($mr['industry'])?$mr['industry']:''?>" size="30"  />
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Branch' ?>: </div>
    <div class="yui3-u-4-5">
    <input tabindex="4" type="text" name="txt_branch" id="txt_branch" value="<?php echo isset($mr['branch'])?$mr['branch']:''?>" size="30"  />
    </div>
    <div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Sub Branch' ?>: </div>
    <div class="yui3-u-4-5">
    <input tabindex="5" type="text" name="txt_subbranch" id="txt_subbranch" value="<?php echo isset($mr['sub_branch'])?$mr['sub_branch']:''?>" size="30"  />
    </div>
</div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Sub Category' ?>: </div>
    <div class="yui3-u-4-5">
    <input tabindex="6" type="text" name="txt_subcate" id="txt_subcate" value="<?php echo isset($mr['sub_category'])?$mr['sub_category']:''?>" size="30"  />
    </div>
</div><?php */?>


<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Percentage' ?>:</div>
    <div class="yui3-u-4-5">
     <input tabindex="7" type="text" name="sel_cate_per" id="sel_cate_per" value="<?php echo isset($mr['category_percentage'])?$mr['category_percentage']:'0'?>" size="10" style="text-align:right;" onkeyup="checkPercent(this)" />%
    <div id="noticepercent" style=""></div></div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo Kohana::lang('account_lang.lbl_status_access') ?>:</div>
    <div class="yui3-u-4-5">
    <select tabindex="8" name="sel_status" id="sel_status">
        <option value="1" <?php echo (isset($mr['status']) && $mr['status']=='1')?'selected="selected"':''?> >Active</option>
        <option value="0"  <?php echo (!isset($mr['status']) || @$mr['status']!='1' )?'selected="selected"':''?>>Inactive</option>
         
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
<script>
$().ready(function(){
						//$(':button[name="btn_save"]').hide();
						//$(':button[name="btn_save_add"]').hide();
						$('#sel_cate_per').trigger('onblur');
						
})
function checkPercent(val){
			$.ajax({
				url:'<?php echo $this->site['base_url']?>admin_category/checkpercent/'+val.value+'/'+$('#sel_test').val()+'/'+$('#hid_uidcategory').val(),
				type: 'POST',
				beforeSend: function() {
					$('#noticepercent').html('Wating...');
					$('#noticepercent').attr('style','position: relative;top: -30px;left: 130px;margin-bottom: -54px;font-size: 10px;height: 50px;width: 224px;background-color: #E1DFFF;padding: 2px;border-radius: 9px;');			
				},
				complete: function() {
					
				},	
				timeout: 20000 ,
				success: function(data) {
					//alert(data);
					//if((parseInt(data) + parseInt(val.value))<=100)
						if(parseInt($('#hid_uidcategory').val()) == ''){
							$('#noticepercent').html('- Please choise parent...');
							return false;	
						}
						if(parseInt($('#sel_test').val())== 0 ){
							$('#noticepercent').html('- Please choise test...');
							return false;	
						}
						
						$('#noticepercent').attr('style','position: relative;top: -30px;left: 130px;margin-bottom: -54px;font-size: 10px;height: 50px;width: 224px;background-color: #E1DFFF;padding: 2px;border-radius: 9px;');	
						$('#noticepercent').hide();
						$('#noticepercent').show('slow');
						$('#noticepercent').html(data);	
						if(data==''){
							$('#noticepercent').html('Good value');
					    	$(':button[name="btn_save"]').show();
							$(':button[name="btn_save_add"]').show();
						}else{
							//$(':button[name="btn_save"]').hide();
							//$(':button[name="btn_save_add"]').hide();
						}
					   
					//else
				//		$('#noticepercent').html(' ERROR...Test Percentage current '+ (parseInt(data) + parseInt(val.value)) +'%');	
				//		$('#noticepercent').attr('style','position: relative;top: -25px;left: 138px;margin-bottom: -25px');	
				},
				error:function() {
					$('.noticepercent').html('<div class="atten" >Err</div>');
				}
			});
}
function getTest(val){
		$.ajax({
				url:'<?php echo $this->site['base_url']?>admin_category/getTestByParent/'+val.value,
				type: 'POST',
				dataType: "json",
				beforeSend: function() {
							
				},
				complete: function() {
					
				},
				success: function(data) {
					$('#sel_test').val(data['uid']);		
				}	
			})	
}
function changeHiden(val){
		$.ajax({
				url:'<?php echo $this->site['base_url']?>admin_category/checkCategory/'+val.value,
				type: 'POST',
				dataType: "json",
				beforeSend: function() {
							
				},
				complete: function() {
					
				},
				success: function(data) {
					alert(data);
				}	
			})	
}

</script>