<?php require('top.php') ?>
<div class="container">
<div class="index">
    <div><?php require('header.php') ?></div>
  	<li><?php require('menu.php') ?></li>
  	<div><?php require('error.php') ?><?php //require('loading.php') ?></div>
    <div class="index_center"><?php echo $content?></div>
    <div class="index_footer"><?php require('footer.php') ?></div>
</div>
</div>
<?php require('bottom.php') ?>