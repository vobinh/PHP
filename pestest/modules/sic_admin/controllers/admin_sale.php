<?php
class Admin_sale_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->payment_model = new Payment_Model();
		$this->member_model  = new Member_Model();
		$this->test_model    = new Test_Model();
		parent::__construct();
		$this->_get_session_msg();
		$this->search = array('type' => '','start_date' => '','end_date' => '','year' => '','sel_month' => 0,'sel_year_month' => '','sel_quarterly' => '','sel_quarterly_y' => '','date_from' => '','date_to' => '');
		
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
		
		if($this->session->get('sess_search'))
		  $this->session->delete('sess_search');
		$this->template->content = new View('admin_sale/list');
	    $this->_get_submit();		
    }
	
	public function _format_monthly($invoice)
	{
		$this->template->content = new View('admin_sale/list');
		$m= 0;
		$mlist = array();
		 for($i = 0; $i < count($invoice); $i++) {     	
        	if ($i==0)
				$date_tmp = $this->format_int_date($invoice[$i]['payment_date'],$this->site['site_short_date']);
        	 $date_tmp1 =    $this->format_int_date($invoice[$i]['payment_date'],$this->site['site_short_date']);
        	
        	if ($date_tmp!=$date_tmp1)
        	{
        		$date_tmp = $this->format_int_date($invoice[$i]['payment_date'],$this->site['site_short_date']);
        		$m +=1;
        	}
			
			$mlist[$m]['payment_date'] = $date_tmp1;
			if(isset($mlist[$m]['price']))
			$mlist[$m]['price'] += isset($invoice[$i]['price'])?$invoice[$i]['price']:0;
			else
			$mlist[$m]['price'] = isset($invoice[$i]['price'])?$invoice[$i]['price']:0;
		}
		$this->template->content->set(array(
            'mlist' => $mlist,
			'mr'=> $this->search
		));
		
	}
	public function _format_daily($invoice)
	{
		$this->template->content = new View('admin_sale/list');
		 $this->template->content->set(array(
            'mlist' => $invoice,
			'mr'=> $this->search
		));
	}
	public function _format_quarterly($invoice)
	{
		$this->template->content = new View('admin_sale/list');
		$m= 0;
		$mlist = array();
		for($i = 0; $i < count($invoice); $i++) {     	
			if ($i==0)
				$date_tmp =getdate($invoice[$i]['payment_date']);
 
			$date_tmp1 = getdate($invoice[$i]['payment_date']);
			
			if ($date_tmp['mon']!=$date_tmp1['mon'])
			{
				$date_tmp = getdate($invoice[$i]['payment_date']);
				$m +=1;
			}
				
			$mlist[$m]['month'] = $date_tmp1['mon'];
			
			$mlist[$m]['year'] = $date_tmp1['year'];
			
			if(isset($mlist[$m]['price']))
			 $mlist[$m]['price'] += isset($invoice[$i]['price'])?$invoice[$i]['price']:0;
			else
			  $mlist[$m]['price'] = isset($invoice[$i]['price'])?$invoice[$i]['price']:0;
		}
			
		 $this->template->content->set(array(
            'mlist' => $mlist,
			'mr'=> $this->search
		));
	}
	public function display()
    {
    	
		$this->payment_model->search($this->search);
		$mlist = $this->payment_model->getpayment();
		foreach($mlist as $key => $value){
			$mlist[$key]['member']   = $this->member_model->get($value['member_uid']);
			$mlist[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		return $mlist;
		
		
    }
	
	public function search($date="", $type=""){

		if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');	
		}
		$type_action     = $this->input->post('type_action');
		$sel_code        = $this->input->post('sel_code');
		$txt_date        = $this->input->post('txt_date');
		$sel_month       = $this->input->post('sel_month');
		$sel_year_month  = $this->input->post('sel_year_month');
		$sel_quarterly   = $this->input->post('sel_quarterly');
		$sel_quarterly_y = $this->input->post('sel_quarterly_y');
		$txt_date_from   = $this->input->post('txt_date_from');
		$txt_date_to     = $this->input->post('txt_date_to');

		if(!empty($type) && $type !='1'){
			$type_action = 2;
			$sel_month = $date;
			$sel_year_month = $type;
		}elseif(!empty($type) && $type =='1'){
		    $txt_date =  $this->format_int_date($date,$this->site['site_short_date']);
			$type_action = 1;
		}

		if(isset($type_action)){
			$this->search['type'] = $type_action;
            switch ($type_action) {
                case 1:
					$this->search['sel_code'] = $sel_code;
                    $this->search['start_date'] = $this->format_str_date($txt_date,$this->site['site_short_date']);
				    $this->search['end_date'] = $this->format_str_date($txt_date,$this->site['site_short_date'],'/',23,59,59);
					$this->search['sel_month'] = "";
					$this->search['sel_year_month'] = "";
					$this->search['sel_quarterly'] = "";
					$this->search['sel_quarterly_y'] = "";
					$this->search['date_from'] = "";
					$this->search['date_to'] = "";
					$this->session->set_flash('sess_search',$this->search);
					return $this->_format_daily($this->display());
                    break;
                case 2:
					$this->search['sel_month'] = $sel_month;
					$this->search['sel_year_month'] = $sel_year_month;
					$this->search['sel_quarterly'] = "";
					$this->search['sel_quarterly_y'] = "";
					$this->search['date_from'] = "";
					$this->search['date_to'] = "";
					$this->search['start_date'] ="";
				    $this->search['end_date'] ="";
					$this->session->set_flash('sess_search',$this->search);
		            return $this->_format_monthly($this->display());
                    break;
                case 3:
                    $this->search['sel_quarterly'] = $sel_quarterly;
					$this->search['sel_quarterly_y'] = $sel_quarterly_y;
					$this->search['sel_month'] = "";
					$this->search['sel_year_month'] = "";
					$this->search['date_from'] = "";
					$this->search['date_to'] = "";
					$this->search['start_date'] ="";
				    $this->search['end_date'] ="";
					$this->session->set_flash('sess_search',$this->search);
		            return $this->_format_quarterly($this->display());
                    break;
				case 4:
                    $this->search['sel_quarterly'] = "";
					$this->search['sel_quarterly_y'] = "";
					$this->search['sel_month'] = "";
					$this->search['sel_year_month'] = "";
					$this->search['date_from'] = $this->format_str_date($txt_date_from,$this->site['site_short_date']);
					$this->search['date_to'] =  $this->search['end_date'] = $this->format_str_date($txt_date_to,$this->site['site_short_date'],'/',23,59,59);
					$this->search['start_date'] ="";
				    $this->search['end_date'] ="";
					$this->session->set_flash('sess_search',$this->search);
		            return $this->_format_monthly($this->display());
                    break;
            }
        }
		die();
	}

    public function delete($id){				
		$result_question = $this->payment_model->delete($id);
		$json['status']  = $result_question?1:0;
		$json['mgs']     = $result_question?'':Kohana::lang('errormsg_lang.error_data_del');
		$json['user']    = array('id' => $id);
		echo json_encode($json);
        die();
    }
}
?>