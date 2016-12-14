<div class="row">
    <div class="col-lg-12">
         <h1 class="page-header"><?= $data['title']; ?></h1><div class="col-lg-12"> 
     </div>              
</div>
<div class="row">
    <div class="col-lg-4">
		<?= isset($data['message'])?$data['message']:''; ?>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group"> 
				<label for="name">Order number:</label><br>
				<input class="form-control"  type="text" id="name" name="name" />
			</div>
			<input class="btn btn-default" type="submit" name="submit" value="Submit"/>
		</form>
	</div>
</div>

