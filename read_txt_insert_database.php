<?php
include "includes/database.php";
$file = fopen("file.txt", "r");
while (!feof($file)) {
    $content = fgets($file);
    $data = explode(",", $content);
    if (count($data) < 9) {
        continue; // Skip lines with insufficient data
    }
    list($firstname, $lastname, $companyname, $address, $city, $state, $phoneno, $email, $web) = $data;
    $studentQuery = "INSERT INTO students (firstname,lastname,companyname,address,city,state,phoneno,email,web) 
            VALUES('$firstname','$lastname','$companyname','$address','$city','$state','$phoneno','$email','$web')";
    $result = mysqli_query($conn, $studentQuery);


}
if ($result) {
    echo "Record Insert Successfully";
} else {
    echo "Error";
}
fclose($file);