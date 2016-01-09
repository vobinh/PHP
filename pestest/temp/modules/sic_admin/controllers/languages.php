<?php
class Languages_Controller extends Template_Controller {

	public $template;
    public function __construct()
    {
        parent::__construct();
	    $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
    }
    
    public function index($page,$lang_id){
	
		if($page == 'client')
		{	
			$this->session->set('sess_client_lang',$lang_id);
		
		}		
		url::redirect($this->site['history']['back']);
	}
 }
?>