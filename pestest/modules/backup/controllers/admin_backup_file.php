<?php
class Admin_backup_file_Controller extends Template_Controller {
	
	public $search;
	public $template = 'admin/index';
	public $backups='backups/';
    public function __construct()
    {
        parent::__construct();
        
       
    }
    
	public function __call($method, $arguments)
	{
		// Disable auto-rendering
		$this->auto_render = FALSE;
	}
	function _get_submit()
	{
		if($this->session->get('error_msg'))
		  $this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
		  $this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
		  $this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
		  $this->template->info_msg = $this->session->get('info_msg');
	}
	public function index()
    {	
        $this->template->content = new View('admin_backup_file/frm');
        $this->show_list();
    }
	public function addFolderToZip($dir, $zipArchive){
    if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
	
				//Add the directory
				$zipArchive->addEmptyDir($dir);
			   
				// Loop through all the files
				while (($file = readdir($dh)) !== false) {
			   
					//If it's a folder, run the function again!
					if(!is_file($dir . $file)){
						// Skip parent and root directories
						if( ($file !== ".") && ($file !== "..")){
							$this->addFolderToZip($dir . $file . "/", $zipArchive);
						}
					   
					}else{
						// Add the files
						$zipArchive->addFile($dir . $file);
					   
					}
				}
			}
		}
	}
    public function backup()
	{
		$this->template->content = new View('admin_backup_file/frm');
		$zipArchive = new ZipArchive();
		$file = 'Backup_'.date('m_d_Y_h_i_s').'.zip';
		$filename = $this->backups.$file;
		$zipArchive->open($filename, ZipArchive::CREATE);
		$dir = 'uploads/';
		$this->addFolderToZip($dir,$zipArchive);
		$this->session->set_flash('success_msg',''.$file.' was backup successfully');
		$filenam = explode('.',$file);
		url::redirect('admin_backup_file/complete/'.$filenam[0]);
		
		 
	}
	public function complete($filename)
	{
		$filename = $this->backups.$filename.'.zip';
		$this->template->content = new View('admin_backup_file/frm');
		$this->template->content->filename = $filename;
		$this->_get_submit();
	}
 	public function show_list()
	{
		$this->template->content = new View('admin_backup_file/list');
		$dirBackup = $this->backups;
		$dir='backups/';
		if(isset($dirBackup)){
			if ($dirhandle = opendir($dirBackup)) 
  			{ 
  				$i=0;
				while (false !== ($dirfile = readdir($dirhandle)))
				{
					
					$arrayFile[$i]['file'] = $dirfile;
					$arrayFile[$i]['time'] = filemtime($dir.$dirfile);
					 $i++;
				}
                 $arrayFile = $this->array_sort($arrayFile, 'time', SORT_DESC); 
				  $dirhead=false;
				  $this->template->content->arrayFile=($arrayFile);
			}
		}
		$this->_get_submit();
	}	
  public function restore($file)	
  {
		
			$zipfile = DOCROOT.$this->backups.$file.'.zip';
			$path=DOCROOT;
			$zip = new ZipArchive();
			if ($zip->open($zipfile) === TRUE)
				{
					if (!$zip->extractTo($path)){
			  			$this->session->set_flash('success_msg',''.$file.'.zip'.' was restore successfully'); 
			  		} else{
			  			
			  			$this->session->set_flash('success_msg',''.$file.'.zip'.' was restore successfully'); 
			  		}
					$zip->close();
				}
			url::redirect('admin_backup_file');
			die();
		
	}
	function delete($id)
	{
		//$this->template->content = new View('admin_backup_file/frm');
		if ($id)
		{
			 $id= $id.'.zip';
			 $upload_dir=$this->backups;
			 if(file_exists($upload_dir.$id)){
			 	if (unlink($upload_dir.$id))
				  $this->session->set_flash('success_msg',''.$id.' was removed successfully');
				 else
				  $this->session->set_flash('error_msg','Can not remove "'.$id.'"');
			 }
			 
			 /*if (unlink($upload_dir.$id))
			  $this->session->set_flash('success_msg',''.$id.' was removed successfully');
			 else
			  $this->session->set_flash('error_msg','Can not remove "'.$id.'"');*/
		  	
			url::redirect('admin_backup_file');
			die();
		}
			
	}
	public function array_sort($array, $on, $order=SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();
		
		if (count($array) > 0) {
		foreach ($array as $k => $v) {
		if (is_array($v)) {
		foreach ($v as $k2 => $v2) {
		if ($k2 == $on) {
			$sortable_array[$k] = $v2;
		}
		}
		} else {
		$sortable_array[$k] = $v;
		}
		}
		
		switch ($order) {
		case SORT_ASC:
		asort($sortable_array);
		break;
		case SORT_DESC:
		arsort($sortable_array);
		break;
		}
		
		foreach ($sortable_array as $k => $v) {
		$new_array[$k] = $array[$k];
		}
		}
		
		return $new_array;
	}
	public function deleteall()
	{
		$arr_id = $this->input->post('chk_id');
		$status = array();
		if(is_array($arr_id))
		{			
			for($i=0;$i<count($arr_id);$i++)
				{
						$id= $arr_id[$i].'.zip';
						 $upload_dir=$this->backups;
						 if(file_exists($upload_dir.$id)){
						 	if (unlink($upload_dir.$id))
							  $this->session->set_flash('success_msg',' Delete all file successfully');
							 else
							  $this->session->set_flash('error_msg','Can not remove');
						 }
				}
		} else {
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}		
	
		url::redirect('admin_backup_file');
	}
}
?>