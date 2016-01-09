<div class="row">
    <div class="col-md-8 col-md-offset-2 box box_shadow">
        <h4 style="font-weight: bold;">Contact Us</h4>
        <hr class="hr">
        <div class="col-sm-12 col-md-8 col-md-offset-2">
            <form class="form-horizontal" name="frm" id="frm" action="<?php echo $this->site['base_url']?>contact/sm" method="post" novalidate="novalidate">
                <div class="form-group">
                    <label for="txt_name" class="col-sm-3 control-label"><?php echo Kohana::lang('account_lang.lbl_name') ?>&nbsp;<font color="#FF0000">*</font></label>
                   <div class="col-sm-9">
                        <input style="width: 65%;" class="form-control input_frm" type="text" name="txt_name" id="txt_name" size="45" value="<?php echo isset($mr['txt_name'])?$mr['txt_name']:''?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_email" class="col-sm-3 control-label"><?php echo Kohana::lang('account_lang.lbl_email') ?>&nbsp;<font color="#FF0000">*</font></label>
                   <div class="col-sm-9">
                        <input style="width: 65%;" class="form-control input_frm" type="text" onkeypress="return  keyPhone(event)" name="txt_email" id="txt_email" size="45" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_phone" class="col-sm-3 control-label"><?php echo Kohana::lang('account_lang.lbl_phone') ?></label>
                   <div class="col-sm-9">
                        <input style="width: 65%;" class="form-control input_frm" type="text" name="txt_phone" id="txt_phone" size="45" value="<?php echo isset($mr['txt_phone'])?$mr['txt_phone']:''?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_subject" class="col-sm-3 control-label"><?php echo Kohana::lang('global_lang.lbl_subject') ?>&nbsp;<font color="#FF0000">*</font></label>
                   <div class="col-sm-9">
                        <input class="form-control input_frm" type="text" name="txt_subject" id="txt_subject" value="<?php echo isset($mr['txt_subject'])?$mr['txt_subject']:''?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="txt_content" class="col-sm-3 control-label">Message&nbsp;<font color="#FF0000">*</font></label>
                   <div class="col-sm-9">
                        <textarea style="resize:none; height:150px;" class="form-control input_frm" id="txt_content" name="txt_content"  rows="20"><?php isset($mr['txt_content'])?$mr['txt_content']:''?></textarea>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                   <div class="col-sm-9 col-sm-offset-3">
                        <button style="padding: 10px 20px;" class="btn btn-success" type="submit" name="submit"><?php echo Kohana::lang('global_lang.btn_send') ?></button>
                        &nbsp;
                        <button style="padding: 10px 15px;" class="btn btn-default" id="reset" name="reset" type="reset"><?php echo Kohana::lang('global_lang.btn_reset') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require('frm_js.php')?>