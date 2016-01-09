<?php
class Admin_json_user_Controller extends Template_Controller {

	public $template = 'pdf/index';
	
	public function __construct()
    {
		parent::__construct();
        $this->administrator_model = new Administrator_Model(); 
		$this->member_model = new Member_Model(); 
    }
    public function check_user_exists($user_name='',$administrator_id='0')
    {
		$this->db->where(array('administrator_id <>' => $administrator_id));
		$this->db->where('administrator_username', $user_name);
		$data = $this->administrator_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_username._check_user');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
    public function check_oldpass($administrator_id='0',$pass='')
    {
		$this->db->where(array('administrator_id =' => $administrator_id));
        if($pass!='') $this->db->where('user_pass', md5($pass));
		$data = $this->administrator_model->get();
		if($data || $pass=='') {
            $json['status'] = true;
			$json['msg'] = 'OK.!';
		} else {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('account_validation.txt_old_pass._check_old_pass');
		}
		echo json_encode($json);
	}
    
    public function check_email_exists($email='',$administrator_id='0')
    {
        $this->db->where(array('administrator_id <>' => $administrator_id));
		$this->db->where('administrator_email', $email);
		$data = $this->administrator_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_email._check_email');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
    
    public function check_user_email_exists($user_name='',$email='',$administrator_id='0')
    {
		$this->db->where(array('administrator_id <>' => $administrator_id));
		if($user_name) $this->db->where('administrator_username', $user_name);
        if($email) $this->db->where('administrator_email', $email);
		$data = $this->administrator_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_username._check_user');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
	
	public function check_emailmember_exists($email='',$uid='0')
    {
        $this->db->where(array('uid <>' => $uid));
		$this->db->where('member_email', $email);
		$data = $this->member_model->get();
		if($data != false) {
			$json['status'] = false;
			$json['msg'] = Kohana::lang('register_validation.txt_email._check_email');
		} else {
			$json['status'] = true;
			$json['msg'] = 'OK.!';
		}
		echo json_encode($json);
	}
    
}