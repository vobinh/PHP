<script type="text/javascript">
$('#btn_type_0').click(function(event) {
	/* Act on the event */
	$('#div_list_test').hide();
	$('#div_list_lesson').show();
	$('#div_control').show();
});
$('#btn_type_1').click(function(event) {
	/* Act on the event */
	$('#div_list_test').show();
	$('#div_list_lesson').hide();
	$('#div_control').hide();
});

function show_crop_img() {
    $.ajax({
        type: 'POST',
        url: '<?php echo url::base() ?>admin_courses/list_crop_company',
        success: function (resp) { 
            $('#wrap_crop_company').html(resp);
            $( "#wrap_crop_company" ).dialog({
             draggable: false,
             modal: true,
             width:800,
             height:550,
             resizable: false,
             dialogClass: "no-close",
             autoOpen:true,
             title:"Image Crop"
         });
        }
    });   
}

function fn_add_sponsor() {
    $.ajax({
        type: 'POST',
        url: '<?php echo url::base() ?>admin_courses/frm_crop_sponsor',
        success: function (resp) { 
            $('#wrap_crop_sponsor').html(resp);
            $( "#wrap_crop_sponsor" ).dialog({
             draggable: false,
             modal: true,
             width:300,
             height:156,
             resizable: false,
             dialogClass: "no-close",
             autoOpen:true,
             title:"Image Crop"
         });
        }
    });   
}

function save(value)
{
	var title       = document.getElementById('txt_title').value;
	var description = document.getElementById('txt_description').value;	
	var price       = document.getElementById('txt_price').value;	
	var days_valid  = document.getElementById('txt_days_valid').value;	

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
	
	if($.isNumeric(price) == false || price < 0){
		$.msgbox('Input price is number, value positive', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}
	if($.isNumeric(days_valid) == false || days_valid < 0){
		$.msgbox('Days Valid is integer, value positive', {
			type : 'error',
			buttons : [
				{type: 'submit', value:'Cancel'},
			]
		});
		return false; 
	}
	if($('#btn_type_1').is(':checked')){
		if($('#slt_id_test_pass').val() == 0){
			$.msgbox('Select test', {
				type : 'error',
				buttons : [
					{type: 'submit', value:'Cancel'},
				]
			});
			$('#slt_id_test_pass').focus();
			return false; 
		}
	}
	$('input#hd_save_add').val('');
	if(value=='add') 
		$('input#hd_save_add').val(1);
	if(value=='add_lesson') 
		$('input#hd_save_add').val(2);
	document.frm.submit();
}
function delete_lesson(id){
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
					$.getJSON("<?php echo $this->site['base_url']?>admin_courses/delete_lesson/"+ id,
						function(obj) {
							if(obj.status) {
								$('tr#row_lesson_'+id).fadeOut('normal', function() {
									$('tr#row_lesson_'+id).remove();
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
function numericFilter(txb) {
   txb.value = txb.value.replace(/[^\0-9]/ig, "");
}

$("#txt_days_valid, #txt_day").keydown(function (e) {
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

$("#txt_price").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
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