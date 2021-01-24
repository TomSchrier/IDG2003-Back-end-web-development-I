<?php

require_once "class_Database.php";

class Course extends Database {
    //initial properties
    protected $courseCode;
    protected $courseName;
    protected $credits;
    
    //derived properties
    protected $employeeID;
    
    function __construct($courseCode,$courseName,$employeeID,$credits){
        $this->courseCode = $courseCode;
        $this->courseName = $courseName;
        $this->credits = $credits;
        $this->employeeID = $employeeID;
        
        $isNewCourse = $this->checkIfNewCourse();
        if($isNewCourse){
            echo "New Course!<br>";
            $this->createCourseEntry();
        }else{
            echo "Course Exists already!<br>";
        }
            
    }
    
    protected function checkIfNewCourse(){
        $isNewCourse = TRUE;
        $courseArray = $this->readFromTable("courses");
        
        echo"<pre>";
        print_r($courseArray);
        
        foreach ($courseArray as $item){
            if($this->courseCode == $item['coursecode']){
                $isNewCourse = FALSE;
                break;
            }
        }
        
        return $isNewCourse;
        
    }
    
    protected function createCourseEntry(){
        
        echo "Course : In createCourseEntry<br>";
        $connection = $this->connect();
        
        //query the database
        $query = "INSERT INTO courses(coursecode,coursename,employeeid,credits)";
        $query .= "VALUES ('$this->courseCode','$this->courseName','$this->employeeID',$this->credits)";
        
        echo $query;echo "<br>";
        
         $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if(!$result){
            die('Course Creation failed!' . mysqli_error($connection));
        }else {
            echo "New Course Created!<br>";
        }
        
        $this->disconnect($connection);
    }
    
    // use to update instructor
    public function setInstructor($employeeID){
        $this->employeeID = $employeeID;
    }
    
    
    
}






?>