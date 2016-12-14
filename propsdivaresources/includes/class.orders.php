<?php 
include_once 'class.models.php';
class Orders extends Models{
	private $name;
	private $pid;
	
	public function get_all(){
		try{
			$sql = "SELECT products.id as pid, products.name as pname, downloads.thumb as pthumb, products.created_on as pcreated FROM products LEFT JOIN downloads ON products.id = downloads.pid GROUP BY products.id";
			$sth = $this->dbh->prepare($sql);
			$sth->execute();					
			$results = $sth->fetchALL(PDO::FETCH_OBJ);		
			return $results;
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}
	}

	public function get($pid){
		//get product data
		$sql = "SELECT products.id as pid, products.name as pname FROM products WHERE products.id = (:id)";
		$sth = $this->dbh->prepare($sql);
		$sth->execute(array(':id' => $pid));
		$results = $sth->fetch(PDO::FETCH_OBJ);
		return $results;		
	}


	public function add(){
		$arg_list = func_get_args();
		if(array_key_exists('name', $arg_list['0'])){			
			$this->name = $arg_list[0]['name'];			
		}
		try{
			$sql = 'INSERT INTO products (name, created_on) VALUES (:name, NOW())';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':name' => $this->name));
			$lastId = $this->dbh->lastInsertId();
			return $lastId;			
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
	}


	public function edit($data){		
				
		$this->name = $data['name'];			
		$this->pid = $data['pid'];			
			
		try{
			$sql = 'UPDATE products SET name=(:name) WHERE id=(:id)';
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':name' => $this->name, ':id'=>$this->pid));
			$lastId = $this->dbh->lastInsertId();
			return true;			
		}catch(Exception $e){
			die("Oh noes! There's an error in the query!");
		}	
	}


	public function delete($pid){
					
		$this->pid = $pid;	
		

		//get the product
		$sql = "SELECT products.id as pid, products.name as pname  FROM products WHERE products.id = (:id)";
		$sth = $this->dbh->prepare($sql);
		$sth->execute(array(':id' => $this->pid));
		$num_rows = $sth->rowCount();

		if($num_rows == 1){
			$result = $sth->fetch(PDO::FETCH_OBJ);				

			$sql = "DELETE from products WHERE id=(:id)";
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':id' => $this->pid));			
			

			//get download files	
			$sql = "SELECT downloads.id FROM downloads WHERE pid = (:id)";
			$sth = $this->dbh->prepare($sql);
			$sth->execute(array(':id' => $this->pid));
			$num_rows = $sth->rowCount();
			
			
			if($num_rows > 0){
				$downloads = $sth->fetchALL(PDO::FETCH_OBJ);	
				foreach($downloads as $download){
					$this->removeFile($download->id);			
				}
			}
			
			
		}
	}
}


$test = new Orders;
print_r($test->get(40));

