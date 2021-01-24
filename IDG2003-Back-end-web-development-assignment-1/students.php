<?php
require_once 'functions.php';
require_once 'classes\class_Student.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\stylesheet.css" media="screen" />
    <title>Students</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="data.php">Upload</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
        </ul>
    </nav>

    <?php
    //Reading student database
    $dataArrays = readFromFile('studentsDatabase.csv');
    $headersArray = $dataArrays['keysArray'];
    $valuesArray = $dataArrays['valuesArray'];
    $resultArray = createAssocArray($headersArray, $valuesArray);

    $student_count = 0;

    /* Creating objects for each student -> used to find the derived properties
    that will be displayed later. Each student object needs these values so 
    that we can use them for comparison in other databases such as
    'courseTakenDatabase.csv'.*/
    foreach ($resultArray as $item) {
        $student_number = $item['Student number'];
        $first_name = $item['First name'];
        $last_name = $item['Last name'];
        $birthdate = $item['Birthdate'];

        $studentObj = new Student($student_number, $first_name, $last_name, $birthdate);

        //A simple counter that counts the amouts of times the loop runs, and thereby counting unique students.
        $student_count++;

        $studentObj->retrieveCourseTaken();
        $studentObj->countCoursesComplatedOrFailed();
        $studentObj->getCourseCredits();

        //Converts the object to an array and removes the 'coursesTaken' key as it won't be displayed.
        $studentAsArray = (get_object_vars($studentObj));
        unset($studentAsArray['courses_taken']);

        //'$arrayToDisplay' is all the information that we want to display. We push each student array into this one.
        if (empty($arrayToDisplay)) {
            $arrayToDisplay = array($studentAsArray);
        } else {
            array_push($arrayToDisplay, $studentAsArray);
        }
    }

    //This function sorts the array by a value of a key that we define. In our case the key is 'GPA'.
    $sortedByGPA = sortByValue($arrayToDisplay, 'GPA');


    /* ------------------------------ Things visible on the page ------------------------------ */
    echo '<h1>Students</h1>';
    echo "<h2>Number of unique students: " . $student_count . "</h2>";
    echo '<p>If the table below is empty go to Upload and upload a CSV-file.</p>';

    //Displaying the table with the relevant information in the relevant order on the page.
    createTable($sortedByGPA);
    ?>
</body>

</html>