<?php
class Article_Controller extends Template_Controller {

	public $template;
	
    public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
        $this->articles_model = new Articles_Model();  
        //View
       
    }
    
	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;
	}
	
    public function index()
    {	
    }
    
    public function detail($id)
    {
    	$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/article/detail');
        //Get
		$mr= $this->articles_model->get($id);
        $this->template->content->mr = $mr;
        //Seo
        $this->template->mr = $mr;
        //Title        
        $this->template->title = $mr['articles_name'];        
    }
}
?>