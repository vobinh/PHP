<?php
class Articles_Model extends Model
{
	protected $table_name = 'articles';
	protected $primary_key = 'articles_id';
	public function get($id = '')
	{
		return $this->get_mod($id);
	}
	
    
	public function __construct()
	{
		parent::__construct();
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
			
                    $this->db->where("LCASE(articles_name) LIKE '%" .$keyword. "%'");
                  
			
           // $this->db->where('LCASE(cus_username) LIKE'.$this->db->escape($keyword.'%'));
            //$this->db->orwhere('LCASE(cus_code) LIKE'.$this->db->escape($keyword.'%'));  
		}	
	}

	public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_name' => '',	        	
	        'txt_content' => '',
			'sel_status' => '' 
	    );
        return $form;
    }
}
?>