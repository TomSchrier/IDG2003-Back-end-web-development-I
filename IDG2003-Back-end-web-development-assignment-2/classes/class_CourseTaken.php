<?php

require_once "class_Database.php";

class CourseTaken extends Database {


    //initial properties
    protected $studentID; // string
    protected $courseCode; //string
    protected $year; // integer
    protected $semester; //string
    protected $grade; //string
    
    function __construct($studentID,$courseCode,$year,$semester,$grade){
        $this->studentID = $studentID;
        $this->courseCode = $courseCode;
        $this->year = $year;
        $this->semester = $semester;
        $this->grade = $grade;
        
        $this->createCourseTakenEntry();
        
    }
    
    protected function createCourseTakenEntry(){
        $connection = $this->connect();
        
        //query the database
        $query = "INSERT INTO coursestaken(studentid,coursecode,year,semester,grade)";
        $query .= "VALUES ('$this->studentID','$this->courseCode',$this->year,'$this->semester','$this->grade')";
        
        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('CourseTaken Creation failed!' . mysqli_error($connection));
        }else {
            echo "New CourseTaken Created!<br>";
        }
        
        $this->disconnect($connection);
    }
    


}



?>
