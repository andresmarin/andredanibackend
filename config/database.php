<?php
class Database{
 
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "andrea_daniel";
    private $username = "root";
    private $password = "root";
	private $port = "8889";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
		if($this->port){
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            }else{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
			}
			$this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }

        
        print_r($this->conn);
 
        return $this->conn;
    }
}
?>
