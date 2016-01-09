<script type="text/javascript" src="<?php echo url::base()?>plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
document.getElementById('txt_title').focus();
$(function() {
	$("#tabs").tabs({
		cookie: {
			expires: 1
		}
	});
});
function active_opt_disabled(sel)
{		
	sel.onchange = function() {
		if(this.options[this.selectedIndex].disabled){
			if(this.options.length<=1){
				this.selectedIndex = -1;
			}else if(this.selectedIndex < this.options.length - 1){
				this.selectedIndex++;
			}else{
				this.selectedIndex--;
			}
		}
		if(this.options[this.selectedIndex].disabled){
			this.onchange();
		}
	}	
	
	for(var j=0; j < sel.options.length; j++){ 
		if(sel.options[j].disabled){
			sel.options[j].style.color = '#CCC';
		}
	}   	
}

function sh_content(sel_type)
{		
	var type = sel_type.options[sel_type.selectedIndex].text;
	
	$('#page_content').hide();
	$('#page_form').hide();
	$('#page_menu').hide();
	$('#page_target').hide();
			
	switch (type)
	{
		case 'menu' :
		{	
			$('#page_status').hide();
			$('#page_menu').show();
			$('#page_target').show();
			
			break;
		}
		case 'form' :
		{		
			$('#page_form').show();

			break;
		}
		
		case 'tab' : break;
		<?php if (!(isset($mr['page_type_special']) && $mr['page_type_special'] == 1)) { ?>
		default : $('#page_content').show();
		<?php } // end if page special ?>
	}	
}

active_opt_disabled(document.getElementById('sel_parent'));
sh_content(document.getElementById('sel_type'));
function save(value)
{
	if(document.getElementById('txt_title').value=="")
	{
		alert('<?php echo Kohana::lang('page_validation.txt_title.required')?>');
		document.getElementById('txt_title').focus();
		return false; 
	}
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	if(value=='add') $('input#hd_save_add').val(1);
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
</script>