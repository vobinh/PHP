<?php if (!(isset($this->sess_cus['username']))) { ?> 
<script type="text/javascript" src="<?php echo url::base()?>js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery.validate/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.js"></script>
<link href="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<?php  }?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="130">
          <tr>
            <td class="header_logo" rowspan="2" align="left">
            <?php if (!empty($this->site['site_logo'])) { ?>
            <a href="<?php echo url::base()?>">
                <img border="0" src="<?php echo url::base()?>uploads/site/<?php echo $this->site['site_logo']?>">
            </a>
            <?php } ?>
            </td>
            <td class="header_menu_top" align="right" style="padding-top:0; padding-right:0" valign="top">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="menu_top">
                  <tr>
                    <td class="menu_top_L"><?php echo $this->sess_cus['name']?$this->sess_cus['name']:$this->sess_cus['email']; ?></td>
                    <td class="menu_top_Ce">
                        <a href="<?php echo url::base();?>">Home</a>
                    	<a href="<?php echo url::base().'article/detail/2';?>">How Pestest Works</a>
                        <?php /*?><a href="<?php echo url::base().'article/detail/3';?>">Pricing</a><?php */?>
                         <?php /*?><a href="<?php echo url::base().'article/detail/5';?>">Support</a><?php */?>
                        <a href="<?php echo url::base().'contact';?>">Contact Us</a>
 
                    </td>
                  </tr>
                </table>
            </td>
          </tr>
          <tr>
            <td align="right" style="padding:5px 0;" valign="top">
            <?php if (!(isset($this->sess_cus['username']))) { ?> 
            	<div class="frm_login">
                   <form class="sky-form" name="frm" id="frm" action="<?php echo $this->site['base_url']?>login/check_login" method="post"  novalidate="novalidate" style="margin-top:0">
                    	<fieldset>
                            <section>
                                <div class="row" style="float:right; width:95%">
                                    <div class="col col-6" style="width:32%; padding-right:0;padding-left:0">
                                        <label class="input">
                                        <input type="email" tabindex="1" name="txt_email" id="txt_email" onkeypress="return  keyPhone(event)" placeholder="Your Email Address">
                                        </label>
                                       <?php /*?> <label class="checkbox">
                                        <input type="checkbox" checked="" name="checkbox-inline">
                                        <i></i>
                                        Keep me logged in
                                        </label><?php */?>
                                    </div>
                                    <div class="col col-6" style="width:32%; padding-right:0">
                                        <label class="input">
                                        <input tabindex="2"  id="txt_password" type="password" placeholder="Password" name="txt_password">
                                        </label>
                                        <div class="note">
                                        <a href="<?php echo $this->site['base_url']?>forgotpass">Forgot password?</a>
                                        </div>
                                    </div>
                                    <div style=""><button onclick="javascript:location.href='<?php echo $this->site['base_url']?>register'" tabindex="3" style="margin-top:0" class="button" type="button">Register</button></div>
                                    <div style="width:auto; float:right"><button tabindex="3" style="margin-top:0" class="button" type="submit">&nbsp;Login&nbsp;</button></div>
                                    
                                </div>
                            </section>
                            </fieldset>
                    </form>
                </div>
             <?php }else { ?>
                <div style="float:right;font-size:20px;padding-top:55px;">
					<?php if (!empty($this->sess_cus['username'])) { ?> 
                      <a href="<?php echo url::base()?>">Home</a>
                    <?php 
					$this->payment_model = new Payment_Model();
					$arraypayment = array();
					$this->db->where('member_uid',$this->sess_cus['id']);
					$payment = $this->payment_model->get();
					?> 
                    <?php if(!empty($payment)) {?>
                    | <a href="<?php echo url::base()?>test/mytest">My Tests</a> 
                    <?php }?>
                    | <a href="<?php echo url::base()?>mypage/testing">Testing History</a> 
                    | <a href="<?php echo url::base()?>test/showlist">Purchase New Test</a> 
                    | <a href="<?php echo url::base()?>mypage">My Account</a>  
                    | <a href="<?php echo url::base()?>login/log_out">Logout</a>  
                    <?php } ?>
                </div>
             <?php } ?>
            </td>
          </tr>
        </table>
<?php if (!(isset($this->sess_cus['username']))) { ?> 
<script type="text/javascript">
    
	$(document).ready(function() {
	
	$('#frm').validate({
		rules: {
			txt_password: {
				required: true
			},
			txt_email:{
				required: true,
				email: true	
			},
			
	    },
	    messages: {
	    	txt_password: {
	        	required: "Password is required"
			},
			txt_email:{
				required: "<?php echo Kohana::lang('account_lang.validate_email') ?>",
				email: "<?php echo Kohana::lang('account_lang.validate_email_valid')?>"
			},
			
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
  <?php } ?>