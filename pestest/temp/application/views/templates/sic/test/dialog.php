<form id="frmcategory" action="<?php  echo url::base() ?>test/testingwrong" method="post">
<input type="hidden" value="<?php echo isset($mr['test_uid'])?$mr['test_uid']:''?>" name="hd_test"/>
<input type="hidden" value="<?php echo isset($mr['testing_code'])?$mr['testing_code']:''?>" name="testing_code"/>
<input type="hidden" value="<?php echo isset($mr['parent_code'])?$mr['parent_code']:''?>" name="parent_id"/>
<?php if(isset($mr['olist'])){?>
<table  style="border: 1px #ccc solid;" width="100%" >
  <?php
	 foreach($mr['olist'] as $key => $value){?>
        <tr  >
                <td  style="border: 1px #ccc solid;" ><input style="width:20px !important" type="checkbox" value="<?php echo $key ?>" name="ocategory[]"/>
                </td>
                <td style="padding: 5px;border: 1px #ccc solid;">
                     <?php echo $value ?>
                </td>
        </tr>
        
	<?php }?>
    <tr>
    		<td ><input style="width:20px !important" type="checkbox"  onclick="$('input:checkbox').attr('checked', this.checked);" /></td>
        	 <td colspan="2" align="center"><span style="float:left;margin-top: 14px;">Check all</span> <button type="submit" style="float: right;" class="button"
            onclick="
            val = 0
            $('input:checkbox').each(function () {
                if (this.checked) {
              	 val = 1; 
          		}
			});
            if(val == 1){
            	 $('#frmcategory').attr('action','<?php echo url::base() ?>test/testingcategory');
            	 $('#frmcategory').submit();
            }
            else {
            	alert('please choise category');
                return false;
            }
           	" name="btn_wrong"><span> Start </span></button> </td>
    </tr>
</table>
<?php }?>
</form>