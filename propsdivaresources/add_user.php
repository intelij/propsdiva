<div class="row">
    <div class="col-lg-12">
         <h1 class="page-header"><?= $data['title']; ?></h1><div class="col-lg-12"> 
     </div>              
</div>
<div class="row">
    <div class="col-lg-4">
		<?= isset($data['message'])&&$data['message']!=''?$data['message']:''; ?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?cmd=add'; ?>" method="post" enctype="multipart/form-data">
			<div class="form-group"> 
				<label for="name">username:</label><br>
				<input class="form-control"  type="text" id="username" name="username" value="<?php echo isset($_POST['username'])?$_POST['username']:'';?>" required/>
			</div>
			<div class="form-group"> 
				<label for="email">email:</label><br>
				<input class="form-control"  type="text" id="email" name="email" value="<?php echo isset($_POST['email'])?$_POST['email']:'';?>" required/>
			</div>
			<div class="form-group"> 
				<label for="password">password:</label><br>
				<input class="form-control"  type="password" id="password" name="password" value="<?php echo isset($_POST['password'])?$_POST['password']:'';?>" required/>
			</div>
			<div class="form-group"> 
				<label for="password_confirm">confirm password:</label><br>
				<input class="form-control"  type="password" id="password_confirm" name="password_confirm" value="<?php echo isset($_POST['password_confirm'])?$_POST['password_confirm']:'';?>" required/>
			</div>			
			<fieldset class="form-group">
    			<label>Role</label>
    			<div class="form-check">
	      			<label class="form-check-label">
	        			<input type="radio" class="form-check-input" name="role" id="role" value="1" <?php echo (isset($_POST['role'])&&$_POST['role']==1)?'checked':'';?>>
	        			Admin
	     			 </label>
    			</div>
    			<div class="form-check">
	      			<label class="form-check-label">
	        			<input type="radio" class="form-check-input" name="role" id="role" value="2" <?php echo (isset($_POST['role'])&&$_POST['role']==2)?'checked':'';?>>
	        			Designer
	     			 </label>
    			</div>
    			<div class="form-check">
	      			<label class="form-check-label">
	        			<input type="radio" class="form-check-input" name="role" id="role" value="3" <?php echo (isset($_POST['role'])&&$_POST['role']==3)?'checked':'';?>>
	        			Supplier
	     			 </label>
    			</div>
    		</fieldset>
    		<div class="form-group"> 
	    			<label for="status">Status</label><br>
	    			<select class="form-control" name="status">	 				
						<option value="2" <?php echo (isset($_POST['status'])&&$_POST['status']==2)?'selected':'';?>>Pending</option>   				
	    				<option value="1" <?php echo (isset($_POST['status'])&&$_POST['status']==1)?'selected':'';?>>Active</option>	  					
	  					<option value="3" <?php echo (isset($_POST['status'])&&$_POST['status']==3)?'selected':'';?>>Suspended</option>
					</select>
				</div>
			<input class="btn btn-default" type="submit" name="submit" value="Submit"/>
		</form>
		<p>&nbsp;</p>
	</div>
</div>

