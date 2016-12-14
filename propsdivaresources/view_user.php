<div class="row">
    <div class="col-lg-12">
		<h1>User Profile</h1>
		<p>
	    	<?php
	    	echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=edit&uid='.$data['user']->uid.'" type="button" class="btn btn-info btn-sm"><span class="fa fa-pencil"> edit </span></a> ';
			echo '<a href="'.$_SERVER['PHP_SELF'].'?cmd=delete&uid='.$data['user']->uid.'" type="button" class="btn btn-danger btn-sm"><span class="fa fa-trash-o"> delete </span></a> ';
			?>
    	</p>
    	<div class="table-responsive">
    		<table class="table table-hover">
    			<thead>
    				<th>id</th>
    				<th>username</th>
    				<th>email</th>
    				<th>role</th>
    				<th>status</th>
    			</thead>
    			<tbody>
    			 	<td><?php echo $data['user']->uid ?></td>
    			 	<td><?php echo $data['user']->username ?></td>
    			 	<td><?php echo $data['user']->email ?></td>
    			 	<td><?php echo $data['user']->role_name ?></td>
    			 	<td><?php echo $data['user']->status_name ?></td>
    			</tbody>
    		</table>
    	</div>
		
	
	</div>

</div>
