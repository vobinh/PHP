<?php
class Configuration_Model extends ORM {
	
	protected $table_name = 'configuration';
	protected $primary_key = 'configuration_id';
    protected $col_key = 'configuration_key';
    protected $col_value = 'configuration_value';
    protected $sorting = array('configuration_id' => 'asc');
	
	public function get()
	{		
		return $this->db->get($this->table_name)->result_array(false);
	}
    
    public function get_value($key='')
    {
        /*$obj = ORM::factory($this->table_name)->where($this->col_key, $key)->find();
        $id = $obj->configuration_value;
        $obj->clear();
        return $id;*/
        $this->db->where($this->col_key,$key);
        $mlist = $this->get();
        if(count($mlist)>0)
            return $mlist[0][$this->col_value];
        else
            return false;
    }
    
    public function update_value($key='',$value='')
    {
        $record = array(
            $this->col_value => $value
        );
        $condition = array(
            $this->col_key => $key
        );
        return $this->db->update($this->table_name,$record,$condition);
    }
}
?>