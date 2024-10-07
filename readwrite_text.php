<?php

// $myFile = fopen("file.txt", "r") or die("Unable to open the file"); // read the file
// echo fgets($myFile);
// fclose($myFile);

// $a = readfile("file.txt"); //readfile also read the character
// echo $a;

$filePointer = fopen("file.txt", "r") or die("Unable to open the file"); //readfile
$content = fread($filePointer, filesize("file.txt"));
fclose($filePointer);
echo $content;

// echo "Welcome to writes file in php"; //write file use -> w
// $fptr = fopen("file.txt", "w");
// fwrite($fptr, "This is best file on this planet");
// fwrite($fptr, "This is another file content");
// fclose($fptr);

// echo "Welcome to writes file in php"; //write file use -> a
// $fptr = fopen("file.txt", "a");
// fwrite($fptr, "This is best file.");
// fwrite($fptr, "This is another file content");
// fclose($fptr);
?>