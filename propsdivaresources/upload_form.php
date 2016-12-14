<?php
include_once 'includes/class.downloads.php';


$download = new Downloads;

if(isset($_POST['submit'])){
	$message = '';
	
	$aok = false;

	$name = trim(htmlentities($_POST['name']));
	$qty = trim(htmlentities($_POST['qty']));
	$material = trim(htmlentities($_POST['material']));
	$pid = trim(htmlentities($_POST['pid']));


	if($name == '' || !ctype_alnum(str_replace(' ','',$name))){		
		$message .= 'Letters and numbers only'.'<br>';		
	}	
	if($qty == '' || !ctype_digit($qty)){
		$message .= 'Do not cheat'.'<br>';
	}
	if($qty == '' || !ctype_digit($qty)){
		$message .= 'numbers only'.'<br>';
	}
	if($material == '' || !ctype_alpha($material)){
		$message .= 'Letters only'.'<br>';		
	}
	
	$file = uploadPdf();		
	if($file[0] == 0){
		$message .= $file[1].'<br>';
	}


	$thumb = uploadImg();
	if($thumb[0] == 0){
		$message .= $thumb[1].'<br>';
	}
	
	if($message == ''){
		$aok = true;
	}else{
		$message = '<div class="alert alert-danger">'.$message.'</div>';
		
	}	

	
	if($aok){
		$data = array(
			'title' => $name,
			'file' => $file[1],
			'thumb' => $thumb[1],
			'qty' => $qty,
			'material' => $material,
			'pid' => $pid
			);
		
		if($download->add($data)){
			$_SESSION['success_message'] = 'The download file has been added successfully.';
			redirect('orders.php?cmd=view&pid='.$pid);
		}	
	}
}



?>


<h2>Add Download files</h2>
<?php echo isset($message)?$message:''; ?>	
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="pid" value="<?= $data['pid'];  ?>">
	<div class="form-group"> 
	<label for="name">Title</label><br>
	<input class="form-control" type="text" id="name" name="name" required/>
	</div>
	<div class="form-group"> 
	<label for="file">Upload PDF file</label><br>
	<input type="file" name="file" id="download" required>
	</div>
	<div class="form-group"> 
	<label for="thumb">Thumbnail Image</label><br>
	<input type="file" name="thumb" id="thumb" required>
	</div>
	<div class="form-group"> 
	<label for="qty">Quantity</label><br>
	<input type="number" name="qty" id="qty" min="1">
	</div>
	<div class="form-group">
	<label>Material</label><br>  	
	<label><input type="radio" name="material" value="pvc" required> PVC</label>
		<label><input type="radio" name="material" value="foam"> Foam</label>
	</div>
	<input class="btn btn-default" type="submit" name="submit" value="Submit"/>
</form>