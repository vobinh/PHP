<?php
class Configuration_orm_Model extends ORM {
	
	protected $table_name = 'configuration';
	protected $primary_key = 'configuration_id';
    protected $col_key = 'configuration_key';
    protected $col_value = 'configuration_value';
    protected $sorting = array('configuration_id' => 'asc');

}
?>