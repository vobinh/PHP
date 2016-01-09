<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Mailgun_key'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_state" name="txt_mailgun_key" type="text" size="50" value="<?php echo  !empty($mailgun[0]['mailgun_key'])?$mailgun[0]['mailgun_key']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Mailgun_pubkey'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_zipcode" name="txt_mailgun_pubkey" type="text" size="50" value="<?php echo  !empty($mailgun[0]['mailgun_pubkey'])?$mailgun[0]['mailgun_pubkey']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Mailgun_domain'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_state" name="txt_mailgun_domain" type="text" size="50" value="<?php echo  !empty($mailgun[0]['mailgun_domain'])?$mailgun[0]['mailgun_domain']:''; ?>"/>
    </div>
</div>
<div class="yui3-g">
    <div class="yui3-u-1-6 right"><?php echo 'Mailgun_from'; ?>:</div>
    <div class="yui3-u-4-5">
        <input id="txt_zipcode" name="txt_mailgun_from" type="text" size="50" value="<?php echo  !empty($mailgun[0]['mailgun_from'])?$mailgun[0]['mailgun_from']:''; ?>"/>
    </div>
</div>