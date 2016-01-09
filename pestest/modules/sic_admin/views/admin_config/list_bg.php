<div class="yui3-g form">
    <div style="text-align: right;padding-bottom: 5px;">
        <button type="button" id="btn_add_bg"><span style="font-weight: bold;color: greenyellow;">+</span> Add Background</button>
    </div>
    <table class="list" cellspacing="1" border="0" cellpadding="5">
        <tbody>
            <tr class="list_header">
                <td>Image</td>
            </tr>
            <?php 
                if(!empty($bg_img)){
                    foreach ($bg_img as $key => $value) {?>
                    <tr id="row_<?php echo $value['id']?>">
                        <td width="100%" style="position: relative;">
                            <?php if(s3_using == 'on'){?>
                                <img style="opacity: <?php echo !empty($value['opacity'])?$value['opacity']:'1' ?>;" width="100%" src="<?php echo linkS3; ?>bg_img/<?php echo $value['name']; ?>">
                            <?php }else{ ?>
                                <img style="opacity: <?php echo !empty($value['opacity'])?$value['opacity']:'1' ?>;" width="100%" src="<?php echo url::base()?>themes/client/styleSIC/index/pics/<?php echo $value['name'];?>" alt="">
                            <?php }?>
                           
                            <div style="position: absolute;top: 0px;left: 5px;background-color: #fff;">
                                <table>
                                    <tbody>
                                        <tr class="list_header">
                                            <td width="80" ><?php echo Kohana::lang('global_lang.lbl_status') ?></td>
                                            <td width="80" ><?php echo Kohana::lang('global_lang.lbl_action') ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?php if($this->sess_admin['level']== 1 || $this->sess_admin['level']== 2) { ?>
                                                    <a href="<?php echo url::base()?>admin_config/setstatus/<?php echo $value['id']?>"><?php } // end if ?>
                                                <?php if($value['status']== 1){ ?>             
                                                    <img src="<?php echo url::base() ?>themes/admin/pics/icon_active.png" title="<?php echo Kohana::lang('global_lang.lbl_active') ?>">  
                                                <?php } else { ?>    
                                                    <img src="<?php echo url::base() ?>themes/admin/pics/icon_inactive.png" title="<?php echo Kohana::lang('global_lang.lbl_inactive') ?>"> 
                                                <?php } ?>
                                                <?php if ($value['id'] == $this->sess_admin['id']) { echo '</a>'; } // end if ?>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="javascript:void(0)" onclick="fn_edit_bg('<?php echo $value['id'] ?>')" class="ico_edit">
                                                    <?php echo Kohana::lang('global_lang.btn_edit') ?>
                                                </a>
                                                <a id="delete_<?php echo $value['id']?>" href="javascript:fn_delete_bg(<?php echo $value['id']?>);" class="ico_delete">
                                                    <?php echo Kohana::lang('global_lang.btn_delete') ?>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
            <?php }}?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    <?php if(s3_using == 'on'){?>
        var url_bg = '<?php echo linkS3; ?>bg_img/';
    <?php }else{?>
        var url_bg = '<?php echo url::base()?>themes/client/styleSIC/index/pics/';
    <?php }?>
    function fn_edit_bg(id){
        $.ajax({
            url: '<?php echo url::base()?>admin_config/update_bg/'+id,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            $("#div_img").empty().html("<img style='width: 100%;' src='"+url_bg+data['name']+"'>");
            $('#txt_hd_id').val(data['id']);
            $("#div_img").fadeTo(1000, data['opacity']);
            $("#txt_img_opacity").val(data['opacity']);
            $( "#div_add_bg" ).dialog('open');
        })
        .fail(function() {
            
        });
        
    }
    function fn_delete_bg(id){
        $.msgbox('Do you really want to delete?', {
        type : 'confirm',
        buttons : [
            {type: 'submit', value:'Yes'},
            {type: 'submit', value:'Cancel'}
            ]
        }, 
        function(re) {
            if(re == 'Yes') {
                $('a#delete_'+id).html('<img src="<?php echo url::base(); ?>themes/admin/pics/icon_loading.gif"/>');
                $.getJSON("<?php echo $this->site['base_url']?>admin_config/delete/"+ id,
                    function(obj) {
                        if(obj.status) {
                            $('tr#row_'+id).fadeOut('normal', function() {
                                $('tr#row_'+id).remove();
                            })
                        } else {
                            if(obj.mgs)
                            $.msgbox(obj.mgs, {type: "error" , buttons : [{type: 'submit', value:'Yes'}]});
                            $('a#delete_'+id).html('<img src="<?php echo url::base() ?>themes/admin/pics/icon_delete.png" title="<?php echo Kohana::lang('global_lang.btn_delete') ?>" />');
                        }
                    }
                );
            }
        }
    );
    }
$(function() {
    $('#txt_img_opacity').keyup(function(event) {
        $("#div_img").fadeTo(1000, $(this).val());
    });
    $('#btn_save').click(function(event) {
        var id = $('#txt_hd_id').val();
        if(id == ''){
            var ext = $('#uploadFile').val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
                alert('You must select an image file only.');
                return false;
            }else{
              $('#frm_bg').submit();  
            } 
        }else{
            $('#frm_bg').submit();  
        }
    });
    $('#btn_add_bg').click(function(event) {
        $("#div_img").empty();
        $('#uploadFile').val('');
        $('#txt_img_opacity').val('');
        $("#div_img").fadeTo(1000, 1);
        $( "#div_add_bg" ).dialog('open');
    });

    
        $( "#div_add_bg" ).dialog({
          autoOpen: false,
           width:500,
           modal: true,
           position:['middle',20],
          show: {
            effect: "blind",
            duration: 1000
          },
          hide: {
          
            duration: 1000
          }
        });
    

    $("#uploadFile").on("change", function(){
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                $("#div_img").empty().html("<img style='width: 100%;' src='"+this.result+"'>");
            }
        }
    });

    });
</script>