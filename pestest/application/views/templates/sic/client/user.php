<style type="text/css">
  html,body{
    height:100%;
  }
  #wrap{
    min-height: 100%;  
  }
  #main{
    overflow:auto;
    padding-bottom:70px; /* this needs to be bigger than footer height*/
  }
  .footer_user{
    position: relative;
    margin-top: -70px; /* negative value of footer height */
    height: 70px;
    clear:both;
    padding-top:20px;
    color:#fff;
  }
</style>
<?php require('top_user.php'); ?>
<div id="wrap">
  <div id="main" class="container">
    <div class="row">
      <div class="col-md-12 header">
       <?php require('header_user.php'); ?>
      </div>
    </div>

    <div class="row">
      <div class="menu_center">
        <?php require('menu.php'); ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 menu_center"></div>
    </div>
    
    <div class="middle">
      <?php require('center.php'); ?>
    </div>
  </div>
</div>
<?php require('div_bottom.php'); ?>
<?php /*?>
<script>
//var button = $('button[pg6A8bG51B]');
$.getJSON('https://checkout.forte.net/getUTC?callback=?').done(function (utc) {
alert(utc);
//button.attr('utc_time', utc);
});
</script><?php */?>
<?php require('footer_user.php'); ?>