<script type="text/javascript">
//document.getElementById('attach_image').focus();
function set_type(r1,r2)
{
	var rdo_type = document.getElementsByName('rdo_type');
	rdo_type[r1].checked = true;
	rdo_type[r2].checked = false;	
}
function save(value)
{
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	if(value=='add') $('input#hd_save_add').val(1);
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
</script>