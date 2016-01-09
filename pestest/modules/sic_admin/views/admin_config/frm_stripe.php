<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Test Secret Key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_test_secret_key" name="txt_test_secret_key" type="text" size="50" value="<?php echo  !empty($stripe_config[0]['test_secret_key'])?$stripe_config[0]['test_secret_key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Test Publishable Key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_test_publishable_key" name="txt_test_publishable_key" type="text" size="50" value="<?php echo  !empty($stripe_config[0]['test_publishable_key'])?$stripe_config[0]['test_publishable_key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Live Secret Key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_live_secret_key" name="txt_live_secret_key" type="text" size="50" value="<?php echo  !empty($stripe_config[0]['live_secret_key'])?$stripe_config[0]['live_secret_key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Live Publishable Key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_live_publishable_key" name="txt_live_publishable_key" type="text" size="50" value="<?php echo  !empty($stripe_config[0]['live_publishable_key'])?$stripe_config[0]['live_publishable_key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Type'; ?>:</div>
    <div class="yui3-u-4-5">
        <select name="slt_stripe">
            <option value="0">Payment Test</option>
            <option <?php if(isset($stripe_config[0]['type']) && $stripe_config[0]['type'] == 1){?>selected<?php }?> value="1">Payment Live</option>
        </select>
    </div>
</div>