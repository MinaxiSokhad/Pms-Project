<?php
include "includes/database.php";
$file = fopen("file.txt", "w");

$studentQuery = "SELECT * FROM students";
$result = mysqli_query($conn, $studentQuery);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    // $num = mysqli_num_fields($result);
    $last = sizeof($row) - 1; // -1 because it count index value so 9th field is 8th index

    $i = 0;
    foreach ($row as $field) {

        fwrite($file, $field);
        if ($i != $last) {
            fwrite($file, ",");
        }
        $i++;
    }
    fwrite($file, "\n");
}
fclose($file);
echo "All the records writes in file";
