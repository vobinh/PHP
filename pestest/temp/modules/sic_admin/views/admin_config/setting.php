<script>
$().ready(function(){
 	$("#sel_test").trigger('onchange');
});
	
</script>
<div class="yui3-g">

    <div class="yui3-u-1-6 right"><?php echo "Test" ?>:</div>
    <div class="yui3-u-4-5">
       
        <select id="sel_test" name="sel_test" style="width:200px" onchange="$('#gridsetting').load('<?php echo $this->site['base_url']?>admin_config/getCateggory/'+this.value)">
        <option value="0">  </option> 
        	<?php foreach($test as $value){?>
           		   	   <option value="<?php echo $value['uid'] ?>"><?php echo $value['test_title'] ?></option> 
            <?php }?>
        </select>
    </div>
    <div id="gridsetting">
    </div>
</div>
