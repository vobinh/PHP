<?php
class Warning_Controller extends Template_Controller
{	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();		
		
		/*if (!$this->session->get('sess_war'))
		{
			url::redirect('home');
			die();
		}*/
		
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		$this->template->layout = $this->get_MCH_layout();	// init layout for template controller		
	}
	
	public function __call($method, $arguments)
	{
		$this->index($method);
	}
	
	private function index($messages)
	{
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/warning/index');
		
		switch ($messages)
		{
			case 'wrong_pid' :
				$msg = Kohana::lang('errormsg_lang.war_wrong_pid');
				break;
				
			case 'block_page' : 
				$msg = Kohana::lang('errormsg_lang.war_page_blocked');
				break;
				
			case 'restrict_access' :
		 		$msg = Kohana::lang('errormsg_lang.war_restrict_access');
		 		break;
		 		
 			default : 
 				url::redirect('home');
 				die();
		}
		
		$this->template->content->msg = $msg;			
	}	
}