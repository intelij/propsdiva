<?php
include 'config.php';
class Models{
	public $dbh;
 	public function __construct(){
 		global $db_host;
 		global $db_database;
 		global $db_user;
 		global $db_password;
 		try {
    		$this->dbh = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_password);
		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 	}


 	protected function removeFile($id){	
		//remove physical file
		try{
			$sql = 'SELECT file, thumb FROM downloads WHERE id=(:id)';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':id' => $id));		
			$num_rows = $sth->rowCount();
			if($num_rows > 0){
				$result = $sth->fetch(PDO::FETCH_OBJ);	
				unlink("uploads/".$result->file);
				unlink("uploads/".$result->thumb);
			}
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
		

		//remove from database
		try{
			$sql = 'DELETE FROM downloads WHERE id=(:id)';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':id' => $id));
			$message = 'The download file has been deleted successfully.';			
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
	}

}



