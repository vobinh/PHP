<style type="text/css" media="screen">
  .pd_reset{
    padding-right: 0;
    padding-left: 0;
  }
</style>

<div class="col-md-12">
  <div class="row">
    <div class="col-md-12">
      <table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td class="frame_content_top" style="font-size: 24px;padding-bottom: 10px;">
                <a class="text-black" href="<?php echo url::base()?>mypage">My Account</a> 
                <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
                <a class="text-black" href="javascript:void(0)">My certificates</a>
            </td>
        </tr>
      </table>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    <table>
      <tbody>
        <tr>
          <td>
            <h4 style="font-weight: bold;">Below are your certificates from previously completed courses.</h4>
          </td>
        </tr>
        <?php if(!empty($list_certificate)){
            foreach ($list_certificate as $key => $value) {?>
        <tr>
          <td>
            <a href="<?php echo url::base()?>courses/print_certificate/<?php echo !empty($value['id'])?base64_encode($value['id']):'0';?>" title="" target="_blank"><h4 class="text-success" style="margin-top: 5px;margin-bottom: 5px;font-weight: bold;"><?php echo !empty($value['date'])?$this->format_int_date($value['date'],'m/d/Y'):''; ?> - <?php echo !empty($value['item']['title'])?$value['item']['title']:''; ?></h4></a>
          </td>
        </tr>
        <?php }}?>
      </tbody>
    </table>
    </div>
  </div>