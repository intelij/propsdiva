<?php 
include_once 'class.models.php';
class Users extends Models{
	private $uid;
	private $username;
	private $email;
	private $password;
	private $role;
	private $last_login;
	private $status;
	
	
	public function get_all(){
		try{
			$sql = "SELECT users.id as uid, users.username, users.email, user_roles.role, user_status.status, users.last_login FROM users LEFT JOIN user_roles ON users.role = user_roles.id LEFT JOIN user_status ON users.status = user_status.id";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();					
			$results = $sth->fetchALL(PDO::FETCH_OBJ);		
			return $results;
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}
	}

	public function get($id){		
		$sql = "SELECT users.id as uid, users.username, users.email, users.password, users.role, users.status, user_roles.role as role_name, user_status.status as status_name FROM users LEFT JOIN user_roles ON users.role = user_roles.id LEFT JOIN user_status ON users.status = user_status.id WHERE users.id = (:id)";
		$sth = $this->dbh->prepare($sql);
		$sth->execute(array(':id' => $id));
		$results = $sth->fetch(PDO::FETCH_OBJ);
		return $results;		
	}


	public function add($data){
				
		$this->username = $data['username'];
		$this->email = $data['email'];
		$this->password = md5($data['password']);
		$this->role = $data['role'];
		$this->status = $data['status'];
		
		try{
			$sql = 'INSERT INTO users (username, email, password, role, status, last_login) VALUES (:username,:email,:password,:role,:status, NOW())';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':username', $this->username);
			$sth->bindParam(':email', $this->email);
			$sth->bindParam(':password', $this->password);
			$sth->bindParam(':role', $this->role);
			$sth->bindParam(':status', $this->status);
			$sth->execute();
			$lastId = $this->dbh->lastInsertId();
			return $lastId;			
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
	}


	public function edit($data){	
		$this->uid = $data['uid'];
		$this->username = $data['username'];
		$this->email = $data['email'];
		$this->password = $data['password'];
		$this->role = $data['role'];
		$this->status = $data['status'];	

		try{
			$sql = 'UPDATE users SET username=(:username), email=(:email), password=(:password), role=(:role), status=(:status) WHERE id=(:uid)';
			$sth = $this->dbh->prepare($sql);
			$sth->bindParam(':username', $this->username);
			$sth->bindParam(':email', $this->email);
			$sth->bindParam(':password', $this->password);
			$sth->bindParam(':role', $this->role);
			$sth->bindParam(':status', $this->status);
			$sth->bindParam(':uid', $this->uid);
			$sth->execute();
			
			return true;			
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
	}


	public function delete($uid){	
		try{
			$sql = "DELETE from users WHERE id=(:uid)";
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':uid' => $uid));
			return true;
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}		
	}

	public function is_exist($data){
		$sql = "SELECT users.id, users.username, user_roles.role  FROM users LEFT JOIN user_roles ON users.role = user_roles.id  WHERE users.username = (:id) AND users.password=(:password) AND users.status = 1";
		$sth = $this->dbh->prepare($sql);
		$sth->execute(array(':id' => $data['username'], ':password'=>$data['password']));
		$results = $sth->fetch(PDO::FETCH_OBJ);
		return $results;
	}
}



