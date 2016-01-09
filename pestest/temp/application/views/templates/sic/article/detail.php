<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top" style="font-size: 18px;  font-weight:bold;padding-bottom: 10px;color:#7f4f26;">
    <a href="<?php echo url::base()?>">Home</a> 
    <img  src="<?php echo url::base();?>themes/ui/pics/icon-parapeter.png" width="12px" /> 
    <a href="#"><?php if(isset($mr['articles_name'])) echo $mr['articles_name'] ?></a></td>
</tr>
</table>
<div class="article"><?php if(isset($mr['articles_content'])) echo $mr['articles_content'] ?></div>