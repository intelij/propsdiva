<?php

try{	

	$file = $_GET["file"];

	$file_path = "uploads/".$file;
	

	if ( !file_exists($file_path) ) {
        throw new Exception('File not found.');
    }

    $fp = fopen($file_path, "r");    
      
    if ( !$fp ) {
		throw new Exception('File open failed.');
    }  


	header("Content-Disposition: attachment; filename=" . urlencode($file));   
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Description: File Transfer");            
	header("Content-Length: " . filesize($file_path));
	flush(); // this doesn't really matter.
	while (!feof($fp)){		
    	echo fread($fp, filesize($file_path));
    	flush(); // this is essential for large downloads
	} 
	fclose($fp); 
}catch (Exception $e) {
	echo 'Error: ' .$e->getMessage();
}

