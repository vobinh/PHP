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
 * @copyright
 * Includes code from Ajex.FileManager
 * Copyright (C) 2009 Demphest Gorphek http://demphest.ru/ajex-filemanager
 * Dual licensed under the MIT and GPL licenses.
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 *
 * This file is part of Amazon S3 File Manager for TinyMCE & CKEditor.
 */

header('Expires: Sun, 13 Sep 2009 00:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache') ;
//header('Content-Type: text/json; charset=utf-8');


//if (!isset($_SESSION['admin'])) {exit;}			// Do not forget to add your user authorization


define('DS', DIRECTORY_SEPARATOR);
mb_internal_encoding('utf-8');
//date_default_timezone_set('Europe/Moscow');

$cfg = array();

// Amazon S3 account config
$cfg['as3']['bucket'] = 'pestest'; // Set you bucket settings here.
$cfg['as3']['key'] = 'AKIAIJLLSYMQ3WTHB4ZQ'; // Set you access key settings here.
$cfg['as3']['secret'] = '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'; // Set you secret key settings here.

$cfg['as3']['acl'] = 'public-read';

// Path settings
$cfg['path']['root'] = 'editorfiles'; // Base path for uploaded files.
$cfg['path']['rootUrl'] = 'http://'. $cfg['as3']['bucket'] .'.s3.amazonaws.com'.'/'. $cfg['path']['root'];
$cfg['path']['thumb'] = $cfg['path']['root'] .'/'. '_thumb'; // Path to store image thumbnails
$cfg['path']['thumbUrl'] = 'http://'. $cfg['as3']['bucket'] .'.s3.amazonaws.com'.'/'. $cfg['path']['thumb'];
$cfg['path']['hidden'] = array($cfg['path']['thumb']);

$cfg['deny'] = array(
  'folder' => array( // This folders are not allowed to delete
    $cfg['path']['root'] .'/'. 'file',
    $cfg['path']['root'] .'/'. 'flash',
    $cfg['path']['root'] .'/'. 'image',
    $cfg['path']['root'] .'/'. 'media')
);

$reply = array(
	'dirs'		=> array(),
	'files'		=> array()
);

//	------------------

require_once 's3lib.php';

$mode = isset($_GET['mode'])? $_GET['mode'] : 'getDirs';;
$dir = isset($_POST['dir'])? urldecode($_POST['dir']) : '';

switch($mode) {
  case 'cfg':
    $rootDir = listDirs();
    $children = array();
    for ($i=-1, $iCount=count($rootDir); ++$i<$iCount;) {
      $children[] = (object) $rootDir[$i];
    }

    $exp_date = time() + 6000;
    $policy = AmazonS3Manager::construct_policy($cfg['as3']['bucket'],
      $exp_date, $cfg['as3']['acl'], $cfg['path']['root']);

    $reply['config'] = array(
			'lang'		=> 'en',
			'url'		=> $cfg['path']['rootUrl'] . '/',
      'thumbUrl' => $cfg['path']['thumbUrl'] . '/',
      'thumb'	=> $cfg['path']['thumb'],
			'thumbWidth'	=> 100,
			'thumbHeight'	=> 100,
			'children'	=> $children,
      'as3' => array(
        'bucket' => $cfg['as3']['bucket'],
        'accessKey' => $cfg['as3']['key'],
        'acl' => $cfg['as3']['acl'],
        'policy' => $policy,
        'root' => $cfg['path']['root'],
        'thumb' => $cfg['path']['thumb'],
        'signature' => AmazonS3Manager::create_signature($policy, $cfg['as3']['secret'])
      )
    );
    break;

  case 'createFolder':
    $path = urldecode($_POST['dir']);
    $name = urldecode($_POST['newname']);

    $reply['isSuccess'] = createDir($path, $name);
    break;

  case 'deleteFolder':
    $path = urldecode($_POST['dir']);
    $reply['isDelete'] = deleteDir($path);
    break;

  case 'deleteFiles':
    $dir = urldecode($_POST['dir']);
    $files = urldecode($_POST['files']);
    $files = explode('::', $files);
    deleteFiles($dir, $files);
    $reply['files'] = listFiles($dir);
    break;

  case 'getFiles':
    $reply['files'] = listFiles($dir);
    break;

  case 'getDirs':
    $reply['dirs'] = listDirs($dir);
    break;

  default:
    exit;
    break;
}

if (isset($_GET['noJson'])) {echo'<pre>';print_r($reply);echo'</pre>';exit;}
exit( json_encode( $reply ) );