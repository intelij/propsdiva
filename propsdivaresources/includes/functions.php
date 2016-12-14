<?php
include 'config.php';

function debug($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	return true;
}



function host_url(){
	global $site_url;	
	return $site_url;
} 

function uploadPdf(){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$message = '';

	

	// Check if file already exists
	if (file_exists($target_file)) {
	    //echo "Sorry, file already exists.";
	    //$uploadOk = 0;
	    
	    	$i = 1;
	    while(file_exists($target_file)){
	    	$target_file = $target_dir . basename($_FILES["file"]["name"], '.'.$imageFileType).'-'.$i.'.'.$imageFileType;
	    	$i++;
	    }
	    
	    
	}


	if($_FILES["file"]["name"] == ''){
		$message .= 'Upload a pdf file. ';
		$uploadOk = 0;
		return array(0, $message);
	}

	// Check file size
	//20MB max
	if ($_FILES["file"]["size"] > 20971520) {
	    $message .= "Sorry, your file is too large. ";	    
	    $uploadOk = 0;
	    return array(0, $message);
	}

	// Allow certain file formats
	if($imageFileType != "pdf") {
	    $message .=  "Sorry, only PDF files are allowed. ";	   
	    $uploadOk = 0;
	    return array(0, $message);
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    $message .= "Your file was not uploaded.";
	    return array(0, $message);
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
	        //echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
	        return array(1, basename($target_file));
	    } else {
	        $message .=  "Sorry, there was an error uploading your file.";
	       return array(0, $message);
	    }
	}

}



function uploadImg(){
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["thumb"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$message = '';

	if($_FILES["thumb"]["name"] == ''){
		$message .= 'Upload an image for thumbnail. ';
		$uploadOk = 0;
		return array(0, $message);
	}

	// Check if file already exists
	if (file_exists($target_file)) {
	    //echo "Sorry, file already exists.";
	    //$uploadOk = 0;
	    
	    	$i = 1;
	    while(file_exists($target_file)){
	    	$target_file = $target_dir . basename($_FILES["thumb"]["name"], '.'.$imageFileType).'-'.$i.'.'.$imageFileType;
	    	$i++;
	    }
	    
	    
	}

	// Check file size
	//20MB max
	if ($_FILES["thumb"]["size"] > 20971520) {
	    $message .= "Sorry, your file is too large. ";
	    $uploadOk = 0;
	    return array(0, $message);
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif") {
	    $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed. ";
	    $uploadOk = 0;
	    return array(0, $message);
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    $message .= "Sorry, your file was not uploaded.";
	    return array(0, $message);
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["thumb"]["tmp_name"], $target_file)) {
	        //echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
	        return array(1, basename($target_file));
	    } else {
	        $message .= "Sorry, there was an error uploading your file.";
	        return array(0, $message);
	    }
	}

}

function redirect($str){
	header('Location: '.host_url().'/'.$str);
}


function render($template, $data = null){
	$data;
	include($template.'.php');
}


