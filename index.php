<?php include "includes/_header.php"; ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
// error_reporting(1); // Report only fatal run-time errors
echo $undefinedVariable;// not show warning because display errors value is set as 0
echo $undefinedVariable;// not show warning because display errors value is set as 0
echo $undefinedVariable;// not show warning because display errors value is set as 0
echo $undefinedVariable;// not show warning because display errors value is set as 0
echo $undefinedVariable;// not show warning because display errors value is set as 0
echo $undefinedVariable;// not show warning because display errors value is set as 0
// error_reporting(1); // Report only fatal run-time errors
// ini_set('display_errors', '0');
// echo $undefinedVariable; // This will not be reported (no error output)
// require('non_existent_file.php'); // This will be reported as a fatal error

?>
<title>Home</title>
<div class="container my-4">
    <h1 class="text-center">Dashboard </h1>
</div>
<?php include "includes/_footer.php"; ?>