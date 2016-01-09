<?php
class Admin_checkupdate_Controller extends Template_Controller 
{	
	 public $template = 'admin/index';
    
    public function __construct()
    {
        parent::__construct();
        
      //  $this->_get_session();
    }
	 public function index(){
		 $this->update_version();
	 }
     public function history(){
		$this->template->content = new View('admin_checkupdate/list');
		$arr_version =array();
		$arr_content =array();
		$time='';
		$filename = "./version/version.txt";
		$version_n = 0;
			if(file_exists($filename)){
				if($handle = fopen($filename, "r")){
					$version_n = fgets($handle);
					fclose($handle);	
				}
			}
			else $version_n= 'ver2.0';
		$ver_host = 'http://www.pestest.com/update_version/';
		$str_tmp = file_get_contents($ver_host.'version.ini');
		eval($str_tmp);
		//get version
		foreach($a_ver as $i_ver){
			if($i_ver <= $version_n){
				$arr_version[]= $i_ver;
			}
		}
		//get content
		foreach($con_tent as $con){
			$arr_content[] =$con;
			
		}
		//get time
		$file_history = "./version/history_version.txt";
		
			if(file_exists($file_history)){
				if($handle = fopen($file_history, "r")){
					$time = fgets($handle);
					fclose($handle);	
				}
			}
		$this->mr['version']=$arr_version;
		$this->mr['content']=$arr_content;
		$this->mr['time']=$time;
        $this->template->content->mr = $this->mr;
	 }
	 public function update_version()
        {
			$server_name=$_SERVER['SERVER_NAME'];
			$server=explode('.',$server_name);
		     
			//conect fpt demo
			$conn_id = ftp_connect($server_name);
			$login_result = ftp_login($conn_id, 'tkpestest', '#nathan123#');
			
			//read file version cilent
			if(isset($server[2]) && !empty($server[2]))
			@ftp_chmod($conn_id, 0777,'./clients/'.$server[0].'/version');
			else 
			@ftp_chmod($conn_id, 0777,'./version');
			
			$filename = "./version/version.txt";
			
			$version_n = 0;
				if(file_exists($filename)){
					if($handle = fopen($filename, "r")){
						$version_n = fgets($handle);
						fclose($handle);	
					}
				}
			//read file version server
			$ver_host = 'http://www.pestest.com/update_version/';
			$str_tmp = file_get_contents($ver_host.'version.ini');
			eval($str_tmp);
		
			 
			if(!$version_n) $version_n = 'ver2.0.1';
			if($version_n >= $version_last) {
				
				if(isset($server[2]) && !empty($server[2]))
				@ftp_chmod($conn_id, 0755,'./clients/'.$server[0].'/version');
				else 
				@ftp_chmod($conn_id, 0755,'./version');
				
				echo("<script>alert('There are no update available!');window.location.href = location.protocol +'//'+location.host+'/'+'admin_home';</script>");
				
				
			}
			else{
				//download mod_upt
				//$mod_upt = file_get_contents($ver_host.'mod_upt.ini');
				//$file_content = $mod_upt;
				//write info
				/*
				if(!$handle = fopen('do_update.php', "w+")){
					
					echo "Cannot open file ($filename)";
					exit;
				
				}
				
				if (fwrite($handle, $file_content) === FALSE) {
					echo "Cannot write to file ($filename)";
					exit;
				}
				
				fclose($handle);
				*/
				
				//updating ...		
				foreach($a_ver as $i_ver){
	
					if($i_ver <= $version_n) continue;
					
						
					$version_path = $ver_host.$i_ver.'/';
					//print_r($version_path);die();
					$do_upt = file_get_contents($version_path.'do_upt.ini');
					
					eval($do_upt);
					//print_r($do_upt);die();
					
					//require_once('module/do_update.php');
				
					
					
					// Try to set read and write for owner and read for everybody else
					
					if(isset($server[2]) && !empty($server[2]))
					ftp_chmod($conn_id, 0777,'httpdocs/clients/'.$server[0].'/version');
					else 
					ftp_chmod($conn_id, 0777,'httpdocs/version');
				
					foreach ($a_fileupt as $file_name=>$a_desc) {
						$file_dest = $a_desc['path'];
						$str_image = "*.png *.gif *.pdf *.jpg *.doc *.docx *.txt *.html *.htm";
						$file_ext = strstr($file_name, '.');
						$file_name = $version_path.$file_name;
						$path_file_dest = explode('/',$file_dest);
						if(isset($server[2]) && !empty($server[2]))
						$path='httpdocs/clients/'.$server[0].'/';
						else
						$path='httpdocs/'; 
						for($i=1;$i<count($path_file_dest)-1;$i++){
							$path=$path.$path_file_dest[$i].'/';
						}
						
						ftp_chmod($conn_id, 0777, $path);
						
						if(strpos($str_image, $file_ext)){
							copy($file_name,$file_dest);
							ftp_chmod($conn_id, 0755, $path);
							continue;
						}
						
						//get info
						
						
						$sFile = file_get_contents($file_name);
						$file_content = $sFile;
					
					
						//write info
						if(isset($server[2]) && !empty($server[2]))
						$file_dest_demo='httpdocs/clients/'.$server[0].'/';
						else
						$file_dest_demo='httpdocs/'; 
						
						for($k=1;$k<count($path_file_dest);$k++){
							$file_dest_demo=$file_dest_demo.$path_file_dest[$k].'/';
						}
						
							//print_r($file_dest);die();
						if(!$handle = @fopen($file_dest, "w+")){
							@ftp_chmod($conn_id, 0777, $file_dest_demo);
							$handle = fopen($file_dest, "w+");
						}
						if (fwrite($handle, $file_content) === FALSE) {
							echo "Cannot write to file ($filename)";
							exit;
						}
						fclose($handle);
						@ftp_chmod($conn_id, 0755, $path);
						//ftp_chmod($conn_id, 0644, $file_dest_demo);
						
						
						
					}//foreach ($a_fileupt
					//save history
					   if(isset($server[2]) && !empty($server[2]))
						$file_ver = './clients/version/history_version.txt';
						else 
						$file_ver = './version/history_version.txt';
						
						if(file_exists($file_ver)){
							//ftp_chmod($conn_id, 0777,'./clients/'.$server[0].'/version/history_version.txt');
							if($handle = fopen($file_ver, "r")){
								$temp = fgets($handle);
								$file_content= $temp.','.time(); 
								fclose($handle);	
							}
							//ftp_chmod($conn_id, 0644,'./clients/'.$server[0].'/version/history_version.txt');
						}
						else{
							$file_content = time();
							
						}
						if(!$handle = @fopen($file_ver, 'w+')){	
						        if(isset($server[2]) && !empty($server[2]))	
								@ftp_chmod($conn_id, 0777,'httpdocs/clients/'.$server[0].'/version/history_version.txt');
								else 
								@ftp_chmod($conn_id, 0777,'httpdocs/version/history_version.txt');
								$handle = fopen($file_ver, "w+");	
							}
							fwrite($handle, $file_content);
							fclose($handle);
				}//for($i_ver 
				
				//save last version - last update
				if(isset($server[2]) && !empty($server[2]))
				@ftp_chmod($conn_id, 0777,'httpdocs/clients/'.$server[0].'/version');
				else
				@ftp_chmod($conn_id, 0777,'httpdocs/version');
				
				if(isset($server[2]) && !empty($server[2]))
				$filename = './clients/version/version.txt';
				else 
				$filename = './version/version.txt';
				
				$somecontent = $version_last;
				
				if(!$handle = @fopen($filename, 'w+')){	
				    if(isset($server[2]) && !empty($server[2]))
					@ftp_chmod($conn_id, 0777,'httpdocs/clients/'.$server[0].'/version/version.txt');
					else
					@ftp_chmod($conn_id, 0777,'httpdocs/version/version.txt');
					
					$handle = fopen($filename, "w+");	
				}
				fwrite($handle, $somecontent);
				fclose($handle);
				if(isset($server[2]) && !empty($server[2]))
				$filename = './clients/version/lastupdate.txt';
				else 
				$filename = './version/lastupdate.txt';
				$somecontent = time();
				if(!$handle = @fopen($filename, 'w+')){	
				    if(isset($server[2]) && !empty($server[2]))  	
					@ftp_chmod($conn_id, 0777,'httpdocs/clients/'.$server[0].'/version/lastupdate.txt');
					else 
					@ftp_chmod($conn_id, 0777,'httpdocs/version/version.txt');
					
					$handle = fopen($filename, "w+");	
				}
				fwrite($handle, $somecontent);
				fclose($handle);
				if(isset($server[2]) && !empty($server[2])) 
				 @ftp_chmod($conn_id, 0755,'httpdocs/clients/'.$server[0].'/version');
				else 
				 @ftp_chmod($conn_id, 0755,'httpdocs/version');
				 
				ftp_close($conn_id);
				
				//save version
				$str_version = substr($version_last,3,6);
				$data= array('cur_version'=>$str_version);
				$this->db->where('version_id > 0');
				$this->db->update('version',$data); 
				
				echo("<script>alert('Update successful!');window.location.href = location.protocol +'//'+location.host+'/'+'admin_home';</script>"); 
				
				
				
			}//end else
		
            
        }	   
}
?>