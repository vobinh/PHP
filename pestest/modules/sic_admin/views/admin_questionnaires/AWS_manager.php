<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Amazon S3 File Manager</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<script src="<?php echo url::base()?>themes/client/styleSIC/bootstrap/js/jquery.js"></script>
	<script src="<?php echo url::base()?>js/jquery.form.min.js"></script>
	<script src="<?php echo url::base()?>js/notification/jquery.growl.js"></script>
	<link href='<?php echo url::base()?>js/notification/jquery.growl.css' rel='stylesheet' />
	<link type="text/css" href="<?php echo url::base()?>js/AmazonS3FileManager/skin/dark/dark.css" rel="stylesheet" />
	<style type="text/css">
		.cls_wap{
			position: relative;
			height: 100px;
			width: 200px;
			float: left;
			margin-right: 5px;
			margin-bottom: 5px;
			border: 2px solid #fff;
		}
		.cls_wap:hover .cls_slt{
			display: block;
		}
		.cls_img{
			position: relative;
			height: 100px;
			width: 200px;
		}
		.cls_img img{
			width: 100%;
    		height: 100%;
		}
		.cls_slt{
			height: 100%;
		    position: absolute;
		    bottom: 0px;
		    width: 100%;
		    background-color: rgba(238, 238, 238, 0.7);
		    vertical-align: middle;
		    display: none;
		}
		.btn {
		    display: inline-block;
		    padding: 6px 12px;
		    margin-bottom: 0;
		    font-size: 14px;
		    font-weight: 400;
		    line-height: 1.42857143;
		    text-align: center;
		    white-space: nowrap;
		    vertical-align: middle;
		    -ms-touch-action: manipulation;
		    touch-action: manipulation;
		    cursor: pointer;
		    -webkit-user-select: none;
		    -moz-user-select: none;
		    -ms-user-select: none;
		    user-select: none;
		    background-image: none;
		    border: 1px solid transparent;
		    border-radius: 4px;
		    text-decoration: none;
		}
		.btn-primary {
		    color: #fff;
		    background-color: #337ab7;
		    border-color: #2e6da4;
		    margin-top: 30px;
		}
		.cls_title:hover{
			color: #fff !important;
    		background-color: transparent !important;
		}
	</style>
	<style type="text/css">
	  .cssload-loader{
	    display:block;
	    position:absolute;
	    height:6em;width:6em;
	    left:50%;
	    top:50%;
	    margin-top:-3em;
	    margin-left:-3em;
	    background-color:rgb(51,136,153);
	    border-radius:3.5em 3.5em 3.5em 3.5em;
	      -o-border-radius:3.5em 3.5em 3.5em 3.5em;
	      -ms-border-radius:3.5em 3.5em 3.5em 3.5em;
	      -webkit-border-radius:3.5em 3.5em 3.5em 3.5em;
	      -moz-border-radius:3.5em 3.5em 3.5em 3.5em;
	    box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
	      -o-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
	      -ms-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
	      -webkit-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
	      -moz-box-shadow:inset 0 0 0 0.5em rgb(236,234,224);
	    background: linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
	      background: -o-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
	      background: -ms-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
	      background: -webkit-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
	      background: -moz-linear-gradient(-45deg, rgb(0,153,0), rgb(0,153,0) 50%, rgb(204,204,0) 50%, rgb(204,204,0));
	    background-blend-mode: multiply;
	    border-top:7px solid rgb(0,153,0);
	    border-left:7px solid rgb(0,153,0);
	    border-bottom:7px solid rgb(204,204,0);
	    border-right:7px solid rgb(204,204,0);
	    animation:cssload-roto 1.15s infinite linear;
	      -o-animation:cssload-roto 1.15s infinite linear;
	      -ms-animation:cssload-roto 1.15s infinite linear;
	      -webkit-animation:cssload-roto 1.15s infinite linear;
	      -moz-animation:cssload-roto 1.15s infinite linear;
	  }


	  @keyframes cssload-roto {
	    0%{transform:rotateZ(0deg);}
	    100%{transform:rotateZ(360deg);}
	  }

	  @-o-keyframes cssload-roto {
	    0%{-o-transform:rotateZ(0deg);}
	    100%{-o-transform:rotateZ(360deg);}
	  }

	  @-ms-keyframes cssload-roto {
	    0%{-ms-transform:rotateZ(0deg);}
	    100%{-ms-transform:rotateZ(360deg);}
	  }

	  @-webkit-keyframes cssload-roto {
	    0%{-webkit-transform:rotateZ(0deg);}
	    100%{-webkit-transform:rotateZ(360deg);}
	  }

	  @-moz-keyframes cssload-roto {
	    0%{-moz-transform:rotateZ(0deg);}
	    100%{-moz-transform:rotateZ(360deg);}
	  }

	</style>	
</head>
<body>
	<div id="listPanel" style="width:100%; bottom: 55px;">
		<ul class="Toolbar">
			<li><a style="font-size: 13px;font-weight: bold;" class="selectfile" onclick="fn_refresh()" href="javascript:void(0)">Refresh</a></li>
		</ul>
		<div class="list">
			<div id="fileThumb">
				<?php 
					if(!empty($list_img)){
						foreach ($list_img as $key => $value) { 
							if($value['Size'] >0 ) { ?>
							<div class="cls_wap">
								<div class="cls_img">
									<img src="<?php echo linkS3.$value['Key'] ?>" alt="">
								</div>
								<div class="cls_slt">
									<a onclick="fn_slt('<?php echo linkS3.$value['Key'] ?>')" href="javascript:void(0)" class="btn btn-primary" title="">Select</a>
								</div>
							</div>
				<?php }}} ?>		
			</div>
		</div>
		<div class="clear">
		</div>
	</div>
	<div id="uploaderPanel" style="width:100%; height: 33px;">
		<form style="margin-top: 5px;" action="<?php echo url::base()?>admin_questionnaires/AWS_upload" onSubmit="return false" method="post" enctype="multipart/form-data" id="MyUploadForm">
			<input style="display:none;" name="image_file" id="imageInput" type="file" />
			<ul class="Toolbar">
				<li><a class="addfile" href="javascript:void(0)" onclick="">
					Select Files For Upload</a></li>
				<li><a class="cls_title" href="javascript:void(0)">Image Type allowed: Jpeg, Jpg, Png and Gif. | Maximum Size 1 MB</a></li>
			</ul>
		</form>
	</div>
	<div id="status">
		<div class="l">
		</div>
		<div id="loading">
		</div>
		<div class="r">
		</div>
	</div>
	<div id="dialog" title="">
		<div class="c">
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() { 

		    var progressbox     = $('#progressbox');
		    var progressbar     = $('#progressbar');
		    var statustxt       = $('#statustxt');
		    var completed       = '0%';
		    
		    var options = { 
		            target:   '#output',   // target element(s) to be updated with server response 
		            beforeSubmit:  beforeSubmit,  // pre-submit callback 
		            dataType: 'json',
		            uploadProgress: OnProgress,
		            success:       afterSuccess,  // post-submit callback 
		            resetForm: true        // reset the form after successful submit 
		        }; 
		        
		     $('#MyUploadForm').submit(function() { 
		            $(this).ajaxSubmit(options);            
		            // return false to prevent standard browser submit and page navigation 
		            return false; 
		        });
		    
		//when upload progresses    
		function OnProgress(event, position, total, percentComplete)
		{
		    //Progress bar
		    progressbar.width(percentComplete + '%') //update progressbar percent complete
		    statustxt.html(percentComplete + '%'); //update status text
		    if(percentComplete>50)
		        {
		            statustxt.css('color','#fff'); //change status text to white after 50%
		        }
		}

		//after succesful upload
		function afterSuccess(data)
		{
			console.log(data);
		    progressbox.hide(); //show progressbar
		    if(data['error'] != 'no'){
		    	$.growl.error({ message: data['error'] });
		    }else{
		        fn_refresh();
		    }
		}

		//function to check file size before uploading.
		function beforeSubmit(){
		    //check whether browser fully supports all File API
		   if (window.File && window.FileReader && window.FileList && window.Blob)
		    {

		        if( !$('#imageInput').val()) //check empty input filed
		        {
		            $.growl.error({ message: "Select a file." });
		            //$("#output").html("Are you kidding me?");
		            return false
		        }
		        
		        var fsize = $('#imageInput')[0].files[0].size; //get file size
		        var ftype = $('#imageInput')[0].files[0].type; // get file type
		        
		        //allow only valid image file types 
		        switch(ftype)
		        {
		            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
		                break;
		            default:
		                $.growl.error({ message: "<b>"+ftype+"</b> Unsupported file type!" });
		                return false
		        }
		        
		        //Allowed file size is less than 1 MB (1048576)
		        if(fsize>1048576) 
		        {
		            $.growl.error({ message: "<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor." });
		            //$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
		            return false
		        }
		        
		        //Progress bar
		        // progressbox.show(); //show progressbar
		        // progressbar.width(completed); //initial value 0% of progressbar
		        // statustxt.html(completed); //set status text
		        // statustxt.css('color','#000'); //initial color of status text 
		        $('#fileThumb').html('<div class="cssload-loader"></div>');
		    }
		    else
		    {
		        //Output error to older unsupported browsers that doesn't support HTML5 File API
		        $.growl.error({ message: "Please upgrade your browser, because your current browser lacks some new features we need!" });
		        return false;
		    }
		}

		//function to format bites bit.ly/19yoIPO
		function bytesToSize(bytes) {
		   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		   if (bytes == 0) return '0 Bytes';
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}

		$("#imageInput").change(function(){
		    $('#MyUploadForm').submit();
		});

	}); 

	</script>

	<script type="text/javascript">
		$('.addfile').click(function(event) {
        	$('#imageInput').click();
    	});

		function fn_slt(url){
			window.top.opener['CKEDITOR'].tools.callFunction('<?php echo $CKEditorFuncNum ?>', url);
			window.top.close();
			window.top.opener.focus();
		}
		function fn_refresh(){
			$('#fileThumb').html('<div class="cssload-loader"></div>');
			$.ajax({
				url: '<?php echo url::base()?>admin_questionnaires/AWS_Manager/ajax',
				type: 'GET',
				dataType: 'html',
			})
			.done(function(data) {
				$('#fileThumb').html(data);
			})
			.fail(function() {
				console.log("error");
			});
			
		}
	</script>
</body>
</html>
