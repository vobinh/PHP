<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $this->site['site_title']?><?php echo !empty($this->site['site_slogan'])?' | '.$this->site['site_slogan']:''?></title>
<meta name="keywords" content="<?php echo $this->site['site_keyword']?>" charset="utf-8" />
<meta name="description" content="<?php echo $this->site['site_description']?>" charset="utf-8" />
<link rel="shortcut icon" href="<?php echo url::base()?>themes/client/styleSIC/index/pics/favicon.ico">

  <link rel="stylesheet" href="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.css" />
  
  <script src="<?php echo $this->site['base_url']?>js/jquery/jquery-1.9.1.js"></script>
  <script src="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.js"></script>
  <!-- <link rel="stylesheet" href="/resources/demos/style.css" /> -->
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
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link href="<?php echo $this->site['theme_url']?>index/index.css" rel="stylesheet" type="text/css">

</head>
<body>
<?php if(snow == 'on'){ ?>
    <div id="snow">
    </div>
<?php }?>
<div id="loading" style=" display:none; position: fixed; z-index: 1000; border: 1px solid #ccc;left: 45%;top: -7px;background: #BFBAFD;padding: 10px;border-radius: 12px;">
Loading ....
</div>
<div class="loading_img" style="display:none; position: fixed;background-color: rgba(204, 204, 204, 0.63);z-index: 3;top: 0px;left: 0px;right: 0px;bottom: 0px;">
  <div class="cssload-loader"></div>
</div>
