<?php
class Questionnaires_Model extends Model {
	
	protected $table_name = 'questionnaires';
	protected $primary_key = 'uid';
	protected $col_name = 'question';
	
	public function get($id = '')
	{
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
	public function randdom()
	{
	   $this->db->where('status','Active');
	   $this->db->orderby(NULL,'RAND()');
	   return $this->get();
	}
	
	public function get_frm(){
        $form = array
	    (
	    	'hd_id' => '',
			'sel_category' => '',
			'sel_liststatus'=>'',
			'erea_question' => '',	        	
	        'erea_description' => '',
	        'erea_note' => '',
            'erea_note_extend' => '',
			'sel_test' => ''
			
	    );
        return $form;
    }
	
	public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	
	public function getQuestionById($column, $id )
	{	
		$this->db->select($this->table_name.'.*');
        $this->db->where($this->table_name.'.'.$column, $id);
        $this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
}
?>