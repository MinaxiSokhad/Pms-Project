<?php

require 'vendor/autoload.php';
use PhpOffice\PhpWord\IOFactory;

$fileName = "sample-docx-files-sample6.docx";
$phpWord = IOFactory::load($fileName);
$text = '';
foreach ($phpWord->getSections() as $section) {
    $elements = $section->getElements();
    foreach ($elements as $element) {
        if (method_exists($element, 'getText')) {
            $text .= $element->getText() . "<br/>";
        }
    }
}
echo $text;

// function parseWord($userDoc)
// {
//     $fileHandle = fopen($userDoc, "r");
//     $line = @fread($fileHandle, filesize($userDoc));
//     $lines = explode(chr(0x0D), $line);
//     $outtext = "";
//     foreach ($lines as $thisline) {
//         $pos = strpos($thisline, chr(0x00));
//         if (($pos !== FALSE) || (strlen($thisline) == 0)) {
//         } else {
//             $outtext .= $thisline . " ";
//         }
//     }
//     $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
//     return $outtext;
// }

// $userDoc = "sample-docx-files-sample6.docx";

// $text = parseWord($userDoc);
// echo $text;

// $homepage = file_get_contents('sample-docx-files-sample6.docx');
// echo $homepage;
// $im = file_get_contents("sample-docx-files-sample6.docx");
// header("Content-type: application/msword");
// echo $im;

// //write document
// require 'vendor/autoload.php';
// use PhpOffice\PhpWord\IOFactory;
// use PhpOffice\PhpWord\Reader\Word2007;
// $fileName = "sample-docx-files-sample3.docx";
// $phpWord = IOFactory::load($fileName);
// $sections = $phpWord->getSections()[1];
// $sections->addText("This page");
// $newFile = "sample-docx-files-sample6.docx";
// $phpWord->save($newFile, 'Word2007');
// // Inform the user
// echo "Document modified and saved successfully: " . $newFile;
