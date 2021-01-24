<?php

class CourseTaken
{
    //CourseTaken properties
    public $student_number;
    public $course_code;
    public $course_year;
    public $course_semester;
    public $grade;

    /* ------------------------------ CourseTaken constructor method ------------------------------ */
    function __construct($student_number, $course_code, $course_year, $course_semester, $grade)
    {
        $this->student_number = $student_number;
        $this->course_code = $course_code;
        $this->course_year = $course_year;
        $this->course_semester = $course_semester;
        $this->grade = $grade;

        $this->populateCourseTakenDatabase();
    }

    /* ------------------------------ Other CourseTaken methods ------------------------------ */
    function populateCourseTakenDatabase()
    {
        $infoToSafe = ("\n" . implode(';', get_object_vars($this)));
        file_put_contents('courseTakenDatabase.csv', $infoToSafe, FILE_APPEND | LOCK_EX);
        //bug: creates duplicates

    }

    function displayCoursesTakenInTable()
    {
        $dataArrays = readFromFile('courseTakenDatabase.csv');
        $headersArray = $dataArrays['keysArray'];
        $valuesArray = $dataArrays['valuesArray'];
        $resultArray = createAssocArray($headersArray, $valuesArray);
        createTable($resultArray);
    }
}
