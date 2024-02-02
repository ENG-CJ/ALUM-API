<?php 

class Connections{

    public static function Connect(){
        $conn= new mysqli("localhost","root","","alumnidb");
        if($conn->connect_error)
          return $conn->error;
        
        return $conn;
    }

}



?>