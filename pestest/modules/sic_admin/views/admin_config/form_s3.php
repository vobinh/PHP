<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'S3_key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_s3_key" name="txt_s3_key" type="text" size="50" value="<?php echo  !empty($s3_config[0]['key'])?$s3_config[0]['key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'S3_secret'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_s3_secret" name="txt_s3_secret" type="text" size="50" value="<?php echo  !empty($s3_config[0]['secret'])?$s3_config[0]['secret']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Main_bucket'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_s3_bucket" name="txt_s3_bucket" type="text" size="50" value="<?php echo  !empty($s3_config[0]['main_bucket'])?$s3_config[0]['main_bucket']:''; ?>"/>
    </div>
</div>