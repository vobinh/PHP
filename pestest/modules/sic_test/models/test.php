<?php
class Test_Model extends Model {
	
	protected $table_name = 'test';
	protected $primary_key = 'uid';
	protected $col_name = 'test_description';
	
	public function get($id = ''){
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby('test_order','asc');
		$this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
	public function randdom(){
	   
	   $this->db->orderby(NULL,'RAND()');
	   return $this->get();
	}
	
	
	public function get_frm(){
        $form = array
	    (
	    	'hd_id' => '',
			'txt_title' => '',
			'erea_description'=>'',
			'txt_question' => '',	        	
	        'txt_time_value' => '',
			'txt_date' => '',
		    'txt_pass_score' => '',
			'txt_price' => '',
			'txt_questionpage' => '',
			'displatex' => '',
	        'sel_type_time' => '',
            'sel_status' => ''
			
	    );
        return $form;
    }
	
	public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	
	public function getTestById($column, $id='' ,$uid='')
	{	
		$this->db->select($this->table_name.'.*');
        $this->db->where($this->table_name.'.'.$column, $id);
        if(isset($uid))
			$this->db->where($this->table_name.'.'.'uid !=', $uid);
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
	
}
?>