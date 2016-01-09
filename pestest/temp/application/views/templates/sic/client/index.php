<?php require('top.php'); ?>
<div class="index">
    	<div class="header"><?php require('header.php'); ?></div>
        <div class="menu_center"><?php require('menu.php'); ?></div>
         <div class="menu_center">
           
           </div>
            
        <div class="middle">
        	<div class="content">
            	<div class="content_center">
				<?php if (isset($error_msg) || isset($success_msg)) { ?><?php require('error.php')?><?php } // end if ?>
				<?php require('center.php'); ?>
                </div>
            </div>
        </div>
</div>
<?php require('footer.php'); ?>