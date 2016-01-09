<?php
class Configuration_Model extends Model {
	
	protected $table_name = 'configuration';
	protected $primary_key = 'configuration_id';
    protected $col_key = 'configuration_key';
    protected $col_value = 'configuration_value';
    protected $sorting = array('configuration_id' => 'asc');
	
	public function get($id = '')
	{
		$this->db->select('configuration.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('configuration.configuration_id', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('configuration')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
    
    public function get_value($key='')
    {
        $obj = ORM::factory('configuration_orm')->where('configuration_key', $key)->find();
        $id = $obj->configuration_value;
        $obj->clear();
        return $id;
        /*$this->db->where('configuration_key',$key);
        $mlist = $this->get();
        if(count($mlist)>0)
            return $mlist[0]['configuration_value'];
        else
            return false;*/
    }
    public function get_desc($key='')
    {
        $obj = ORM::factory('configuration_orm')->where('configuration_key', $key)->find()->as_array();
        return $obj['configuration_description'];
        
    }
    public function update_value($key='',$value='')
    {
        $record = array(
            'configuration_value' => $value
        );
        $condition = array(
            'configuration_key' => $key
        );
        return $this->db->update('configuration',$record,$condition);
    }
}
?>