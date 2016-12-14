<div class="row">
    <div class="col-lg-12">
         <h1 class="page-header"><?php echo $data['title']; ?></h1><div class="col-lg-12"> 
     </div>              
</div>
<div class="row">
    <div class="col-lg-12">         
         <a href="<?php echo $_SERVER['PHP_SELF'].'?cmd=add'; ?>" type="button" class="btn btn-success "><span class="fa fa-plus-circle"> ADD NEW </span></a>
         <p></p>
    </div>              
</div>
<div class="row">
    <div class="col-lg-12">
		<div class="panel panel-default">                        
		    <!-- /.panel-heading -->
		    <div class="panel-body">
		        <div class="dataTable_wrapper">
		            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
		                <thead>
		                    <tr>                                            
		                        <th>Username</th>
		                        <th>Email</th>
		                        <th>Role</th>
		                        <th>Status</th>
		                        <th>Last Login</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	<?php
		                	if(count($data['users']) > 0){						
								foreach($data['users'] as $user){
									echo '<tr>';												
									echo '<td>'.$user->username.'</td>';
									echo '<td>'.$user->email.'</td>';
									echo '<td>'.$user->role.'</td>';
									echo '<td>'.$user->status.'</td>';
									echo '<td>'.$user->last_login.'</td>';							
									echo '<td>';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=view&uid='.$user->uid.'" type="button" class="btn btn-success btn-xs"><span class="fa fa-check"> view </span></a> ';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=edit&uid='.$user->uid.'" type="button" class="btn btn-info btn-xs"><span class="fa fa-pencil"> edit </span></a> ';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=delete&uid='.$user->uid.'" type="button" class="btn btn-danger btn-xs"><span class="fa fa-trash-o"> delete </span></a>';
									echo '</td>';												
									//echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=view&pid='.$result->pid.'">'.$result->pname.'</a> ';
								}
							}else{
								echo 'There are no users';
							}
		                	?>                  
		                </tbody>
		            </table>
		        </div>
		        
		    </div>
		</div>
	</div>
</div>