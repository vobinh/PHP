<script type="text/javascript">
$(document).ready(function() {
	$('#frm').validate({
		rules: {
			txt_name: {
				required: true
			},
			txt_email:{
				required: true,
				email: true	
			},
			txt_subject: {
				required: true
			},
			txt_content:
			{
				required: true
			}
	    },
	    messages: {
	    	txt_name: {
	        	required: "<?php echo Kohana::lang('account_lang.validate_name') ?>"
			},
			txt_email:{
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
			},
			txt_subject:{
				required: "<?php echo Kohana::lang('account_lang.validate_subject') ?>"
			},
			txt_content:{
				required: "<?php echo Kohana::lang('account_lang.validate_content') ?>"
			}
		},
		errorPlacement: function(error, element)
		{
			var elem = $(element),
				corners = ['right center', 'left center'],
				flipIt = elem.parents('span.right').length > 0;

			if(!error.is(':empty')) {
				elem.filter(':not(.valid)').qtip({
					overwrite: false,
					content: error,
					position: {
						my: corners[ flipIt ? 0 : 1 ],
						at: corners[ flipIt ? 1 : 0 ],
						viewport: $(window)
					},
					show: {
						event: false,
						ready: true
					},
					hide: false,
					style: {
						classes: 'ui-tooltip-plain'
					}
				})
				.qtip('option', 'content.text', error);
			}

			// If the error is empty, remove the qTip
			else { elem.qtip('destroy'); }
		},
		success: $.noop
	});
});
</script>