<?php
class Lesson_Model extends Model {
	
	protected $table_name = 'lesson';
	protected $primary_key = 'id';
	
	public function get($id = ''){
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
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
        $form = array(
			'hd_id'               => '',
			'title'               => '',
			'id_lesson_pass'      => '',
			'percent_lesson_pass' => '',
			'id_test_pass'        => '',
			'percent_test_pass'   => '',
			'video_link'          => '',
			'pass'                => '',
			'id_courses'          => '',
			'type'                => '',
	    );
        return $form;
    }
	public function delete_lesson_by_courses_id($id){
		$this->db->where('id_courses',$id);
		$this->db->delete($this->table_name);
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
			$this->db->where($this->table_name.'.'.'id !=', $uid);
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false) ;   
        return $result;
	}
	
}
?>