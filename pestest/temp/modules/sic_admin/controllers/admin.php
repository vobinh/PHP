<?php
class Admin_Controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		url::redirect(url::base());
		die();
	}	
}
?>