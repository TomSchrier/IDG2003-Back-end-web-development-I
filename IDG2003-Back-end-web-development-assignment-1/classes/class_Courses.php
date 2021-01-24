<?php
require_once 'functions.php';

class Course
{
    //Course properties
    public $course_code;
    public $course_name;
    public $course_year;
    public $course_semester;
    public $course_instrucor;
    public $course_credits;

    /* ------------------------------ Course constructor method ------------------------------ */
    function __construct($course_code, $course_name, $course_year, $course_semester, $course_instrucor, $course_credits)
    {
        /* Check if $course_code is empty, if so, don't add it to the course database. 
        If an existing course code is detected it does not get added to the database. */
        if (empty($course_code) == FALSE) {
            $this->course_code = $course_code;
            $this->course_name = $course_name;
            $this->course_year = $course_year;
            $this->course_semester = $course_semester;
            $this->course_instrucor = $course_instrucor;
            $this->course_credits = $course_credits;

            $doesCourseExist = $this->checkCourseDatabase();

            if ($doesCourseExist == FALSE) {
                $this->populateCourseDatabase();
            }
        }
    }

    /* ------------------------------ Other Course Methods ------------------------------ */
    function checkCourseDatabase()
    {
        $dataArrays = readFromFile('coursesDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];

        $resultArray = createAssocArray($headersArray, $valuesArray);

        foreach ($resultArray as $item) {
            if ($item['Course code'] === $this->course_code) {
                return true;
            }
        }
    }

    /* This function converts the current course object to an array and implodes it. 
    This way it can be saved in 'coursesDatabase.csv'. */
    function populateCourseDatabase()
    {
        $infoToSafe = ("\n" . implode(';', get_object_vars($this)));
        file_put_contents('coursesDatabase.csv', $infoToSafe, FILE_APPEND | LOCK_EX);
    }

    /* This function is a collection of functions from functions.php. 
    It creates an associative array from 'coursesDatabase.csv' and displays the result in a table sorted by course code */
    function displayCoursesInTable()
    {
        $dataArrays = readFromFile('coursesDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];
        $resultArray = createAssocArray($headersArray, $valuesArray);
        asort($resultArray);
        createTable($resultArray);
    }
}