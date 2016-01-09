<script language="javascript" src="<?php echo url::base()?>themes/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
function save(type)
{
	document.getElementById('hd_save_add').value= type;
	var question = CKEDITOR.instances.erea_question.getData();
	var description = document.getElementById('erea_description').value;
		
	var note = document.getElementById('erea_note').value;	
	var note_extend = document.getElementById('erea_note_extend').value;
	if( question.length < 1 )
	{
		
		alert('<?php echo Kohana::lang('question_frm_lang.val_erae_question_required')?>');
		return false; 
	}
	
	var chk_answer = $(':checkbox:checked').map(function () {
  		return this.value;
	}).get();
	
	if(chk_answer==''){
		alert('<?php echo Kohana::lang('question_frm_lang.val_chk_question_required')?>');
		return false; 
	}
	
	val = true;
	$('input[name="answer_ans[]"]').each(function () {
  		if(this.value == ''){
			val = false ;
		}
	});
	val2 = true;
	$('input[id="answer_file[]"]').each(function () {
  		if(this.value == ''){
			val2 = false ;
		}else{
			 val2 = true ;
		}
	});
	
	if(!val && !val2){
		alert('<?php echo Kohana::lang('question_frm_lang.val_txt_question_required')?>');
		return false; 
	}

	document.frm.submit();
}


function deleteAnswer(id,elem){
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
				$.getJSON("<?php echo $this->site['base_url']?>admin_questionnaires/deleteAnswer/"+ id,
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
				$( elem ).parent().parent().remove();
			}
		}
	);
}
</script>