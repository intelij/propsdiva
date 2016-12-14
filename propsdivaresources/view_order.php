<div class="row">
    <div class="col-lg-12">


	<div class="row">
	    <div class="col-lg-12">
			<h1>Order number: <?php echo $data['order']->pname; ?></h1>
			<p>
		    	<?php
		    	echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=edit&pid='.$data['order']->pid.'" type="button" class="btn btn-info btn-sm"><span class="fa fa-pencil"> edit </span></a> ';
				echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=delete&pid='.$data['order']->pid.'" type="button" class="btn btn-danger btn-sm"><span class="fa fa-trash-o"> delete </span></a> ';
				echo '<a href="mail.php?pid='.$data['order']->pid.'" type="button" class="btn btn-info btn-sm" target="_blank"><span class="fa fa-email"> email this </span></a>';

		    	?>
	    	</p>
		</div>
	</div>
	<div class="row">
	    <div class="col-lg-6">
	    	
	    	
	    	<h2>Downloads</h2>
	<?= isset($_SESSION['success_message'])?'<div class="alert alert-success">'.$_SESSION['success_message'].'</div>':''; ?>   	
	<?php unset($_SESSION['success_message']);?>
	<?php 
	
	//get download files
	if(isset($data['order']->downloads)){		
		foreach($data['order']->downloads as $download){
			
			$download_link = '<a type="button" class="btn btn-success btn-xs" href="download.php?file='.$download->file.'" ><span class="fa fa-download"> download </span></a> ';
			$download_link .= '<a type="button" class="btn btn-danger btn-xs" href="'.$_SERVER['PHP_SELF'].'?cmd=view&rmf=1&fid='.$download->id.'&pid='.$data['order']->pid.'"><span class="fa fa-trash-o"> delete </span></a>';
			$download_link .= '<br>';

			echo '<h3>'.strtoupper($download->title).'</h3>';
			echo '<p><strong>Qty:</strong> '.$download->qty.' <strong>Material:</strong> '.$download->material.'</p>';
			echo '<img src="uploads/'.$download->thumb.'" width="200"/>';	
			echo '<p></p>';
			echo '<p>'.$download_link.'</p>';	
		}
		
	}else{
		echo 'NO FILE';
	}?>
	   
	    </div>
	    <div class="col-lg-6">	
	    		    	    	
			<?php 
			$data['pid'] = $data['order']->pid;
			render('upload_form',$data); 
			?>
		</div>
    </div>
</div>

</div>
