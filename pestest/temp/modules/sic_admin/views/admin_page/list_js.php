<?php if ($mr['view_list'] == 'mptt') { ?>
<link href="<?php echo url::base()?>plugins/jquery/jquery.treeTable.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo url::base()?>plugins/jquery/jquery.treeTable.min.js"></script>
<?php } // end if view page as mptt ?>
<script type="text/javascript">
<?php if ($mr['view_list'] == 'mptt') { ?>
$(document).ready(function()  {
  $(".list").treeTable();
  $("#sel_method_move").change(function(){
	var val = $(this).val();
	if(val == 'copy_layout')
		$("#btn_move span").text('<?php echo Kohana::lang('page_lang.btn_apply') ?>')
	else
		$("#btn_move span").text('<?php echo Kohana::lang('page_lang.btn_move_to') ?>')
  })
});

function check_method()
{
	var sel_method = document.getElementById('sel_method_move');
	var sel_move = document.getElementById('sel_move');
	var frm = document.getElementById('frmlist');
	
	if ($("input:checked[name=chk_id\\[\\]]").length < 1)
	{
		alert('<?php echo Kohana::lang('errormsg_lang.msg_no_check')?>');
	}
	else
	{
		if (sel_method.value == '') alert('<?php echo Kohana::lang('page_lang.msg_no_sel_method')?>');
		else
		{
			if ((sel_method.value == 'next_sibling' || sel_method.value == 'prev_sibling') && sel_move.value == <?php echo ORM::factory('page_mptt')->__get('root')->page_id?>)
			{
				alert("<?php echo Kohana::lang('page_lang.msg_root_sibling')?>");
			}
			else 
			{
				if(sel_method.value == 'copy_layout')
					frm.action = '<?php echo url::base()."admin_layout"?>' + '/copy_to';
				else
					frm.action = '<?php echo url::base().uri::segment(1)?>' + '/move_to';
				frm.submit();
			}
		}
	}
	
	//return false;
}
<?php } else { // end view list as mptt ?>
function save_menu_order()
{ 
	var frm = document.getElementById('frmlist');
	
	frm.action = frm.action + '/save_menu_order';
	frm.submit();
}
<?php } // end if order page as mptt | list ?>

function create_layout(url)
{
	if (confirm('<?php echo Kohana::lang('layout_lang.msg_confirm_create')?>'))
		url += 'global';
	else
		url += 'new';
		
	location.href = url;
}
</script>