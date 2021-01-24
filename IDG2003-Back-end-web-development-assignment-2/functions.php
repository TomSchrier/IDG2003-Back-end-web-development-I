<?php

// file handling functions

// function to read file and return headers and entries
function readThisFile($filename)
{
    //echo "In readThisFile <br>";

    $file = fopen($filename, "r") or die("Unable to open file");

    //Output one line until end-of-file
    $idx = 0;
    while (!feof($file)) {

        if ($idx == 0) {
            $headersArray = fgetcsv($file);
        } else {
            $line = fgetcsv($file);

            if (!(is_null($line[1]))) {
                $valuesArray[$idx - 1] = $line;
            }
        }

        $idx++;
    }

    fclose($file);

    return array(
        'headersArray' => $headersArray,
        'valuesArray' => $valuesArray
    );
}

function createAssocArray($headersArray, $valuesArray)
{
    // create an associative Array given headers and Values
    foreach ($valuesArray as $item => $value) {
        //print_r($item);print_r($value);echo "<br>";
        $idx = 0;
        foreach ($headersArray as $key) {
            $resArray[$item][$key] = $value[$idx];
            $idx++;
        }
    }

    return $resArray;
}

// Validate user entry. 
function validateInput($entry)
{
    // $entry takes in the whole $_GET array
    //echo "In Validate Input!<br>";
    $areFieldsEmpty = FALSE;
    $isDataOK = FALSE;

    //check if form was submitted
    if (array_key_exists('username', $entry)) {
        foreach ($entry as $key => $value) {
            $length = strlen($value);
            if ($length == 0) {
                $areFieldsEmpty = TRUE;
                break;
            }
        }

        if ($areFieldsEmpty == TRUE) {
            echo "Fields cannot be empty!<br>";
        } else {
            echo "Data is OK!<br>";
            $isDataOK = TRUE;
        }
    } else {
        echo "Array Empty!<br>";
    }

    return $isDataOK;
}

function getTime($str)
{
    $array = explode('/', $str);

    $month = $array[0];
    $date = $array[1];
    $year = $array[2];

    $unixTime = mktime(0, 0, 0, $month, $date, $year);

    return $unixTime;
}

// HTML functions

function createTable($resArray)
{
    echo "<table>";

    foreach ($resArray as $item) {

        if ($isFirstRow == FALSE) {
            // first print headers
            echo "<tr>";
            foreach ($item as $key => $value) {
                echo "<th> $key </th>";
            }
            echo "</tr>";

            //then print first row of values
            echo "<tr>";
            foreach ($item as $key => $value) {
                echo "<td> $value </td>";
            }
            echo "</tr>";

            $isFirstRow = TRUE;
        } else {
            // then print every subsequent row of values
            echo "<tr>";
            foreach ($item as $key => $value) {
                echo "<td> $value </td>";
            }
            echo "</tr>";
        }
    }
    echo "</table>";
}


// Session function --- (added by me)

/* This function unsets all the $_SESSION variable values, removes the 
session cookie from the browser, and then destroys the session. */
function endOfSession()
{
    session_unset();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
}

/* This function is supposed to check if the user is still the same. It checks if 
the session's IP and user agent still are the same as the servers; if not, 
the session is destroyed. */
function checkSessionAgentAndIP()
{
    if (
        $_SESSION['ipadress'] != $_SERVER['REMOTE_ADDR'] OR
        $_SESSION['useragent'] != $_SERVER['HTTP_USER_AGENT']
    ) {
        endOfSession();
    } 
}
