<?php


//content type
header('Content-Type: image/png');

$base_url = "http://propsdiva.com/staging/wp-content/uploads/";


function getxmldata($pid){
	global $imageUrl;
	$array = array();
	$json = file_get_contents("data.json");
	$data = json_decode($json, true);
	
	foreach($data['products'] as $product){
		if($pid == $product['pid']){	
			
			$array['x'] = (int) $product['boxarea']['x'];
			$array['y'] =  (int) $product['boxarea']['y'];
			$array['width'] = (int) $product['boxarea']['width'];
			$array['height'] = (int) $product['boxarea']['height'];
			$array['textcolor'] = (string) $product['textcolor'];			
			$array['preview_image'] = (string) $product['preview'];
			break;		
		}else{
		
			$im = imagecreatefrompng($imageUrl);
			//return image
			imagepng($im);
			//frees any memory associated with image
			imagedestroy($im);die();
		}

	}
	return $array;
}

//font
$font = 'georgia.ttf';
//font size
$font_size = 8;
//image width

//text margin
$margin = 0;


 
//text
$text = (isset($_GET['msg']) && $_GET['msg']!='')?$_GET['msg']:"Customise Your Text Here";
$title = urldecode($_GET['title']);
$pid =  $_GET['pid'];
$imageUrl = urldecode($_GET['imgurl']);

$text = trim($text);
$title = trim(strtolower($title));


//boxarea
$boxArea = getxmldata($pid);
$width = $boxArea['width'];

//explode text by words
$text_a = explode(' ', $text);
$text_new = '';

/*
foreach($text_a as $word){
    //Create a new text, add the word, and calculate the parameters of the text
    $box = imagettfbbox($font_size, 0, $font, $text_new.' '.$word);

    //if the line fits to the specified width, then add the word with a space, if not then add word with new line
    if($box[2] > $width - $margin*2){
        $text_new .= "\n".$word;
     
    } else {
       $text_new .= " ".$word;
      
    }

}*/

$text_new = implode("\n",$text_a);


//trip spaces
$text_new = trim($text_new);
$lines = explode("\n", $text_new);


//$im = imagecreatefrompng('WP01_champagnebottleweb-01.png');
$im = imagecreatefrompng($base_url.$boxArea['preview_image']);


//create colors
if(trim($boxArea['textcolor']) == 'white'){

	$color = imagecolorallocate($im, 255, 255, 255);
}else{

	$color = imagecolorallocate($im, 0, 0, 0);
}
 
//add text to image
foreach($lines as $line){
	$testbox = imagettfbbox($font_size, 0, $font, $line);
	$line_width = $testbox[4]-$testbox[6];	
	$lpad = intval(($boxArea['width'] - $line_width)/2);
	imagettftext($im, $font_size, 0, $boxArea['x']+$lpad, $boxArea['y'], $color, $font, $line);
	$boxArea['y'] += 12;
}

 
//return image
imagepng($im);
//frees any memory associated with image
imagedestroy($im);

//$image = base64_encode(imagepng($im));
//echo json_encode(array('image'=>$image)); 