<?php

class Admin_config_Controller extends Template_Controller {

    public $template = 'admin/index';

    public function __construct() {
        parent::__construct();
        $this->_get_session_msg();
        $this->questionnaires_model = new Questionnaires_Model();
        $this->category_model       = new Category_Model(); 
        $this->test_model           = new Test_Model();
        $this->Site_Model           = new Site_Model();
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
		$mr = $this->Site_Model->get();
        // echo '<pre>';
        // print_r($mr);
		$mlist = $this->category_model->get();
		for($i=0;$i<count($mlist);$i++)
		{
            $this->db->where('category_uid',$mlist[$i]['uid']);
			$questionnaires_total = count($this->questionnaires_model->get()); 	
			$mlist[$i]['questionnaires_total']	 = $questionnaires_total;
		}
        $mailgun       = $this->db->get('mailgun_config')->result_array(false);
        $s3_config     = $this->db->get('s3_config')->result_array(false);
        $stripe_config = $this->db->get('stripe')->result_array(false);
        $bg_img        = $this->db->get('bg_img')->result_array(false);

		$this->template->content->set(array(
            'mlist'         => $mlist,
            'mr'            => $mr,
            's3_config'     => $s3_config,
            'stripe_config' => $stripe_config,
            'mailgun'       => $mailgun,
            'bg_img'        => $bg_img,
            'test'          => $this->test_model->get()
		));
    }
    protected function _save_img_s3($image){
        require_once Kohana::find_file('views/aws','init');
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
            $this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
            url::redirect('admin_config');
            die();
        }else{
            $name = $image['name'];
            $size = $image['size'];
            $tmp  = $image['tmp_name'];
            $actual_image_name = 'bg_'.md5($image['name'].time()).".".'png';
            try {
                // Upload data.
                $result = $s3Client->putObject(array(
                    'Bucket'      => $s3_bucket,
                    'Key'         => 'bg_img/'.$actual_image_name,
                    'SourceFile'  => $tmp,
                    'ACL'         => 'public-read',
                    'ContentType' => 'image/png'
                ));
                return $actual_image_name;
            }catch (S3Exception $e) {
                return FALSE;
            }
         return FALSE;
        }
    }
    protected function _save_img_bg($image){
        if (! upload::valid($image) OR ! upload::type($image, array('jpg', 'jpeg', 'png', 'gif'))){
          $this->session->set_flash('error_msg','Only upload file jpg , jpeg , png , gif');
          url::redirect('admin_config');
        }else{
            $directory = DOCROOT.'themes/client/styleSIC/index/pics/';
            if ($file = upload::save($image, NULL, $directory)){
                $filename = 'bg_'.md5(rand(0, 999)).time().'.png';
                Image::factory($file)->save($directory.$filename);
                // Delete the temporary file
                unlink($file);
                return $filename;
            }
            return FALSE;
        }
    }
    public function save_bg(){
        // echo '<pre>';
        // print_r($_POST);
        // die();
        $hd_id   = $this->input->post('txt_hd_id');
        $opacity = $this->input->post('txt_img_opacity');

        $file_bg = '';
        $bg      = @$_FILES['uploadFile'];
        if(isset($bg['error']) && $bg['error'] == 0){
            if(s3_using == 'on')
                $file_bg = $this->_save_img_s3($bg);
            else
                $file_bg = $this->_save_img_bg($bg);
        }
        if(!empty($hd_id)){
            //update status == 0
            $this->db->where('1');
            $this->db->update('bg_img',array('status' => 0));
            if(!empty($file_bg)){
                $data = array(
                    'name'    => $file_bg,
                    'opacity' => !empty($opacity)?$opacity:1,
                    'status'  => 1
                );
            }else{
               $data = array(
                    'status' => 1,
                    'opacity' => !empty($opacity)?$opacity:1,
                ); 
            }
            
            $this->db->where('id',$hd_id);
            $result = $this->db->update('bg_img',$data);
            url::redirect('admin_config');
        }else{
            //update status == 0
            $this->db->where('1');
            $this->db->update('bg_img',array('status' => 0));
            $data = array(
                'name'    => $file_bg,
                'opacity' => !empty($opacity)?$opacity:1,
                'status'  => 1
            );
            $result = $this->db->insert('bg_img',$data);
            url::redirect('admin_config');
        }
        die();
    }
    public function update_bg($id){
        $this->db->where('id',$id);
        $bg_img = $this->db->get('bg_img')->result_array(false);
        $arr_result = array();
        $arr_result['name']    = !empty($bg_img[0]['name'])?$bg_img[0]['name']:'error';
        $arr_result['opacity'] = !empty($bg_img[0]['opacity'])?$bg_img[0]['opacity']:'1';
        $arr_result['id']      = !empty($bg_img[0]['id'])?$bg_img[0]['id']:'error';
        echo json_encode($arr_result);
        die();
    }

    public function delete($id){            
        $this->db->where('id',$id);
        $result = $this->db->delete('bg_img');

        $json['status'] = $result?1:0;
        $json['mgs']    = $result?'':Kohana::lang('errormsg_lang.error_data_del');
        $json['user']   = array('id' => $id);
        echo json_encode($json);
        die();
    }

    public function setstatus($id){
        //update all status == 0             
        $this->db->where('1');
        $this->db->update('bg_img',array('status' => 0));

        //update
        $this->db->where('id',$id);
        $result = $this->db->update('bg_img',array('status' => 1));      
        $this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));           
        url::redirect($this->uri->segment(1));
        die();
       
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
		// echo '<pre>';
		// print_r($_POST);
		// die();
        $frm = $this->_get_frm_valid();
        /*payment config*/
        $txt_test_secret_key      = $this->input->post('txt_test_secret_key');
        $txt_test_publishable_key = $this->input->post('txt_test_publishable_key');
        $txt_live_secret_key      = $this->input->post('txt_live_secret_key');
        $txt_live_publishable_key = $this->input->post('txt_live_publishable_key');
        $slt_stripe               = $this->input->post('slt_stripe');

        $stripe_config = $this->db->get('stripe')->result_array(false);
        $data_stripe_config = array(
            'test_secret_key'      => $txt_test_secret_key,
            'test_publishable_key' => $txt_test_publishable_key,
            'live_secret_key'      => $txt_live_secret_key,
            'live_publishable_key' => $txt_live_publishable_key,
            'type'                 => $slt_stripe,
        );
        if(!empty($stripe_config)){
            $this->db->where('id', $stripe_config[0]['id']);
            $this->db->update('stripe', $data_stripe_config);
        }else{
            $this->db->insert('stripe',$data_stripe_config);
        }
        /*End payment config*/

        /*S3 config*/
        $s3_key    = $this->input->post('txt_s3_key');
        $s3_secret = $this->input->post('txt_s3_secret');
        $s3_bucket = $this->input->post('txt_s3_bucket');
        $s3_config = $this->db->get('s3_config')->result_array(false);
        $data_s3_config = array(
            'key'         => $s3_key,
            'secret'      => $s3_secret,
            'main_bucket' => $s3_bucket,
        );
        if(!empty($s3_config)){
            $this->db->where('id', $s3_config[0]['id']);
            $this->db->update('s3_config', $data_s3_config);
        }else{
            $this->db->insert('s3_config',$data_s3_config);
        }
        /*End S3 config*/

        // mailgun
        $mailgun_key    = $this->input->post('txt_mailgun_key');
        $mailgun_pubkey = $this->input->post('txt_mailgun_pubkey');
        $mailgun_domain = $this->input->post('txt_mailgun_domain');
        $mailgun_from   = $this->input->post('txt_mailgun_from');

        $mailgun = $this->db->get('mailgun_config')->result_array(false);
        $data_mailgun=array(
            'mailgun_key'    => $mailgun_key,
            'mailgun_pubkey' => $mailgun_pubkey,
            'mailgun_domain' => $mailgun_domain,
            'mailgun_from'   => $mailgun_from,
        );
        if(!empty($mailgun)){
            $this->db->where('id', $mailgun[0]['id']);
            $this->db->update('mailgun_config', $data_mailgun);
        }else{
            $this->db->insert('mailgun_config',$data_mailgun);
        }
        // end mailgun

		//$this->_check_total($frm['txt_per_test'],$total);
		
		//$this->save_category();
		
        $msg = array('error' => '', 'success' => '');
        $pos = $set = array(
            'site_name'        => $frm['txt_name'],
            'site_phone'       => $frm['txt_phone'],
            'site_fax'         => $frm['txt_fax'],
            'site_email'       => $frm['txt_email'],
            'site_address'     => $frm['txt_address'],
            'site_city'        => $frm['txt_city'],
            'site_zipcode'     => $frm['txt_zipcode'],
            'site_state'       => $frm['txt_state'],
            'site_slogan'      => $frm['txt_slogan'],
            'site_title'       => $frm['txt_title'],
            'site_keyword'     => $frm['txt_keyword'],
            'site_description' => $frm['txt_description'],
            'site_pertest'     => $frm['txt_per_test'],
            'site_logo_width'  => $frm['txt_width'],
            'site_logo_height' => $frm['txt_height'],
            'site_cart'        => $frm['rdo_enable_cart'],
            'site_about'       => !empty($_POST['txt_abount_us'])?$_POST['txt_abount_us']:'',
            'site_sub_title'   => !empty($_POST['txt_sub_title'])?$_POST['txt_sub_title']:'',
        );
        if (!empty($frm['attach_logo']['name'])) {
            if ($frm['attach_logo']['error'] > 0) {
                $msg['error'] = Kohana::lang('errormsg_lang.error_upload');
            } else {
				require_once Kohana::find_file('views/aws','init');
				$image_logo = @$_FILES['attach_logo'];
				$name_logo  = $image_logo['name'];
				$size_logo  = $image_logo['size'];
				$tmp_logo   = $image_logo['tmp_name'];
				$actual_img = time().'_'.$name_logo;

				try {
                    // Upload data.
                    $result = $s3Client->putObject(array(
                        'Bucket'      => $s3_bucket,
                        'Key'         => 'site/'.$actual_img,
                        'SourceFile'  => $tmp_logo,
                        'ACL'         => 'public-read',
                        'ContentType' => 'image/png'
                    ));
                }catch (S3Exception $e) {
                    echo $e->message();
                }

				$msg['success'] = Kohana::lang('errormsg_lang.msg_file_upload');
                $set['site_logo'] = $actual_img;
            }
        }
   
        $update_conf = TRUE;
        if ($this->Site_Model->update($set) || $update_conf)
            $msg['success'] .= Kohana::lang('errormsg_lang.msg_data_update');
        else
            $this->session->set_flash('info_msg', Kohana::lang('errormsg_lang.war_data_update'));

        if ($msg['error'] !== '')
            $this->session->set_flash('error_msg', $msg['error']);

        if ($msg['success'] !== '')
            $this->session->set_flash('success_msg', $msg['success']);

        url::redirect($this->uri->segment(1));
        die();
    }

    public function del_logo() {
		$msg       = array('error' => '', 'success' => '');
		$path_file = DOCROOT . 'uploads/site/' . $this->site['site_logo'];

        if (is_file($path_file) && file_exists($path_file)) {
            if (@unlink($path_file))
                $msg['success'] = Kohana::lang('errormsg_lang.msg_file_del');
            else
                $msg['error'] = Kohana::lang('errormsg_lang.error_del_file');
        }else
            $msg['error'] = Kohana::lang('errormsg_lang.error_file_not_exist');

        if ($this->Site_Model->update(array('site_logo' => '')))
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