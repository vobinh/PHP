<?php
class Admin_home_Controller extends Template_Controller
{

	public $template = 'admin/index';	
	
    public function __construct()
    {
		$this->questionnaires_model = new Questionnaires_Model(); 
		$this->testing_model     = new Testing_Model();
		$this->author_model   = new Author_Model(); 
		$this->member_model     = new Member_Model();
		$this->test_model     = new Test_Model();
		$this->answer_model     = new Answer_Model();
		$this->category_model     = new Category_Model();
		$this->payment_model = new Payment_Model();
		parent::__construct();
		
		
	}
	
	
	public function index()
    {             
		$this->showlist();
    }
	
	private function showlist()
    {
    	$this->template->content = new View('admin_home/list');
		$this->db->where('status','Pending');
		$this->db->limit(10);
		$this->db->orderby('uid', 'DESC');
		$mlistquestion = $this->questionnaires_model->get();
		foreach($mlistquestion as $key => $value){
			$mlistquestion[$key]['answer']   = $this->answer_model->get($value['uid']);
			$mlistquestion[$key]['author']   = $this->getAuthor($value['created_by']);
			$mlistquestion[$key]['category'] = $this->category_model->get($value['category_uid']);
			$mlistquestion[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		
		$this->db->limit(10);
		$this->db->orderby('uid', 'DESC');
		$mlisttesting = $this->testing_model->get();
		foreach($mlisttesting as $key => $value){
			$mlisttesting[$key]['menber']   = $this->member_model->get($value['member_uid']);
			$mlisttesting[$key]['test']     = $this->test_model->get($value['test_uid']);
		}
		$this->db->limit(10);
		$this->db->orderby('uid', 'DESC');
		$mlistmenber = $this->member_model->get();
		/////////
		$this->db->limit(10);
		$this->db->orderby('uid', 'DESC');
		$mlistpayment = $this->payment_model->getpayment();
		
		////////// 
		
		$this->template->content->set(array(
		     'mlistpayment' => $mlistpayment,
            'mlistquestion' => $mlistquestion,
			'mlisttesting' => $mlisttesting,
			'mlistmenber' => $mlistmenber
		));
    }
	private function getAuthor($uid){
		return $this->author_model->getAuthorByQuestionId($uid);
	}
	
}
?>