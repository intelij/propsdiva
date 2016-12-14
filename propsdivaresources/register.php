<?php
include 'includes/dbconnect.php';
if(isset($_POST['submit'])){
	$username = htmlentities($_POST['username']);
	$email = htmlentities($_POST['email']);
	$password = htmlentities($_POST['password']);
	$enc_password = md5($password);

	try{
		$sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
			$sth = $dbh->prepare($sql);
			$sth->execute(array(':username' => $username, ':email' => $email, ':password' => $enc_password ));
	}catch(Exception $e){
		die("Oh noes! There's an error in the query!");
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
<label for="name">Username</label><br>
<input type="text" id="username" name="username" />
<br><br>
<label for="email">Email</label><br>
<input type="text" id="email" name="email" />
<br><br>
<label for="name">Password</label><br>
<input type="text" id="password" name="password" />
<br><br>
<input type="submit" name="submit" value="Submit"/>
</form>