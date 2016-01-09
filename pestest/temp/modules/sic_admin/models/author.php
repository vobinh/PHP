<?php
class Author_Model extends Model
{
	protected $table_name = 'author';
	protected $primary_key = 'uid';
	public function get($id = '')
	{
		return $this->get_mod($id);
	}
    
	public function __construct()
	{
		parent::__construct();
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
	public function search($search){
		if($search['keyword'])
    	{
			 $keyword = strtolower($search['keyword']);
			 switch ($search['option']) {
			   case 1:
                    $this->db->where("LCASE(fname) LIKE '%" .$keyword. "%'");
					$this->db->orwhere("LCASE(lname) LIKE '%" .$keyword. "%'");
					
                    break;
				case 2:
                    $this->db->where("LCASE(email) LIKE '%" .$keyword. "%'");
					
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
			'hd_id_author'=>'',
			'txt_username' => '',	        	
	        'txt_pass' => '',
	        'txt_email' => '',
            'txt_fname' => '',
			'txt_lname' => '' ,
			'sel_status' => '' 
	    );
        return $form;
    }
	public function getAuthorByQuestionId($uid){
		$this->db->select($this->table_name.'.*');
		$this->db->where('uid',$uid);
		$this->db->orderby($this->primary_key,'ASC');
		
		$result = $this->db->get($this->table_name)->result_array(false);
		return $result;
	}
}
?>