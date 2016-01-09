<?php
class Payment_Model extends Model {
	protected $table_name      = 'payment';
	protected $primary_key     = 'uid';
	protected $foreign_test    = 'payment.test_uid';
	protected $foreign_courses = 'payment.courses_id';
	protected $foreign_member  = 'payment.member_uid';

	public function get($id = ''){
        $this->db->select($this->table_name.'.*');
        
        if($id != '')
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false);
        if($id != '' && !is_array($id) && !empty($result) )
        return $result[0];
        
        return $result;
       
	}
	
	public function getpayment($id='')
	{
		$table_test    = 'test';
		$table_courses = 'courses';
		$table_member  = 'member';
        $this->db->select($this->table_name.'.*',$table_courses.'.id as test_id' ,$this->table_name.'.payment_price as price',$table_courses.'.title',$table_courses.'.day_valid',$table_member.'.uid as member_id',$table_member.'.member_fname',$table_member.'.member_lname');
        if($id != '')
       		$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
		
        $this->db->join($table_courses,'courses.id',$this->foreign_courses);
		$this->db->join($table_member,'member.uid',$this->foreign_member);
		
        $this->db->orderby($this->primary_key,'desc');
        $result = $this->db->get($this->table_name)->result_array(false);
        
        if($id != '' && !is_array($id) && !empty($result) )
        	return $result[0];
        
        return $result;
       
	}
	
	public function get_frm(){
        $form = array
	    (
	    	
			
	    );
        return $form;
    }
	
	public function delete($id, $type='') {
        $this->db->{is_array($id) ? 'in' : 'where'}($this->primary_key, $id);
        if ($type)
            $this->db->delete($type);
        return count($this->db->delete($this->table_name));
    }
	public function search($search) {
        if (isset($search['type']) && !empty($search['type'])) {
            switch ($search['type']) {
                case 1:
					if(isset($search['sel_code']) && !empty($search['sel_code'])){
					$keyword = strtolower($this->db->escape('%' . $search['sel_code'] . '%'));
					$sql = "(LCASE(transaction_code) LIKE $keyword";
					$sql.= " OR LCASE(promotion_code) LIKE $keyword)";
					$this->db->where($sql);
					}
					if(!empty($search['start_date'])){
                    $this->db->where('payment_date >=',$search['start_date']);				
					$this->db->where('payment_date <=',$search['end_date']); 
					}
                    break;
                case 2:
                    $date_from = mktime(0,0,0,$search['sel_month'],1,$search['sel_year_month']);
           			$this->db->where('payment_date >=',$date_from);
					if ($search['sel_month']==12)
					{
						$y = $search['sel_year_month']+1;
						$m = 1;
					}else{
						$m = $search['sel_month']+1;
						$y = $search['sel_year_month'];
					}
					$date_to = mktime(0,0,0,$m,1,$y);
					$date_to =$date_to - 60;
					$this->db->where('payment_date <=',$date_to);
                    break;
                case 3:
					if ($search['sel_quarterly']==1)
					{
						$date_from = mktime(0,0,0,1,1,$search['sel_quarterly_y']);
						$date_to   = mktime(0,0,0,4,1,$search['sel_quarterly_y']);
						$date_to   = $date_to - 60;
					}
					
					if ($search['sel_quarterly']==2)
					{
						$date_from = mktime(0,0,0,4,1,$search['sel_quarterly_y']);
						$date_to   = mktime(0,0,0,7,1,$search['sel_quarterly_y']);
						$date_to   = $date_to - 60;
					}
					
					if ($search['sel_quarterly']==3)
					{
						$date_from = mktime(0,0,0,7,1,$search['sel_quarterly_y']);
						$date_to   = mktime(0,0,0,10,1,$search['sel_quarterly_y']);
						$date_to   = $date_to - 60;
					}
					
					if ($search['sel_quarterly']==4)
					{
						 $y = $search['sel_quarterly_y']+1;
						 $date_from = mktime(0,0,0,10,1,$search['sel_quarterly_y']);
						 $date_to   = mktime(0,0,0,1,1,$y);
						 $date_to   = $date_to - 60;
					}
					
					$this->db->where('payment_date >=',$date_from);				
					$this->db->where('payment_date <=',$date_to);  
					
                    break;
				 case 4:
                    $this->db->where('payment_date >=',$search['date_from']);				
					$this->db->where('payment_date <=',$search['date_to']);  
                    break;
            }
        }
    }
}
?>