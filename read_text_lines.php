<?php
$lines = file("file.txt");
$count = 0;
foreach ($lines as $line) {
    // $count += 1;
    // echo str_pad($count, 2, 0, STR_PAD_LEFT) . ". " . $line . "<br />";
    echo $line . "<br/>";
}
?>