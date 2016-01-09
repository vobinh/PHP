<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ADMIN PANEL&nbsp;<?php echo isset($this->site['site_name']) ? $this->site['site_name']: ''?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="keywords" content="<?php echo isset($this->site['site_keyword'])?$this->site['site_keyword']:$this->site['site_keyword']?>"/>
<meta name="description" content="<?php echo isset($this->site['site_description'])?$this->site['site_description']:$this->site['site_description']?>"/>
<meta name="author" content="SIC"/>
<meta name="copyright" content="<?php echo @$this->site['site_name']?> [<?php echo @$this->site['site_email']?>]" />
<meta name="robots" content="index, archive, follow, noodp" /> 
<meta name="googlebot" content="index,archive,follow,noodp" /> 
<meta name="msnbot" content="all,index,follow" /> 
<meta name="generator" content="SIC" />
<link rel="shortcut icon" href="<?php echo url::base()?>themes/admin/pics/favicon.ico">
<!-- CSS -->
<link href="<?php echo $this->site['theme_url']?>admin.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['theme_url']:''?>reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['base_url']:''?>themes/ui/ui.css" rel="stylesheet" type="text/css">
<link href="<?php echo isset($this->site)?$this->site['base_url']:''?>themes/paging/paging.css" rel="stylesheet" type="text/css">
<!-- CSS Yui3 -->
<link href="<?php echo $this->site['theme_url']?>cssgrids/cssgrids.css" rel="stylesheet" type="text/css">
<!-- jQuery -->
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.ui/jquery-ui-1.8.17.custom.js"></script>
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.ui/external/jquery.cookie.js"></script>
<link href="<?php echo $this->site['base_url']?>js/jquery.ui/themes/redmond/jquery-ui-1.8.17.custom.css" media="screen" rel="stylesheet" type="text/css" />
<!-- Menu -->
<script type="text/javascript" src="<?php echo url::base()?>js/jsbtab/jsbtab.js"></script>
<link rel="stylesheet" href="<?php echo url::base()?>js/jsbtab/style.css" type="text/css"/>
<!-- Msg -->
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.msgbox/jquery.msgbox.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->site['base_url']?>js/jquery.msgbox/jquery.msgbox.css" />
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.tMessage/tTopMessage.js"></script>
<link type="text/css" href="<?php echo $this->site['base_url']?>js/jquery.tMessage/tTopMessage.css" rel="stylesheet" />
<!-- Noty -->
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.noty/jquery.noty.js"></script>
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.noty/layouts/topCenter.js"></script>
<script type="text/javascript" src="<?php echo $this->site['base_url']?>js/jquery.noty/themes/default.js"></script>
<!-- Float Banner -->
<script type="text/javascript" src="<?php echo url::base()?>js/jquery.float.banner/float.banner.js"></script>
<link rel="stylesheet" href="<?php echo url::base()?>js/jquery.float.banner/float.banner.css" type="text/css"/>
<!-- JS -->
<script language="javascript" src="<?php echo url::base()?>js/collection/form.js"></script>
<!--[if lt IE 7]>
<script src="<?php echo url::base()?>js/fixIE/IE7.js" type="text/javascript"></script>
<![endif]-->
<script>
 $(document).bind("ajaxSend", function(){
   $("#loading").show();
 }).bind("ajaxComplete", function(){
   $("#loading").hide();
 });
</script>
</head>

<body topmargin="0" bottommargin="0" class="contain">
<div id="loading" style="display:none; position: fixed;z-index: 2000; border: 1px solid #ccc;left: 45%;top: -7px;background: #BFBAFD;padding: 10px;border-radius: 12px;">
Loading ....
</div>
<div id="tTopMessage-parent" class="tTopMessage-parent">
    <div id="tTopMessage-child" class="tTopMessage-child"><span>loading</span></div>
</div>