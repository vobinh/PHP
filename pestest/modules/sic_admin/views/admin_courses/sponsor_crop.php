<style>
  .imageBox_sponsor
{
    position: relative;
    height: 60px;
    width: 265px;
    border:1px solid #aaa;
    background: #fff;
    overflow: hidden;
    background-repeat: no-repeat;
    cursor:move;
    margin: auto;
}

.imageBox_sponsor .thumbBox_sponsor
{

  width: 120px;
  height: 40px;
  margin: auto;
  margin-top: 10px;
  border: 1px solid rgb(102, 102, 102);
  box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
  background: none repeat scroll 0% 0% transparent;
}

.imageBox_sponsor .spinner_sponsor
{
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    text-align: center;
    line-height: 400px;
    background: rgba(0,0,0,0.7);
}
.action_sponsor
{
  width: 100%;
  height: 30px;
  padding-top: 5px;
  position: relative;
}

.zom_img_sponsor {
  width: 30px;
  opacity: 0.5;
  filter: alpha(opacity=50);
  cursor: pointer;
  margin-right: 24px;
}
.zom_img_sponsor:hover {
  opacity: 1.0;
  filter: alpha(opacity=100);
}
#file{
    border: 2px solid #1A4A99;
    background-color: #afc4db;
}

.custom-file-upload-hidden_sponsor {
    display: none;
    visibility: hidden;
    position: absolute;
    left: -9999px;
}

.file-upload-wrapper_sponsor {
    position: relative; 
    float: left;
}
.file-upload-input_sponsor {
    border: none;
    border-radius: 0px !important;
    height: 24px !important;
    float: none !important;
    width: 80px;
    display: none;
    border-radius: 0px !important;
}
.file-upload-button_sponsor {
    cursor: pointer; 
    display: inline-block; 
    text-transform: uppercase;
    border: none;
    background-color: #000;
    float: left; /* IE 9 Fix */
    border-right:none !important; 
    border-radius: 0px !important;
}


</style>
<script>
function close_form_crop_sponsor()
   {
    $( "#wrap_crop_sponsor" ).dialog( "destroy" );
  }
</script>
<div  class="container">
    <div class="imageBox_sponsor">
        <div class="thumbBox_sponsor"></div>
        <div class="spinner_sponsor" style="display: none">Loading...</div>
        <div style="position: absolute;right: 0px;bottom: 0px;">
          <img class="zom_img_sponsor" style="float: right" src="<?php echo url::base() ?>themes/icon/zoom_in.png" id="btnZoomIn_sponsor" alt=""> <br>
          <img class="zom_img_sponsor" style="float: right" src="<?php echo url::base() ?>themes/icon/zoom_out.png" id="btnZoomOut_sponsor" alt="">
        </div>
    </div>  
</div>
<div class="action_sponsor">
  <input style="width:100%" type="file" id="file_sponsor" name="file_sponsor" class="jfilestyle" data-input="false">
  <button class="btn" id="btnCrop_sponsor" value="Crop" style="float: right;">Crop</button>
</div>
        
<script type="text/javascript">
$(function(){
     var options =
        {
            imageBox: '.imageBox_sponsor',
            thumbBox: '.thumbBox_sponsor',
            spinner: '.spinner_sponsor',
            imgSrc: 'company.png'
        }
        var cropper;
        document.querySelector('#file_sponsor').addEventListener('change', function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = new cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        })

        document.querySelector('#btnCrop_sponsor').addEventListener('click', function(){
            $('.loading_img').show();
            var img = cropper.getDataURL();
            document.querySelector('#div_sponsor_img').innerHTML='';
            document.querySelector('#div_sponsor_img').innerHTML += '<img height="40px" width="120px" src="'+img+'"><a title="Delete" style="line-height: 79px;position: absolute;right: 0;top: 0;" href="javascript:0;" class="ico_delete delete_sponsor_img">Delete</a>';
            $.ajax({
                type: 'POST',
                url: '<?php echo url::base() ?>admin_courses/save_img/sponsor_icon',
                data: {image: img},
                dataType: 'json',
                success: function (resp) { 
                  $('#txt_sponsor_img').val(resp); 
                  $('.loading_img').hide();
                }
            });
           close_form_crop_sponsor();
        })
        document.querySelector('#btnZoomIn_sponsor').addEventListener('click', function(){
            cropper.zoomIn();
        })
        document.querySelector('#btnZoomOut_sponsor').addEventListener('click', function(){
            cropper.zoomOut();
        })
});
       
var cropbox = function(options){
    var el = document.querySelector(options.imageBox),
    obj =
    {
        state : {},
        ratio : 1,
        options : options,
        imageBox : el,
        thumbBox : el.querySelector(options.thumbBox),
        spinner : el.querySelector(options.spinner),
        image : new Image(),
        getDataURL: function ()
        {
            var width = this.thumbBox.clientWidth,
                height = this.thumbBox.clientHeight,
                canvas = document.createElement("canvas"),
                dim = el.style.backgroundPosition.split(' '),
                size = el.style.backgroundSize.split(' '),
                dx = parseInt(dim[0]) - el.clientWidth/2 + width/2,
                dy = parseInt(dim[1]) - el.clientHeight/2 + height/2,
                dw = parseInt(size[0]),
                dh = parseInt(size[1]),
                sh = parseInt(this.image.height),
                sw = parseInt(this.image.width);

            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext("2d");
            context.drawImage(this.image, 0, 0, sw, sh, dx, dy, dw, dh);
            var imageData = canvas.toDataURL('image/png');
            return imageData;
        },
        getBlob: function()
        {
            var imageData = this.getDataURL();
            var b64 = imageData.replace('data:image/png;base64,','');
            var binary = atob(b64);
            var array = [];
            for (var i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            return  new Blob([new Uint8Array(array)], {type: 'image/png'});
        },
        zoomIn: function ()
        {
            this.ratio*=1.1;
            setBackground();
        },
        zoomOut: function ()
        {
            this.ratio*=0.9;
            setBackground();
        }
    },
    attachEvent = function(node, event, cb)
    {
        if (node.attachEvent)
            node.attachEvent('on'+event, cb);
        else if (node.addEventListener)
            node.addEventListener(event, cb);
    },
    detachEvent = function(node, event, cb)
    {
        if(node.detachEvent) {
            node.detachEvent('on'+event, cb);
        }
        else if(node.removeEventListener) {
            node.removeEventListener(event, render);
        }
    },
    stopEvent = function (e) {
        if(window.event) e.cancelBubble = true;
        else e.stopImmediatePropagation();
    },
    setBackground = function()
    {
        var w =  parseInt(obj.image.width)*obj.ratio;
        var h =  parseInt(obj.image.height)*obj.ratio;

        var pw = (el.clientWidth - w) / 2;
        var ph = (el.clientHeight - h) / 2;

        el.setAttribute('style',
                'background-image: url(' + obj.image.src + '); ' +
                'background-size: ' + w +'px ' + h + 'px; ' +
                'background-position: ' + pw + 'px ' + ph + 'px; ' +
                'background-repeat: no-repeat');
    },
    imgMouseDown = function(e)
    {
        stopEvent(e);

        obj.state.dragable = true;
        obj.state.mouseX = e.clientX;
        obj.state.mouseY = e.clientY;
    },
    imgMouseMove = function(e)
    {
        stopEvent(e);

        if (obj.state.dragable)
        {
            var x = e.clientX - obj.state.mouseX;
            var y = e.clientY - obj.state.mouseY;

            var bg = el.style.backgroundPosition.split(' ');

            var bgX = x + parseInt(bg[0]);
            var bgY = y + parseInt(bg[1]);

            el.style.backgroundPosition = bgX +'px ' + bgY + 'px';

            obj.state.mouseX = e.clientX;
            obj.state.mouseY = e.clientY;
        }
    },
    imgMouseUp = function(e)
    {
        stopEvent(e);
        obj.state.dragable = false;
    },
    zoomImage = function(e)
    {
        var evt=window.event || e;
        var delta=evt.detail? evt.detail*(-120) : evt.wheelDelta;
        delta > -120 ? obj.ratio*=1.1 : obj.ratio*=0.9;
        setBackground();
    }

    obj.spinner.style.display = 'block';
    obj.image.onload = function() {
        obj.spinner.style.display = 'none';
        setBackground();

        attachEvent(el, 'mousedown', imgMouseDown);
        attachEvent(el, 'mousemove', imgMouseMove);
        attachEvent(document.body, 'mouseup', imgMouseUp);
        var mousewheel = (/Firefox/i.test(navigator.userAgent))? 'DOMMouseScroll' : 'mousewheel';
        attachEvent(el, mousewheel, zoomImage);
    };
    obj.image.src = options.imgSrc;
    attachEvent(el, 'DOMNodeRemoved', function(){detachEvent(document.body, 'DOMNodeRemoved', imgMouseUp)});

    return obj;
};

</script>
<script type="text/javascript" src="<?php echo url::base()?>js/jquery-filestyle/src/jquery-filestyle.min.js"></script>

<script type="text/javascript">
  $("#file_sponsor:file").jfilestyle({inputSize: "300px"});
</script>