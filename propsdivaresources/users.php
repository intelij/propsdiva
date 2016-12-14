<?php
session_start();


include 'includes/functions.php';
include 'includes/class.users.php';

if(!isset($_SESSION['user']) || !$_SESSION['user']->is_login){
	redirect('login.php');
}
?>
<?php render('includes/header');?>
<div id="page-wrapper">

<?php

$user = new Users;
$cmd = isset($_GET['cmd'])?$_GET['cmd']:'';
$uid = isset($_GET['uid'])?$_GET['uid']:'';

switch($cmd){
case 'view':
	if($uid == ''){
		render('users.php');
	}
	$data['user'] = $user->get($uid);	
	render('view_user',$data);
	break;
case 'add':
	$message = '';
	if(isset($_POST['submit'])){		
		$aok = false;

		$username = trim(htmlentities($_POST['username']));	
		$email = trim(htmlentities($_POST['email']));	
		$password = trim(htmlentities($_POST['password']));
		$password_confirm = trim(htmlentities($_POST['password_confirm']));
		$role = trim(htmlentities($_POST['role']));
		$status = trim(htmlentities($_POST['status']));

		
		if($username == '' || strlen($username) < 1){		
			$message .= 'Please enter  username'.'<br>';		
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		$message .=  'This email address is not valid.'.'<br>';
		}
		if($password == '' || strlen($password) < 1){		
			$message .= 'Please enter password'.'<br>';		
		}
		if($password_confirm == '' || strlen($password_confirm) < 1){		
			$message .= 'Please enter confirm password'.'<br>';		
		}
		if($password !=  $password_confirm){
			$message .= 'Please enter same passwords'.'<br>';	
		}
		if(!in_array($role, array(1,2,3))|| !in_array($status, array(1,2,3))){
			$message .= 'Don\'t cheat'.'<br>';	
		}
		if($message == ''){
			$aok = true;
		}else{
			$message = '<div class="alert alert-danger">'.$message.'</div>';
		}

		$param = array(
			'username'=> $username,
			'email' => $email,
			'password' => $password,
			'role'=> $role,
			'status' => $status
			);	

		if($aok){
			$lastId = $user->add($param);
			$message = '<div class="alert alert-success">';
			$message .= 'The user has been saved successfully.'.'<br>';
			$message .='View user <a href="'.$_SERVER['PHP_SELF'].'?cmd=view&pid='.$lastId.'">here</a>';	
			$message .= '</div>';
		}
	}


	$data['title'] = 'Add New User';
	$data['message'] = $message;
	render('add_user', $data);
	break;
case 'edit':

	$message = '';		

	if(isset($_POST['submit'])){
		$aok = false;
		
		$username = trim(htmlentities($_POST['username']));	
		$email = trim(htmlentities($_POST['email']));	
		$password = trim(htmlentities($_POST['password']));
		$password_confirm = trim(htmlentities($_POST['password_confirm']));
		$role = trim(htmlentities($_POST['role']));
		$status = trim(htmlentities($_POST['status']));
		$uid = trim(htmlentities($_POST['uid']));
		

		if($username == '' || strlen($username) < 1){		
			$message .= 'Please enter  username'.'<br>';		
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    		$message .=  'This email address is not valid.'.'<br>';
		}
		if($password == '' || strlen($password) < 1){		
			$message .= 'Please enter password'.'<br>';		
		}
		if($password_confirm == '' || strlen($password_confirm) < 1){		
			$message .= 'Please enter confirm password'.'<br>';		
		}
		if($password !=  $password_confirm){
			$message .= 'Please enter same passwords'.'<br>';	
		}
		if(!in_array($role, array(1,2,3))|| !in_array($status, array(1,2,3))){
			$message .= 'Don\'t cheat'.'<br>';	
		}
		if($message == ''){
			$aok = true;
		}else{
			$message = '<div class="alert alert-danger">'.$message.'</div>';
		}

		
		if(isset($_POST['update_password']) && $_POST['update_password'] == 1){
			$password = md5($password);
		}else{
			$data['user'] = $user->get($uid);	
			$password = $data['user']->password;
		}

		$param = array(
			'username'=> $username,
			'email' => $email,
			'password' => $password,
			'role'=> $role,
			'status' => $status,
			'uid'=>$uid
			);	

		if($aok){			
				
			if($user->edit($param)){
				redirect('users.php'.'?cmd=view&uid='.$uid);	
			}
		}

	}

	$data['user'] = $user->get($uid);	
	if($data['user']){
		$data['title'] = 'Edit User';
		$data['message'] = $message;
		render('edit_user',$data);	
	}else{
		redirect('users.php');
	}	
	break;
case 'delete':	
	if($uid == ''){
		render('users.php');
	}
	$user->delete($uid);	
	redirect('users.php');
	break;
default:
	$data['title'] = 'Users List';
 	$data['users'] = $user->get_all();
	render('list_users', $data);
}?>

</div>
<?php render('includes/footer'); ?>