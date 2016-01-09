<?php

class Admin_banner_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();

        $this->banner_model = new Banner_Model();

        $this->_get_session_msg();
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
        $this->template->content = new View('admin_banner/list');

        $mr['page_title'] = Kohana::lang('banner_lang.tt_page');
        $mlist = $this->banner_model->get_mod();
		//print_r($mlist);die();

        foreach ($mlist as $key => $list) {
           // $mlist[$key]['banner_type'] = ORM::factory('file_type_orm', $list['file_type_id'])->file_type_detail;
            $mlist[$key]['path_file'] = DOCROOT . 'uploads' . DIRECTORY_SEPARATOR . 'banner' . DIRECTORY_SEPARATOR . $list['banner_name'];
        }

        $this->template->content->mr = $mr;
        $this->template->content->mlist = $mlist;
    }

    public function edit($banner_id) { // banner id
        $this->template->content = new View('admin_banner/frm');

        $mr = $this->banner_model->get_mod($banner_id);
        $mr['banner_type'] = 'image';
        $mr['path_file'] = DOCROOT . 'uploads' . DIRECTORY_SEPARATOR . 'banner' . DIRECTORY_SEPARATOR . $mr['banner_name'];
        $mr['page_title'] = Kohana::lang('banner_lang.tt_page') . ' :: ' . Kohana::lang('global_lang.lbl_edit');

        $this->template->content->mr = $mr;
    }

    public function create() {
        $this->template->content = new View('admin_banner/frm');

        $mr['page_title'] = Kohana::lang('banner_lang.tt_page') . ' :: ' . Kohana::lang('global_lang.lbl_create');

        $this->template->content->mr = $mr;
    }

    private function _get_frm_valid() {
		 $rdo_type = 'image';
        $file_ext = 'jpg,jpeg,gif,png';
        $form = array
            (
            'hd_id' => '',
            'attach_image' => '',
            'txt_width' => '',
            'txt_height' => '',
            'sel_status' => ''
        );

        $errors = $form;

        if ($_POST) {
            $post = new Validation(array_merge($_FILES, $_POST));

            $post->add_rules('attach_' . $rdo_type, 'upload::type[' . $file_ext . ']', 'upload::size[10M]');
            $post->add_rules('txt_width', 'digit');
            $post->add_rules('txt_height', 'digit');

            if ($post->validate()) {
                $form = arr::overwrite($form, $post->as_array());
                return $form;
            } else {
                $errors = $post->errors('banner_validation');
                $str_error = '';

                foreach ($errors as $id => $name)
                    if ($name)
                        $str_error.=$name . '<br>';
                $this->session->set_flash('error_msg', $str_error);

                url::redirect($this->site['history']['current']);
                die();
            }
        }
    }

    public function save() {
        $msg = array('error' => '', 'success' => '');

        $frm = $this->_get_frm_valid();

        $type='image';

        $set = array(
          
          
            'banner_width' => $frm['txt_width'],
            'banner_height' => $frm['txt_height'],
            'banner_status' => $frm['sel_status']
        );

        if (isset($frm['attach_' . $type]['error']) && $frm['attach_' . $type]['error'] == 0) {
            $path_banner = DOCROOT . 'uploads/banner/';

            $path_file = Upload::save('attach_' . $type, NULL, $path_banner);
            $file_name = basename($path_file);

            $set['banner_name'] = $file_name; 
			
		
			//Image::factory( $path_file)
			//	->resize(135, 70, Image::NONE)
			//	->save(DOCROOT.'uploads/banner/thumb/'.$file_name);
				
            $msg['success'] = Kohana::lang('errormsg_lang.msg_file_upload') . '<br>';
        } elseif (isset($frm['attach_' . $type]['error']) && $frm['attach_' . $type]['error'] != 4)
            $msg['error'] .= Kohana::lang('errormsg_lang.error_file_upload') . '<br>';

        if (empty($frm['hd_id'])) {
            if ($this->banner_model->insert_mod($set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_add') . '<br>';
        } else {
            if ($this->banner_model->update_mod_pk($frm['hd_id'], $set))
                $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save') . '<br>';
            else
                $msg['error'] .= Kohana::lang('errormsg_lang.error_data_save') . '<br>';
        }

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        if (isset($_POST['btn_save_add']))
            url::redirect(uri::segment(1) . '/create');

        if (empty($frm['hd_id']))
            url::redirect(uri::segment(1));
        else
            url::redirect(uri::segment(1) . '/edit/' . $frm['hd_id']);
        die();
    }
	
    public function delete($id = '') {
       
           
                $msg = $this->del_file($id);
                $result = $this->banner_model->delete($id);

                if (empty($result))
                    $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_del');
                else
                    $msg['error'] .= Kohana::lang('errormsg_lang.error_data_del');

                $json['status'] = $result;
                $json['mgs'] = $result ? $msg['success'] : $msg['error'];
                $json['banner'] = array('id' => $id);
                echo json_encode($json);
          
       
        die();
    }

    private function del_file($banner_id) {
        $msg = array('error' => '', 'success' => '');

        $banner = ORM::factory('banner_orm', $banner_id);

        $path_file = DOCROOT . 'uploads/banner/' . $banner->banner_name;

        if (is_file($path_file) && file_exists($path_file)) {
            if (@unlink($path_file))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del') . '<br>';
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_file_del') . '<br>';
        }
        else
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist') . '<br>';

        $banner->banner_name = '';
        $banner->save();

        return $msg;
    }

    public function delete_file($banner_id) {
        $msg = $this->del_file($banner_id);

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect(uri::segment(1) . '/edit/' . $banner_id);
        die();
    }
	 public function setstatus($id)
    {    	   
			$result = ORM::factory('banner_orm', $id);
        	$result->banner_status = abs($result->banner_status - 1);
        	$result->save();       	
        	$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));        	
        	url::redirect(uri::segment(1));
            die();
    }

}

?>