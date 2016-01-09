<script type="text/javascript">
	function showcategory(id){
		$.ajax({
 			url: "<?php echo $this->site['base_url']?>admin_questionnaires/getCategoryByIdJson/"+ id,
  			type: "POST",
			dataType: "json",
			beforeSend : function (){
				//$('#slt_id_categories').empty('');
        	},
			complete: function() {	
			},
			success: function (data) {
                if(data.length > 0){
					$('#slt_id_categories').empty();
					$('#slt_id_categories').append('<option value="0">All Categorise</option>');
					for (var key in data){	
					  	$('#slt_id_categories').append('<option value='+data[key].uid+'>'+data[key].category+'</option>');
					}
				}else{
					$('#slt_id_categories').empty();
					$('#slt_id_categories').append('<option value="0">All Categorise</option>');
				}
			}
		});
	}

	function delete_attach_file() {
		$.msgbox('Do you really want to delete?', {
			type : 'confirm',
			buttons : [
				{type: 'submit', value:'Yes'},
				{type: 'submit', value:'Cancel'}
				]
			}, 
			function(re) {
				if(re == 'Yes') {
					$('.cls_text_attach').empty();
					$('#txt_file_acttach').val('');
				}
			}
		);
	};


	function add_delete(){
		$('.btn_delete_lesson').click(function(event) {
			$(this).parents('tr').first().remove();
		});
	}
	$('.btn_delete_lesson').click(function(event) {
		$(this).parents('tr').first().remove();
	});

	$('#btn_add_annotation').click(function(event) {
		$('#tb_list_item_annotation').append(
			'<tr>'+
                '<td style="width: 20px;vertical-align: top;">'+
                  '<button class="btn_delete_lesson" type="button">x</button>'+
                '</td>'+
                '<td style="vertical-align: top;">'+
                  '<input style="width:50px;" type="text" name="txt_time[]" value="" placeholder="time">'+
                '</td>'+
                '<td>'+
                  '<textarea style="width: 61%;resize: none;" rows="5" name="txt_text[]" placeholder="text"></textarea>'+
                '</td>'+
             '</tr>'+
             '<script>add_delete()</' + 'script>'
		);

	});
// $("textarea[name='txt_text[]']").keypress(function(event) {
//   if (event.which == 13) {
//     event.preventDefault();
//       var s = $(this).val();
//       $(this).val(s+"<br>");
//   }
// })
function save(value)
{
	var title       = document.getElementById('txt_title').value;
	var video_link  = document.getElementById('txt_video_link').value;		
	
	if( title.length < 3 )
	{
		$.msgbox('<?php echo Kohana::lang("test_frm_lang.val_title")?>', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		document.getElementById('txt_title').focus();
		return false; 
	}
	if(video_link == ''){
		$.msgbox('Embebded link empty!', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		document.getElementById('txt_video_link').focus();
		return false; 
	}
	
	$('input#hd_save_add').val('');
	if(value=='add') 
		$('input#hd_save_add').val(1);
	document.frm.submit();
}

function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

$("#txt_percent_lesson_pass,#txt_percent_test_pass").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
         // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
         // Allow: Ctrl+C
        (e.keyCode == 67 && e.ctrlKey === true) ||
         // Allow: Ctrl+X
        (e.keyCode == 88 && e.ctrlKey === true) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});


function checkSelect(){
		 if($('#sel_type_time').val() == '1' && ($('#txt_time_value').val() == '' || $('#txt_time_value').val() == '0')){
			$('#txt_time_value').focus();
			alert('Please input again. If "Type time" is Down input value > 0');
		 }
}
</script>