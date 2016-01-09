<?php

class Admin_config_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();
        $this->_get_session_msg();
		$this->questionnaires_model = new Questionnaires_Model();
		$this->category_model = new Category_Model(); 
		$this->test_model=new Test_Model(); 
    }

    public function __call($method, $arguments) {
        $this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');

        $this->index();
    }

    private function _get_session_msg() {
        if ($this->session->get('error_msg'))
            $this->template->error_msg = $this->session->get('error_msg');
        if ($this->session->get('warning_msg'))
            $this->template->warning_msg = $this->session->get('warning_msg');
        if ($this->session->get('success_msg'))
            $this->template->success_msg = $this->session->get('success_msg');
        if ($this->session->get('info_msg'))
            $this->template->info_msg = $this->session->get('info_msg');
    }

    public function index() {
        $this->template->content = new View('admin_config/index');
		$mr = Site_Model::get();
		$mlist = $this->category_model->get();
		for($i=0;$i<count($mlist);$i++)
		{
            $this->db->where('category_uid',$mlist[$i]['uid']);
			$questionnaires_total = count($this->questionnaires_model->get()); 	
			$mlist[$i]['questionnaires_total']	 = $questionnaires_total;
		}
		$this->template->content->set(array(
            'mlist' => $mlist,
			'mr' => $mr,
			'test' =>$this->test_model->get()
		));
    }

    private function _get_frm_valid() {
        
		
		$form = array
            (
            'txt_name' => '',
            'txt_phone' => '',
            'txt_fax' => '',
            'txt_email' => '',
            'txt_address' => '',
            'txt_city' => '',
            'txt_zipcode' => '',
            'txt_contact' => '',
            'txt_state' => '',
            'txt_slogan' => '',
            'txt_title' => '',
            'txt_keyword' => '',
            'txt_description' => '',
			'txt_per_test' => '',
			'txt_width' => '',
			'txt_height' => '',
			'rdo_enable_cart' => '',
			'attach_logo' => '',
        );

        $errors = $form;

        if ($_POST) {
            $post = new Validation(array_merge($_POST, $_FILES));

            if (!empty($_FILES['attach_logo']['name'])) {
                $post->add_rules('attach_logo', 'upload::type[gif,jpg,png,jpeg]', 'upload::size[2M]');
            }
            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_name', 'required');
            $post->add_rules('txt_phone', 'required');
            //$post->add_rules('txt_fax','phone[7,10,11,14]');
            $post->add_rules('txt_email', 'required', 'email');
            $post->pre_filter('trim', TRUE);
            $post->add_rules('txt_width', 'digit');
            $post->add_rules('txt_height', 'digit');
			$post->add_rules('txt_per_test', 'digit');
            if ($post->validate()) {
                $form = arr::overwrite($form, $post->as_array());
                return $form;
            } else {
                $errors = arr::overwrite($errors, $post->errors('site_validation'));
                $str_error = '';
                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error.=$name . '<br>';
                $this->session->set_flash('error_msg', $str_error);
            }
        }

        url::redirect('admin_config');
        die();
    }
    
	public function _check_total($value,$total)
	{
		
		if ($value >$total)
		{
			 $this->session->set_flash('error_msg', 'Questions per test cannot larger than '.$total.'.');
			  url::redirect('admin_config');
            die();
		}	
	}
	
	public function save_category()
	{
		 $totalpercent=0;
		 $per_test = $_POST['txt_per_test'];
		  $str_error = '';
		 for($i=0;$i<count($_POST['txt_category']);$i++)
		 {
		    if(isset($_POST['txt_percentage_'.$_POST['txt_category'][$i]]) && $_POST['txt_percentage_'.$_POST['txt_category'][$i]]!=0
			   && !empty($_POST['txt_percentage_'.$_POST['txt_category'][$i]]))
			{
			  $qty_questions =  $_POST['txt_questions_'.$_POST['txt_category'][$i]];
			  $percentage =  $_POST['txt_percentage_'.$_POST['txt_category'][$i]];
			  $category =  $_POST['txt_name_'.$_POST['txt_category'][$i]];
			  $check_percentage = ($percentage*$per_test)/100;
			  if($check_percentage > $qty_questions)
			  $str_error.=$category .' missing '.($check_percentage - $qty_questions).' questions.<br>';
			  if(!$this->isInteger($check_percentage))
			  $str_error.='Qty questions of '.$category. ' should be integers.<br>';
			  if($qty_questions==0)
			  $str_error.='Qty questions of '.$category. ' can not empty.<br>';
			  
			}
			$this->db->update('category',array('category_percentage'=>$_POST['txt_percentage_'.$_POST['txt_category'][$i]]),array('uid'=> $_POST['txt_category'][$i]));
			$totalpercent=$totalpercent+$_POST['txt_percentage_'.$_POST['txt_category'][$i]];
		 }
		 
		 if($totalpercent !=100)
		 $str_error.='Total percentage is 100%.<br>';
		 if(!empty($str_error))
		 {
		   $this->session->set_flash('error_msg', $str_error);
		   url::redirect('admin_config');
            die();
		 }
	}
	
	public function isInteger($input){
      return(ctype_digit(strval($input)));
   }

    public function save() {
		// 
		 //$this->db->where('status','Active');
		// $total = count($this->questionnaires_model->get());
		 
		//
        $frm = $this->_get_frm_valid();
		//$this->_check_total($frm['txt_per_test'],$total);
		
		//$this->save_category();
		
        $msg = array('error' => '', 'success' => '');
        $pos = $set = array(
            'site_name' => $frm['txt_name'],
            'site_phone' => $frm['txt_phone'],
            'site_fax' => $frm['txt_fax'],
            'site_email' => $frm['txt_email'],
            'site_address' => $frm['txt_address'],
            'site_city' => $frm['txt_city'],
            'site_zipcode' => $frm['txt_zipcode'],
            'site_state' => $frm['txt_state'],
            'site_slogan' => $frm['txt_slogan'],
            'site_title' => $frm['txt_title'],
            'site_keyword' => $frm['txt_keyword'],
            'site_description' => $frm['txt_description'],
			'site_pertest' => $frm['txt_per_test'],
            'site_logo_width' => $frm['txt_width'],
            'site_logo_height' => $frm['txt_height'],
			'site_cart' => $frm['rdo_enable_cart']
        );
        if (!empty($frm['attach_logo']['name'])) {
            if ($frm['attach_logo']['error'] > 0) {
                $msg['error'] = Kohana::lang('errormsg_lang.error_upload');
            } else {
                $path_site = DOCROOT . 'uploads/site/';

                $path_file = Upload::save('attach_logo', NULL, $path_site);

                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_upload');
                $set['site_logo'] = basename($path_file);

                $img = new Image($path_file);
                if (empty($frm['txt_width']))
                    $set['site_logo_width'] = $img->__get('width');
                if (empty($frm['txt_height']))
                    $set['site_logo_height'] = $img->__get('height');
            }
        }
   
        $update_conf = TRUE;
        if (Site_Model::update($set) || $update_conf)
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $this->session->set_flash('info_msg', Kohana::lang('errormsg_lang.war_data_update'));

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect(uri::segment(1));
        die();
    }

    public function del_logo() {
        $msg = array('error' => '', 'success' => '');
        $path_file = DOCROOT . 'uploads/site/' . $this->site['site_logo'];

        if (is_file($path_file) && file_exists($path_file)) {
            if (@unlink($path_file))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del');
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_del_file');
        }
        else
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist');

        if (Site_Model::update(array('site_logo' => '')))
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $msg['error'] .= Kohana::lang('errormsg_lang.error_data_update');

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect('admin_config');
        die();
    }
	
	public function getCateggory($id){
		$list = $this->category_model->getCategoryById('test_uid',$id);
		$view = new View('admin_config/gridseting');
		for($i=0;$i<count($list);$i++)
		{
            $this->db->where('category_uid',$list[$i]['uid']);
			$questionnaires_total = count($this->questionnaires_model->get()); 	
			$list[$i]['questionnaires_total']	 = $questionnaires_total;
		}
		$view->set(array(
            'mlist' => $list,
			'test' =>$this->test_model->get()
		));
		$view->render(TRUE);
		die();
		
	}

}

?>