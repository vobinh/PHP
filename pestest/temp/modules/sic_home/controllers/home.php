<?php
class Home_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		// Init session 
		$this->_get_session_template();	
		$this->questionnaires_model = new Questionnaires_Model();
		$this->answer_model = new Answer_Model();
		$this->member_model = new Member_Model();
		if($this->sess_cus !="")
		{
			url::redirect('test');
			die();
		}
    }
    
   	private function _get_session_template()
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
	
	public function __call($method, $arguments)
	{
		
	} 
    
   	public function index()
	{
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/home/index');
		$this->db->limit($this->site['site_pertest']);
		$mlist = $this->questionnaires_model->randdom();
		for($i=0;$i<count($mlist);$i++)
		{
		   $mlist[$i]['answer'] = $this->answer_model->get_questionnaires($mlist[$i]['uid']);
		}
		$this->template->content->mlist = $mlist;
			
	}
	
	public function checkEmail($val){
			$this->db->where('member_email',$val);
			$list = $this->member_model->get();
			if(!empty($list))
				echo 1;
			else
				echo 0;
			die();	
	}

	public function checkEmailVal(){
			$val = $_POST['txt_email'];
			$this->db->where('member_email',$val);
			$list = $this->member_model->get();
			if(!empty($list))
				echo 'false';
			else
				echo 'true';
			exit;
	}
	
	public function resulttest()
	{   
	  $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/test/resulttesting');
	  if($_POST)
	  {
	  	 $pass =0;
		 $fail=0;
		 $arrayquesidtrue = array();
		 for($i=0;$i<count($_POST['hd_question']);$i++)
		 {
		    if(isset($_POST['radio'.$_POST['hd_question'][$i]]) && $_POST['radio'.$_POST['hd_question'][$i]]==1){
			 $pass++;
			 $arrayquesidtrue[] = $_POST['hd_question'][$i];
			
			}
			else
			 $fail++;
		  }
		  
		  $mr['timeduration'] = $_POST['hd_timeduration'];
		  $mr['fail'] = ($fail*100)/(count($_POST['hd_question']));
		  $mr['pass'] = ($pass*100)/(count($_POST['hd_question']));
		  print_r($arrayquesidtrue);
		  die();
		  $this->template->content->mr = $mr;
		  $this->template->content->arrayquesidtrue = $arrayquesidtrue;
	  }
	  else
	   url::redirect('');
	
	}
	
	
}
?>