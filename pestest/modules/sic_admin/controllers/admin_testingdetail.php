<?php
class Admin_testingdetail_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->testingdetail_model = new Testingdetail_Model(); 
		$this->questionnaires_model = new Questionnaires_Model(); 
		$this->answer_model   = new Answer_Model(); 
		
		parent::__construct();
		
	}
	
	public function index()
    {             
		$this->showlist();
		
		$this->search = array('keyword' => '');
        	
    }
	
	private function showlist()
    {
    	$this->template->content = new View('admin_testingdetail/list');
		
		$total_items = count($this->testingdetail_model->get());
		
        if(isset($this->search['display']) && $this->search['display']){
            if($this->search['display'] == 'all')
                $per_page = $total_items;
            else $per_page = $this->search['display']; 
		} else
            $per_page = $this->site['site_num_line2'];
	
	 	$this->pagination = new Pagination(array(
    		'base_url'       => 'admin_testingdetail/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $per_page,
		    'style'          => 'digg',
		));		
		$this->testingdetail_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		$this->where_sql();
		$mlist = $this->testingdetail_model->get();
		foreach($mlist as $key => $value){
			$mlist[$key]['answer']   = $this->answer_model->get($value['selected_answer']);
			$mlist[$key]['question']   = $this->questionnaires_model->get($value['questionnaires_uid']);
		}
		$this->template->content->set(array(
            'mlist' => $mlist
		));
    }
	
	public function search($id='')
	{
		if(isset($id)){
			$this->search = $id;	
		}
		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$keyword = $this->input->post('txt_keyword');
		if(isset($keyword))
			$this->search['keyword'] = $keyword;
		
		$this->session->set_flash('sess_search',$this->search);
		$this->showlist();
	}
	
	public function where_sql()
    {
		if($this->search['keyword'])
			$this->db->like('testing_code',$this->search['keyword']);
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
}
?>