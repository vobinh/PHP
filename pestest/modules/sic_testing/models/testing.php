<?php
class Testing_Model extends Model {
	
	protected $table_name = 'testing';
	protected $primary_key = 'uid';
	protected $col_name = 'test_uid';
	
	protected $tbmember = 'member';
	protected $tbtest = 'test';
	
	
	public function get($id = '')
	{
		$select ='';
		if(isset($this->tbmember) && isset($this->tbtest) ) 
		{	
				$select .= ", member_fname , member_lname , test_title";
		}
        $this->db->select($this->table_name.'.*'.$select);
		if(isset($this->tbmember) && isset($this->tbtest)){
			$this->db->join($this->tbmember, array($this->tbmember.'.uid' => $this->table_name.'.member_uid'));
			$this->db->join($this->tbtest, array($this->tbtest.'.uid' => $this->table_name.'.test_uid'));
		}
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
	
	public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	
	public function get_code($date) {
		$this->db->select($this->table_name.'.testing_code')
			->where(array('testing_date >=' => $date))
			->where(array('testing_date <' => ($date+86400)))
			->orderby($this->primary_key,'desc');
		$result = $this->db->get($this->table_name)->result_array(false);
		if(count($result) <= 0) {
			$number =  0;
		}
		else {
			$number = count($result);
		}
		$number += 1;
		return $number;
	}
	
	public function getTestingById($column, $id)
	{	
		$this->db->select($this->table_name.'.*');
        $this->db->where($this->table_name.'.'.$column, $id);
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
	
	public function getTestingByChart($column, $id)
	{	
		$this->db->select($this->table_name.'.*');
        $this->db->where($this->table_name.'.'.$column, $id);
        $this->db->orderby($this->primary_key,'asc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
}
?>