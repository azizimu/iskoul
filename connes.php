<?php
 class connec{
    private $host="localhost";
    private $username="root";
    private $password="";
    private $bd_name="iskoul";
 
 public $connection;
 public function getConnection(){
    $this -> connection = null;
    try{
        $this->connection = new PDO("mysql:host={$this->host}; dbname={$this-> bd_name}",  $this-> username , $this -> password);
        $this ->connection -> setAttribute(PDO:: ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "erreur de connection:" . $e->getMessage();
    }
    return $this ->connection ;
 }

 }
 


?>