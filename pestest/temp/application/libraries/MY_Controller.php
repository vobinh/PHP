<?php 
class Controller extends Controller_Core
{
	public $db;			// Database
	public $session;	// Session
	public $site;		// Site information
	public $mr = array();			// one data record
	public $mlist;		// list data records
	public $warning = '';
	
    public function __construct()
    {
        parent::__construct();
		date_default_timezone_set('America/Los_Angeles');
        $this->db = Database::instance();		// init property db use Database
        $this->session = Session::instance();	// init property session use Session   
		  
        // get site information, config, language		
		$this->site = Site_Model::get();
		$this->site['base_url'] = url::base();	
        $this->site['config']['TEMPLATE'] ='sic';
        // Get search (keyword, curpage) from session if have  
		if ($this->session->get('sess_search'))	
		{
			$this->search = $this->session->get('sess_search');
			$this->session->set_flash('sess_search',$this->search);
		}
		else
      		$this->search = array('keyword' => '','cur_page' => '');        
        
        $cur_page = $this->uri->segment('page');
        if ($cur_page)
        	$this->search['cur_page'] = '/page/'.$cur_page;
        
        // init admin or client
		if(strpos($this->uri->segment(1),"admin") === false)	// if in url no contain 'admin' then init client
		{		
			$this->site['theme_url'] = url::base().'themes/client/'.$this->site['site_client_theme'].'/';			
			$this->site['version'] = "";
			$this->init_client();
						
		}
		else	// else init admin
		{
			$this->site['theme_url'] = url::base().'themes/admin/';			
			$this->site['site_footer'] = "";
			$this->site['version'] = "";
			
			$this->init_admin();
		}
		
		//echo Kohana::debug($_SESSION);			
    }   
  
	
	public function init_client()
	{		
		$this->set_sess_history('client');	// Get history back from session if have
		
		$lang_id = $this->site['site_lang_client'];
		$lang_code = ORM::factory('languages')->find($lang_id)->languages_code;		
		
		Kohana::config_set('locale.language',$lang_code);//$this->site['site_lang_admin']);
		$this->site['lang_id'] = $lang_id;		
		
		//load customer info if logined
		$this->sess_cus = Login_Model::get('customer');		
	} 
    
	public function init_admin()
	{	
		$this->set_sess_history('admin');	// Get history back from session if have
		
		$this->sess_admin = Login_Model::get('admin', FALSE);
		
		if ($this->sess_admin === FALSE)	// not yet login
		{
			if($this->uri->segment(1) != "admin_login")
				url::redirect('admin_login');
		}
		
		
		
		//load language		
		if ($this->session->get('sess_admin_lang'))
		{
			$lang_id = $this->session->get('sess_admin_lang');
			$lang_code = ORM::factory('languages')->find($lang_id)->languages_code;
		}
		else		// language default
		{			
			$lang_id = $this->site['site_lang_admin'];
			$lang_code = ORM::factory('languages')->find($lang_id)->languages_code;	
			
			$this->session->set('sess_admin_lang',$lang_id);
		}		
		
		Kohana::config_set('locale.language',$lang_code);
		$this->site['lang_id'] = $lang_id;
		
		// Save active last time
		//if($this->uri->segment(1) != "admin_login") Login_Model::save_active_last($this->sess_admin['id']);			
	}  
	
	public function set_sess_history($type)
	{		
		if ($this->session->get('sess_his_'.$type))
		{				
			$this->site['history'] = $this->session->get('sess_his_'.$type);
			if ($this->_check_url_valid($this->site['history']))
			{
				$this->site['history']['back'] = $this->site['history']['current'];
				$this->site['history']['current'] = url::current(true);
			}
			$this->session->set('sess_his_'.$type,$this->site['history']);
		}
		else
			$this->session->set('sess_his_'.$type, array('back' => url::current(true),'current' => url::current(true)));
	}
	
	public function get_admin_lang()
	{
		return $this->site['lang_id'];
	}
	
	public function get_client_lang()
	{
		return $this->site['lang_id'];
	} 
	
	private function _check_url_valid($history)
	{
		$arr_invalid = array(			
			'save','delete','download','check_login','log_out','order',
			'viewaccount','update_account','calendar',
			'wrong_pid','block_page','restrict_access',
			'captcha'
		);
		
		if ($history['current'] == url::current(true))
			return FALSE;
		
		foreach ($arr_invalid as $value)
		{
			if (strpos(url::current(true),$value) !== FALSE) return FALSE;
		}
			
		return TRUE;
	}
	
	//Search focus
    public function format_focus_search($str_search,$str_format)
    {
        $str_temp = substr($str_format,strpos($str_format,$str_search),strlen($str_search));
        return preg_replace('#(?!<.*)(?<!\w)(' .$str_search. ')(?!\w|[^<>]*(?:</s(?:cript|tyle))?>)#is'
        , '<span style="background-color:#FF6">'.$str_temp.'</span>'
        , $str_format);
    }
    
    public function get_thumbnail_size($path_image, $max_width = 300, $max_height = 150)
    {
    	if (is_file($path_image) && file_exists($path_image))
    	{
			$image = new Image($path_image);    	
	    	
	    	if ($image->__get('width') > $image->__get('height'))
	    	{
	    		if ($image->__get('width') > $max_width && $max_width > 0) return "width='$max_width'";
	   		}
	   		else
	   		{
	   			if ($image->__get('height') > $max_height && $max_height > 0) return "height='$max_height'";  
			}
		}	
    }
    
    public function formatBytes($bytes, $precision = 2) 
	{
	    $units = array('B', 'KB', 'MB', 'GB', 'TB');
	  
	    $bytes = max($bytes, 0);
	    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	    $pow = min($pow, count($units) - 1);
	  
	    $bytes /= pow(1024, $pow);
	  
	    return round($bytes, $precision) . ' ' . $units[$pow];
	}
	
	protected function warning_msg($messages)
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
		 		
 			case 'empty_search' :
 				$msg = Kohana::lang('errormsg_lang.msg_no_result');
 				break;
		 		
 			default : 
 				url::redirect('home');
 				die();
		}
		
		$this->template->content->msg = $msg;			
	} 
	//Int date
	public function format_int_date($int_date,$str_format)
	{
		if(!$int_date) return false;
		return date($str_format,$int_date);
	}
	//String date
	public function format_str_date($str_date,$str_format = 'Y/m/d',$str_sep='/',$h=0,$mi=0,$s=0)
	{
		if(!$str_date) return false;
		
		$arr = explode($str_sep, $str_date);
			
		switch($str_format)
		{
			case 'Y/m/d':	list($y,$m,$d) = $arr;break;
			
			case 'm/d/Y':	list($m,$d,$y) = $arr;break;
			
			case 'n/j/Y':	list($m,$d,$y) = $arr;break;
			
			case 'd/m/Y':	list($d,$m,$y) = $arr;break;		
		}		
		return mktime($h,$mi,$s,$m,$d,$y);
	}
	public static function format_currency($val=0, $site_lang=1,$number=2)
	{
		$f = '';
		//if(!$val) return false;
		if ($val<0)
		{
			$val = abs($val);
			$f = "- ";
		}
		//if(!$val) return false;		
		if($site_lang == 1){
			//format English
			return $f.'$'.number_format($val,$number,".",",");
		} elseif($site_lang == 2) {
			//format Vietnam
			return number_format($val,0,",",".").' VND';
		} elseif($site_lang == 3) {
			//format Korean
			return number_format($val,0,".",",").'&#50896;';
		} elseif($site_lang == 4){
			//format Japan
			return '¥'.number_format($val,0,".",",");		
		} else {
			return $val;
		}	
	}
}