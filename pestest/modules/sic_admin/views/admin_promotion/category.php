<style type="text/css">
<!--
#questionnaires {
	padding: 10px;
	width: 98%;
	border: 1px solid #CCCCCC;
	margin-top: 5px;
	margin-bottom: 5px;
	border-radius: 6px;
}
#category_questionnaires {
	border: solid 1px #ccc;
	padding: 5px;
	width: 30%;
	float: left;
	border-radius: 4px;
}
#content_questionnaires {
	border: thin solid #ccc;
	margin-left:32%;
	border-radius: 4px;
}
#float_none {
	clear: both;
}
#content_question {
	padding: 5px;
}
#line_dot {
	border-top-width: thin;
	border-top-style: dotted;
	border-right-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-top-color: #CCCCCC;
	border-right-color: #CCCCCC;
	border-bottom-color: #CCCCCC;
	border-left-color: #CCCCCC;
}
#answer_question {
	padding: 5px;
}
-->
</style>
<div id='questionnaires'>
    <div id='category_questionnaires'>
       <ul>
       <?php foreach($mlist as $value){?>
            <li><a style="cursor:pointer"> <?php echo 'Question '.$value['uid']?> </a></li>
       <?php }?>
      </ul>
             <div class='pagination'><?php echo isset($this->pagination)?$this->pagination:''?><br />
            <form id="frm_display" name="frm_display" method="post" action="<?php echo $this->site['base_url']?>admin_account/display">
            <?php echo Kohana::lang('global_lang.lbl_display')?> #<select id="sel_display" name="sel_display" onchange="document.frm_display.submit();">
                <option value="">---</option>
                <option value="20" <?php echo !empty($display)&&$display==20?'selected="selected"':''?>>20</option>
                <option value="30" <?php echo !empty($display)&&$display==30?'selected="selected"':''?>>30</option>
                <option value="50" <?php echo !empty($display)&&$display==50?'selected="selected"':''?>>50</option>
                <option value="100" <?php echo !empty($display)&&$display==100?'selected="selected"':''?>>100</option>
                <option value="all" <?php echo !empty($display)&&$display=='all'?'selected="selected"':''?>><?php echo Kohana::lang('global_lang.lbl_all')?></option>
            </select>
            <?php echo Kohana::lang('global_lang.lbl_tt_items')?>: <?php echo isset($this->pagination)?$this->pagination->total_items:''?>
            </form>
            </div>
    </div>
    <div id='content_questionnaires'>
        <div id='content_question'>
		
        </div>
        <div id='line_dot'></div>
        <div id='answer_question'>
        	<ul>
            	<li> dap an 1 </li>
                <li> dap an 2 </li>
            </ul>
        </div> 
    </div>
    <div id='float_none'></div>
</div>