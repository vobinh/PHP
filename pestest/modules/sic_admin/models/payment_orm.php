<?php
class Payment_ORM_Model extends ORM
{
	protected $table_name      = 'payment';
	protected $primary_key     = 'uid';
	protected $foreign_test    = 'payment.test_uid';
	protected $foreign_courses = 'payment.courses_id';
	protected $foreign_member  = 'payment.member_uid';
}
?>