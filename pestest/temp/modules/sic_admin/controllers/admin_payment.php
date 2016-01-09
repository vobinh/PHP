<?php
class Admin_payment_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->payment_model = new Payment_Model();
		$this->member_model = new Member_Model();
		$this->test_model   = new Test_Model();
		parent::__construct();
		$this->_get_session_msg();
		
	}
	
	public function __call($method, $arguments)
	{
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
	}
	
	private function _get_session_msg()
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
	
	public function _get_submit()
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
	
	public function index()
    {             
		
		
		$this->search = array('keyword' => '');
        $this->search = array('test' => '');
	    $this->showlist();
       
	    $this->_get_submit();		
    }
	
	
	private function showlist()
    {
    	$this->template->content = new View('admin_payment/list');
		$this->where_sql();
		$total_items = count($this->payment_model->getpayment());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_payment/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->payment_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		$mlist = $this->payment_model->getpayment();
		
		foreach($mlist as $key => $value){
			$mlist[$key]['member']   = $this->member_model->get($value['member_uid']);
			$mlist[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		
		$this->template->content->set(array(
            'mlist' => $mlist
		));
		$this->template->content->set(array(
            'test' => $this->test_model->get() 
		));
    }
	
	public function search()
	{
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$test = $this->input->post('sel_test');	
		if(isset($test))
		{
			$this->search['test'] = $this->input->post('sel_test');
		}
		
		$this->session->set_flash('sess_search',$this->search);
		$this->showlist();
	}
	
	public function where_sql()
    {
		if(isset($this->search['keyword']) || isset($this->search['test']))
		{
			if(isset($this->search['keyword']) && !empty($this->search['keyword'])){
		   		 $this->db->where('LCASE(member.member_fname) LIKE'.$this->db->escape('%'.trim($this->search['keyword']).'%'));
				 $this->db->orwhere('LCASE(member.member_lname) LIKE'.$this->db->escape('%'.trim($this->search['keyword']).'%'));
			}
			if(isset($this->search['test']) &&!empty($this->search['test']))
				$this->db->where('test.uid',$this->search['test']);
		}
		
    }
	
	
	
 	public function display()
	{
		//Get
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');
		}
        //Display
		$display = $this->input->post('sel_display');		
		if(isset($display)){    		
			$this->search['display'] = $display;
		}
		//Set
		$this->session->set('sess_search',$this->search);	
		$this->showlist();
	}
	
    public function delete($id)
    {				
        
    		$result_question = $this->payment_model->delete($id);
        	$json['status'] = $result_question?1:0;
			$json['mgs'] = $result_question?'':Kohana::lang('errormsg_lang.error_data_del');
			$json['user'] = array('id' => $id);
			echo json_encode($json);
            die();
    }
	
	
	
}
?>