<?php session_start();?>
<?
	$contact_email ='sonata@techknowledge.vn';
	$email ='sonata@techknowledge.vn';
	$mailto = "$contact_email";
	$subject = "Delivery notification from Symprotek";
	$content = "Dear Trung Tran,<br><br>";
	$content .= "We have shipped the following items:<br><br>";
	$content .= "----------------------------------------------------------<br><br>";
	$content .= "P.O. No.:".$_SESSION['orderid']." <br>";
	$content .= "Invoice No.: <br>";
	$content .= "QTY: <br>";
	$content .= "Assembly No:  Rev.: <br>";
	$content .= "Description: <br>";
	$content .= "Received by: <br>";
	$content .= "Delivery Date: e<br>";
	$content .= "Delivery Time: <br><br>";
	$content .= "----------------------------------------------------------<br><br>";
	$content .= "Thank you!<br><br><a href='http://www.pestest.com'>Pestest com</a>";
	$header = "From: Symprotek Corporation <sonata.techknowledge@gmail.com>\n";	
	$header .= "Reply-To:$email\n";
	$header .= "MIME-Version:1.0\n";
	$header .= "Content-type: TEXT/HTML\n";
	mail("sonata.techknowledge@gmail.com",$subject,$content,$header);
	if(mail($mailto,$subject,$content,$header)) {
      
	}
?>