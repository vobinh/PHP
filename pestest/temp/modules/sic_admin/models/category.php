<?php
class Category_Model extends Model
{
	protected $table_name = 'category';
	protected $primary_key = 'uid';
	  
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get($id = '')
	{
		$this->db->select($this->table_name.'.*');
		$this->db->select('test.test_title');
		if ($id != '')
		{
			$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
		}
		$this->db->join('test', array('test.uid' => $this->table_name.'.test_uid'),'','left');
		$result = $this->db->get($this->table_name)->result_array(false);
	
		if ($id != '' && !is_array($id) && isset($result[0])) return $result[0];
		elseif($id !='') return false;
		return $result;
	}
	
  
	
	public function account_exist($username, $pass, $type='') {
		 
        $this->db->where("(administrator_username = '".$username."' OR administrator_email = '".$username."')");
        $this->db->where($this->table_name . '_pass', $pass);
        if ($type == 'admin') {
            $this->db->where($this->table_name . '_level<=', 2);
        }
        $result = $this->db->get($this->table_name)->result_array(false);

        return (count($result) > 0 ? $result[0] : FALSE);
    }

    public function set_status($id, $status='') {
        $set = array($this->table_name . '_status' => $status);
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        return count($this->db->update($this->table_name, $set));
    }

    public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	 public function delete_email($email) {
       
        if ($email)
            $this->db->where('administrator_email',$email);
        return count($this->db->delete($this->table_name));
    }
	public function get_email($email)
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->where('administrator_email',$email);
		$query = $this->db->get();
		return $query->result_array(false);
	}
	public function search($search){
		if($search['keyword'])
    	{
			 $keyword = strtolower($search['keyword']);
			 switch ($search['option']) {
			   case 1:
                    $this->db->where("LCASE(category) LIKE '%" .$keyword. "%'");
					
                    break;
				case 2:
                    $this->db->where("LCASE(test_title) LIKE '%" .$keyword. "%'");
					
                    break;
				
			
			}
			
           // $this->db->where('LCASE(cus_username) LIKE'.$this->db->escape($keyword.'%'));
            //$this->db->orwhere('LCASE(cus_code) LIKE'.$this->db->escape($keyword.'%'));  
		}	
	}
	public function search1($search)
	{
		if($search['keyword'] != '')
    		$this->db->like($this->table_name.'_username',$search['keyword']);		// WHERE LIKE query
	}
	public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_cate' => '',
			'sel_parent_name' => '' ,	        	
	        /*'txt_state' => '',
	        'txt_indus' => '',
            'txt_branch' => '',
			'txt_subbranch' => '' ,
			'txt_subcate' => '' ,*/
			'sel_cate_per' => '' ,
			'sel_status' => '' ,
			'sel_test' => '' ,
	    );
        return $form;
    }
	
	public function getCategoryById($column, $id ,$uid='')
	{	
		$this->db->select($this->table_name.'.*');
        $this->db->where($this->table_name.'.'.$column, $id);
        if(isset($uid)&&$uid!='')
			$this->db->where($this->table_name.'.'.'uid =', $uid);
        $this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
	
}
?>