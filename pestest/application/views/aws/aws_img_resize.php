<?php 
	function s3_resize($file_name, $max_width, $max_height){
		list($orig_width, $orig_height) = getimagesize($file_name);
	    $width = $orig_width;
	    $height = $orig_height;

	    # taller
	    if ($height > $max_height) {
			$width  = ($max_height / $height) * $width;
			$height = $max_height;
	    }

	    # wider
	    if ($width > $max_width) {
			$height = ($max_width / $width) * $height;
			$width  = $max_width;
	    }

		$data   = file_get_contents($file_name);
		$vImg   = imagecreatefromstring($data);
		$dstImg = imagecreatetruecolor($width, $height);
	    imagecopyresampled($dstImg, $vImg, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

        $jpeg_to_upload = tempnam(sys_get_temp_dir(), $file_name);
		imagejpeg($dstImg, $jpeg_to_upload);
		return $jpeg_to_upload;
	}
?>