<?php // superclass definition : Database
// contains Database related methods. 

class Database{
    
    protected function connect(){
        $host = 'localhost';
        $username = 'root';
        $password = 'root';
        $database = 'university';

        $connection = mysqli_connect($host,$username,$password,$database);

        if($connection){
           // echo "We are connected!<br>";
        }else {
            die ("Database connection failed");
        }
        return $connection;
    }
    
    protected function disconnect($connection){
        mysqli_close($connection);
    }
    
    protected function readFromTable($tableName){
        $connection = $this->connect();
        //query the database
        $query = "SELECT * FROM $tableName";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('Query failed!' . mysqli_error($connection));
        }else {
            //echo "Entries Retrieved!<br>";
        }

        //read 1 row at a time
        $idx = 0;
        while($row=mysqli_fetch_assoc($result)){
            //print_r($row);echo "<br>";
            $resArray[$idx] = $row;
            $idx++;
        }

        $this->disconnect($connection);
        return $resArray;

    }
    
    // sanitize input
    protected function cleanVar($var, $connection){
        $var = stripslashes($var);
        $var = htmlentities($var);
        $var = strip_tags($var);
        $var = mysqli_real_escape_string($connection, $var);
	    return $var;
    }
    
}
