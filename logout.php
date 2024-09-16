<?php
include "includes/function.php";
session_start();
// session_unset();
session_destroy();
redirectTo("login.php");
exit;
?>