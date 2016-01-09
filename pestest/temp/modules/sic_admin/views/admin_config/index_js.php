<script language="javascript">
$().ready(function() {
	$('#float_table').floatBanner();
});
$(function() {
	$("#tabs").tabs({
		cookie: {
			expires: 1
		}
	});
});
function save(value)
{
	$(document).tTopMessage({load: true, type : 'loading', text : 'loading...', effect : 'slide'});
	document.frm.submit();
	$(document).tTopMessage({load: false, type : 'loading', text : 'loading...', effect : 'slide'});
}
</script>