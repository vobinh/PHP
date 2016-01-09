<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php //url::redirect('warning/wrong_pid')?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<style type="text/css">
<?php include Kohana::find_file('views', 'kohana_errors', FALSE, 'css') ?>
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title><?php echo $error ?></title>
</head>
<body>
<div id="framework_error" style="width:42em;margin:20px auto;">
<p><img align="middle" src="<?php echo url::base()?>uploads/site/logo.png" /></p>
<h3><img align="absmiddle" src="<?php echo url::base()?>uploads/site/sorry.png"/>&nbsp;We are sorry about this error !</h3>
<p><?php echo html::specialchars($error) ?>: 
<?php echo html::specialchars($description) ?></p>
<p><i><?php echo $message ?></i></p>
</div>
</body>
</html>