<!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Page Not Found</title>
		<!-- Bootstrap CSS -->
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
			body{
				font-family: 'Courgette', cursive;
			}
			body{
				background:#f3f3e1;
			}	
			.wrap{
				margin:0 auto;
			}
			.logo{
				margin-top:50px;
			}	
			.logo h1{
				font-size:200px;
				color:#8F8E8C;
				text-align:center;
				margin-bottom:1px;
				text-shadow:1px 1px 6px #fff;
			}	
			.logo p{
				color:rgb(228, 146, 162);
				font-size:20px;
				margin-top:1px;
				text-align:center;
			}
			.logo img{
				margin: auto;
			}	
			.logo p span{
				color:lightgreen;
			}	
			.sub a{
				color:white;
				background:#8F8E8C;
				text-decoration:none;
				padding:7px 120px;
				font-size:13px;
				font-family: arial, serif;
				font-weight:bold;
				-webkit-border-radius:3em;
				-moz-border-radius:.1em;
				-border-radius:.1em;
			}		
		</style>
	</head>
	<body>
	<?php
		$db = Database::instance();
		$s3_config_main = $db->get('s3_config')->result_array(false);
		$s3_bucket_main = !empty($s3_config_main[0]['main_bucket'])?$s3_config_main[0]['main_bucket']:'';
		$linkS3 = 'https://s3-us-west-2.amazonaws.com/'.$s3_bucket_main.'/'; 
	?>
		<div class="col-xs-12 wrap">
		   <div class="logo">
		   <img border="0" class="img-responsive" src="<?php echo $linkS3 ?>site/1447649646_pestschool_logo_large.png">
		   <h1>404</h1>
		    <p>Error occured! - File not Found</p>
	  	      <div class="sub">
		        <p><a href="">Back</a></p>
		      </div>
	        </div>
		</div>
	</body>
</html>