<?php
session_start();
include_once('includes/class.users.php');
include_once('includes/functions.php');

if(isset($_SESSION['user']) && $_SESSION['user']->is_login){
	redirect('orders.php');
}


if(isset($_POST['submit'])){
	$username = trim(htmlentities($_POST['username']));
	$password = trim(htmlentities($_POST['password']));
	
	$aok = false;
	$message = '';
	if($username=='' || strlen($username) < 0){
		$message .= 'Please enter username.'.'<br>';
	}
	if($password=='' || strlen($password) < 0){
		$message .= 'Please enter username';
	}
	if($message == ''){		
		$aok = true;		
	}
	
	if($aok){		
		$u = new Users;
		$data = array(
			'username' => $username,
			'password' => md5($password)
			);
		$user = $u->is_exist($data);

		if($user){
			$user->is_login = true;
			$_SESSION['user'] = $user;
			redirect('orders.php');			
		}else{
			$message = 'Invalid username or password';
		}
	}


}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="caesarnay">

    <title>Propsdiva File Manager</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background-color:#FF5733; color:#fff;">

     <div class="container">
     	 <div class="row">
            <div class="col-lg-12">
				<h1>BATALLAMOS HASTA LA VICTORIA, SIEMPRE</h1>
                        	
            </div>
            
        </div>
        <div class="row">
        	<div class="col-lg-4" >

        		<?php echo isset($message)&&strlen($message)>0?'<div class="alert alert-danger">'.$message.'</div>':'';  ?>                               
            	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
				<div class="form-group">
				<label for="name">Username</label><br>
				<input class="form-control"  type="text" id="username" name="username" value="<?php echo isset($username)?$username:''; ?>" required/>
				</div>
				<div class="form-group">
				<label for="name">Password</label><br>
				<input class="form-control"  type="text" id="password" name="password" value="<?php echo isset($password)?$password:''; ?>" required/>
				</div>
				<input class="btn btn-default" type="submit" name="submit" value="Submit"/>
				</form>	
            </div>
        </div>				
	</div>
</body>
</html>