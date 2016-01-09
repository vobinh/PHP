<link rel="stylesheet" media="screen" href="<?php echo url::base()?>themes/admin/menu/superfish.css" /> 
<link rel="stylesheet" media="screen" href="<?php echo url::base()?>themes/admin/menu/superfish-vertical.css" /> 
<script src="<?php echo url::base()?>themes/admin/menu/hoverIntent.js"></script> 
<script src="<?php echo url::base()?>themes/admin/menu/superfish.js"></script> 
<script> 
    $(document).ready(function(){ 
        $("ul.sf-menu").superfish({ 
            pathClass:  'current' 
        }); 
	 ///////
	 $('.check_update').click(function() {
			var ver = $('#hd_version').val();
			if( ver == ''){
				window.location.href = location.protocol +'//'+location.host+'/'+'admin_checkupdate';
			}
			else{
			$('#ConfirmBox').html('<?php echo 'Do you want update version' ?>' + ' ' + ver )
				.dialog({
					title: 'Confirm',
					closeOnEscape: false,
					resizable: false,
					modal: true,
					open: function(event, ui) {
						$('.ui-dialog-titlebar-close').hide();
					},
					buttons: {
						'No': function() {
							$(this).dialog('destroy');
						},
						'Yes': function() {
							window.location.href = location.protocol +'//'+location.host+'/'+'admin_checkupdate';
							$(this).dialog('destroy');
						}
					}
				});
			}//end else
		});
    }); 
</script>

<?php
	function cate_multi_menu_mpt($node,$languages_id,&$str,$level){
		$arr_where = array('menu_categories_id'=>$node->menu_categories_id,'languages_id'=>$languages_id);
		$node_name = ORM::factory('menu_category_description_orm')->where($arr_where)->find()->menu_categories_name;
		$root      = ORM::factory('menu_category_orm')->__get('root');

		// echo '<pre>';
		// 	print_r($root);
		// echo '</pre>';
		// die();
		if($level==3){ ?>
		  	 <?php if($node->menu_categories_link == 'admin_questionnaires') {?>
           		<li>
           			<a href="<?php echo url::base().$node->menu_categories_link ?>">
           				<span><?php echo $node_name ?></span>
           			</a>
           		</li>
            <?php } ?>
           	
	  <?php }else{
				if ($node->is_leaf() && !$node->is_child($root)){ ?>
                  	<?php if($node->menu_categories_link == 'admin_checkupdate'){ ?>
                		<li><a href="#" ><span class="check_update"><?php echo $node_name ?></span></a></li> 
                	<?php }else{ ?>
						<li><a href="<?php echo $node->menu_categories_link!='#'?url::base().$node->menu_categories_link:"#" ?>"><span><?php echo $node_name ?></span></a></li>
					<?php } ?>
				<?php }else{ ?>
				   	<?php if($node->menu_categories_link !="" && $node->menu_categories_link !='#') { ?>
                   		<li><a class="sf-with-ul" href="<?php echo url::base().$node->menu_categories_link ?>"><span><?php echo $node_name ?></span></a>
				   	<?php }else { ?>
						<li><a class="sf-with-ul" href="#"><span><?php echo $node_name ?></span></a>
				   	<?php } ?>
					
					<?php 
						$node_children = ORM::factory('menu_category_orm',$node->menu_categories_id)->__get('children'); 
					?>
                    <?php if(count($node_children)>0)
                    	echo '<ul>';
					foreach ($node_children as $nc){
						if($nc->menu_categories_status==1)
							cate_multi_menu_mpt($nc,$languages_id,$str,$level); 
					}//end foreach ?> 
					
					<?php 
					if(count($node_children)>0) 
						echo '</ul>';
					?>
                    </li>
				<?php } // end if	
			} 
        } // end function 
   ?>
 	<?php
		$language_id   =$this->get_admin_lang();
		$root_cate     = ORM::factory('menu_category_orm')->__get('root');
		$root_children = $root_cate->__get('children');
  	?>
   	<ul id="sample-menu-4" class="sf-menu sf-navbar">
		 <?php
			foreach($root_children as $children){
				if($children->menu_categories_status==1)
				cate_multi_menu_mpt($children,$language_id,$str,$this->sess_admin['level']);
			}
		?>
    </ul>
  <div id="ConfirmBox" ></div>
    <?php
		// $file_name = "./version/version.txt";
		
		// if(file_exists($file_name)) {
		// 	if($handle = fopen($file_name, "r")){
		// 		$version = fgets($handle);
				
		// 		fclose($handle);	
		// 	}
		// }
		// else{
		// 	$version='';
		// }
		// $ver_host = './update_version/';
		// $str_tmp = file_get_contents($ver_host.'version.ini');

		
		// if($version >= $version_last) {
		// 	$version='';
		// }
		// else{
		// 	$version= substr($version_last,3,6);
		// }
		
	?>
    <input type="hidden" id="hd_version" class="hd_version" value="<?php echo !empty($version)?$version:'';?>" />