<?php
class Data_template_Model extends Model {
	
	protected $table_name = 'data_template';
	protected $primary_key = 'configuration_id';
    protected $col_lang_id = 'languages_id';
    protected $col_key = 'configuration_key';
    protected $col_value = 'configuration_value';
    protected $sorting = array('configuration_id' => 'asc');
	
	public function get($id = '')
	{
		$this->db->select('data_template.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('data_template.configuration_id', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('data_template')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
    
    public function get_group()
	{
		$this->db->select('*');
        $this->db->orderby('configuration_group_title','asc');
		$result = $this->db->get('configuration_group')->result_array(false);
        return $result;
	}
    
    public function get_value($key='')
    {
        $obj = ORM::factory('data_template_orm')->where(array('configuration_key' => $key))->find();
        $id = $obj->configuration_value;
        return $id;
    }
    
    public function get_desc($key='',$lang='')
    {
        $obj = ORM::factory('data_template_orm')->where('configuration_key', $key)->find()->as_array();
        return $obj['configuration_description'];
        
    }
    
    public function update_value($key='',$value='',$lang='')
    {
        $record = array(
            'configuration_value' => $value
        );
        $condition = array(
            'configuration_key' => $key
        );
        return $this->db->update('data_template',$record,$condition);
    }
	public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_name' => '',	        	
	        'txt_content' => '',
	    );
        return $form;
    }
}
?>