<?php
class Tkadmin_Controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
		
		url::redirect('admin_account');
		die();
	}	
}
?>