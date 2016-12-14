<?php
include_once 'class.models.php';
class Downloads extends Models{
	private $id;
	private $title;
	private $file;
	private $thumb;
	private $qty;
	private $material;	
	private $pid;

	public function get_all($pid = null){
		if($pid == null){
			try{
				$sql = "SELECT downloads.id, downloads.title, downloads.file, downloads.thumb, downloads.qty, downloads.material FROM downloads";
	   			$sth = $this->dbh->prepare($sql);
				$sth->execute(array(':id' => $pid));			
				$results = $sth->fetchALL(PDO::FETCH_OBJ);		
				return $results;
				
			}catch(Exception $e){
				die("Oh noes! There's an error in the query!");
			}			
		}else{				//get downloads associate to product
			try{
				$sql = "SELECT downloads.id, downloads.title, downloads.file, downloads.thumb, downloads.qty, downloads.material FROM downloads\n"    
		    				. "WHERE\n"
		    				. "pid = (:id)";
				$sth = $this->dbh->prepare($sql);
				$sth->execute(array(':id' => $pid));
				$results = $sth->fetchALL(PDO::FETCH_OBJ);		
				return $results;
			}catch(Exception $e){
				die("Oh noes! There's an error in the query!");
			}

		}
		
	}

	
	public function add($data){		

		$this->title = $data['title'];
		$this->file = $data['file'];
		$this->thumb = $data['thumb'];
		$this->qty = $data['qty'];
		$this->material = $data['material'];
		$this->pid = $data['pid'];				
	
		try{
			$sql = 'INSERT INTO downloads (title, file, thumb, qty, material, pid, created_on) VALUES (:title, :file, :thumb, :qty, :material, :pid, NOW())';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':title' => $this->title, ':file' => $this->file, ':thumb' => $this->thumb, 'qty' => $this->qty, 'material' => $this->material, ':pid'=>$this->pid ));
			$lastId = $this->dbh->lastInsertId();
			return $lastId;		
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	

	}

	public function delete($id){
		try{
			$sql = 'SELECT file, thumb FROM downloads WHERE id=(:id)';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':id' => $id));		
			$num_rows = $sth->rowCount();
			if($num_rows > 0){
				$result = $sth->fetch(PDO::FETCH_OBJ);	
				if(unlink("uploads/".$result->file) && unlink("uploads/".$result->thumb)){
					//remove from database
					try{
						$sql = 'DELETE FROM downloads WHERE id=(:id)';
						$sth = $this->dbh->prepare($sql);
						$sth->execute(array(':id' => $id));
						return true;								
					}catch(Exception $e){
						die("Oh noes! There's an error in the query!");
					}	
				}				
			}
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}		

		
	}

	


}
