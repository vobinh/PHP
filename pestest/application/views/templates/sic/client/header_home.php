<?php 
  require Kohana::find_file('views/aws','init');
?>
<div class="main-nav-top" style="margin-bottom: 10px;">
    <nav class="navbar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
            <?php 
                if (!empty($this->site['site_logo'])) {
                    $check_img = $s3Client->doesObjectExist($s3_bucket, "site/".$this->site['site_logo']);
                    if($check_img == '1'){
            ?>
                <a class="hidden-sm navbar-brand" href="<?php echo url::base()?>" style="padding: 0px; margin-bottom: 10px;">
                    <img style="margin-top: 10px; height: 45px;" class="img-responsive" border="0" src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>">
                </a>
             <?php }} ?>
              <ul class="nav navbar-toggle" style="background-color: rgba(0, 0, 0, 0.5);border: none;float: right;padding: 0px;margin: 0;border-radius: 0;"> 
                <li class="active" style="float: left;">
                    <a href="<?php echo url::base()?>">
                        <i class="glyphicon glyphicon-home"></i>&nbsp;Home
                    </a>
                </li>
                <li id="m_menu" data-toggle="collapse" data-target="#main_menu" aria-expanded="false" style="float: left;" title="Menu">
                    <a href="javascript:void(0)">
                        <i class="m_menu glyphicon glyphicon-menu-down"></i>
                        <i class="m_menu glyphicon glyphicon-menu-up" style="display: none"></i>
                    </a>
                </li>
            </ul>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="main_menu">
              <ul class="nav navbar-nav navbar-right" style="background-color: rgba(0, 0, 0, 0.5);"> 
                <?php if(!empty($this->sess_cus['name'])){?>
                    <li class="active">
                        <a href="<?php echo url::base()?>mypage">
                            <i class="glyphicon glyphicon-user"></i>&nbsp;<?php echo !empty($this->sess_cus['name'])?$this->sess_cus['name']:$this->sess_cus['email']; ?>
                        </a>
                    </li>
                <?php }?>
                <li class="hidden-xs">
                    <a href="<?php echo url::base()?>">
                        <i class="glyphicon glyphicon-home"></i>&nbsp;Home
                    </a>
                </li>
                <li class="">
                    <a href="<?php echo url::base().'article/detail/2';?>">
                        <i class="glyphicon glyphicon-info-sign"></i> <?php echo !empty($this->site['site_name'])?"About ".$this->site['site_name']:"About"; ?>
                    </a>
                </li>
                 <li class="">
                    <a href="<?php echo url::base().'contact';?>">
                        <i class="glyphicon glyphicon-envelope"></i>&nbsp;Contact Us
                    </a>
                </li>
                <?php if(!empty($this->sess_cus['name'])){?>
                 <li class="">
                    <a href="<?php echo url::base()?>login/log_out">
                        <i class="glyphicon glyphicon-log-out"></i>&nbsp;Logout
                    </a>
                </li>
                <?php }?>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
    </nav>
</div>
<?php 
if (!empty($this->site['site_logo'])) {
    $check_logo = $s3Client->doesObjectExist($s3_bucket, "site/".$this->site['site_logo']);
    if($check_logo == '1'){
?>
    <div style="padding-bottom:5px;" class="hidden-xs hidden-md hidden-lg">
        <a href="<?php echo url::base()?>">
            <img border="0" class="img-responsive" src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>">
        </a>
    </div>
<?php }} ?>
<div class="main-nav">
    <nav class="navbar ">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <p class="navbar-brand hidden-sm hidden-md hidden-lg" style="color: #fff;padding-left: 0px;font-weight: bold;margin-bottom: 0px;">
            Menu
          </p>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right"> 
            <li class="active list_menu m_1">
                <a href="<?php echo url::base()?>courses">My Courses</a>
            </li>
            <li class="list_menu m_6">
                <a href="<?php echo url::base()?>courses/certificate">My Certificates</a>
            </li>
            <li class="list_menu m_2">
                <a href="<?php echo url::base()?>mypage/testing">Testing History</a>
            </li>
             <li class="list_menu m_3">
                <a href="<?php echo url::base()?>courses/showlist">Browse Courses</a>
            </li>
             <li class="list_menu m_4">
                <a href="<?php echo url::base()?>mypage">My Account</a>
            </li>
            <?php /*?>
             <li class="list_menu m_5">
                <a href="<?php echo url::base()?>login/log_out">Logout</a>
            </li>
            <?php */?>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
</div>
