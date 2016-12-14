
<div class="row">
    <div class="col-lg-12">
         <h1 class="page-header"><?= $data['title']; ?></h1><div class="col-lg-12"> 
     </div>              
</div>
<div class="row">
    <div class="col-lg-4">
<?php echo isset($message)?$message:''; ?>
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="pid" value="<?= $data['order']->pid;  ?>">
<div class="form-group"> 
<label for="name">Product name:</label><br>
<input class="form-control"  type="text" id="name" name="name" value="<?php echo $data['order']->pname; ?>" />
</div>
<input class="btn btn-default" type="submit" name="submit" value="Submit"/>
</form>
</div></div>
