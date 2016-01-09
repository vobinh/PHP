<?php
class Promotion_Model extends Model {
	
	protected $table_name = 'promotion';
	protected $primary_key = 'uid';
	protected $col_name = 'test_id';
	
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
	
	public function get_frm(){
        $form = array
	    (
			'hd_id' => '',
			'txt_date' => '',
	    	'sel_test' => '',
			'txt_code' => '',
			'txt_company' => '',	        	
	        'txt_email' => '',
	        'txt_qty' => '',
            'txt_start' => '',
			'txt_end' => '',
			'erea_description' => '',
			'sel_status' => '',
			'chbdiscount' => '',
			'txt_promotion_price' => ''	
	    );
        return $form;
    }
	
	public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
}
?>