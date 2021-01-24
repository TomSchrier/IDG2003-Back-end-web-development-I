<?php

/* Original code from Lab 5 solutions (modified).
It reads each line from a CSV-file and extracts the header/keys in one array and the values in another. */
function readFromFile($file)
{
    $openFile = fopen($file, 'r')
        or die('Error: Could not open' . $file);

    $idx = 0;
    $firstLineExtracted = FALSE;
    while (!feof($openFile)) {
        // trim is used here to remove an line break in the last key that occurrs when using 'createAssocArray($headersArray, $valuesArray)'
        $line = trim(fgets($openFile));

        if ($firstLineExtracted == FALSE) {
            // extract first line as keys
            $keysArray = explode(';', $line);  // array with all the keys / headers
            $firstLineExtracted = TRUE;
            continue;
        }

        $lineArray = explode(';', $line);
        if (is_null($lineArray[1])) {
            // check if entry in array is null.
            continue;
        } else {
            $valueArray[$idx] = $lineArray;
            $idx++;
        }
    }
    fclose($openFile);

    return array('keysArray' => $keysArray, 'valuesArray' => $valueArray);
}

/* Original code from Lab 5 solutions.
This functions creates an Associative Arrayfrom two given arrays */
function createAssocArray($headersArray, $valuesArray)
{
    foreach ($valuesArray as $item => $value) {
        $idx = 0;
        foreach ($headersArray as $key) {
            $resArray[$item][$key] = $value[$idx];
            $idx++;
        }
    }

    return $resArray;
}

/* Original code from Lab 5 solutions.
The function creates an HTML table with appropriate headers and table data from a given associative array. */
function createTable($resArray)
{
    $isFirstRow = FALSE;

    echo "<table>";

    foreach ($resArray as $item) {

        if ($isFirstRow == FALSE) {
            // first print headers
            echo "<tr>";
            foreach ($item as $key => $value) {
                echo "<th>".ucfirst(str_replace("_"," ",$key))."</th>"; // formatting the table headers by removing "_" and making the first letter uppercase.
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

/* Original code by John Morris from 'shorturl.at/iAOR0' (Modified).
Loop through and get the values of our specified key - ends up as a small array only consisting of the key we want to sort by ($b). */
function sortByValue($array, $keyToSortBy)
{
    //original array is separated into key and value pairs
    foreach ($array as $key => $value) {
        $b[] = $value[$keyToSortBy];
    }

    arsort($b); //Sort an array in reverse order and maintain index association (important for the reconstruction of the array).

    //loop through our sorted array and reconstruct a complete sorted array that is ready to be displayed.
    foreach ($b as $key => $value) {
        $c[] = $array[$key];
    }

    return $c;
}