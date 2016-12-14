<?php
include_once 'includes/dbconnect.php';
include_once 'includes/functions.php';
$pid = isset($_GET['pid'])?$_GET['pid']:header('Location: orders.php');

$sql = "SELECT products.id as pid, products.name as pname FROM products\n"   
    . "WHERE\n"
    . "products.id = (:id)";
$sth = $dbh->prepare($sql);
$sth->execute(array(':id' => $pid));
$num_rows = $sth->rowCount();

if($num_rows > 0){
	$product = $sth->fetch();	
	

	$sql = "SELECT downloads.title as ftitle, downloads.file, downloads.thumb as fthumb, downloads.qty, downloads.material FROM downloads\n"    
	    . "WHERE\n"
	    . "pid = (:id)";
	$sth = $dbh->prepare($sql);
	$sth->execute(array(':id' => $pid));
	$num_rows = $sth->rowCount();

	if($num_rows > 0){
		$results = $sth->fetchALL(PDO::FETCH_OBJ);		
		$i = 0;
		foreach($results as $result){			
			$product['download'][$i]['title'] = $result->ftitle;
			$product['download'][$i]['file'] = $result->file;
			$product['download'][$i]['thumb'] = $result->fthumb;			
			$product['download'][$i]['qty'] = $result->qty;
			$product['download'][$i]['material'] = $result->material;
			$i++;
		
		}
	}
	

}

$thumb_url = host_url().'/uploads/';
$file_url = host_url().'/download.php?file=';


$to = "caesar821009@gmail.com";
$subject = "HTML email";



$message = '<html>';
$message .= '<head><title>order email</title></head>';
$message .= '<body bgcolor="#ebebeb">';
$message .= '<table align="center" width="700" border="0" cellpadding="5" cellspacing="1" style="border:1px solid black; background-color:#ffffff;">';
$message .= '<tr><td colspan="4"><p>Dear Printer </p><p>Print this</p></td></tr>';
$message .= '<tr><td>Title</td><td>Thumbnail</td><td>Download</td><td>material</td><td>qty</td></tr>';

foreach($product['download'] as $download){
	$message .= '<tr>';
	$message .= '<td>'.$download['title'].'</td>';
	$message .= '<td><img src="'.$thumb_url.$download['thumb'].'" width="200"/></td>';
	$message .= '<td><a href="'.$file_url.$download['file'].'">download</a></td>';
	$message .= '<td>'.$download['material'].'</td>';
	$message .= '<td>'.$download['qty'].'</td>';
	$message .= '</tr>';
}
$message .= '</table>';
$message .= '</body>';
$message .= '</html>';

//echo $message;
// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <no-reply@aptoscreations.com>' . "\r\n";


if(mail($to,$subject,$message,$headers)){
	echo 'mail sent';
}
?>