<?php
class Model extends Model_Core
{
	protected $table_name;
	protected $primary_key;
	protected $order_key;
	public $result_query = '';
	
	public function __construct($dbconnection = '')
	{
		parent::__construct();
		
		if (!empty($dbconnection)) $this->db = new Database($dbconnection);		
	}
	
	/**
	 * Get data in table
	 * 
	 * @param $id null string | integer | array (primary key of table)
	 * @param $type_result arr | obj (type of result return)
	 *
	 * @access public
	 * @return array | object
	 **/
	public function get_mod($id = '', $type_result = 'arr')
	{
		if ($id != '')
			$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);
		
		$result = $this->db->get($this->table_name)->result_array($type_result=='arr' ? FALSE : TRUE);
		
		if ($type_result == 'arr')	// result return is array
		{
			if (count($result) ==1 && $id != '')	return $result[0];
		}
		
		$this->result_query = $result;
		return $result;
	}
	
	public function set_query($query_name, $field = '', $value)
	{
		if (is_array($value)) $str_value = implode(',',$value);
		
		if ($field === '')
			$this->db->{$query_name}($value);
		else
			$this->db->{$query_name}($field, $value);
	}
	
	public function order_mod($key = '', $order_type = 'asc')
	{
		if ($key != '')
			$this->db->orderby($key, $order_type);
		else
			$this->db->orderby($this->order_key, $order_type);
	}	 
	
	public function limit_mod($limit, $offet = '')
	{
		if ($offet == '')
			$this->db->limit($limit);
		else
			$this->db->limit($limit, $offet);		
	}
	
	public function update_mod($set, $where = '')
	{
		if ($where == '')
			$this->db->where($this->primary_key.' >= ', 0);	
			
		$result = $this->db->where($where)->update($this->table_name, $set);
		
		$this->result_query = $result;
		
		if (count($result) > 0)	return TRUE;
		return FALSE;
	}
	public function update_mod_pk($id, $set)
	{
		$this->db->where($this->primary_key, $id);
		
		$result = $this->db->update($this->table_name, $set);
		
		$this->result_query = $result;
		
		if (count($result) > 0)	return TRUE;
		return FALSE;
	}
	public function insert_mod($set)
	{		
		$result = $this->db->insert($this->table_name, $set);
		
		$this->result_query = $result;
		
		if (count($result) > 0)	return $result->insert_id();
		return FALSE;
	}
	
	public function delete_mod($where)
	{
		$result = $this->db->where($where)->delete($this->table_name);
		
		$this->result_query = $result;
		
		if (count($result) > 0)	return TRUE;
		return FALSE;
	}
	
	public function delete_mod_pk($id = '')
	{
		if ($id != '')
			$this->db->{is_string($id)?'in':'where'}($this->primary_key, $id);
		
		$result = $this->db->delete($this->table_name);
		
		$this->result_query = $result;
		
		if (count($result) > 0)	return TRUE;
		return FALSE;
	}
	
	public function get_rq()
	{
		return $this->result_query;
	}
    
    public function insert_new_order()
    {
        $result = $this->db->insert($this->table_name,array($this->order_key => 0));
        $this->db->update($this->table_name,array($this->order_key => $result->insert_id()), array($this->primary_key => $result->insert_id()));
        
        $this->result_query = $result;
    }
    
    /**
	 * Return value of any field has exist in table 
	 * 
	 * @param $field_name  name of field
	 * @param $field_value value of field     
	 *
	 * @access public
	 * @return boolean
	 **/
	public function field_value_exist($field_name, $field_value)
    {
    	$this->db->where($field_name, $field_value);
		
		return  ($this->db->count_records($this->table_name) > 0 ? TRUE : FALSE);
    }
    
    /**
	 * Change order position 
	 * 
	 * @param $id  integer primary key
	 * @param $order string asc | desc
     * @param $position string up | down
	 *
	 * @access public
	 * @return array | object
	 **/
    public function change_order($id, $order = '', $postion = '')
    {
        if ($order === '')   $order = 'asc';
        if ($postion === '') $postion = 'down';
        
        $cur_position = $this->get_mod($id);
        $position_bigger = $this->db->where($this->order_key.' > ', $cur_position[$this->order_key])->limit(1)->get($this->table_name)->result_array(false);
        $position_smaller = $this->db->where($this->order_key.' < ', $cur_position[$this->order_key])->limit(1)->orderby($this->order_key,'desc')->get($this->table_name)->result_array(false);
        
        if ($order == 'asc' && $postion == 'down')
        {
            $change_position = $position_bigger[0];
        }
        elseif ($order == 'asc' && $postion == 'up')
        {
            $change_position = $position_smaller[0];
        }
        elseif ($order == 'desc' && $postion == 'down')
        {
            $change_position = $position_smaller[0];
        }
        elseif ($order == 'desc' && $postion == 'up')
        {
            $change_position = $change_position[0];
        }
        
        // Change order postion
        $this->db->update($this->table_name, array($this->order_key => $change_position[$this->order_key]), array($this->primary_key => $cur_position[$this->primary_key]));
        
        $this->db->update($this->table_name, array($this->order_key => $cur_position[$this->order_key]), array($this->primary_key => $change_position[$this->primary_key]));
    }
}
?>