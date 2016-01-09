<?php
class Admin_Invoice_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->member_model = new Member_Model(); 
		$this->test_model = new Test_Model(); 
		$this->payment_model =new Payment_Model();
		parent::__construct();
		$this->_get_session_msg();
		
	}
	public function printInvoice($id) {
    $data = $this->payment_model->get($id);
	  if(!$data) {
	   echo "Not data";
	   die();
	  } else {
	   //echo $id;
	   $this->template = new View('admin_invoice/invoice',true);
	   $this->template->set(array(
		'test'=> $this->test_model->get($data['test_uid']),
		'member' => $this->member_model->get($data['member_uid']),
		'mr' => $data,
		//'mMaterial' => $this->material_incoming_rp_model->get($data['incoming_job_no'],1),
		//'mManufac' => $this->manufac_incoming_rp_model->get($data['incoming_job_no'],1),
		//'mCus_Incoming' => $this->cus_incoming_rq_model->get($data['incoming_job_no'],1)
	   ));   
	   
	   require Kohana::find_file('vendor/html2pdf','html2pdf');
	   $html2pdf = new HTML2PDF();
	   //$html2pdf->HTML2PDF('L','A4', 'en', array(10, 10, 10, 10));
	   $html2pdf->HTML2PDF('P','letter','en',  array(10, 10, 10, 10));
	   $html2pdf->WriteHTML($this->template,false);
	   echo $html2pdf->Output('Sales_Order_'.$data['uid'].'.pdf');
	  }
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
	
	public function index()
    {             
		
    }
	
	public function search()
	{
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
			$this->db->like('test_description',$this->search['keyword']);
    }
	
	function _get_submit()
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