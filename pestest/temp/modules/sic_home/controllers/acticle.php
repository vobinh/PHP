<?php
class Acticle_Controller extends Template_Controller {
	
	public $template;	

	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		// Init session 
		$this->_get_session_template();	
		$this->articles_model = new Articles_Model(); 
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
    
   	public function content($id='')
	{
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/acticles/acticles');
		$mlist = $this->articles_model->get($id);
		$this->template->content->mlist = $mlist;
	}
	
}
?>