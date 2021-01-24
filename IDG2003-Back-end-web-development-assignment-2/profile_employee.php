<?php
require "classes/class_Employee.php";
require "functions.php";
session_start();

/* Check if the user is logged in if so, regenerate the session ID, else display 
an alert and a link to the login.php page. */
if (isset($_SESSION['isloggedin'])) {
    session_regenerate_id();
} else {
    echo "<script type='text/javascript'>alert('You are are not logged in.');</script>;
        <a href='login.php' id='loginlink'>Go to log in page</a>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stylesheet.css" media="screen" />
    <title>Employee profile</title>
</head>

<body>
    <?php
    //--- Step 1 ---

    // Set session variables
    // $_SESSION['username'] = 'rajoy75';
    // $_SESSION['name'] = 'Raj';
    // $_SESSION['surname'] = 'Joye';
    // $_SESSION['isloggedin'] = TRUE;
    // $_SESSION['ipadress'] = $_SERVER['REMOTE_ADDR'];
    // $_SESSION['useragent'] = $_SERVER['HTTP_USER_AGENT'];
    ?>

    <?php
    //--- Step 2 ---

    /* This section assigns the values from the session to simple variables that
    are easier to read. These variables give us a tidier code further down.*/
    $employeeFirstName = $_SESSION['name'];
    $employeeSurname = $_SESSION['surname'];
    $employeeID = Employee::getEmplyeeID($_SESSION['username']);


    /* This section retrieves the courses the 
    employee instructs using the 'retrieveCoursesAssigned()' method.*/
    $employee = new Employee($employeeID, $employeeFirstName, $employeeSurname);
    $coursesAssigned = $employee->retrieveCoursesAssigned();


    //---Visable on the page---
    echo "<h1>Welcome, " . $employeeFirstName . " " . $employeeSurname . ".</h1>";
    echo "<h2>Your Employee ID is: " . $employeeID . "</h2>";

    echo "<h3>Instructor</h3>";
    echo "<p>You are the instructor for the courses displayed below:</p>";
    createTable($coursesAssigned);

    //--- Step 3 ---

    /* This code snippet retrieves all the students that are assigned to a course 
    that the employee instructs. It creates a table for each course and
    displays all the students in it. */
    echo "<h3>Your students</h3>";
    echo "<p>Below is an overview of all the students assigned to the courses you instruct</p>";

    foreach ($coursesAssigned as $item) {
        echo "<h4>" . $item['coursecode'] . " – " . $item['coursename'] . "</h4>";
        createTable(Employee::getStudentsinCourse($item['coursecode']));
    }

    //--- Step 4 ---
    echo "<h3>Edit grades</h3>";
    echo "<p>The forms below allow you to edit your student's grades for the courses you instruct.</p>";
    $idx = 0;
    foreach ($coursesAssigned as $item) {
        // form start
        echo    "<form method='POST'>
                <fieldset>
                <legend>$item[coursecode] – $item[coursename]</legend>";

        // course code (hidden from user)
        echo "<input type='hidden' name='courseCode' value='$item[coursecode]'>";

        // student
        echo    "<label for='students$idx'>Choose a student:</label>
                <select name='studentID' id='students$idx'>";

        // printing the list of students in the course
        foreach (Employee::getStudentsinCourse($item['coursecode']) as $item) {
            echo "<option value = '$item[studentid]'>" . $item['studentid'] . ": " . $item['NAME'] . " " . $item['surname'] . "</option>";
        }
        echo "</select>" . "<br>";

        //grade
        echo "<label for='grade$idx'>Choose a grade:</label>";

        echo "<select name='newGrade' id='grade$idx'>
                <option value = 'A'>A</option>
                <option value = 'B'>B</option>
                <option value = 'C'>C</option>
                <option value = 'D'>D</option>
                <option value = 'E'>E</option>
                <option value = 'F'>F</option>
                </select><br><br>";

        //submit button
        echo "<input type='submit' name='updateGrade' value='Update grade'>
                </fieldset>
                </form>";
        $idx++;
    }

    /* This If statement checks if the user has pressed the "Update grade" 
    button and passed the variables from $_POST to a MySQL query. Then, 
    the page is refreshed to display the changes made. */
    if (isset($_POST['updateGrade'])) {

        checkSessionAgentAndIP();

        $courseCode = $_POST['courseCode'];
        $studentID = $_POST['studentID'];
        $newGrade = $_POST['newGrade'];

        Employee::updateStudentGrade($courseCode, $studentID, $newGrade);
        header("Location: profile_employee.php");
    }
    ?>

    <!--  
    The rest of the code underneath is the logout button. If pressed, the 
    session details are unset, the session cookie is removed from the 
    browser, and the session is destroyed. Lastly, the user gets redirected to 
    the login page.
    -->
    <form method="POST">
        <input type="submit" name="logout" value="LOG OUT" id="logout" />
    </form>

    <?php
    if (isset($_POST['logout'])) {
        endOfSession();
        header("Location: login.php");
    }
    ?>

</body>

</html>