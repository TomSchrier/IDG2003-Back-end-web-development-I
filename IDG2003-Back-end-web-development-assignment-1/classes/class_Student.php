<?php
require_once 'functions.php';

class Student
{
    //Initial student properties
    public $student_number;
    public $first_name;
    public $last_name;
    public $birthdate;

    //Derived student properties
    public $courses_taken;
    public $number_of_courses_completed;
    public $number_of_courses_failed;
    public $GPA;
    public $status;

    /* ------------------------------ Student constructor method ------------------------------ */
    function __construct($student_number, $first_name, $last_name, $birthdate)
    {

        /* Check if $student_number is empty, if so, don't add it to the student database. 
        If an existing student number is detected it does not get added to the database. */
        if (empty($student_number) == FALSE) {
            $this->student_number = $student_number;
            $this->first_name = $first_name;
            $this->last_name = $last_name;
            $this->birthdate = $birthdate;

            $doesStudentExist = $this->checkStudentDatabase();

            if ($doesStudentExist == FALSE) {
                $this->populateStudentsDatabase();
            }
        }
    }

    /* ------------------------------ Setter methods ------------------------------ */
    function setCourses_taken($courses_takenArray)
    {
        if (empty($this->courses_taken)) {
            $this->courses_taken = array($courses_takenArray);
        } else {
            array_push($this->courses_taken, $courses_takenArray);
        }
    }

    function setNumber_of_courses_completed()
    {
        $this->number_of_courses_completed++;
    }

    function setNumber_of_courses_failed()
    {
        $this->number_of_courses_failed++;
    }

    function setGPA($GPA)
    {
        $this->GPA = $GPA;
    }

    function setStatus($status)
    {
        $this->status = $status;
    }

    /* ------------------------------ Other Student Methods ------------------------------ */

    /* This method prevents duplicates in the 'studentsDatabase.csv'. It is
    called in the constructor. */
    function checkStudentDatabase()
    {
        $dataArrays = readFromFile('studentsDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];

        $resultArray = createAssocArray($headersArray, $valuesArray);

        foreach ($resultArray as $item) {
            if ($item['Student number'] === $this->student_number) {
                return true;
            }
        }
    }

    /* This method converts the current student object to an array and implodes it. 
    This way it can be saved in 'studentsDatabase.csv'. */
    function populateStudentsDatabase()
    {
        $infoToSafe = ("\n" . implode(';', get_object_vars($this)));
        file_put_contents('studentsDatabase.csv', $infoToSafe, FILE_APPEND | LOCK_EX);
    }

    /* This function starts with reading 'coursesDatabase.csv' to compare the course codes a 
    student had taken against the 'coursesDatabase.csv'-file. If a match is found, the credits 
    from that course are assigned to the student. $totalCredits is counted and $totalWeightedCredits 
    is calculated with the help of 'convertGrade(). In the end, the relevant information is sent off 
    to the 'calculateGPA()' method. */
    function getCourseCredits()
    {
        $dataArrays = readFromFile('coursesDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];
        $coursesArray = createAssocArray($headersArray, $valuesArray);

        $totalCredits = 0;
        $totalWeightedCredits = 0;


        //$item = array inside student object
        //$item1 = array in course database
        foreach ($this->courses_taken as $studentItem) {
            foreach ($coursesArray as $courseItem) {

                if ($studentItem['Course code'] === $courseItem['Course code']) {
                    $totalCredits += $courseItem['Number of credits'];

                    $gradeAsNumber = $this->convertGrade($studentItem['Grade']);
                    $totalWeightedCredits += $gradeAsNumber * $courseItem['Number of credits'];

                    $this->calculateGPA($totalWeightedCredits, $totalCredits);
                }
            }
        }
    }

    /* This method reads 'courseTakenDatabase.csv' and compares the 
    current student number with each line in the database. If a match is 
    found, the relevant information about the course is saved as an array 
    in the '$courses_taken' property. */
    function retrieveCourseTaken()
    {
        $dataArrays = readFromFile('courseTakenDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];
        $courseTakenResultArray = createAssocArray($headersArray, $valuesArray);

        foreach ($courseTakenResultArray as $item) {
            if ($item['Student number'] === $this->student_number) {

                $courses_takenArray = array(
                    'Course code' => $item['Course code'],
                    'Course year' => $item['Course year'],
                    'Course semester' => $item['Course semester'],
                    'Grade' => $item['Grade']
                );

                $this->setCourses_taken($courses_takenArray);
            }
        }
    }

    /* This method calculates the students GPA and rounds it off to two decimals. 
    It sends the GPA to the 'calculate status()' method. */
    function calculateGPA($totalWeightedCredits, $totalCredits)
    {
        $GPA = round($totalWeightedCredits / $totalCredits, 2);
        $this->setGPA($GPA);

        $this->calculateStatus($this->GPA);
    }

    /* This method loops through each course a student has taken and checks 
    if the grade they got is an F or not. With this information, it assigns the 
    number of failed or completed courses to the corresponding property. */
    function countCoursesComplatedOrFailed()
    {
        $this->number_of_courses_failed = 0;
        $this->number_of_courses_completed = 0;

        foreach ($this->courses_taken as $item) {
            if ($item['Grade'] === 'F') {
                $this->setNumber_of_courses_failed();
            } else {
                $this->setNumber_of_courses_completed();
            }
        }
    }

    /* This method is a simple switch statement that returns the number 
    equivalent of a letter grade. (A = 5 -> F = 0) */
    function convertGrade($grade)
    {
        switch ($grade) {

            case 'A':
                return 5;
            case 'B':
                return 4;
            case 'C':
                return 3;
            case 'D':
                return 2;
            case 'E':
                return 1;
            case 'F':
                return 0;
        }
    }

    /*This method is a simple If, elseif statement. It takes the GPA and assigns
    a status to the student based on their GPA.*/
    function calculateStatus($GPA)
    {
        if ($GPA < 1.99) {
            $status = 'Unsatisfactory';
        } elseif ($GPA > 2 && $GPA < 2.99) {
            $status = 'Satisfactory';
        } elseif ($GPA > 3 && $GPA < 3.99) {
            $status = 'Honour';
        } elseif ($GPA >= 4 && $GPA <= 5) {
            $status = 'High honour';
        }

        $this->setStatus($status);
    }
}