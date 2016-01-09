<?php
class Answer_Model extends Model {
	
	protected $table_name = 'answer';
	protected $primary_key = 'uid';
	protected $random = 'random';
    protected $col_id = 'questionnaires_uid';
	public function get($id = '')
	{
		$this->db->select($this->table_name.'.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'ASC');
		$result = $this->db->get($this->table_name)->result_array(false);
        //
        if($id != '' && !is_array($id))
         {
		  	if(isset($result[0]))
			return $result[0];
			else return false;
		  }   
            
		return $result;
	}
   	public function get_questionnaires($id)
    {     
        $this->db->where($this->col_id,$id);
        $this->db->where($this->random,1);
        $this->db->orderby(NULL, 'RAND()');
		return $this->get();  
    }
    public function get_questionnaires_zero($id)
    {
        $this->db->where($this->col_id,$id);
        $this->db->where($this->random,0);
		return $this->get();   
    }
	public function getAnswerByQuestionId($questionnaires_uid){
		$this->db->select($this->table_name.'.*');
		$this->db->where('questionnaires_uid',$questionnaires_uid);
		$this->db->orderby($this->primary_key,'ASC');
		
		$result = $this->db->get($this->table_name)->result_array(false);
		return $result;
	}
	
	public function delete($id, $type='') {
		$col_id = 'questionnaires_uid';
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	
	public function getByColumn($comlumn = '',$value = '')
	{
		$this->db->select($this->table_name.'.*');
        
        if($value != '')
			$this->db->{is_array($value)?'in':'where'}($this->table_name.'.'.$comlumn, $value);
        
        $this->db->orderby($this->primary_key,'ASC');
		$result = $this->db->get($this->table_name)->result_array(false);
		return $result;
	}
	
	public function deleteByColumn($comlumn = '',$value = '',$type = '') {
	    $this->db->{is_array($value) ? 'in' : 'where'}($comlumn, $value);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	
}
?>