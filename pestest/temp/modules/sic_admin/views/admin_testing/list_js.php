<script type="text/javascript">
$().ready(function(){
	$('#sel_option').trigger('onchange');
})
function setStatus(id,value){
	$.ajax({
 		url: "<?php echo $this->site['base_url']?>admin_questionnaires/setStatusQuestion/"+ id +'/'+ value,
  		type: "POST",
		beforeSend : function (){
				$('#notice'+ id).html('<div ><img src="<?php echo url::base(); ?>themes/admin/pics/loading.gif" alt="" /> waiting </div>');
            },
		complete: function() {
				$('#notice'+ id).html('');
			},
	
		success: function (data) {
                //alert(data);
		    }
	});
}
function delete_admin(id) {
	$.msgbox('Do you really want to delete?', {
		type : 'confirm',
		buttons : [
			{type: 'submit', value:'Yes'},
			{type: 'submit', value:'Cancel'}
			]
		}, 
		function(re) {
			if(re == 'Yes') {
				$('a#delete_'+id).html('<img src="<?php echo url::base(); ?>themes/admin/pics/icon_loading.gif"/>');
				$.getJSON("<?php echo $this->site['base_url']?>admin_testing/delete/"+ id,
					function(obj) {
						if(obj.status) {
							$('tr#row_'+id).fadeOut('normal', function() {
								$('tr#row_'+id).remove();
							})
						} else {
							if(obj.mgs)
							$.msgbox(obj.mgs, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]});
							$('a#delete_'+id).html('<img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />');
						}
					}
				);
			}
		}
	);
};
function checkSelectOption(val){
	if(val.value == "null"){
		html = '';
		$('#search').html(html);
	}
	
	if(val.value == "testing_code" || val.value == "test_title" || val.value == "member_fname"){
		html = ' <input type="text" id="txt_keyword" name="txt_keyword" value="<?php if($this->search['keyword']) echo $this->search['keyword'] ?>" onclick(this.value="")/>';
		$('#search').html(html);
	}
	
	if(val.value == "testing_date"){
	    
		html = '<input type="text" id="datepicker" name="txt_valdate1"  value="<?php if(isset($this->search['valdate1'])) echo $this->search['valdate1'] ?>"/> To <input id="datepicker2" type="text" name="txt_valdate2"  value="<?php if(isset($this->search['valdate2'])) echo $this->search['valdate2'] ?>"/>';
		$('#search').html(html);
		$( "#datepicker" ).datepicker();
		$( "#datepicker2" ).datepicker();
	}
	
	if(val.value == "testing_score"){
		html = '<input type="text" name="txt_val1"  value="<?php if(isset($this->search['val1'])) echo $this->search['val1'] ?>"/> To <input type="text" name="txt_val2"  value="<?php if(isset($this->search['val2'])) echo $this->search['val2'] ?>"/>';
		$('#search').html(html);
	}
}
</script>