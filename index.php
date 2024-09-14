<?php
include "includes/function.php";
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid)) {
    redirectTo("login.php");
}
?>

<?php include "includes/_header.php"; ?>
<title>Home</title>
<?php include "includes/_nav.php"; ?>



<?php include "includes/_footer.php"; ?>