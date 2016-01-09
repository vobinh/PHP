<?php
	/*get config s3*/
	$s3_config = $this->db->get('s3_config')->result_array(false); 

	$s3_key    = !empty($s3_config[0]['key'])?$s3_config[0]['key']:'';
	$s3_secret = !empty($s3_config[0]['secret'])?$s3_config[0]['secret']:'';
	$s3_bucket = !empty($s3_config[0]['main_bucket'])?$s3_config[0]['main_bucket']:'';
	/*end get config s3*/

	require_once Kohana::find_file('vendor/aws','aws-autoloader');
	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;

	// Instantiate the S3 client with your AWS credentials
	$s3Client = S3Client::factory(array(
	    'key'    => $s3_key,
	    'secret' => $s3_secret
	   
	));
?>