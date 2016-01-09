<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->site['site_title']?><?php echo !empty($this->site['site_slogan'])?' | '.$this->site['site_slogan']:''?></title>
<meta name="keywords" content="<?php echo $this->site['site_keyword']?>" charset="utf-8" />
<meta name="description" content="<?php echo $this->site['site_description']?>" charset="utf-8" />
<link rel="shortcut icon" href="http://pestest.com/themes/client/styleSIC/index/pics/favicon.ico">

  <link rel="stylesheet" href="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.css" />
  
  <script src="<?php echo $this->site['base_url']?>js/jquery/jquery-1.9.1.js"></script>
  <script src="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
  <script>
  $(function() {
   	 $( "#tabs" ).tabs();
  });
  </script>
  <script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
  <script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>

<script>
 $(document).bind("ajaxSend", function(){
   $("#loading").show();
 }).bind("ajaxComplete", function(){
   $("#loading").hide();
 });
 function keyPhone(e)
	{
	
	var keyword=null;
		if(window.event)
		{
		keyword=window.event.keyCode;
		}else
		{
			keyword=e.which; //NON IE;
		}
		
		if(keyword==32 )
		{
			return false;
		}
	}
</script>
<link href="<?php echo $this->site['theme_url']?>index/index.css" rel="stylesheet" type="text/css">

</head>
<body>
<div id="loading" style=" display:none; position: fixed; z-index: 1000; border: 1px solid #ccc;left: 45%;top: -7px;background: #BFBAFD;padding: 10px;border-radius: 12px;">
Loading ....
</div>
