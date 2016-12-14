
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
		                        <th>Order number</th>
		                        <th>Thumbnail</th>
		                        <th>Created on</th>
		                        <th>Action</th>
		                    </tr>
		                </thead>
		                <tbody>
		                	<?php
		                	if(count($data['orders']) > 0){						
								foreach ($data['orders'] as $order) {
									echo '<tr>';												
									echo '<td>'.$order->pname.'</td>';
									echo '<td><img src="uploads/'.$order->pthumb.'" width="50" height="50"></td>';
									echo '<td>'.$order->pcreated.'</td>';
									echo '<td>';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=view&pid='.$order->pid.'" type="button" class="btn btn-success btn-xs"><span class="fa fa-check"> view </span></a> ';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=edit&pid='.$order->pid.'" type="button" class="btn btn-info btn-xs"><span class="fa fa-pencil"> edit </span></a> ';
									echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=delete&pid='.$order->pid.'" type="button" class="btn btn-danger btn-xs"><span class="fa fa-trash-o"> delete </span></a>';
									echo '</td>';												
									//echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=view&pid='.$result->pid.'">'.$result->pname.'</a> ';
								}
							}else{
								echo 'There are no products';
							}
		                	?>                  
		                </tbody>
		            </table>
		        </div>
		        
		    </div>
		</div>
	</div>
</div>