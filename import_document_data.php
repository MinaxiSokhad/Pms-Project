<?php
include "includes/database.php";
require 'vendor/autoload.php';
use PhpOffice\PhpWord\IOFactory;
try {

    $filepath = "output.docx";
    $phpWord = IOFactory::load($filepath);

    $text = '';
    $selectionData = [];
    foreach ($phpWord->getSections() as $section) {
        $elements = $section->getElements();
        foreach ($elements as $element) {
            if (method_exists($element, 'getRows')) {
                foreach ($element->getRows() as $row) {
                    $rowData = [];
                    foreach ($row->getCells() as $cell) {
                        $cellText = '';
                        foreach ($cell->getElements() as $cellElement) {
                            if (method_exists($cellElement, 'getText')) {
                                $cellText .= $cellElement->getText();
                            }
                        }
                        $rowData[] = $cellText;
                    }
                    $selectionData[] = $rowData;
                }

            }
        }
    }
    $lines = explode("\n", $text);
    foreach ($selectionData as $rowIndex => $row) {
        if (isset($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8])) {
            if ($rowIndex > 0) {
                $firstname = $row[0];
                $lastname = $row[1];
                $companyname = $row[2];
                $address = $row[3];
                $city = $row[4];
                $state = $row[5];
                $phoneno = $row[6];
                $email = $row[7];
                $web = $row[8];
                $studentQuery = "INSERT INTO students(firstname,lastname,companyname,address,city,state,phoneno,email,web) 
            VALUES('$firstname','$lastname','$companyname','$address','$city','$state','$phoneno','$email','$web')";
                $result = mysqli_query($conn, $studentQuery);

            }
        }
    }
    if ($result) {
        echo "Data inserted successfully.";
    } else {
        echo "Error";
    }
} catch (Exception $e) {
    echo "Error:" . $e->getMessage();
}