<?php
class Recommended_Controller extends Template_Controller {
	
	public $template;	
	public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/user');
    }

	
	public function __call($method, $arguments){}
    
    public function set_recommended($array="",$img=""){
        $m_time = time() + (86400 * 1);
    	if(!empty($array)){
    		$cookie_name  = "arr_courses";
			$cookie_value = $array;
			setcookie($cookie_name, $cookie_value, $m_time, "/"); // 86400 = 1 day
    	}
        if(!empty($img)){
            setcookie('sponsor_img', $img, $m_time, "/"); // 86400 = 1 day
        }else{
            setcookie('sponsor_img', '', time() - (86400 * 1), "/"); // 86400 = 1 day
        }
    	url::redirect(url::base().'home');
    	die();
    }
}
?>