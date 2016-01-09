<?php
/**
 * Amazon S3 File Manager for TinyMCE & CKEditor
 *
 * @copyright
 * Copyright 2010, Aurigma
 * Dual licensed under the MIT and GPL licenses.
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 *
 * This file is part of Amazon S3 File Manager for TinyMCE & CKEditor.
 */

class AmazonS3Manager {

  static $_wsdl = '';
  static $_service = NULL;

  private $_accessKey;
  private $_secretKey;

  function __construct($accessKey = NULL, $secretKey = NULL) {
    $this->_accessKey=$accessKey;
    $this->_secretKey=$secretKey;

    if (!self::$_service) {
      self::$_service = new SoapClient('http://s3.amazonaws.com/doc/2006-03-01/AmazonS3.wsdl');
    }
  }

  function DeleteFile($bucket, $fullPath, $accessKey = NULL, $secretKey = NULL) {
    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }

    $timestamp = time();
    // Create policy
    $policy = "AmazonS3" . "DeleteObject". gmdate("Y-m-d\TH:i:s.000\Z", $timestamp);;
    // Create signature
    $signature = AmazonS3Manager::create_signature($policy, $secretKey);

    // Call SOAP service and return result
    try {

      $result = self::$_service->DeleteObject(array(
        'Bucket' => $bucket,
        'Key' => $fullPath,
        'AWSAccessKeyId' => $accessKey,
        'Timestamp' => $timestamp,
        'Signature' => $signature
      ));

      return $result;

    } catch (SoapFault $fault) {
      trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    }
  }

  function IsEmptyFolder($bucket, $fullPath, $accessKey = NULL, $secretKey = NULL) {
    $listBucketResult = $this->ListBucket($bucket, $fullPath, '', $accessKey, $secretKey);
    if (isset($listBucketResult->Contents)) {
      if (is_array($listBucketResult->Contents) || $listBucketResult->Contents->Key != $fullPath.'index.html') {
        $result = FALSE;
      } else {
        $result = TRUE;
      }
    } else {
      $result = TRUE;
    }
    return $result;
  }

  function DeleteFolder($bucket, $fullPath, $accessKey = NULL, $secretKey = NULL) {
    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }

    // Remove folder
    $timestamp = time();
    // Create policy
    $policy = "AmazonS3" . "DeleteObject". gmdate("Y-m-d\TH:i:s.000\Z", $timestamp);;
    // Create signature
    $signature = AmazonS3Manager::create_signature($policy, $secretKey);

    // Call SOAP service and return result
    try {

      $result = self::$_service->DeleteObject(array(
        'Bucket' => $bucket,
        'Key' => $fullPath.'index.html',
        'AWSAccessKeyId' => $accessKey,
        'Timestamp' => $timestamp,
        'Signature' => $signature
      ));

      return $result;

    } catch (SoapFault $fault) {
      trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    }
  }

  function GetFiles($bucket, $base, $accessKey = NULL, $secretKey = NULL) {
    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }

    if ($base[strlen($base) - 1] !== '/') {
      $base .= '/';
    }

    if (empty($accessKey) || empty($secretKey) || empty($base)) {
      return array();
    }

    $files = array();
    $listBucketResult = $this->ListBucket($bucket, $base, '/');

    if (isset($listBucketResult->Contents)) {
      if (!is_array($listBucketResult->Contents)) {
        $listBucketResult->Contents = array($listBucketResult->Contents);
      }
      foreach ($listBucketResult->Contents as $item) {
        $path = $item->Key;
        // It is a folder if it ends with slash
        if (substr($path, -1) !== '/') {
          $files[] = array(
            'path' => $path,
            'size' => $item->Size,
            'm_time' => strtotime($item->LastModified)
          );
          $item;
        }
      }
    }

    sort($files);
    return $files;
  }

  function  GetFolders($bucket, $base, $accessKey = NULL, $secretKey = NULL) {
    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }

    if ($base[strlen($base) - 1] !== '/') {
      $base .= '/';
    }

    if (empty($accessKey) || empty($secretKey) || empty($base)) {
      return array();
    }

    $folders = array();

    $listBucketResult = $this->ListBucket($bucket, $base, '/');

    if (isset($listBucketResult->CommonPrefixes)) {
      if (is_array($listBucketResult->CommonPrefixes)) {
        foreach ($listBucketResult->CommonPrefixes as $item) {
          $folders[] = $item->Prefix;
        }
      } else {
        $folders[] = $listBucketResult->CommonPrefixes->Prefix;
      }
    }

    sort($folders);

    return $folders;
  }

  function CreateFolder($bucket, $path, $acl = NULL, $accessKey = NULL, $secretKey = NULL ) {
    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }
    if (empty($acl)) {
      $acl = 'public-read';
    }

    $key = $path .'/';

    // Put index.html with the key, that contains folder name
    $data = '';
    $content_length = strlen($data);
    $data = base64_encode($data);

    $timestamp = time();
    // Create policy
    $policy = "AmazonS3" . "PutObjectInline". gmdate("Y-m-d\TH:i:s.000\Z", $timestamp);;
    // Create signature
    $signature = AmazonS3Manager::create_signature($policy, $secretKey);

    // Call SOAP service and return result
    try {

      $result = self::$_service->PutObjectInline(array(
        'Bucket' => $bucket,
        'Key' => $key,
        'Data' => $data,
        'ContentLength' => $content_length,
        'AWSAccessKeyId' => $accessKey,
        'Timestamp' => $timestamp,
        'Signature' => $signature
      ));

      return $result;

    } catch (SoapFault $fault) {
      trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    }
  }

  function ListBucket($bucket, $path, $delimiter = '/', $accessKey = NULL, $secretKey = NULL) {

    if (empty($accessKey)) {
      $accessKey = $this->_accessKey;
    }
    if (empty($secretKey)) {
      $secretKey = $this->_secretKey;
    }

    $timestamp = time();

    // Create policy
    $policy = "AmazonS3" . "ListBucket". gmdate("Y-m-d\TH:i:s.000\Z", $timestamp);;
    // Create signature
    $signature = AmazonS3Manager::create_signature($policy, $secretKey);

    // Call SOAP service and return result
    try {

      $result = self::$_service->ListBucket(array('Bucket' => $bucket, 'Prefix' => $path, 'Delimiter' => $delimiter,
        'AWSAccessKeyId' => $accessKey, 'Timestamp' => $timestamp, 'Signature' => $signature));

      return $result->ListBucketResponse;

    } catch (SoapFault $fault) {
      trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
    }
  }

  static function construct_policy($bucket, $expiration_date, $acl, $key) {
    // See about policy construction
    // http://docs.amazonwebservices.com/AmazonS3/2006-03-01/dev/HTTPPOSTForms.html#HTTPPOSTConstructPolicy
    $expiration_date = gmdate("Y-m-d\TH:i:s.000\Z", $expiration_date);
    $policy = "{ 'expiration': '$expiration_date',
                'conditions': [
                  { 'acl': '$acl' },
                  { 'bucket': '$bucket' },
                  { 'success_action_status': '200' },
                  [ 'starts-with', '$"."key', '' ]
                ]
              }";

    // Encode policy UTF-8 bytes using Base64 and return.
    return base64_encode($policy);
  }

  // read more at http://en.wikipedia.org/wiki/HMAC
  static function create_signature($policy, $secretAccessKey) {
    // helper function binsha1 for amazon_hmac (returns binary value of sha1 hash)
    if (!function_exists('binsha1'))
    {
      if (version_compare(phpversion(), "5.0.0", ">=")) {
        function binsha1($d) { return sha1($d, true); }
      } else {
        function binsha1($d) { return pack('H*', sha1($d)); }
      }
    }

    if (strlen($secretAccessKey) == 40)
    $secretAccessKey = $secretAccessKey.str_repeat(chr(0), 24);

    $ipad = str_repeat(chr(0x36), 64);
    $opad = str_repeat(chr(0x5c), 64);

    $hmac = binsha1(($secretAccessKey^$opad).binsha1(($secretAccessKey^$ipad).$policy));
    return base64_encode($hmac);
  }
}