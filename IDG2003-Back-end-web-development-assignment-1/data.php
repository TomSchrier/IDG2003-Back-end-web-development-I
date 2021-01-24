<?php
require_once 'functions.php';
require_once 'classes\class_Student.php';
require_once 'classes\class_Courses.php';
require_once 'classes\class_CourseTaken.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css\stylesheet.css" media="screen" />
    <title>Upload data</title>
</head>

<body>
    <nav>
        <ul>
            <li><a href="data.php">Upload</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
        </ul>
    </nav>
    <h1>Upload a CSV-file</h1>
    <form action="data.php" method="POST" enctype="multipart/form-data">
        Select CSV-file to upload:
        <input type="file" name="file">
        <button type="submit" name="submit">Upload</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        //if the upload button is pressed: assign these variables to the uploaded file
        $fileName = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileError = $_FILES['file']['error'];


        //If there are no errors during upload: move the file to /uploads.
        if ($fileError === 0) {
            $fileDestination = 'uploads/' . $fileName;
            move_uploaded_file($fileTmpName, $fileDestination);

            echo "<br><b>File '" . $fileName . "' uploaded successfully. The data is displayed below:</b><br><br>";

            /* '$dataArrays' is now an array with two arrays in it.
            Make the first array the '$headersArray' array.
            Make second array the ' $valuesArray' array. */
            $dataArrays = readFromFile($fileDestination);
            $headersArray = $dataArrays['keysArray'];
            $valuesArray = $dataArrays['valuesArray'];


            //call function to create associative array consisting of the two arrays above
            $resultArray = createAssocArray($headersArray, $valuesArray);

            // create table to display content of associative array
            createTable($resultArray);

            //flag for detecting when a file has been uploaded
            $datauploaded = TRUE;
        } else {
            echo 'There was an error uploading your file.';
        }
    } else {
        echo 'No file selected.';
    }

    if ($datauploaded) {

        //For each line of the uploaded file -> create object for students, courses, and courses taken.
        //The constructor saves the information to the databases.
        foreach ($resultArray as $item) {
            //Student information
            $student_number = $item['Student number'];
            $first_name = $item['First name'];
            $last_name = $item['Last name'];
            $birthdate = $item['Birthdate'];

            $studentObj = new Student($student_number, $first_name, $last_name, $birthdate);

            //Course information
            $course_code = $item['Course code'];
            $course_name = $item['Course name'];
            $course_year = $item['Course year'];
            $course_semester = $item['Course semester'];
            $course_instrucor = $item['Instructor name'];
            $course_credits = $item['Number of credits'];

            $courseObj = new Course($course_code, $course_name, $course_year, $course_semester, $course_instrucor, $course_credits);

            //CourseTaken information
            $student_number = $item['Student number'];
            $course_code = $item['Course code'];
            $course_year = $item['Course year'];
            $course_semester = $item['Course semester'];
            $grade = $item['Grade'];

            $courseTakenObj = new CourseTaken($student_number, $course_code, $course_year, $course_semester, $grade);
        }
    }

    ?>
</body>

</html>