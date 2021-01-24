<?php //login page
session_start();
?>
<h1>Log in page</h1>
<form method="POST">
    <input type="submit" name="login" value="PRETEND TO LOG IN" />
</form>

<?php
if (isset($_POST['login'])) {
    $_SESSION['username'] = 'rajoy75';
    $_SESSION['name'] = 'Raj';
    $_SESSION['surname'] = 'Joye';
    $_SESSION['isloggedin'] = TRUE;
    $_SESSION['ipadress'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['useragent'] = $_SERVER['HTTP_USER_AGENT'];
    header("Location: profile_employee.php");
}

/*
In this page, the user should be able to login into their respective profile. 

Step 1 : 20% of the Grade
Here you should create a form for the user login. 
The form should have 2 input fields, 1 button and 1 checkbox:
1. username
2. password
3. Login button
4. Remember me checkbox

Upon clicking on the login button, the data should be sent to the backend for basic validation and the login process. 

Validation : Check that no fields are left empty. Else prompt the user to fill all the fields. 

Step 2 : 30% of the Grade

If the input data is ok, use the User class to login the user.
Essentially, you should add a method in the User class to Login the user. 
The method should compare the input password with the password stored in the database for this user with this specific username. 

Step 3 : 10% of the Grade
If the password matches, allow the user to login :
1. Display a successful message to inform the user he/she is now logged in. 
2. Display a link that redirects the user to his/her profile. 
3. Note that you should retrieve the role of the user also, and redirect them to the correct page, such as profile_admin.php, profile_student.php, profile_employee.php based on their role. 

Step 4 : 20% of the Grade
Additionally, start a session. Store the relevant parameters in the session, such as :
1. username
2. name
3. surname
4. loginStatus, should be TRUE if login was successful
5. ip address
6. useragent

Step 5 : 10% of the Grade
If the 'Remember Me' checkbox was checked, set a cookie that stores the login status of the user, such that if he/she closes the browser and restarts it, and open the login page, he/she would be automatically logged in, without having to enter his/her username and password. Ideally, the login form should be hidden. 
HINT: the cookie should store the same data as the session. 

Step 6: 10 % of the Grade
If the user is logged in, everytime they navigate to the login.php page, they should not see the login form. Only the display message that says that they are logged in and the link that would redirect them to their profile, should be visible.
HINT : use session to do this. 
You could also consider putting the login form in a function such as displayLoginForm() that readily displays the login form when called. 


Requirement for passing : 75 %
This DOES NOT mean that some steps may be excluded. All steps are mandatory, but I dont expect all of them to work flawlessly. 
*/
?>