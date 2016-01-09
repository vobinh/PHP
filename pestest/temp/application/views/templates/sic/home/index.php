
<script src="<?php echo url::base()?>js/jquery/jquery-ui.js"></script>


<script>
var checkmail =false;
function checkEmail(val){
 	
	filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(val.value)) {
		$.ajax({
			url: '<?php echo url::base()?>home/checkEmail/'+val.value,
			type: "post",
			
			success: function (data) {
				if(data == 0){
					$('#notice').html('<span style="color:#729ED6"> The e-mail is available.</span>');
					$('#singup').attr('onclick','signUp("'+val.value+'")');
					checkmail = true;
					
				}
				else{
					$('#notice').html('Email had registered !.');
					checkmail = false;
				}	
			}
		});
	}
	else{
			if(val.value!=''){
				$('#notice').html('<span class="displayckmail" style="display:none"> E-mail is invalid!</span>');
				$('.displayckmail').delay(1000).show(0);	
				$('#singup').attr('onclick','invalidMail()');
			}else{
				$('#notice').html('');
				$('#singup').attr('onclick','emptyMail()');
			}
			checkmail = false;
	}
}

</script>
<script>
$(function() {
    $("#dialog").dialog({
       autoOpen: false,
	   height:800,
       width:600,
       show: {
          effect: "blind",
          duration: 1000
       },
       hide: {
          duration: 1000
       }
    });
});
function signUp(val){
	if(checkmail){
		$('#dialog').dialog('open');
		$('#dialog').load('<?php echo url::base()?>register/loadregister/'+val);
	}
}

function emptyMail(){
	$('#notice').html('Email required!.');
}

function invalidMail(){
	$('#notice').html('E-mail is invalid!.');
}

$().ready(function(){
		$('#txtmail').keyup(function (e) {
			if (e.which == 13) {
				$('#singup').click();
			}
		});
})
</script>

<div id="dialog" title="Sign Up" style="width:500px">
</div>
<div class="" style="width:100%; display:table; padding:15px 0">
		<div style="float:left; width:100%; font-size:48px; text-align:center">
        	We will help you pass the<br /> pest control licensing test.
        </div>
		</div>
<div class="index_banner_top">
    <table width="100%" border="0">
      <tr>
        <td class="">
        <table>
    	<tr>
        	<td style="width:45%; padding-left:50px">
                <img src="<?php echo url::base()?>uploads/site/left.png" height="246px"/>
            </td>
            <td align="center" valign="middle" style="font-size:20px;font-weight:bold;text-align:center;">
                <div  style="padding-bottom:10px;">PesTesT is a tool for studying efficiently, for </div> <div style="padding-bottom:10px;">those interested in taking a surgical </div>
approach to testing.

            </td>
        </tr>
    </table>
     <table>
    	<tr>        	
            <td align="left"><img src="<?php echo url::base()?>uploads/site/bottombanner.jpg" height="266px"/></td>
            <!--td align="center" valign="middle" style="width:55%;text-align:left;">
                <div style="padding-bottom:22px;font-size:16px; font-weight:bold; color:#fff;">Test yourself with PesTesT's randomly generated questions. </div>
                
                 <div style="padding-bottom:22px;font-size:16px; font-weight:bold; color:#fff;">Let PesTesT track your performance and pinpoint which areas you need to work on. </div>
                  <div style="padding-bottom:22px;font-size:16px; color:#fff; font-weight:bold">Review and retake just the portions you need help with, or generate questions based on category. </div>
                  <div style="padding-bottom:22px;font-size:16px;color:#fff; font-weight:bold">Spend a few hours on PesTesT, and marvel at your incredible new score. </div>

            </td>
            <td style="width:45%;text-align:right;padding-right:30px;">
                <img src="<?php echo url::base()?>uploads/site/right.png" height="250px"/>
            </td-->
        </tr>
    </table>
        </td>
      </tr>
    </table>

  </div>
  <div class="middle"></div>
