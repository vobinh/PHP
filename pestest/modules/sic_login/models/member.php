<?php
class Member_Model extends Model
{
	protected $table_name = 'member';
	protected $primary_key = 'uid';
	
    public function get($id = '')
	{
		if(!empty($id))
		return $this->get_mod($id);
		else{
		    $this->db->select($this->table_name.'.*');
			$this->db->orderby('member_fname','asc');
			$this->db->orderby('member_lname','asc');
		   $result = $this->db->get($this->table_name)->result_array(false);
		   return $result;
		}
	}
	
	public function cus_exist($username, $pass)
	{		
		$this->db->where('member_email', $username);
		$this->db->where('member_pw', $pass);
		//$this->db->where('status', 1);
		$result = $this->db->get($this->table_name)->result_array(false);
		
		if (count($result) > 0)
			return $result[0];
		return FALSE;
	}
	
	public function search($search)
	{
		if($search['keyword'])
    	{
			 $keyword = strtolower($search['keyword']);
			 switch ($search['option']) {
			   case 2:
                    $this->db->where("LCASE(member_email) LIKE '%" .$keyword. "%'");
                    break;
				case 1:
                    $this->db->where("LCASE(member_fname) LIKE '%" .$keyword. "%'");
					$this->db->orwhere("LCASE(member_lname) LIKE '%" .$keyword. "%'");
                    break;
			}
		}	
	}
	
	public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_email' => '',	        	
	        'txt_pass' => '',
	        'txt_comcontact_email' => '',
			'txt_comname' => '',
			'txt_comcontact_name' => '',
            'txt_fname' => '',
			'txt_lname' => '' ,
			'sel_status' => '' 
	    );
        return $form;
    }
	
	public function delete($id)
	{
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);
		
		return count($this->db->delete($this->table_name));
	}
	public function set_status($id, $status)
	{
		$set = array('status' => $status);
		
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);	
		
		return count($this->db->update($this->table_name, $set));
	}
}
?>