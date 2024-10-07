<?php
include "includes/database.php";
require 'vendor/autoload.php';
use Smalot\PdfParser\Parser;
try {
    $parser = new Parser();
    $pdf = $parser->parseFile('output.pdf');
    $text = $pdf->getText();
    $lines = explode("\n", $text);
    for ($i = 1; $i < count($lines); $i += 9) {
        if (isset($lines[$i], $lines[$i + 1], $lines[$i + 2], $lines[$i + 3], $lines[$i + 4], $lines[$i + 5], $lines[$i + 6], $lines[$i + 7], $lines[$i + 8])) {

            $firstname = $lines[$i];
            $lastname = $lines[$i + 1];
            $companyname = $lines[$i + 2];
            $address = $lines[$i + 3];
            $city = $lines[$i + 4];
            $state = $lines[$i + 5];
            $phoneno = $lines[$i + 6];
            $email = $lines[$i + 7];
            $web = $lines[$i + 8];
            $studentQuery = "INSERT INTO students(firstname,lastname,companyname,address,city,state,phoneno,email,web) 
            VALUES('$firstname','$lastname','$companyname','$address','$city','$state','$phoneno','$email','$web')";
            $result = mysqli_query($conn, $studentQuery);

        }
    }
    if ($result) {
        echo "Data inserted successfully.";
    } else {
        echo "Error";
    }
    // echo $lines;
} catch (Exception $e) {
    echo "Error" . $e->getMessage();
}