<?php
class Payment_method_orm_Model extends ORM {
	
	protected $table_name = 'payments_method';
	protected $primary_key = 'payments_method_id';
	protected $col_name = 'payments_method_name';
	protected $col_sort_order = 'payments_method_sort_order';
	protected $sorting = array('payments_method_id' => 'desc');
	
}
?>