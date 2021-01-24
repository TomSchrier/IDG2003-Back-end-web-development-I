<?php
require_once "class_User.php";

class Student extends User{
    //initial properties
    protected $studentID;
    protected $name; 
    protected $surname;
    protected $birthDate;
    
    function __construct($studentID, $name, $surname, $birthDate){
        
        $connection = $this->connect();

        $this->studentID = $this->cleanVar($studentID,$connection);
        $this->name = $this->cleanVar($name,$connection);
        $this->surname = $this->cleanVar($surname,$connection);
        $this->birthDate = $birthDate;
        
        $this->disconnect($connection);
        
        $isNewStudent = $this->checkIfNewStudent();
        if($isNewStudent){
            echo "New Student!<br>";
            $this->createUser($this->name,$this->surname);
            $this->createStudentEntry();
            
        }else {
            echo "Student exists already!<br>";
        }
    
        
    }
    
    protected function checkIfNewStudent(){
        $isNewStudent = TRUE;
        $studentArray = $this->readFromTable("students");
        
        echo "<pre>";
        print_r($studentArray);

        foreach ($studentArray as $item){
            if ($this->studentID == $item['studentid']){
                $isNewStudent = FALSE;
                //echo "Not New customer!<br>";
                break;
            }
        }    
        return $isNewStudent;        
    }
    
    protected function createStudentEntry(){
        $connection = $this->connect();
        
        //query the database
        $query = "INSERT INTO students(studentid,username,name,surname,birthdate)";
        $query .= "VALUES ('$this->studentID','$this->username','$this->name','$this->surname',$this->birthDate)";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('Student Creation failed!' . mysqli_error($connection));
        }else {
            echo "New Student Created!<br>";
        }
        
        $this->disconnect($connection);
    }
    
}




?>