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

require_once 'AmazonS3Manager.php';

/**
 * List directories
 * @param $dir Directory name. Should be related to the root path.
 * @return array
 */
function listDirs($dir = NULL) {
  global $cfg;
  $root = $cfg['path']['root'];
  $dir = _clean($root .'/'. $dir);
  $bucket = $cfg['as3']['bucket'];
  $rootUrl = $cfg['path']['rootUrl'];

  $as3 = new AmazonS3Manager($cfg['as3']['key'], $cfg['as3']['secret']);
  $dirs = $as3->GetFolders($bucket, $dir);
  $list = array();
  foreach ($dirs as $subdir) {
    $subdir = _clean($subdir);
    if (!in_array($subdir, $cfg['path']['hidden'])) {
      // Crop base path to make folder relative
      $key = str_replace($root .'/', '', $subdir);
      $key = _clean($key);
      $title = _getTitleFromPath(str_replace($dir.'/', '', $subdir));
      $list[] = array(
        'title'   => $title,
        'key'   => urlencode($key),
        'isLazy'  => true,
        'isFolder'  => true
      );
    }
  }

  return $list;
}

/**
 * Create directory
 * @param $path Parent directory path
 * @param $name Directory name
 */
function createDir($path = NULL, $name = NULL) {
  if ($name == NULL) {
    return;
  }

  global $cfg;
  $root = $cfg['path']['root'];
  $path = $root .'/'. $path;
  $dir = _clean($path .'/'. $name);
  $bucket = $cfg['as3']['bucket'];

  $as3 = new AmazonS3Manager($cfg['as3']['key'], $cfg['as3']['secret']);
  $result = $as3->CreateFolder($bucket, $dir, $cfg['as3']['acl']);

  if ($result) {
    return TRUE;
  } else {
    return FALSE;
  }
}

/**
 * Delete directory
 * @param $dir Directory path
 */
function deleteDir($dir = NULL) {
  if (empty($dir)) {
    return FALSE;
  }
  global $cfg;
  $root = $cfg['path']['root'];
  $dir = _clean($root .'/'. $dir);

  if ($dir == $root) {
    return FALSE;
  }

  if (in_array($dir, $cfg['deny']['folder'])) {
    return FALSE;
  }

  $bucket = $cfg['as3']['bucket'];

  $as3 = new AmazonS3Manager($cfg['as3']['key'], $cfg['as3']['secret']);
  $listBucketResult = $as3->ListBucket($bucket, $dir, NULL);

  if (isset($listBucketResult->Contents)) {
    if (is_array($listBucketResult->Contents)) {
      foreach ($listBucketResult->Contents as $item) {
        $item = $item->Key;
        $as3->DeleteFile($bucket, $item);
      }
    } else {
      $item = $listBucketResult->Contents->Key;
      $as3->DeleteFile($bucket, $item);
    }
  }
  return TRUE;
}

function listFiles($dir) {
  global $cfg;
  $root = $cfg['path']['root'];
  $dir = _clean($root .'/'. $dir);
  $bucket = $cfg['as3']['bucket'];
  $rootUrl = $cfg['path']['rootUrl'];

  $as3 = new AmazonS3Manager($cfg['as3']['key'], $cfg['as3']['secret']);
  $files = $as3->GetFiles($bucket, $dir);
  $list = array();
  foreach ($files as $file) {
    if (substr($file['path'], -1) != '/') {
      $pathinfo = pathinfo($file['path']);
      if (in_array(strtolower($pathinfo['extension']),
        array('png', 'jpg', 'jpeg', 'bmp', 'gif'))) {
        $thumb = str_replace($root . '/', '', $file['path']) . '.jpg';
      } else {
        $thumb = '';
      }
      $list[] = array(
        'name'   => $pathinfo['basename'],
        'ext'    => $pathinfo['extension'],
        'size'   => $file['size'],
        'mtime'  => $file['m_time'],
        'thumb'  => $thumb
      );
    }
  }

  return $list;
}

function deleteFiles($dir = NULL, $files = NULL) {
  if ($dir == NULL || $files == NULL || count($files) <= 0) {
    return FALSE;
  }
  global $cfg;
  $root = $cfg['path']['root'];
  $thumbRoot = $cfg['path']['thumb'];
  $bucket = $cfg['as3']['bucket'];
  $as3 = new AmazonS3Manager($cfg['as3']['key'], $cfg['as3']['secret']);

  // Loop through files and delete them
  foreach ($files as $file) {
  	$path = _clean($root .'/'. $dir .'/'. $file);
  	$as3->DeleteFile($bucket, $path);
  	$thumbPath = _clean($thumbRoot .'/'. $dir .'/'. $file . '.jpg');
  	$as3->DeleteFile($bucket, $thumbPath);
  }
}

function _getTitleFromPath($path) {
  $path = _clean($path);
  $title = explode('/', $path);
  $title = $title[0];
  return $title;
}

function _clean($path) {
  global $cfg;

  $path = trim($path);
  $path = trim($path, '/\\');

  if (empty($path)) {
    $path = $cfg['path']['root'];
  } else {
    // Remove double slashes and backslahses and convert all slashes and backslashes to /
    $path = preg_replace('#[/\\\\]+#', '/', $path);
  }

  return $path;
}