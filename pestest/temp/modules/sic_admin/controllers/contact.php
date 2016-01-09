<?php
class Contact_Controller extends Template_Controller
{
	public $template;
	
    public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		
		// Init session 
		$this->_get_session(); 
		
    }
    
    public function __call($method, $arguments)
    {
    	$this->warning_msg('wrong_pid');
    }
    
    private function _get_session()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
		if ($this->session->get('input_data'))	
		{
			$indata = $this->session->get('input_data');
			$this->mr['txt_name'] = $indata['txt_name'];
			$this->mr['txt_email'] = $indata['txt_email'];
			$this->mr['txt_phone'] = $indata['txt_phone'];
			$this->mr['txt_subject'] = $indata['txt_subject'];
			$this->mr['txt_content'] = $indata['txt_content'];
			$this->mr['txt_content'] = $indata['txt_content'];
		}
	}
    
	function _get_submit()
	{
		if($this->session->get('error_msg')){
			$this->template->content->error_msg = $this->session->get('error_msg');
		}
		if($this->session->get('frm')){
			$this->template->content->mr = $this->session->get('frm');
		}
	}	
	
    public function index()
    {	
        if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{
			$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/contact/frm');
	        $this->_get_submit();
   		}
    }
    
    private function _get_record()
    {
    	$form = array
	    (
	        'txt_name' => '',
	        'txt_email' => '',
	        'txt_phone' => '',
	        'txt_subject' => '',
			'txt_content' => '',
			'txt_code' => '',
			'txt_last_name' => '',
			'txt_first_name' => '',
			'txt_company' => '',
			//'txt_random' => '',
			//'sel_send' => '',						
	    );
	    
	    $errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_name','required');
			$post->add_rules('txt_email','required','email');
			$post->add_rules('txt_subject','required');
			$post->add_rules('txt_content','required');
			//$post->add_rules('txt_code','required');
			//$post->add_callbacks('txt_random',array($this,'_check_security_code'));
			//$post->add_rules('sel_send','trim');
			
			if($post->validate())
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form;
			} 
			else 
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('contact_validation'));
				$error_msg = '';
				foreach($errors as $id => $name) 
					if($name) $error_msg .= '<br>'.$name;
				$this->session->set_flash('error_msg',$error_msg);
				
				url::redirect('contact');
				die();
			}
        }
    }
	
	public function _check_security_code(Validation $array, $field)
	{	//echo $this->session->get('sess_random');
		if($this->session->get('sess_random') != $array[$field])
		{
			$array->add_error($field, '_check_security_code');
		}
	}
	
     private function send_email($record) {
        //Use connect() method to load Swiftmailer
        $swift = email::connect();

        //From, subject
        $from = $record['txt_email'];
        $subject = Kohana::lang('contact_lang.tt_page') . ' ' . $this->site['site_name'];

        //HTML message
        $html_content = Data_template_Model::get_value('EMAIL_CONTACT', $this->get_client_lang());
        //Replate content
		$record['txt_content'] = isset($record['txt_content'])?str_replace(array("\r\n", "\r", "\n"), "<br/>",$record['txt_content']):'';
				
        $html_content = str_replace('#contact_name#', $record['txt_name'], $html_content);
        $html_content = str_replace('#contact_phone#', $record['txt_phone'], $html_content);
        $html_content = str_replace('#contact_subject#', $record['txt_subject'], $html_content);
		$html_content = str_replace('#contact_content#', $record['txt_content'], $html_content);

        //Build recipient lists
        $recipients = new Swift_RecipientList;
        $recipients->addTo($this->site['site_email']);

        //Build the HTML message
        $message = new Swift_Message($subject, $html_content, "text/html");

        if ($swift->send($message, $recipients, $from)) {
            url::redirect('contact/thanks');
            die();
        }
        //Disconnect
        $swift->disconnect();
    }

    public function sm() {
        $record = $this->_get_record();

        if ($record && $_SERVER['SERVER_NAME'] != "localhost")
            $this->send_email($record);

        $this->session->set_flash('error_msg', Kohana::lang('contact_lang.error_contact'));

        url::redirect('contact');
        die();
    }

    public function thanks() {
        $this->template->content = new View('templates/' . $this->site['config']['TEMPLATE'] . '/contact/thanks');
    }

}
?>