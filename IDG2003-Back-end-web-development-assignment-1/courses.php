<?php
require_once 'functions.php';
require_once 'classes\class_Courses.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\stylesheet.css" media="screen" />
    <title>Courses</title>
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
    $dataArrays = readFromFile('coursesDatabase.csv');
    $headersArray = $dataArrays['keysArray'];
    $valuesArray = $dataArrays['valuesArray'];
    $resultArray = createAssocArray($headersArray, $valuesArray);

    $courses_counter = 0;

    foreach ($resultArray as $item) {
        $course_code = $item['Course code'];
        $course_name = $item['Course name'];
        $course_year = $item['Course year'];
        $course_semester = $item['Course semester'];
        $course_instrucor = $item['Instructor name'];
        $course_credits = $item['Number of credits'];

        $courseObj = new Course($course_code, $course_name, $course_year, $course_semester, $course_instrucor, $course_credits);

        $courses_counter++;
    };


    //-----Visible on the page-----
    echo '<h1>Courses</h1>';
    echo "<h2>Number of unique courses: ".$courses_counter."</h2>";
    echo '<p>If the table below is empty go to Upload and upload a CSV-file.</p>';
    $courseObj->displayCoursesInTable();
    ?>
</body>

</html>