<?php
class State_orm_Model extends ORM {
	
	protected $table_name = 'state';
	protected $primary_key = 'state_id';
	protected $col_name = 'state_name';
	protected $sorting = array('state_id' => 'desc');
	
}
?>