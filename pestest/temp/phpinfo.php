<?php 
echo date("F j, Y, g:i a"); 
$fp   =   fsockopen("www.google.com",   80,   &$errno,   &$errstr,   10);  // work fine
  if(!   $fp)  
      echo   "www.google.com -  $errstr   ($errno)<br>\n";  
  else  
      echo   "www.google.com -  ok<br>\n";

  
      $fp   =   fsockopen("smtp.gmail.com",   465,   &$errno,   &$errstr,   10);   // NOT work
  if(!   $fp)  
      echo   "smtp.gmail.com 465  -  $errstr   ($errno)<br>\n";  
  else  
      echo   "smtp.gmail.com 465 -  ok<br>\n";  
      
      
      $fp   =   fsockopen("smtp.gmail.com",   587,   &$errno,   &$errstr,   10);   // NOT work
  if(!   $fp)  
      echo   "smtp.gmail.com 587  -  $errstr   ($errno)<br>\n";  
  else  
      echo   "smtp.gmail.com 587 -  ok<br>\n";        

echo "<br />".phpinfo(); 


      ?>