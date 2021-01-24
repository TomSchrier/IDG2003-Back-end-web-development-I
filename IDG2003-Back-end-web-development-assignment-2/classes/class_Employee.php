<?php

require_once "class_User.php";

class Employee extends User
{

    //initial properties
    protected $employeeID;
    protected $name;
    protected $surname;

    //derived properties
    protected $coursesAssigned;

    function __construct($employeeID, $name, $surname)
    {

        $connection = $this->connect();

        $this->employeeID = $this->cleanVar($employeeID, $connection);
        $this->name = $this->cleanVar($name, $connection);
        $this->surname = $this->cleanVar($surname, $connection);

        $this->disconnect($connection);

        $isNewEmployee = $this->checkIfNewEmployee();
        if ($isNewEmployee) {
            //echo "New Employee!<br>";
            $this->createUser($this->name, $this->surname);
            $this->createEmployeeEntry();
        } else {
            //echo "Employee exists already!<br>";
        }
    }

    protected function checkIfNewEmployee()
    {
        $isNewEmployee = TRUE;
        $employeeArray = $this->readFromTable("employees");

        //echo "<pre>";
        //print_r($employeeArray);

        foreach ($employeeArray as $item) {
            if ($this->employeeID == $item['employeeid']) {
                $isNewEmployee = FALSE;
                //echo "Not New customer!<br>";
                break;
            }
        }
        return $isNewEmployee;
    }

    protected function createEmployeeEntry()
    {
        $connection = $this->connect();

        //query the database
        $query = "INSERT INTO employees(employeeid,username,name,surname)";
        $query .= "VALUES ('$this->employeeID','$this->username','$this->name','$this->surname')";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if (!$result) {
            die('Employee Creation failed!' . mysqli_error($connection));
        } else {
            echo "New Employee Created!<br>";
        }

        $this->disconnect($connection);
    }


    //--- Added by me --- 

    /* This public static method finds the employees employee-ID by searching 
    for it in the database using their username. The query returns an array with
    '[employeeid] => xzy'. We then only return the value. */
    public static function getEmplyeeID($username)
    {
        $connection = parent::connect();

        //query the database
        $query = "SELECT employeeid FROM employees WHERE username = '$username' ";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if (!$result) {
            die('Query failed!' . mysqli_error($connection));
        } else {
            //echo "Employee ID retrieved<br>";
        }
        $row = mysqli_fetch_assoc($result);
        parent::disconnect($connection);
        return $row['employeeid'];
    }

    /* This method finds all the courses the current employee instructs. It does 
    this by selecting the course code and course name from the 'courses' 
    table in our database where the employee is the same as the current employee. */
    function retrieveCoursesAssigned()
    {
        $connection = $this->connect();

        //query the database
        $query = "SELECT coursecode, coursename FROM `courses` WHERE employeeid = '$this->employeeID'";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if (!$result) {
            die('Query failed!' . mysqli_error($connection));
        } else {
            //TURE;
        }

        //read 1 row at a time
        $idx = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            //print_r($row);echo "<br>";
            $resArray[$idx] = $row;
            $idx++;
        }

        $this->disconnect($connection);
        return $resArray;
    }

    /* This public static method finds all the students that are assigned to a 
    course the employee instructs. It does this by selecting the students' 
    information from the 'students' table and joining it on the 'coursestaken' 
    table where the studentID is alike. Then it uses the course code from 
    each course the employee instructs. */
    public static function getStudentsinCourse($courseCode)
    {
        $connection = parent::connect();

        //query the database
        $query = "SELECT coursestaken.studentid, students.NAME, students.surname, coursestaken.grade FROM coursestaken INNER JOIN students ON coursestaken.studentid = students.studentid WHERE coursestaken.coursecode = '$courseCode' ORDER BY `students`.`NAME` ASC ";

        $result = mysqli_query($connection, $query);

        // printing error message in case of query failure
        if (!$result) {
            die('Query failed!' . mysqli_error($connection));
        }

        //read 1 row at a time
        $idx = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $resArray[$idx] = $row;
            $idx++;
        }

        parent::disconnect($connection);
        return $resArray;
    }

    /* This public static method uses an UPDATE query to update a grade for a 
    given student and course. */
    public static function updateStudentGrade($courseCode, $studentID, $newGrade)
    {
        if (!empty($courseCode)) {

            $connection = parent::connect();

            //query the database
            $query = "UPDATE `coursestaken` SET `grade` = '$newGrade' WHERE `coursestaken`.`studentid` = '$studentID' AND `coursestaken`.`coursecode` = '$courseCode'";

            $result = mysqli_query($connection, $query);

            if (!$result) {
                die('Query failed!' . mysqli_error($connection));
            }
            
            parent::disconnect($connection);
        }
    }
}
