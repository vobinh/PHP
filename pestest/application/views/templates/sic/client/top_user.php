<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $this->site['site_title']?><?php echo !empty($this->site['site_slogan'])?' | '.$this->site['site_slogan']:''?></title>
		<meta name="keywords" content="<?php echo $this->site['site_keyword']?>" charset="utf-8" />
		<meta name="description" content="<?php echo $this->site['site_description']?>" charset="utf-8" />
		<link rel="shortcut icon" href="<?php echo url::base()?>themes/client/styleSIC/index/pics/favicon.ico">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jquery-ui -->
		<link rel="stylesheet" href="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.css" />
  		<script type="text/javascript" src="<?php echo url::base()?>js/jquery/jquery-1.7.1.min.js"></script>
  		<script src="<?php echo $this->site['base_url']?>js/jquery/jquery-ui.js"></script>
  		<script src="<?php echo $this->site['base_url']?>js/notification/jquery.growl.js"></script>
  		<!-- highcharts -->
		<script src="<?php echo $this->site['base_url']?>js/highcharts/highcharts.js"></script>
  		<script src="<?php echo $this->site['base_url']?>js/highcharts/modules/exporting.js"></script>

  		<!-- Bootstrap CSS -->
		<link href="<?php echo $this->site['base_url']?>themes/client/styleSIC/bootstrap/css/bootstrap.css" rel="stylesheet">


		<!-- notification -->
  		<link href='<?php echo url::base() ?>js/notification/jquery.growl.css' rel='stylesheet' />

  		<script type="text/javascript" src="<?php echo url::base()?>js/jquery.validate/jquery.validate.js"></script>
		<script type="text/javascript" src="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.js"></script>
		<link href="<?php echo url::base()?>js/jquery.qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
		<!-- mycss -->
		<link href="<?php echo $this->site['theme_url']?>index/index_main.css" rel="stylesheet" type="text/css">
	</head>
	<?php 
		$this->db->where('status',1);
		$result = $this->db->get('bg_img')->result_array(false);
		$m_opacity = !empty($result[0]["opacity"])?$result[0]["opacity"]:1;
		if(s3_using == 'on'){
            $url_bg = linkS3.'bg_img/'.(!empty($result[0]["name"])?$result[0]["name"]:'');
		}else{
			$url_bg = url::base().'themes/client/styleSIC/index/pics/'.$result[0]["name"];
		}
		
	?>
	<style>
		body::after {
		  content: "";
		  background: #fff url('<?php echo $url_bg?>') no-repeat center center fixed;
		  opacity: <?php echo $m_opacity?>;
		  filter: alpha(opacity=<?php echo ($m_opacity*100)?>);
		  top: 0;
		  left: 0;
		  bottom: 0;
		  right: 0;
		  position: fixed;
		  z-index: -1;
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		}
	</style>
	<?php //die();?>
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
