<style type="text/css" media="screen">
    .cls_logo_text h1, .cls_logo_text h2, 
    .cls_logo_text h3, .cls_logo_text h4, 
    .cls_logo_text h5,.cls_logo_text h6,
    .cls_logo_text p,.cls_logo_text span{
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .cls_logo_text{
        margin-top: 5px;
        margin-bottom: 5px;
        text-shadow: -1px 0px 0 #FFF, -1px -1px 0 #FFF, 1px -1px 0 #FFF, -1px 1px 0 #FFF, 1px 1px 0 #FFF;
    }
</style>
<?php 
  require_once Kohana::find_file('views/aws','init');
?>
<div class="main-nav-top">
    <nav class="navbar">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <ul class="nav navbar-toggle" style="background-color: rgba(0, 0, 0, 0.5);border: none;float: right;padding: 0px;margin: 0;border-radius: 0;"> 
                <li class="active" style="float: left;">
                    <a href="<?php echo url::base()?>">
                        <i class="glyphicon glyphicon-home"></i>&nbsp;Home
                    </a>
                </li>
                <li id="m_menu" data-toggle="collapse" data-target="#main_menu" aria-expanded="false" style="float: left;" title="Menu">
                    <a href="javascript:void(0)">
                        <i class="m_menu glyphicon glyphicon-menu-down"></i><i class="m_menu glyphicon glyphicon-menu-up" style="display: none"></i>&nbsp;
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" height="130">
    <tr>
        <td class="header_logo text-center" style="padding-top: 50px;">
        <?php
            if (!empty($this->site['site_logo'])) { 
                $check_img = $s3Client->doesObjectExist($s3_bucket, "site/".$this->site['site_logo']);
                if($check_img == '1'){
            ?>
                <a href="<?php echo url::base()?>">
                    <img border="0" style="margin: auto;" class="img-responsive" src="<?php echo linkS3; ?>site/<?php echo $this->site['site_logo']?>">
                </a>
        <?php }} ?>
        </td>
    </tr>
    <tr>
        <td class="cls_logo_text text-center">
            <?php 
                echo !empty($this->site['site_sub_title'])?$this->site['site_sub_title']:'';
            ?>
        </td>
    </tr>
</table>
