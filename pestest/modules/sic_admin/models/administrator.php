<?php
class Administrator_Model extends Model
{
	protected $table_name = 'administrator';
	protected $primary_key = 'administrator_id';
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
        /*if ($type == 'admin') {
            $this->db->where($this->table_name . '_level<=', 2);
        }*/
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
	
	public function search($search)
	{
		if($search['keyword'] != '')
    		$this->db->like($this->table_name.'_username',$search['keyword']);		// WHERE LIKE query
	}
	public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_username' => '',	        	
	        'txt_pass' => '',
	        'txt_email' => '',
            'txt_fname' => '',
			'txt_lname' => '' ,
			'sel_status' => '' 
	    );
        return $form;
    }
	
	public function delete_email($email) {
       
        if ($email)
            $this->db->where('administrator_email',$email);
        return count($this->db->delete($this->table_name));
    }
	public function get_email($email){
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->where('administrator_email',$email);
		$query = $this->db->get();
		return $query->result_array(false);
	}
}
?>