<?php
class Admin_menu_category_Controller extends Template_Controller {
	
	var $mr;
	var $mlist;
	
	public $template = 'admin/index';
	
	public function __construct()
    {
        parent::__construct(); // This must be included
 
        $this->search = array('keyword' => '');
        
        $this->_get_submit();
    }

	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;
	}
	
	function _get_submit()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
	}
	
	public function index()
	{
		$this->showlist();	
	}
    
	public function showlist()
	{
		$this->template->content = new View('admin_menu_category/list');
		
		//Assign
        if(isset($this->search['keyword']))
            $this->template->content->keyword = $this->search['keyword'];
        
        $model = new Menu_category_orm_Model();
        $cat = new Menu_Category();
        $this->where_sql();
        $mlist = $cat->get($model);
		
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_menu_category/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => count($mlist),
		    'items_per_page' => count($mlist),
		    'style'          => 'digg',
		));		
		$this->db->limit($this->pagination->items_per_page,$this->pagination->sql_offset);
        
        $this->where_sql();
        $mlist = $cat->get($model);
		$this->template->content->mlist = $mlist;
		$model_lang = new Language_Model();
		$this->template->content->list_language = $model_lang->get_with_active();
		$this->_get_submit();
	}
	
    public function where_sql()
    {
		if($this->search['keyword'])
			$this->db->like('menu_categories_name',$this->search['keyword']);
    }
    
	public function search()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		//Keyword
        $keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$this->session->set_flash('sess_search',$this->search);

		$this->showlist();
	}	
	
    public function _list_language()
    {
        $model_lang = new Language_Model();		
		$this->template->content->list_language = $model_lang->get_with_active();
    }
    
    public function _list_category()
    {
        $model = new Menu_category_orm_Model();
        $cat = new Menu_Category();
		$this->template->content->list_category = $cat->get($model);
		
    }
    
	public function create()
	{
		$this->template->content = new View('admin_menu_category/frm');
        //Language
		$this->_list_language();
        //menu Category
       	$this->_list_category();
                           		
	}
	
	private function _get_record()
	{		
		$form = array(
	    	'sel_parent_name'   => '',
	    	'hd_id'				=> '',
	        'hd_old_image'		=> '',
            'txt_link'		=> '',
	        'txt_sort_order'    => '',
	        'chk_delete'		=> '',
	        'sel_status'		=> '',
		);
		
		$model_lang = new Language_Model();
		$list = $model_lang->get_with_active();	
		for($i=0; $i<count($list); $i++)
		{
			$form['txt_name'.$list[$i]['languages_id']] = '';
			$form['txt_description'.$list[$i]['languages_id']] = '';
		}
	 
	    $errors = $form;
	 	
	    if($_POST)
	    {
	        $post = new Validation($_POST);
	 		
	        $post->pre_filter('trim', TRUE);
	 
	        for($i=0; $i<count($list); $i++)
				$post->add_rules('txt_name'.$list[$i]['languages_id'],'required');
	        
	        if($post->validate())
	        {
	        	$form = arr::overwrite($form, $post->as_array());
     			return $form;
	        } else {	        	
	        	$form = arr::overwrite($form, $post->as_array());
	            $errors = arr::overwrite($errors,$post->errors('menu_category_validation'));
	            
	            $str = '';
	            for($i=0; $i<count($list); $i++)
				{
					if ($errors['txt_name'.$list[$i]['languages_id']])
						$str .= $errors['txt_name'.$list[$i]['languages_id']].'<br>';
				}
				$this->session->set_flash('error_msg', $str);
				
				if ($form['hd_id'])
					url::redirect('admin_menu_category/edit/'.$form['hd_id']); 
				else
					url::redirect('admin_menu_category/create'); 	            
	        }
	    }
	}
	
	private function _get_image($chk_delete,$hd_old_image)
	{
		//Check image old
		if($hd_old_image)
			$path_filedel = './uploads/menu_category/'.$hd_old_image;
		else	
			$path_filedel = '';
	
		if($chk_delete)
		{
			if(file_exists($path_filedel)) unlink($path_filedel);			
			return '';
		}	
		//Upload	
		//Uses Kohana upload helper		
		$_FILES = Validation::factory($_FILES)
			->add_rules('txt_image', 'upload::valid', 'upload::type[gif,jpg,png]', 'upload::size[1M]');
		if($_FILES['txt_image']['error']==0)
		{
			//Delete file if have upload image
			if(file_exists($path_filedel)) unlink($path_filedel);
			
			//Temporary file name
			$filename = upload::save('txt_image');
			//Resize, sharpen, and save the image
			Image::factory($filename)
				//->resize(100, 100, Image::AUTO)
				->save(DOCROOT.'uploads/menu_category/'.md5(basename($filename)).'.png');
		 	
			//Remove the temporary file
			unlink($filename);
		 
			//Redirect back to the account page
			return md5(basename($filename)).'.png';
		} else {
			if ($hd_old_image)
				return $hd_old_image;
			else
				return '';
		}			
	}
	
	private function _save_categories($hd_id,$record) // hd_id = menu categories id
	{
		if(!$hd_id){ // create new
			$result = ORM::factory('menu_category_orm');
			$result->insert_as_first_child($record['sel_parent_name']);
		} else { // edit
			$result = ORM::factory('menu_category_orm',$hd_id);	// current menu categories id
			$old_parent = ORM::factory('menu_category_orm',$hd_id)->__get('parent');		// old parent 	
			$new_parent = ORM::factory('menu_category_orm',$record['sel_parent_name']);	// new parent has choice
			// if new parent different old parent && not itself
			if ($record['sel_parent_name'] != $old_parent->menu_categories_id && $record['sel_parent_name'] != $hd_id)
			{			
				$result->move_to_first_child($new_parent);
			}
		} 
		
		$result->menu_categories_image = $this->_get_image($record['chk_delete'],$record['hd_old_image']);
        $result->menu_categories_link = $record['txt_link'];
		$result->menu_categories_parent_id = $record['sel_parent_name'];
		$result->menu_categories_pid = $record['sel_parent_name'];
		$result->menu_categories_sort_order = $record['txt_sort_order'];
		$result->menu_categories_status = $record['sel_status'];

		$hd_id = $result->save();
		
		return $hd_id;
	}
	
	private function _save_categories_desc($hd_id,$record)
	{
		//status = 1 'new', = 2 'edit'
		$model_lang = new Language_Model();
		$list = $model_lang->get_with_active();
			
		for($i=0; $i<count($list); $i++)
		{
			$query = $this->db->query("SELECT * FROM menu_categories_description WHERE menu_categories_id=" .$hd_id .' AND languages_id ='.$list[$i]['languages_id']);
 
			if ($query->count()>0)
			{
				$this->db->update('menu_categories_description', 
					array('menu_categories_name' => $record['txt_name'.$list[$i]['languages_id']],
						  'menu_categories_description' => $record['txt_description'.$list[$i]['languages_id']]
						  ), 
					array('menu_categories_id' => $hd_id,
						  'languages_id' => $list[$i]['languages_id']
						 )
					);
			} else {
				$this->db->insert('menu_categories_description',									
					array('menu_categories_id' => $hd_id,
						  'languages_id' => $list[$i]['languages_id'],
						  'menu_categories_name' => $record['txt_name'.$list[$i]['languages_id']],
						  'menu_categories_description' =>$record['txt_description'.$list[$i]['languages_id']]
						)
				);
			}
		}
	}
	
	private function _save_categories_gid($hd_id,$record)
	{
		if($record['sel_parent_name'])
		{	
			$object = ORM::factory('menu_category_orm')->where('menu_categories_id', $record['sel_parent_name'])->find();
			$record['menu_categories_parent_id'] = $record['sel_parent_name'];
			$record['menu_categories_pid'] = $record['sel_parent_name'];
			$record['menu_categories_gid'] = $object->menu_categories_gid;
			
			$tmp = explode("|",$object->menu_categories_path);			
			if ($tmp[count($tmp)-1] != $hd_id)
				$record['menu_categories_path'] = $object->menu_categories_path.$hd_id.'|';
			else
				$record['menu_categories_path'] = $object->menu_categories_path;
		} else {
			$record['menu_categories_parent_id'] = $record['sel_parent_name'];
			$record['menu_categories_pid'] = $record['sel_parent_name'];
			
			$record['menu_categories_path'] = $hd_id.'|';
			
			$this->db->select('MAX(menu_categories_gid) as gid');
			$count = $this->db->get('menu_categories');
			$tmp = $count->result_array(false);
			$record['menu_categories_gid'] = $tmp[0]['gid'] + 1;
		}
		
		//$record['menu_categories_level'] = substr_count($record['menu_categories_path'],'|');		
		
		$this->db->update('menu_categories', 
										array('menu_categories_gid'   => $record['menu_categories_gid'],
											  'menu_categories_parent_id' => $record['menu_categories_parent_id'],
											  'menu_categories_pid' => $record['menu_categories_pid'],
											  //'menu_categories_level' => $record['menu_categories_level'],
											  'menu_categories_path'  => $record['menu_categories_path']
											  ), 
										array('menu_categories_id'  => $hd_id
											  )
										);
		return $hd_id;		
	}	
	
	public function save()
	{
		$hd_id = $this->input->post('hd_id');
		
		$record = $this->_get_record();
		
		if($record)
		{
			$hd_id1 = $this->_save_categories($hd_id,$record);
			$this->_save_categories_desc($hd_id1,$record);
			$this->_save_categories_gid($hd_id1,$record);
		}
		
		if(!$this->input->post('hd_id'))
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
		else
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
		
        if(isset($_POST['btn_save_add']) && $hd_id1)
            url::redirect('admin_menu_category/create'); 
        elseif($hd_id1)
            url::redirect('admin_menu_category/edit/'.$hd_id1);
        else
            url::redirect('admin_menu_category');     
	}
	
	public function saveall()
	{
		$arr_id = $this->input->post('chk_id');
		$status = array();
		if(is_array($arr_id))
		{			
			//do with action select
			$sel_action = $this->input->post('sel_action');
		
			if($sel_action == 'delete')
			{
				for($i=0;$i<count($arr_id);$i++)
				{
					$this->db->delete('menu_categories_description', array('menu_categories_id' => $arr_id[$i]));
	 				$status = $this->db->delete('menu_categories', array('menu_categories_id' => $arr_id[$i]));
				}
			} elseif($sel_action == 'block') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('menu_categories', array('menu_categories_status' => 0), array('menu_categories_id' => $arr_id[$i]));
				}				
			} elseif($sel_action == 'active') {
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->update('menu_categories', array('menu_categories_status' => 1), array('menu_categories_id' => $arr_id[$i]));
				}
			}
		} else {		
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if (count($status)>0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));	
		url::redirect('admin_menu_category');
	}
	
	public function edit($id)
	{
		$this->template->content = new View('admin_menu_category/frm');        
        //Language
		$this->_list_language();
        //menu Category
       	$this->_list_category();
        //Edit
        $model = new Menu_category_orm_Model();
        $cat = new Menu_Category();	
		$list = $cat->get($model,$id);
		$mr = $list[0]['languages'];
		$this->template->content->mr = $mr;
		$this->_get_submit();	
	}
	
	public function delete($id)
	{
		$this->db->delete('menu_categories_description',array('menu_categories_id' => $id));
 		$status = ORM::factory('menu_category_orm',$id)->delete();
		if(count($status)>0)
		{
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_del'));
		}
		url::redirect('admin_menu_category');  
		die();   
	}
	
	public function delete_image($id)
	{
		$result = ORM::factory('menu_category_orm',$id);
        //Delete
        $file_name = './uploads/menu_category/'.$result->menu_categories_image;
        if(file_exists($file_name)) unlink($file_name);
		$result->menu_categories_image = '';
		$result->save();
		url::redirect('admin_menu_category/edit/'.$id); 
	}
	
	public function setstatus($id,$s)
	{
		$status = $this->db->update('menu_categories',array('menu_categories_status' => $s),array('menu_categories_id' => $id));		
		if(count($status)>0)
		{
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));
		}
		url::redirect('admin_menu_category');
	}
	
	public function change_language($id)
	{
		if(request::is_ajax())
		{
			$this->auto_render=false; 
			$result = ORM::factory('language_orm')->find($id);
			
	        echo $result->languages_image; 
		} else {
			$this->template->content='nothing yet';
		}
	}
	
	public function chcateo()	// CHange Categories Order
    {
    	$up_down = $this->uri->segment('chcateo','');	
		$categories_id = $this->uri->segment('id','');	// id of current menu categories
		if (!empty($up_down) && !empty($categories_id))
		{		
			if ($up_down == 'up')	// move current menu categories to previous categories
			{
				$prev_category = ORM::factory('menu_category_orm',$categories_id)->prev_sibling();
				ORM::factory('menu_category_orm',$categories_id)->move_to_prev_sibling($prev_category);
			}
			else //down	// move current menu categories to next categories
			{
				$next_category = ORM::factory('menu_category_orm',$categories_id)->next_sibling();
				ORM::factory('menu_category_orm',$categories_id)->move_to_next_sibling($next_category);
			}
		}
		url::redirect("admin_menu_category");
		die();
    }
}